<?php
include_once '../asset/db.php';
include_once '../asset/headeraccueil.php'; // Inclure l'en-tête de la page
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <h2 class="text-center m-2">Creer un QCM</h2>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card shadow p-4" style="max-width: 600px; width: 100%;">
            <form action="traitement_creer_qcm.php" method="post">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre du QCM</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                <div class="mb-3">
                    <label for="question" class="form-label">Question</label>
                    <input type="text" class="form-control" id="question" name="question[]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Réponses</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="reponse1" name="reponse[0][]" required placeholder="Réponse 1">
                        <div class="input-group-text">
                            <input type="radio" name="bonne_reponse[0]" value="0" required>
                            <span class="ms-1">Bonne réponse</span>
                        </div>
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="reponse2" name="reponse[0][]" required placeholder="Réponse 2">
                        <div class="input-group-text">
                            <input type="radio" name="bonne_reponse[0]" value="1">
                            <span class="ms-1">Bonne réponse</span>
                        </div>
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="reponse3" name="reponse[0][]" required placeholder="Réponse 3">
                        <div class="input-group-text">
                            <input type="radio" name="bonne_reponse[0]" value="2">
                            <span class="ms-1">Bonne réponse</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Créer le QCM</button>
            </form>
        </div>
</body>
</html>