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

    require $lien.'pages/conn_bdd.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Retro-Games</title>
        <link rel="shortcut icon" type="image/ico" href="./../img/icone.png">
    
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/fontawesome.min.css" integrity="sha512-xX2rYBFJSj86W54Fyv1de80DWBq7zYLn2z0I9bIhQG+rxIF6XVJUpdGnsNHWRa6AvP89vtFupEPDP8eZAtu9qA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        <link rel="stylesheet" href="./../css/back.css"/>
    
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
                        <?php
                            if ($_SESSION['logged'] == 'oui' && $_SESSION['admin'] = 'oui') {
                                echo '<li class="nav-item">
                                            <a class="nav-link active" aria-current="page" href="./../pages/back_office.php">Accueil Admin</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" href="./../pages/back_consoles.php">Consoles</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" href="./../pages/back_jeux.php">Jeux</a>
                                        </li>';
                                        if ($_SESSION['niv_admin'] == 3){
                                            echo '<li class="nav-item">
                                                <a class="nav-link active" href="./../pages/back_utilisateurs.php">Utilisateurs</a>
                                            </li>';
                                        }
                                echo   '<li class="nav-item">
                                            <a class="nav-link active" href="./../pages/back_msg.php">Messages</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" href="./../pages/logout.php">Déconnexion</a>
                                        </li>';
                            }else{
                                echo '<li class="nav-item">
                                            <a class="nav-link active" href="./../pages/connexion.php">Connexion</a>
                                        </li>';
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <main>

            <?php 

                if ($_SESSION['logged'] == 'oui' && $_SESSION['admin'] = 'oui') {

                    $id_msg = $_POST['id_msg'];
                    $identifiant_user = $_SESSION['user'];
                    date_default_timezone_set('Europe/Paris');
                    setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                    $date_jour = date('d-m-Y H:i:s');

                    echo '<div class="container mb-2">
                            <h3 class="mt-3 mb-4">Répondre au message : </h3>';

                        // On extrait l'id user en fonction de l'identifiant de la session de l'admin afin de pouvoir
                        // l'insérer dans la table reponses par la suite.
                        $sth = $conn->prepare("SELECT Id_user FROM utilisateurs WHERE Ident_user = :ident_user");
                        $sth->bindParam(':ident_user', $identifiant_user);
                        $sth->execute();
                        //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                        $id_user = $sth->fetchColumn();

                        try{

                            $sth = $conn->prepare("SELECT * FROM messages WHERE Id_msg = :id_msg");
                            $sth->bindParam(':id_msg', $id_msg);
                            $sth->execute();
                            //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                            $messages = $sth->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($messages as $message) {

                                echo '  <form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="" action="./../pages/send_msg.php">

                                            <div class="row mt-1 mb-3">
                                                <div class="col">
                                                    <label for="id_msg" class="form-label">Id : </label>
                                                    <label class="form-label">'.$message['Id_msg'].'</label>
                                                    <input type="text" class="form-control d-none" id="id_msg" name="id_msg" value="'.$message['Id_msg'].'">
                                                </div>
                                                <div class="col">
                                                    <label for="dte_msg" class="form-label">Date : </label>
                                                    <label class="form-label">'.$message['Dte_msg'].'</label>
                                                    <input type="text" class="form-control d-none" id="dte_msg" name="dte_msg" value="'.$message['Dte_msg'].'">
                                                </div>
                                            </div>
                                            <div class="row mt-1 mb-3">
                                                <div class="col">
                                                    <label for="nom_msg" class="form-label">Nom : </label>
                                                    <label class="form-label">'.$message['Nom_msg'].'</label>
                                                    <input type="text" class="form-control d-none" id="nom_msg" name="nom_msg" value="'.$message['Nom_msg'].'">
                                                </div>
                                                <div class="col">
                                                    <label for="prenom_msg" class="form-label">Prénom : </label>
                                                    <label class="form-label">'.$message['Prenom_msg'].'</label>
                                                    <input type="text" class="form-control d-none" id="prenom_msg" name="prenom_msg" value="'.$message['Prenom_msg'].'">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3">
                                                    <label for="mail_msg" class="form-label">Adresse E-mail : </label>
                                                    <label class="form-label">'.$message['Mail_msg'].'</label>
                                                    <input type="text" class="form-control d-none" id="mail_msg" name="mail_msg" value="'.$message['Mail_msg'].'">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mb-3">
                                                    <label for="msg_msg" class="form-label">Message : </label>
                                                    <label class="form-label">'.$message['Msg_msg'].'</label>
                                                    <textarea type="text" class="form-control d-none" id="msg_msg" name="msg_msg">'.$message['Msg_msg'].'</textarea>
                                                </div>
                                            </div>
                                        </form>';

                                /*Fermeture de la connexion à la base de données*/
                                $sth = null;
                                $conn = null;

                                break;

                            } 

                        }
                        catch(PDOException $e){
                            date_default_timezone_set('Europe/Paris');
                            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                            $format1 = '%A %d %B %Y %H:%M:%S';
                            $date1 = strftime($format1);
                            $fichier = fopen('./../log/error_log_form_msg.txt', 'c+b');
                            fseek($fichier, filesize('./../log/error_log_form_msg.txt'));
                            fwrite($fichier, "\n\n" .$date1. " - Erreur import données message. Erreur : " .$e);
                            fclose($fichier);

                            /*Fermeture de la connexion à la base de données*/
                            $sth = null;
                            $conn = null;    
                        }

                echo '  </div>

                        <!-- Trait de séparation -->
                        <div class="container">
                            <div class="divider py-1 bg-dark"></div>
                        </div>

                        <div class="container mb-5">
                            <h3 class="mt-3 mb-4">Votre réponse :</h3>

                            <form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="return valider_rep()" action="./../pages/send_msg.php">

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="dte_msg" class="form-label">Le :</label>
                                        <input type="text" class="form-control" id="dte_msg" name="dte_msg" value="'.$date_jour.'">
                                    </div>
                                    <div class="col">
                                        <label for="ident_user" class="form-label">Auteur : </label>
                                        <input type="text" class="form-control" id="ident_user" name="ident_user" value="'.$identifiant_user.'">
                                    </div>
                                </div>

                                <div class="row mb-3 d-none">
                                    <label for="id_user" class="form-label"></label>
                                    <input type="text" class="form-control" id="id_user" name="id_user" value="'.$id_user.'">
                                </div>
                                <div class="row mb-3 d-none">
                                    <label for="id_msg" class="form-label"></label>
                                    <input type="text" class="form-control" id="id_msg" name="id_msg" value="'.$id_msg.'">
                                </div>

                                <div class="row">
                                    <div class="mb-3">
                                        <label for="msg_rep" class="form-label">Corps de l\'e-mail :</label>
                                        <textarea type="text" class="form-control" id="msg_rep" name="msg_rep"></textarea>
                                        <div id="repHelp" class="form-text">1000 caractères maximum.</div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center mt-5 mb-3">
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-primary">Envoyer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>';

                } else {

                    echo '<div class="container">
                            <h3 class="mt-5 mb-5">Merci de vous connecter à votre compte.</h3>
                        </div>';
        
                }
        
            ?>

        </main>

        <footer class="footer mt-auto py-3 bg-light">
            <div class="container">
                <span class="text-muted d-flex justify-content-center">Tous droits réservés @Retro-Games.com</span>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    </body>
    
</html>