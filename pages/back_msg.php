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

    $niv_admin = $_SESSION['niv_admin'];

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

                    echo '<div class="container mb-2">
                            <h3 class="mt-3 mb-4">Liste des messages</h3>';

                    /* Connexion à une base de données en PDO */
                    $configs = include($lien.'pages/config.php');
                    $servername = $configs['servername'];
                    $username = $configs['username'];
                    $password = $configs['password'];
                    $db = $configs['database'];
                    //On établit la connexion
                    try{
                        $conn = new PDO("mysql:host=$servername;dbname=$db;charset=UTF8", $username, $password);
                        //On définit le mode d\'erreur de PDO sur Exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        try{

                            $sth = $conn->prepare("SELECT * FROM messages");
                            $sth->execute();
                            //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                            $messages = $sth->fetchAll(PDO::FETCH_ASSOC);

                            echo '<table class="table table-striped" id="tableau_user">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="text-center text-nowrap">Id <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                        <th scope="col" class="text-center text-nowrap">Nom <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                        <th scope="col" class="text-center text-nowrap">Prénom <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                        <th scope="col" class="text-center text-nowrap">E-mail <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                        <th scope="col" class="text-center text-nowrap">Message <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                        <th scope="col" class="text-center text-nowrap">Date <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                        <th scope="col" class="text-center text-nowrap">Outils</th>
                                    </tr>
                                    </thead>
                                    <tbody>';

                            // on remplit la liste de sélection de console
                            foreach ($messages as $message) {

                                echo '<tr>
                                        <th class="align-middle text-center" scope="row">'.$message['Id_msg'].'</th>
                                        <td class="align-middle text-center">'.$message['Nom_msg'].'</td>
                                        <td class="align-middle text-center">'.$message['Prenom_msg'].'</td>
                                        <td class="align-middle text-center">'.$message['Mail_msg'].'</td>
                                        <td class="align-middle text-center">'.$message['Msg_msg'].'</td>
                                        <td class="align-middle text-center">'.$message['Dte_msg'].'</td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-row">
                                                <div>
                                                    <button type="button" class="btn open_modal" data-id="'.$message['Id_msg'].'" name="mod_'.$message['Id_msg'].'">
                                                        <i name="mod_'.$message['Id_msg'].'" class="fas fa-eye" data-id="'.$message['Id_msg'].'" id="mod_'.$message['Id_msg'].'"></i>
                                                    </button>
                                                </div>
                                                <div >
                                                    <button type="button" class="btn" onclick="Suppr_msg(event)" name="del_'.$message['Id_msg'].'">
                                                        <i name="del_'.$message['Id_msg'].'" class="fas fa-trash-can" id="del_'.$message['Id_msg'].'"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>';
                            };

                            echo    '</tbody>
                                </table>
                            </div>';
                        }
                        catch(PDOException $e){
                            
                            date_default_timezone_set('Europe/Paris');
                            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                            $format1 = '%A %d %B %Y %H:%M:%S';
                            $date1 = strftime($format1);
                            $fichier = fopen('./../log/error_log_back_msg.txt', 'c+b');
                            fseek($fichier, filesize('./../log/error_log_back_msg.txt'));
                            fwrite($fichier, "\n\n" .$date1. " - Erreur import liste messages. Erreur : " .$e);
                            fclose($fichier);

                            /*Fermeture de la connexion à la base de données*/
                            $sth = null;
                            $conn = null;    
                        }

                        echo '  <!-- Trait de séparation -->
                                <div class="container">
                                    <div class="divider py-1 bg-dark"></div>
                                </div>

                                <div class="container mb-5">
                                    <div class="row mb-3">
                                        <div class="d-flex col-12 justify-content-between">
                                            <h3 class="col-8">Liste des réponses</h3>
                                            <input type="text" class="form-control text-center ms-5" id="filtre_id_rep" name="filtre_id_rep" onchange="chargement_rep()" value="0">
                                            <label class="label ms-2 col-2">Recherche par n°id_msg <br> 0 = tous</label>
                                        </div>

                                        <div class="modal-body" id="affichage_modal_reponse">


                                        </div>

                                    </div>
                            
                                </div>';
                    }
                    /*On capture les exceptions et si une exception est lancée, on écrit dans un fichier log
                    *les informations relatives à celle-ci*/
                    catch(PDOException $e){
                    //echo "Erreur : " . $e->getMessage();
                    date_default_timezone_set('Europe/Paris');
                    setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                    $format1 = '%A %d %B %Y %H:%M:%S';
                    $date1 = strftime($format1);
                    $fichier = fopen('./../log/error_log_back_msg.txt', 'c+b');
                    fseek($fichier, filesize('./../log/error_log_back_msg.txt'));
                    fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données - extraction liste messages pour tableau. Erreur : " .$e);
                    fclose($fichier);
                        
                    echo   '<article class="container">
                                <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                                    Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>
                            </article>';
                    }    

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
        <script src="./../js/back_message.js"></script> 
        <script src="./../js/tri_tableau.js"></script> 
    </body>
    
</html>



<div class="modal fade" id="Consult_Msg_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Consultation d'un message</h5>
      </div>
      <div class="modal-body" id="affichage_modal">
            <!-- affichage des données depuis le fetch en js -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
    </div>
    </div>
  </div>
</div>

<div class="modal fade" id="Consult_Rep_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Consultation d'un message</h5>
      </div>
      <div class="modal-body" id="affichage_modal_rep">
            <!-- affichage des données depuis le fetch en js -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
    </div>
    </div>
  </div>
</div>