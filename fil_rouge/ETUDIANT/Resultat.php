<?php
include_once '../asset/db.php';
include_once '../asset/header_e.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
    header("Location: ../connexion.php");
    exit;
}
if (!isset($_GET['qcm_id'])) {
    echo "QCM non spécifié.";
    exit;
}
$qcm_id = intval($_GET['qcm_id']);
$etudiant_id = $_SESSION['id'];

// Récupérer le dernier score pour ce QCM et cet étudiant
$stmt = $pdo->prepare("SELECT score, date_passage FROM statistiques WHERE etudiant_id = ? AND qcm_id = ? ORDER BY date_passage DESC LIMIT 1");
$stmt->execute([$etudiant_id, $qcm_id]);
$resultat = $stmt->fetch();

if (!$resultat) {
    echo "Aucun résultat trouvé.";
    exit;
}

// Récupérer toutes les questions du QCM
$stmt = $pdo->prepare("SELECT * FROM questions WHERE qcm_id = ?");
$stmt->execute([$qcm_id]);
$questions = $stmt->fetchAll();

// Récupérer les réponses de l'étudiant pour ce QCM
$stmt = $pdo->prepare("SELECT question_id, reponse_id FROM reponses_etudiant WHERE etudiant_id = ? AND qcm_id = ?");
$stmt->execute([$etudiant_id, $qcm_id]);
$reponses_etudiant = [];
while ($row = $stmt->fetch()) {
    $reponses_etudiant[$row['question_id']] = $row['reponse_id'];
}

$score = 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat du QCM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow" style="max-width: 600px; margin: auto;">
            <div class="card-header bg-success text-white text-center">
                <h3><i class="bi bi-trophy-fill"></i> Votre résultat</h3>
            </div>
            <div class="card-body text-center">
                <div class="text-start">
                <?php foreach ($questions as $index => $question): ?>
                    <div class="mb-3">
                        <strong>Q<?php echo $index+1; ?>. <?php echo htmlspecialchars($question['question']); ?></strong><br>
                        <?php
                        // Bonne réponse (admin)
                        $stmt2 = $pdo->prepare("SELECT r.reponse FROM reponses_correctes rc JOIN reponses r ON rc.reponse_id = r.id WHERE rc.question_id = ?");
                        $stmt2->execute([$question['id']]);
                        $bonne_reponse = $stmt2->fetchColumn();

                        // Réponse de l'étudiant
                        $reponse_id_etudiant = $reponses_etudiant[$question['id']] ?? null;
                        $texte_reponse_etudiant = null;
                        if ($reponse_id_etudiant) {
                            $stmt3 = $pdo->prepare("SELECT reponse FROM reponses WHERE id = ?");
                            $stmt3->execute([$reponse_id_etudiant]);
                            $texte_reponse_etudiant = $stmt3->fetchColumn();
                        }

                        // Comparaison pour le score
                        if ($texte_reponse_etudiant && $texte_reponse_etudiant == $bonne_reponse) {
                            $score++;
                        }
                        ?>
              <div class="mt-2">
    <?php if ($texte_reponse_etudiant): ?>
        <div class="fw-bold">
            Votre réponse :
            <span class="<?php echo ($texte_reponse_etudiant == $bonne_reponse) ? 'text-success' : 'text-danger'; ?>">
                <?php echo htmlspecialchars($texte_reponse_etudiant); ?>
                <?php if ($texte_reponse_etudiant == $bonne_reponse): ?>
                    <i class="bi bi-check-circle-fill"></i> <span class="text-success">Bonne réponse !</span>
                <?php else: ?>
                    <i class="bi bi-x-circle-fill"></i> <span class="text-danger">Mauvaise réponse.</span>
                <?php endif; ?>
            </span>
        </div>
    <?php else: ?>
        <div class="text-warning">
            <i class="bi bi-exclamation-circle"></i> Vous n'avez pas répondu à cette question.
        </div>
    <?php endif; ?>
    <div class="text-success">
        <i class="bi bi-patch-check-fill"></i> Réponse correcte attendue : <strong><?php echo htmlspecialchars($bonne_reponse); ?></strong>
    </div>
</div>

                    </div>
                <?php endforeach; ?>
                </div>
                <hr>
                <p class="fs-4">
                    <i class="bi bi-star-fill text-warning"></i>
                    Score obtenu : <span class="fw-bold"><?php echo $score; ?> / <?php echo count($questions); ?></span>
                </p>
                <a href="Accueil_e.php" class="btn btn-primary mt-3"><i class="bi bi-house-door-fill"></i> Retour à l'accueil</a>
            </div>
        </div>
    </div>
</body>
</html>