<?php
include_once '../asset/db.php';
include_once '../asset/header_e.php';
session_start();

// Vérifie si l'utilisateur est un étudiant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../plateforme_qcm/connexion.php");
    exit;
}
$etudiant_id = $_SESSION['id'];

// Récupérer les résultats de l'étudiant
$stmt = $pdo->prepare("SELECT qcm.titre, statistiques.score, COUNT(questions.id) as total_questions, statistiques.qcm_id
    FROM statistiques
    JOIN qcm ON statistiques.qcm_id = qcm.id
    JOIN questions ON questions.qcm_id = qcm.id
    WHERE statistiques.etudiant_id = ?
    GROUP BY statistiques.qcm_id, qcm.titre, statistiques.score
    ORDER BY statistiques.qcm_id DESC");
$stmt->execute([$etudiant_id]);
$resultats = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Résultats</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .resultats-container { max-width: 800px; margin: 40px auto; }
        .card { border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); margin-bottom: 25px; }
        .card-header { background: #0d6efd; color: #fff; border-radius: 15px 15px 0 0; font-weight: bold; font-size: 1.1rem; }
        .score { font-size: 1.3rem; font-weight: bold; color: #198754; }
        .qcm-title { font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="resultats-container">
        <h2 class="mb-4 text-center"><i class="bi bi-clipboard-data"></i> Mes Résultats QCM</h2>
        <?php if (empty($resultats)): ?>
            <div class="alert alert-info text-center">Aucun résultat trouvé pour le moment.</div>
        <?php else: ?>
            <?php foreach ($resultats as $res): ?>
                <div class="card">
                    <div class="card-header">
                        <?= htmlspecialchars($res['titre']) ?>
                    </div>
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="qcm-title mb-2 mb-md-0">
                            Score : <span class="score"><?= $res['score'] ?> / <?= $res['total_questions'] ?></span>
                        </div>
                        <a href="Resultat.php?qcm_id=<?= $res['qcm_id'] ?>" class="btn btn-outline-primary btn-sm">
                            Voir le détail <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
