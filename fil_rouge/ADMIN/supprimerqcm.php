<?php

include_once '../asset/db.php';
include_once '../asset/fonctions.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Supprimer le QCM (les questions et réponses liées seront supprimées grâce aux contraintes ON DELETE CASCADE)
    $stmt = $pdo->prepare("DELETE FROM qcm WHERE id = ?");
    $stmt->execute([$id]);

    supprimerQCM($_GET['id']);
    // Redirection ou message de succès
    // Rediriger vers la liste des QCM
    header("Location: /fil_rouge/ADMIN/listes_qcm.php");
    exit;
} else {
    echo "QCM introuvable.";
}
?>