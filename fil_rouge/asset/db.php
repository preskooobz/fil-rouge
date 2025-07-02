<?php
$host = 'localhost';
$dbname = 'fil_rouge';
$username = 'root';
$password = ''; // Mets ici ton mot de passe MySQL si nécessaire

try {
    // Connexion au serveur MySQL sans base sélectionnée
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création silencieuse de la base si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    // Connexion à la base fil_rouge
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
// creation d la table admin si elle n'existe pas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL,
        role ENUM('admin', 'etudiant') NOT NULL
    )");
} catch (PDOException $e) {
    die("Erreur lors de la création de la table utilisateurs : " . $e->getMessage());
}
// Insertion d'un utilisateur admin par défaut si la table est vide
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs");
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Insertion d'un utilisateur admin par défaut
        $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, role) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $passwordHash, 'admin']);
    }
} catch (PDOException $e) {
    die("Erreur lors de l'insertion de l'utilisateur admin : " . $e->getMessage());
}
//creation de la table etudiant si elle n'existe pas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS etudiants (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(50) NOT NULL,
        prenom VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        mot_de_passe VARCHAR(255) NOT NULL
    )");
} catch (PDOException $e) {
    die("Erreur lors de la création de la table etudiants : " . $e->getMessage());
}
// Insertion d'un étudiant par défaut si la table est vide
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM etudiants");
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Insertion d'un étudiant par défaut
        $passwordHash = password_hash('etudiant123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
        $stmt->execute(['', '', '', $passwordHash]);
    }
} catch (PDOException $e) {
    die("Erreur lors de l'insertion de l'étudiant par défaut : " . $e->getMessage());
}
// Création de la table QCM si elle n'existe pas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS qcm (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titre VARCHAR(255) NOT NULL
    )");
} catch (PDOException $e) {
    die("Erreur lors de la création de la table QCM : " . $e->getMessage());
}
// Création de la table Questions si elle n'existe pas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        qcm_id INT NOT NULL,
        question TEXT NOT NULL,
        FOREIGN KEY (qcm_id) REFERENCES qcm(id) ON DELETE CASCADE
    )");
} catch (PDOException $e) {
    die("Erreur lors de la création de la table Questions : " . $e->getMessage());
}
// Création de la table Réponses si elle n'existe pas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS reponses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question_id INT NOT NULL,
        reponse TEXT NOT NULL,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    )");
} catch (PDOException $e) {
    die("Erreur lors de la création de la table Réponses : " . $e->getMessage());
}
// Création de la table Réponses Correctes si elle n'existe pas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS reponses_correctes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question_id INT NOT NULL,
        reponse_id INT NOT NULL,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
        FOREIGN KEY (reponse_id) REFERENCES reponses(id) ON DELETE CASCADE
    )");
} catch (PDOException $e) {
    die("Erreur lors de la création de la table Réponses Correctes : " . $e->getMessage());
}
// Création de la table Statistiques si elle n'existe pas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS statistiques (
        id INT AUTO_INCREMENT PRIMARY KEY,
        etudiant_id INT NOT NULL,
        qcm_id INT NOT NULL,
        score INT NOT NULL,
        date_passage DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
        FOREIGN KEY (qcm_id) REFERENCES qcm(id) ON DELETE CASCADE
    )");
} catch (PDOException $e) {
    die('Erreur lors de la création de la table Statistiques : ' . $e->getMessage());
}
// Création de la table Réponses Étudiant si elle n'existe pas
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS reponses_etudiant (
        id INT AUTO_INCREMENT PRIMARY KEY,
        etudiant_id INT NOT NULL,
        qcm_id INT NOT NULL,
        question_id INT NOT NULL,
        reponse_id INT NOT NULL,
        date_passage DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    die("Erreur lors de la création de la table Réponses Étudiant : " . $e->getMessage());
}

// Requêtes pour sélectionner les questions et réponses
try {
    $stmt = $pdo->query("SELECT id, question FROM questions");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT id, question_id, reponse FROM reponses");
    $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la sélection des questions ou réponses : " . $e->getMessage());
}

?>
