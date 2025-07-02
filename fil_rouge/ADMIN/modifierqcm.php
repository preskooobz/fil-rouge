<?php

include_once '../asset/db.php';
include_once '../asset/fonctions.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "QCM introuvable.";
    exit;
}

// Récupérer le QCM
$stmt = $pdo->prepare("SELECT * FROM qcm WHERE id = ?");
$stmt->execute([$id]);
$qcm = $stmt->fetch();

if (!$qcm) {
    echo "QCM introuvable.";
    exit;
}

// Récupérer les questions et réponses
$stmt = $pdo->prepare("SELECT * FROM questions WHERE qcm_id = ?");
$stmt->execute([$id]);
$questions = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    // Mettre à jour le titre
    $stmt = $pdo->prepare("UPDATE qcm SET titre = ? WHERE id = ?");
    $stmt->execute([$titre, $id]);

    // Mettre à jour les questions et réponses
    foreach ($_POST['question'] as $qIndex => $questionText) {
        $questionId = $_POST['question_id'][$qIndex];
        $stmt = $pdo->prepare("UPDATE questions SET question = ? WHERE id = ?");
        $stmt->execute([$questionText, $questionId]);

        foreach ($_POST['reponse'][$qIndex] as $rIndex => $reponseText) {
            $reponseId = $_POST['reponse_id'][$qIndex][$rIndex];
            $stmt = $pdo->prepare("UPDATE reponses SET reponse = ? WHERE id = ?");
            $stmt->execute([$reponseText, $reponseId]);
        }
    }

    echo "<div class='alert alert-success'>QCM modifié avec succès !</div>";
    // Redirection possible ici
    // header("Location: listes_qcm.php");
    // exit;
}

// Récupérer les réponses pour chaque question
$reponses = [];
foreach ($questions as $q) {
    $stmt = $pdo->prepare("SELECT * FROM reponses WHERE question_id = ?");
    $stmt->execute([$q['id']]);
    $reponses[] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier QCM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Modifier le QCM</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Titre du QCM</label>
            <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($qcm['titre']) ?>" required>
        </div>
        <?php foreach ($questions as $qIndex => $question): ?>
            <div class="mb-3">
                <label class="form-label">Question <?= $qIndex + 1 ?></label>
                <input type="hidden" name="question_id[]" value="<?= $question['id'] ?>">
                <input type="text" name="question[]" class="form-control" value="<?= htmlspecialchars($question['question']) ?>" required>
                <?php foreach ($reponses[$qIndex] as $rIndex => $reponse): ?>
                    <div class="mt-2">
                        <label>Réponse <?= $rIndex + 1 ?></label>
                        <input type="hidden" name="reponse_id[<?= $qIndex ?>][<?= $rIndex ?>]" value="<?= $reponse['id'] ?>">
                        <input type="text" name="reponse[<?= $qIndex ?>][]" class="form-control" value="<?= htmlspecialchars($reponse['reponse']) ?>" required>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>
</body>
</html>