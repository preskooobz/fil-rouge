<?php
include_once '../asset/db.php';
include_once '../asset/fonctions.php';
include_once '../asset/header_e.php';
session_start();

if (!isset($_SESSION['id_etudiant'])) {
    // Redirige vers la page de connexion ou affiche un message d’erreur
    header('Location: ../plateforme_qcm/connexion.php');
    exit;
}

$id_etudiant = $_SESSION['id_etudiant'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_qcm = $_POST['id_qcm'];
    $score = $_POST['score']; // À calculer selon les réponses
    ajouterResultat($id_etudiant, $id_qcm, $score);

    foreach ($questions as $question) {
        $question_id = $question['id'];
        $reponse_id = $_POST['reponse_' . $question_id] ?? null;
        // Enregistre $reponse_id pour $question_id dans la table reponses_etudiant
    }
    // Redirection ou message de succès
}
?>