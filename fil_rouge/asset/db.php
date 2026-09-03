<?php
/**
 * Connexion à la base de données PostgreSQL (hébergée sur Render).
 *
 * La chaîne de connexion est lue depuis la variable d'environnement
 * DATABASE_URL (fournie par Render quand la base est liée au service),
 * avec un repli sur des variables PG* individuelles, puis sur une base
 * locale par défaut pour le développement.
 */
function getPdoConnection() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $databaseUrl = getenv('DATABASE_URL');

    if ($databaseUrl) {
        $parts = parse_url($databaseUrl);
        $host = $parts['host'];
        $port = $parts['port'] ?? 5432;
        $dbname = ltrim($parts['path'] ?? '', '/');
        $user = $parts['user'] ?? 'postgres';
        $password = $parts['pass'] ?? '';
    } else {
        $host = getenv('PGHOST') ?: 'localhost';
        $port = getenv('PGPORT') ?: 5432;
        $dbname = getenv('PGDATABASE') ?: 'fil_rouge';
        $user = getenv('PGUSER') ?: 'postgres';
        $password = getenv('PGPASSWORD') ?: '';
    }

    try {
        $pdo = new PDO(
            "pgsql:host=$host;port=$port;dbname=$dbname",
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Table des utilisateurs (admin / enseignant)
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS utilisateurs (
            id SERIAL PRIMARY KEY,
            nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
            mot_de_passe VARCHAR(255) NOT NULL,
            role VARCHAR(20) NOT NULL CHECK (role IN ('admin', 'etudiant'))
        )");
    } catch (PDOException $e) {
        die("Erreur lors de la création de la table utilisateurs : " . $e->getMessage());
    }

    // Utilisateur admin par défaut si la table est vide
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs");
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, role) VALUES (?, ?, ?)");
            $stmt->execute(['admin', $passwordHash, 'admin']);
        }
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion de l'utilisateur admin : " . $e->getMessage());
    }

    // Table des étudiants
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS etudiants (
            id SERIAL PRIMARY KEY,
            nom VARCHAR(50) NOT NULL,
            prenom VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            mot_de_passe VARCHAR(255) NOT NULL
        )");
    } catch (PDOException $e) {
        die("Erreur lors de la création de la table etudiants : " . $e->getMessage());
    }

    // Étudiant par défaut si la table est vide
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants");
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $passwordHash = password_hash('etudiant123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
            $stmt->execute(['', '', '', $passwordHash]);
        }
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion de l'étudiant par défaut : " . $e->getMessage());
    }

    // Table QCM
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS qcm (
            id SERIAL PRIMARY KEY,
            titre VARCHAR(255) NOT NULL
        )");
    } catch (PDOException $e) {
        die("Erreur lors de la création de la table QCM : " . $e->getMessage());
    }

    // Table Questions
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS questions (
            id SERIAL PRIMARY KEY,
            qcm_id INT NOT NULL,
            question TEXT NOT NULL,
            FOREIGN KEY (qcm_id) REFERENCES qcm(id) ON DELETE CASCADE
        )");
    } catch (PDOException $e) {
        die("Erreur lors de la création de la table Questions : " . $e->getMessage());
    }

    // Table Réponses
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS reponses (
            id SERIAL PRIMARY KEY,
            question_id INT NOT NULL,
            reponse TEXT NOT NULL,
            FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
        )");
    } catch (PDOException $e) {
        die("Erreur lors de la création de la table Réponses : " . $e->getMessage());
    }

    // Table Réponses Correctes
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS reponses_correctes (
            id SERIAL PRIMARY KEY,
            question_id INT NOT NULL,
            reponse_id INT NOT NULL,
            FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
            FOREIGN KEY (reponse_id) REFERENCES reponses(id) ON DELETE CASCADE
        )");
    } catch (PDOException $e) {
        die("Erreur lors de la création de la table Réponses Correctes : " . $e->getMessage());
    }

    // Table Statistiques
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS statistiques (
            id SERIAL PRIMARY KEY,
            etudiant_id INT NOT NULL,
            qcm_id INT NOT NULL,
            score INT NOT NULL,
            date_passage TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
            FOREIGN KEY (qcm_id) REFERENCES qcm(id) ON DELETE CASCADE
        )");
    } catch (PDOException $e) {
        die('Erreur lors de la création de la table Statistiques : ' . $e->getMessage());
    }

    // Table Réponses Étudiant
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS reponses_etudiant (
            id SERIAL PRIMARY KEY,
            etudiant_id INT NOT NULL,
            qcm_id INT NOT NULL,
            question_id INT NOT NULL,
            reponse_id INT NOT NULL,
            date_passage TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
    } catch (PDOException $e) {
        die("Erreur lors de la création de la table Réponses Étudiant : " . $e->getMessage());
    }

    return $pdo;
}

$pdo = getPdoConnection();

// Requêtes pour sélectionner les questions et réponses (comportement d'origine conservé)
try {
    $stmt = $pdo->query("SELECT id, question FROM questions");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT id, question_id, reponse FROM reponses");
    $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la sélection des questions ou réponses : " . $e->getMessage());
}
