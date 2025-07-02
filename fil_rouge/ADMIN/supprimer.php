<?php
include_once '../asset/db.php';
include_once '../asset/fonctions.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM etudiants WHERE id = ?");
    $stmt->execute([$id]);
}

// Redirige vers la liste après suppression
header("Location: liste_etudiants.php");
exit;
?>