<?php

    session_start();

    if (!isset($_SESSION['logged'])){
        $_SESSION['logged'] = 'non';
    }

    if (!isset($_SESSION['admin'])){
        $_SESSION['admin'] = 'non';
    }

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

?>
    
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Retro-Games</title>
        <link rel="shortcut icon" type="image/ico" href="./../img/icone.png">
    
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- Custom styles for this template -->
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        </style>
        <link rel="stylesheet" href="./../css/back_office.css"/>
    
    </head>

    <body>

        <header>
            <!-- Fixed navbar -->
            <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
                <div class="container">
                    <a class="navbar-brand" href="./../index.php">Retro-Games.com</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <ul class="navbar-nav me-auto mb-2 mb-md-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="./../pages/back_office.php">Accueil Admin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="./../pages/back_consoles.php">Consoles</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="./../pages/back_jeux.php">Jeux</a>
                            </li>
                            <?php
                                if ($_SESSION['niv_admin'] == 3){
                                    echo '<li class="nav-item">
                                                <a class="nav-link active" href="./../pages/back_utilisateurs.php">Utilisateurs</a>
                                            </li>';
                                }
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="./../pages/logout.php">Déconnexion</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <main class="flex-shrink-0">
            <div class="container">
                <div class="col mt-5">
                    <a class="liens" href="./../pages/back_consoles.php">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-md-4">
                                <img class="bd-placeholder-img" src="./../img/retrogaming-console-cover.jpg" width="100%" height="250" role="img" aria-label="Placeholder: Image" preserveAspectRatio="xMidYMid slice" focusable="false"></img>

                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Consoles</h5>
                                        <p class="card-text">Ici vous pouvez ajouter, modifier ou supprimer des consoles du site.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col mt-4 mb-4">
                    <a class="liens" href="./../pages/back_jeux.php">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img class="bd-placeholder-img" src="./../img/collection_jeux_retro.jpg" width="100%" height="250" role="img" aria-label="Placeholder: Image" preserveAspectRatio="xMidYMid slice" focusable="false"></img>

                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Jeux</h5>
                                        <p class="card-text">Ici vous pouvez ajouter, modifier ou supprimer des jeux du site.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php 
                    if ($_SESSION['niv_admin'] == 3){
                        echo '<div class="col mb-5">
                            <a class="liens" href="./../pages/back_utilisateurs.php"> 
                                <div class="card">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                        <img class="bd-placeholder-img" src="./../img/silhouette.png" width="100%" height="250" role="img" aria-label="Placeholder: Image" preserveAspectRatio="xMidYMid slice" focusable="false"></img>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">Utilisateurs</h5>
                                                <p class="card-text">Administration des utilisateurs.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>';
                    }
                ?>
            </div>
        </main>

        <footer class="footer mt-auto py-3 bg-light">
            <div class="container">
                <span class="text-muted d-flex justify-content-center">Tous droits réservés @Retro-Games.com</span>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <!--<script src="custom.js"></script>-->    
    </body>
    
</html>


