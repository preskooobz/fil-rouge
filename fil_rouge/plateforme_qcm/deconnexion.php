<?php
session_start();
session_unset();
session_destroy();

// Démarre une nouvelle session pour stocker le message flash
session_start();
$_SESSION['message'] = "Vous êtes déconnecté avec succès.";

header("Location: connexion.php");
exit();
?>