<?php

include_once '../asset/fonctions.php';
include_once '../asset/header_e.php';
session_start();
$id_etudiant = $_SESSION['id_etudiant']; // ou autre méthode pour récupérer l’ID

$qcms = VoirQCM($id_etudiant);
foreach ($qcms as $qcm) {
    echo $qcm['nom'] . '<br>';
}
?>