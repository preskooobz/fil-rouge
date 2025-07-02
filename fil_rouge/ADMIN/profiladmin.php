<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: connexion.php');
    exit();
}
include_once '../asset/fonctions.php';

// Utilise une fonction pour récupérer/modifier les infos admin si besoin
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil Administrateur</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Profil Administrateur</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($_SESSION['user']['nom']) ?></li>
        <li class="list-group-item"><strong>Rôle :</strong> <?= htmlspecialchars($_SESSION['user']['role']) ?></li>
        <!-- Ajoute d'autres infos si besoin -->
    </ul>
</div>
</body>
</html>