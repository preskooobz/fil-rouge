<?php

session_start();
include_once '../asset/db.php';
include_once '../asset/headerco.php';
include_once '../asset/fonctions.php';

$message = '';
$type = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Vérifie si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $message = "Cet email est déjà utilisé.";
        $type = "danger";
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nom, $prenom, $email, $passwordHash])) {
            $message = "Inscription réussie !";
            $type = "success";
        } else {
            $message = "Erreur lors de l'inscription.";
            $type = "danger";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    ajouterEtudiant($nom, $prenom, $login, $password);
    // Redirection ou message de succès
}
?>

<?php if ($message): ?>
    <div class="alert alert-<?= $type; ?> text-center"><?= $message; ?></div>
<?php endif; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</html>
</head>
<body>
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
        <h1 class="text-center mb-4">Inscription</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>
    </div>
</div>
<?php include_once '../asset/footer.php'; ?>
</body>
</html>