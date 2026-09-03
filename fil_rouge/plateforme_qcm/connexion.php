<?php
session_start();
if (isset($_SESSION['message'])) {
    echo '<div style="color: green;">' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
}
include_once '../asset/db.php'; // Connexion à la BDD
include_once '../asset/fonctions.php'; // Fichier  de fonctions


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['nom_utilisateur'];
    $password = $_POST['mot_de_passe'];

    // Recherche d'abord dans la table utilisateurs (admin)
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom_utilisateur = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['nom_utilisateur'] = $user['nom_utilisateur'];
        $_SESSION['role'] = $user['role'];

        // Après vérification du mot de passe et du rôle
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom_utilisateur'],
            'role' => $user['role']
            // Ajoute d'autres champs si tu en as
        ];

        if ($user['role'] === 'admin') {
            header("Location: /ADMIN/Admin.php");
        } else {
            header("Location: /ETUDIANT/Accueil_e.php");
        }
        exit;
    } else {
        // Recherche dans la table etudiants (connexion par email)
        $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE email = ?");
        $stmt->execute([$username]);
        $etudiant = $stmt->fetch();

        if ($etudiant && password_verify($password, $etudiant['mot_de_passe'])) {
            $_SESSION['id'] = $etudiant['id'];
            $_SESSION['nom_utilisateur'] = $etudiant['email'];
            $_SESSION['role'] = 'etudiant';

            header("Location: /ETUDIANT/Accueil_e.php");
            exit;
        } else {
            $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>

<!-- HTML Connexion -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="min-width: 300px; max-width: 500px; width: 100%;">
<div class="container mt-5">
  <h2 class="text-center mb-4">Connexion</h2>
  <?php if (isset($erreur)) echo "<div class='alert alert-danger text-center'>$erreur</div>"; ?>
  <form method="POST" class="mx-auto" style="max-width: 400px;">
    <div class="mb-3">
      <label for="nom_utilisateur" class="form-label">Nom d'utilisateur</label>
      <input type="text" class="form-control" id="nom_utilisateur" name="nom_utilisateur" required>
    </div>
    <div class="mb-3">
      <label for="mot_de_passe" class="form-label">Mot de passe</label>
      <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
  </form>
</div>
</div>
</body>
</html>
