<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Étudiant</title>
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
<body >
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="Accueil_e.php">fil_rouge</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../ETUDIANT/profil.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../plateforme_qcm/deconnexion.php">Déconnexion</a>
                    </li>
                </ul>
</div>
</div>
</nav>
</body>
</html>