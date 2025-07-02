<?php
// Inclusion du fichier de connexion à la base de données
include_once '../asset/db.php';
// Inclusion du header de connexion (navbar, etc.)
include_once '../asset/headerco.php';
?>
<?php
   session_start(); // Démarre la session PHP
    // Affiche un message d'erreur s'il existe dans la session
    if (isset($_SESSION['erreur'])) {
        echo '<div class="alert alert-danger">'.$_SESSION['erreur'].'</div>';
        unset($_SESSION['erreur']); // Supprime le message après affichage
    }
    // Affiche un message de succès s'il existe dans la session
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
        unset($_SESSION['success']); // Supprime le message après affichage
    }
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> <!-- Encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->
    <title>Document</title> <!-- Titre de la page -->
    <link rel="stylesheet" href="../DESIGN/style.css"> <!-- Feuille de style personnalisée -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons -->
</head>
<body>

<div class="container mt-5 d-flex justify-content-center"> <!-- Conteneur principal centré -->
  <div class="card shadow p-4" style="max-width: 500px; width: 100%;"> <!-- Carte Bootstrap -->
    <h1 class="text-center mb-4">Inscription</h1> <!-- Titre -->
 
    <form action="traitement_inscription.php" method="post"> <!-- Formulaire d'inscription -->
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label> <!-- Label nom -->
            <input type="text" class="form-control" id="nom" name="nom" required> <!-- Champ nom -->
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label> <!-- Label prénom -->
            <input type="text" class="form-control" id="prenom" name="prenom" required> <!-- Champ prénom -->
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label> <!-- Label email -->
            <input type="email" class="form-control" id="email" name="email" required> <!-- Champ email -->
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label> <!-- Label mot de passe -->
            <input type="password" class="form-control" id="password" name="password" required> <!-- Champ mot de passe -->
        </div>
        <button type="submit" class="btn btn-primary w-100"><a href="#" style="text-decoration: none; color: white;">S'inscrire</a></button> <!-- Bouton d'inscription -->
    </form>
  </div>
</div>
    <?php include_once '../asset/footer.php'; ?> <!-- Inclusion du footer -->
</body>
</html>
