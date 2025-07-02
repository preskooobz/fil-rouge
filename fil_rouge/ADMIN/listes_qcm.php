<?php
include_once '../asset/db.php';
//recuperer les qcm
$stmt = $pdo->query("SELECT * FROM qcm");
$qcm = $stmt->fetchAll();
session_start();
// Vérifie que l'utilisateur est connecté (admin, prof ou étudiant)
if (!isset($_SESSION['role'])) {
    header("Location: ../plateforme_qcm/connexion.php");
    exit;
}
// Si tu veux que seuls les étudiants voient la liste, tu peux ajouter :
// if ($_SESSION['role'] !== 'etudiant') { ... }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des QCM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <?php include_once '../asset/headeraccueil.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Liste des QCM</h1>
        <div class="row">
            <?php foreach ($qcm as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['titre']) ?></h5>
                            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'prof'): ?>
                                <a href="modifierqcm.php?id=<?= $item['id'] ?>" class="btn btn-primary mb-2">
                                    <i class="bi bi-pencil-square"></i> Modifier
                                </a>
                                <a href="supprimerqcm.php?id=<?= $item['id'] ?>" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce QCM ?');">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            <?php endif; ?>
                            <?php if ($_SESSION['role'] === 'etudiant'): ?>
                                <a href="../ETUDIANT/faireqcm.php?id=<?php echo $item['id']; ?>" class="btn btn-success">
                                    <i class="bi bi-play-circle"></i> Faire ce QCM
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'prof'): ?>
        <div class="text-center mt-4">
            <a href="creer_qcm.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Créer un nouveau QCM
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php include_once '../asset/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>