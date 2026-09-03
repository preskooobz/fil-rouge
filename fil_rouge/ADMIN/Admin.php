<?php
include_once '../asset/headeraccueil.php';
include_once '../asset/db.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../plateforme_qcm/connexion.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<!-- Titre + Description -->
<div class="mt-5 mb-4 text-center">
  <h1 id="texte-anim" class="fs-1 fw-bold mb-3"></h1>
  <p class="fs-5 text-dark fade-in">Gérez facilement vos QCM et les comptes étudiants depuis cette interface.</p>
</div>

<!-- Boutons sans boîtes blanches -->
<div class="container">
  <div class="d-flex flex-row justify-content-center flex-wrap gap-3">

    <div class="fade-in" style="min-width:220px;max-width:250px;">
      <a href="creer_qcm.php" class="btn btn-danger w-100 py-3 mb-2">
        <i class="bi bi-journal-plus me-2"></i>Créer un QCM
      </a>
    </div>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div class="fade-in" style="min-width:220px;max-width:250px;">
      <a href="inscription.php" class="btn btn-success w-100 py-3 mb-2">
        <i class="bi bi-person-plus-fill me-2"></i>Ajouter un Étudiant
      </a>
    </div>

    <div class="fade-in" style="min-width:220px;max-width:250px;">
      <a href="supprimer.php" class="btn btn-warning w-100 py-3 mb-2">
        <i class="bi bi-person-dash-fill me-2"></i>Supprimer un Étudiant
      </a>
    </div>

    <div class="fade-in" style="min-width:220px;max-width:250px;">
      <a href="liste_etudiants.php" class="btn btn-primary w-100 py-3 mb-2">
        <i class="bi bi-pencil-square me-2"></i>Modifier un Étudiant
      </a>
    </div>
    
    <div class="fade-in" style="min-width:220px;max-width:250px;" >
      <a href="liste_etudiants.php" class="btn btn-info w-100 py-3 mb-2">
        
        <i class="bi bi-people-fill me-2"></i>Liste des Étudiants
      </a>
    </div>
    <?php endif; ?>

    <div class="fade-in" style="min-width:220px;max-width:250px;">
      <a href="listes_qcm.php" class="btn btn-secondary w-100 py-3 mb-2">
        <i class="bi bi-journal-text me-2"></i>Liste des QCM
      </a>
    </div>
    <div class="fade-in" style="min-width:220px;max-width:250px;">
      <a href="statistiques.php" class="btn btn-dark w-100 py-3 mb-2">
        <i class="bi bi-bar-chart-fill me-2"></i>Statistiques
      </a>
    </div>

  </div>
</div>
<div class="container mt-5">
  <h2 class="mb-4 text-center fade-in">Liste des étudiants inscrits</h2>
  <div class="table-responsive fade-in">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Email</th>
          <th>Actions</th> <!-- Nouvelle colonne -->
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT id, nom, prenom, email FROM etudiants ORDER BY nom, prenom";
        $result = $pdo->query($sql);
        if ($result && $result->rowCount() > 0):
          $i = 1;
          while($row = $result->fetch(PDO::FETCH_ASSOC)):
        ?>
        <tr>
          <td><?= $i++ ?></td>
          <td><?= htmlspecialchars($row['nom']) ?></td>
          <td><?= htmlspecialchars($row['prenom']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td>
            <a href="modifier.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary me-2" title="Modifier">
              <i class="bi bi-pencil-square"></i>
            </a>
            <a href="supprimer.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet étudiant ?');">
              <i class="bi bi-trash"></i>
            </a>
          </td>
        </tr>
        <?php
          endwhile;
        else:
        ?>
        <tr>
          <td colspan="5" class="text-center">Aucun étudiant inscrit.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</section>
</main>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../DESIGN/script.js"></script>

</body>
</html>