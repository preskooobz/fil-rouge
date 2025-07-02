<?php
include_once '../asset/db.php'; // Connexion à la base de données
include_once '../asset/headeraccueil.php'; // Inclure l'en-tête de la page
include_once '../asset/fonctions.php';

// Récupérer les statistiques avec jointure pour afficher les noms
$sql = "SELECT s.id, e.nom, e.prenom, q.titre, s.score, s.date_passage
        FROM statistiques s
        JOIN etudiants e ON s.etudiant_id = e.id
        JOIN qcm q ON s.qcm_id = q.id
        ORDER BY s.date_passage DESC";
$stmt = $pdo->query($sql);
$stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques des QCM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../DESIGN/style.css"> <!-- Lien vers le CSS -->
</head>
<body>
<div class="container mt-5">
    
    <h2 class="mb-4 text-center">Statistiques des QCM</h2>
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>QCM</th>
                <th>Score</th>
                <th>Date de passage</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($stats) > 0): ?>
            <?php foreach ($stats as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= htmlspecialchars($row['prenom']) ?></td>
                    <td><?= htmlspecialchars($row['titre']) ?></td>
                    <td><?= htmlspecialchars($row['score']) ?></td>
                    <td><?= htmlspecialchars($row['date_passage']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Aucune statistique disponible.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>