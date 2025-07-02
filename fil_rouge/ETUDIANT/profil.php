<?php
session_start();
include_once '../asset/fonctions.php';
include_once '../asset/header_e.php';

// Vérifier que l'utilisateur est connecté et que c'est un étudiant
$acces_etudiant = isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'etudiant';

$etudiant = null;
if ($acces_etudiant) {
    $id = $_SESSION['id'];
    $etudiant = getEtudiantById($id, connexionBD());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil étudiant</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <i class="bi bi-person-circle" style="font-size: 2.5rem;"></i>
                        <h2 class="mt-2 mb-0">Mon profil</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($acces_etudiant && $etudiant): ?>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <i class="bi bi-person-fill"></i>
                                    <strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom']) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-person-badge-fill"></i>
                                    <strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom']) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-envelope-fill"></i>
                                    <strong>Email :</strong> <?= htmlspecialchars($etudiant['email']) ?>
                                </li>
                                <li class="list-group-item">
                                    <i class="bi bi-mortarboard-fill"></i>
                                    <strong>Rôle :</strong> <?= htmlspecialchars($_SESSION['role']) ?>
                                </li>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-danger text-center mt-3">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Accès refusé. Vous devez être connecté en tant qu'étudiant pour voir ce profil.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
