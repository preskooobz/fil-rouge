<?php
include_once '../asset/db.php';
include_once '../asset/fonctions.php';

$id = $_GET['id'] ?? null;
$message = '';
$type = '';

if ($id) {
    // Récupérer les infos de l'étudiant
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
    $stmt->execute([$id]);
    $etudiant = $stmt->fetch();

    if (!$etudiant) {
        $message = "Étudiant introuvable.";
        $type = "danger";
    }

    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Si le champ mot de passe est rempli, on le met à jour
        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE etudiants SET nom = ?, prenom = ?, email = ?, mot_de_passe = ? WHERE id = ?");
            $success = $stmt->execute([$nom, $prenom, $email, $passwordHash, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE etudiants SET nom = ?, prenom = ?, email = ? WHERE id = ?");
            $success = $stmt->execute([$nom, $prenom, $email, $id]);
        }

        if ($success) {
            $message = "Modification réussie.";
            $type = "success";
            // Mettre à jour les infos affichées
            $etudiant['nom'] = $nom;
            $etudiant['prenom'] = $prenom;
            $etudiant['email'] = $email;
        } else {
            $message = "Erreur lors de la modification.";
            $type = "danger";
        }
    }
} else {
    $message = "Aucun étudiant sélectionné.";
    $type = "danger";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier étudiant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Modifier un étudiant</h2>
    <?php if ($message): ?>
        <div class="alert alert-<?= $type; ?> text-center"><?= $message; ?></div>
    <?php endif; ?>
    <?php if (!empty($etudiant)): ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($etudiant['nom']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($etudiant['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="liste_etudiants.php" class="btn btn-secondary">Retour</a>
    </form>
    <?php endif; ?>
</div>
</body>
</html>