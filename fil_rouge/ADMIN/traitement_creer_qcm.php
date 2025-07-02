<?php
include_once '../asset/db.php';
include_once '../asset/fonctions.php'; // Inclure l'en-tête de la page

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    // Création du QCM
    $stmt = $pdo->prepare("INSERT INTO qcm (titre) VALUES (?)");
    $stmt->execute([$titre]);
    $qcm_id = $pdo->lastInsertId();

    // Pour chaque question
    if (isset($_POST['question']) && is_array($_POST['question'])) {
        foreach ($_POST['question'] as $qIndex => $questionText) {
            // Insérer la question
            $stmt = $pdo->prepare("INSERT INTO questions (qcm_id, question) VALUES (?, ?)");
            $stmt->execute([$qcm_id, $questionText]);
            $question_id = $pdo->lastInsertId();

            // Insérer les réponses
            $bonne_reponse_index = isset($_POST['bonne_reponse'][$qIndex]) ? intval($_POST['bonne_reponse'][$qIndex]) : -1;
            $reponse_ids = [];
            if (isset($_POST['reponse'][$qIndex]) && is_array($_POST['reponse'][$qIndex])) {
                foreach ($_POST['reponse'][$qIndex] as $rIndex => $reponseText) {
                    $stmt = $pdo->prepare("INSERT INTO reponses (question_id, reponse) VALUES (?, ?)");
                    $stmt->execute([$question_id, $reponseText]);
                    $reponse_ids[] = $pdo->lastInsertId();
                }
                // Enregistrer la bonne réponse
                if ($bonne_reponse_index >= 0 && isset($reponse_ids[$bonne_reponse_index])) {
                    $bonne_reponse_id = $reponse_ids[$bonne_reponse_index];
                    $stmt = $pdo->prepare("INSERT INTO reponses_correctes (question_id, reponse_id) VALUES (?, ?)");
                    $stmt->execute([$question_id, $bonne_reponse_id]);
                }
            }
        }
    }
    // Redirection ou message de succès
    header("Location: listes_qcm.php?success=1");
    exit;
}
?>