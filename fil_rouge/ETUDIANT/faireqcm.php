<?php
include_once '../asset/db.php';
include_once '../asset/header_e.php';
session_start();

// Vérifie si l'utilisateur est un étudiant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../connexion.php");
    exit;
}

// Vérifie que le QCM est spécifié
if (!isset($_GET['id'])) {
    echo "QCM introuvable.";
    exit;
}

$qcm_id = intval($_GET['id']);

// Récupère les questions du QCM
$stmt = $pdo->prepare("SELECT * FROM questions WHERE qcm_id = ?");
$stmt->execute([$qcm_id]);
$questions = $stmt->fetchAll();

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = $_SESSION['id'];
    $score = 0;
    $reponses_etudiant = $_POST['reponse'];

    foreach ($questions as $question) {
        $question_id = $question['id'];
        $reponse_id = $reponses_etudiant[$question_id] ?? null;

        if ($reponse_id) {
            // Enregistre la réponse
            $stmt = $pdo->prepare("INSERT INTO reponses_etudiant (etudiant_id, qcm_id, question_id, reponse_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$etudiant_id, $qcm_id, $question_id, $reponse_id]);

            // Vérifie si c'est la bonne réponse
            $stmt2 = $pdo->prepare("SELECT reponse_id FROM reponses_correctes WHERE question_id = ?");
            $stmt2->execute([$question_id]);
            $bonne_reponse = $stmt2->fetchColumn();

            if (intval($reponse_id) === intval($bonne_reponse)) {
                $score++;
            }
        }
    }

    // Enregistre le score
    $stmt = $pdo->prepare("INSERT INTO statistiques (etudiant_id, qcm_id, score) VALUES (?, ?, ?)");
    $stmt->execute([$etudiant_id, $qcm_id, $score]);

    // Redirige vers la page de résultats
    header("Location: Resultat.php?qcm_id=$qcm_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Faire le QCM</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .qcm-container {
            max-width: 700px;
            margin: 40px auto;
        }
        .card {
            margin-bottom: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .card-header {
            background: #0d6efd;
            color: #fff;
            border-radius: 15px 15px 0 0;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .btn-primary {
            width: 100%;
            font-size: 1.2rem;
            padding: 12px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="qcm-container">
        <h2 class="mb-4 text-center"><i class="bi bi-ui-checks-grid"></i> Répondez au QCM</h2>
        <form method="post">
            <?php foreach ($questions as $index => $question): ?>
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        Question <?= $index + 1; ?>
                    </div>
                    <div class="card-body">
                        <strong><?= htmlspecialchars($question['question']); ?></strong>
                        <?php
                        // Récupère les réponses pour cette question
                        $stmt2 = $pdo->prepare("SELECT * FROM reponses WHERE question_id = ?");
                        $stmt2->execute([$question['id']]);
                        $reponses = $stmt2->fetchAll();
                        foreach ($reponses as $reponse):
                        ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="reponse[<?= $question['id'] ?>]"
                                       value="<?= $reponse['id'] ?>"
                                       id="q<?= $question['id'] ?>_r<?= $reponse['id'] ?>">
                                <label class="form-check-label" for="q<?= $question['id'] ?>_r<?= $reponse['id'] ?>">
                                    <?= htmlspecialchars($reponse['reponse']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary mt-3">
                <i class="bi bi-send-check"></i> Valider mes réponses
            </button>
        </form>
    </div>
    <?php include_once '../asset/footer.php'; ?>
</body>
</html>
