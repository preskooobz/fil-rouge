<?php
include_once '../asset/db.php'; // Connexion à la BDD
include_once '../asset/header_e.php'; // Inclure l'en-tête de la page
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Étudiant</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>

    .texte {
        text-align: center;
        margin-bottom: 0px;
        margin-top: 50px;
    }
    </style>
</head>
<body>
    <section>
    <div class="texte">
        <h2 id="texte-anim" class="fw-bold fs-1 mb-1"></h2>
        <p>tester vos connaissances en quelques clique!</p>
    </div>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 10vh;">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 mb-3">
                <div class="card text-center" style="background-color: #e0f0ff; border-radius: 10px; border: 1px solid #b2ffb2;">
                    <div class="card-body" >
                        <div class="mb-1">
                            <i class="bi bi-eye" style="font-size: 2rem; color: #0d6efd;"></i>
                        </div>
                        <h5 class="text-center">Consultez la liste <br> des QCM disponibles et vos résultats.</h5>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'etudiant'): ?>
                            <a href="../ADMIN/listes_qcm.php" class="btn btn-primary" style="width: 350px;">Voir QCM</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card text-center" style="background-color: #e6ffe6; border-radius: 10px; border: 1px solid #b2ffb2;">
                    <div class="card-body">
                        <div class="mb-1">
                            <i class="bi bi-bar-chart-line" style="font-size: 2rem; color: #198754;"></i>
                        </div>
                        <h5 class="form-check-label">Consultez vos résultats,<br> sans stresse</h5>
                        <a href="resultatsetudiant.php" class="btn btn-success" style="width: 350px;">Voir mes résultats</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="../DESIGN/script.js"></script>
    <?php
    include_once '../asset/footer.php'; // Inclure le pied de page
    ?>
    </body>
</html>