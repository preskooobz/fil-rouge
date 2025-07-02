<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Plateforme QCM</title>
    <link rel="stylesheet" href="../DESIGN/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .navbar .nav-link {
            color: #000;
            position: relative;
            transition: color 0.2s;
        }
        .navbar .nav-link:hover {
            color: #dc3545;
        }
        .navbar .nav-link::after {
            content: "";
            display: block;
            width: 0;
            height: 2px;
            background: #dc3545;
            transition: width 0.2s;
            position: absolute;
            left: 0;
            bottom: -2px;
        }
        .navbar .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="../ADMIN/Admin.php">fil_rouge</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?=
                        (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin')
                        ? '../ADMIN/profiladmin.php'
                        : ' ../ADMIN/profiladmin.php'
                        ?>">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../plateforme_qcm/deconnexion.php">Déconnexion</a>
                </li>
            </ul>
            <?php if (isset($_SESSION['user'])): ?>
                <span class="navbar-text ms-auto">
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        Admin :
                        <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?>
                        <?= htmlspecialchars($_SESSION['user']['prenom'] ?? '') ?>
                        <?php if (!empty($_SESSION['user']['nationalite'])): ?> | Nationalité : <?= htmlspecialchars($_SESSION['user']['nationalite']) ?><?php endif; ?>
                        <?php if (!empty($_SESSION['user']['fonction'])): ?> | Fonction : <?= htmlspecialchars($_SESSION['user']['fonction']) ?><?php endif; ?>
                    <?php elseif ($_SESSION['user']['role'] === 'etudiant'): ?>
                        Étudiant :
                        <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?>
                        <?= htmlspecialchars($_SESSION['user']['prenom'] ?? '') ?>
                        <?php if (!empty($_SESSION['user']['nationalite'])): ?> | Nationalité : <?= htmlspecialchars($_SESSION['user']['nationalite']) ?><?php endif; ?>
                        <?php if (!empty($_SESSION['user']['classe'])): ?> | Classe : <?= htmlspecialchars($_SESSION['user']['classe']) ?><?php endif; ?>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</nav>
</body>
</html>
