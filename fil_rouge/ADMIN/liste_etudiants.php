<?php
// Inclusion du fichier de connexion à la base de données
include_once '../asset/db.php';
// Inclusion du header d'accueil (navbar, etc.)
include_once '../asset/headeraccueil.php';
// Inclusion des fonctions utilitaires
include_once '../asset/fonctions.php';

// Fonction pour récupérer tous les étudiants depuis la base de données
function getAllEtudiants() {
    $bd = connexionBD(); // Connexion à la base
    $query = "SELECT * FROM etudiants"; // Requête SQL pour sélectionner tous les étudiants
    $stmt = $bd->prepare($query); // Préparation de la requête
    $stmt->execute(); // Exécution de la requête
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne tous les résultats sous forme de tableau associatif
}

$etudiants = getAllEtudiants(); // Appel de la fonction pour obtenir la liste des étudiants
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Encodage des caractères -->
    <title>Liste des étudiants</title> <!-- Titre de la page -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap -->
    <link rel="stylesheet" href="../DESIGN/style.css"> <!-- Feuille de style personnalisée -->
</head>
<body>
<div class="container mt-5"> <!-- Conteneur principal avec une marge en haut -->
    <h2 class="mb-4 text-center">Liste des étudiants</h2> <!-- Titre centré -->
    <table class="table table-bordered"> <!-- Tableau Bootstrap avec bordures -->
        <thead>
            <tr>
                <th>Nom</th> <!-- Colonne Nom -->
                <th>Prénom</th> <!-- Colonne Prénom -->
                <th>Email</th> <!-- Colonne Email -->
                <th>Action</th> <!-- Colonne pour modifier -->
                <th>Supprimer</th> <!-- Colonne pour supprimer -->
            </tr>
        </thead>
        <tbody>
        <?php foreach ($etudiants as $etudiant): ?> <!-- Boucle sur chaque étudiant -->
            <tr>
                <td><?= htmlspecialchars($etudiant['nom']) ?></td> <!-- Affiche le nom -->
                <td><?= htmlspecialchars($etudiant['prenom']) ?></td> <!-- Affiche le prénom -->
                <td><?= htmlspecialchars($etudiant['email']) ?></td> <!-- Affiche l'email -->
                <td>
                    <a href="modifier.php?id=<?= $etudiant['id'] ?>" class="btn btn-warning btn-sm">Modifier</a> <!-- Lien pour modifier -->
                </td>
                <td>
                    <a href="supprimer.php?id=<?= $etudiant['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet étudiant ?');">Supprimer</a> <!-- Lien pour supprimer avec confirmation -->
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>