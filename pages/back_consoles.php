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


    if (!isset($_SESSION['suppr_console'])){
        $_SESSION['suppr_console'] = false;
    }

    if ($_SESSION['suppr_console']== true){
        ?>
            <script type="text/javascript">
                alert ("console supprimé avec succès !");
            </script>
        <?php
        $_SESSION['suppr_console']= false;
    }

    if (!isset($_SESSION['modif_console'])){
        $_SESSION['modif_console'] = false;
    }

    if ($_SESSION['modif_console']== true){
        ?>
            <script type="text/javascript">
                alert ("console modifié avec succès !");
            </script>
        <?php
        $_SESSION['modif_console']= false;
    }

    if (!isset($_SESSION['ajout_console'])){
        $_SESSION['ajout_console'] = false;
    }

    if ($_SESSION['ajout_console']== true){
        ?>
            <script type="text/javascript">
                alert ("console ajouté avec succès !");
            </script>
        <?php
        $_SESSION['ajout_console']= false;
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

                    echo    '<div class="container">
                                <h3 class="mt-5"> Ajout de console dans la base de données</h3>
                                <!-- Formulaire d ajout de console dans la bdd -->
                                <form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="return valider_console()" action="./../pages/ajout_console.php">
                                    <div class=" mt-4 mb-5">
                                        <div class="col mb-5">
                                            <label for="nom_cat" class="form-label">Plate-forme</label>
                                            <input type="text" class="form-control" id="nom_cat" name="nom_cat" placeholder=" Exemple = nintendo 64 ">
                                            <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                        </div>
                                        <div class="col mb-5">
                                            <label for="logo_cat" class="form-label">Logo</label>
                                            <input class="form-control" type="file" id="logo_cat" name="logo_cat">
                                            <div id="emailHelp" class="form-text">Pas d\'apostrophe dans le nom - remplacer les espaces par des underscores - format jpg/jpeg/png.</div>
                                        </div>
                                        <div class="col">
                                            <label for="img_cat" class="form-label">Image console</label>
                                            <input class="form-control" type="file" id="img_cat" name="img_cat">
                                            <div id="emailHelp" class="form-text">Pas d\'apostrophe dans le nom - remplacer les espaces par des underscores - format jpg/jpeg/png.</div>
                                        </div>
                                                    <!-- Bouton d\'ajout -->
                                        <div class="row d-flex justify-content-center mt-5 mb-3">
                                            <div class="col-2">
                                                <button type="submit" class="btn btn-primary">Ajouter</button>
                                            </div>
                                        </div>
                                    </div>     
                                </form>       
                            </div>
                            
                            <!-- Trait de séparation -->
                            <div class="container">
                                <div class="divider py-1 bg-dark"></div>
                            </div>
        
                            <!-- liste des jeux en fonction du niveau d\'admin -->
                            <div class="container mb-5">
                                <h3 class="mt-3 mb-4">Liste des consoles</h3>';
                 

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

                                if ($niv_admin==3){

                                    $sth = $conn->prepare("SELECT * FROM categories ");
                               
                            
                            
                                }elseif ($niv_admin==2 or $niv_admin==1){

                                    $ident_user = $_SESSION['user'];

                                    echo 'vous n\'avez pas l\'autorisation de modifier les consoles'; 
                                }

                                $sth->execute();
                                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                $consoles = $sth->fetchAll(PDO::FETCH_ASSOC);

                                echo '<table class="table table-striped" id="tableau_consoles">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="text-center text-nowrap">Id <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                            <th scope="col" class="text-center text-nowrap">Nom <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                            <th scope="col" class="text-center text-nowrap">Img_logo <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                            <th scope="col" class="text-center text-nowrap">Img_console <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                            <th scope="col" class="text-center text-nowrap">Visible <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                            <th scope="col" class="text-center text-nowrap">Outils</th>
                                        </tr>
                                        </thead>
                                        <tbody>';

                                        // on remplit la liste de sélection de console
                                foreach ($consoles as $console) {


                                    echo '<tr>
                                            <th scope="row" class="align-middle text-center">'.$console['Id_cat'].'</th>
                                            <td class="align-middle text-center">'.$console['Nom_cat'].'</td>
                                            <td class="align-middle text-center">'.$console['Logo_cat'].'</td>
                                            <td class="align-middle text-center">'.$console['Img_cat'].'</td>
                                            <td class="align-middle text-center">'.$console['Etat_cat'].'</td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex flex-row">
                                                    <div>
                                                        <button type="button" class="btn open_modal" data-id="'.$console['Id_cat'].'" name="mod_'.$console['Id_cat'].'">
                                                            <i name="mod_'.$console['Id_cat'].'" class="fas fa-pen" data-id="'.$console['Id_cat'].'" id="mod_'.$console['Id_cat'].'"></i>
                                                        </button>
                                                    </div>
                                                    <div >
                                                        <button type="button" class="btn" onclick="Suppr_console(event)" name="del_'.$console['Id_cat'].'">
                                                            <i name="del_'.$console['Id_cat'].'" class="fas fa-trash-can" id="del_'.$console['Id_cat'].'"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>';
                                };


                                 echo     '</tbody>
                                        </table>';
                            }
                            catch(PDOException $e){
                            
                                date_default_timezone_set('Europe/Paris');
                                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                $format1 = '%A %d %B %Y %H:%M:%S';
                                $date1 = strftime($format1);
                                $fichier = fopen('./../log/error_log_back_jeux.txt', 'c+b');
                                fseek($fichier, filesize('./../log/error_log_back_jeux.txt'));
                                fwrite($fichier, "\n\n" .$date1. " - Erreur import liste jeux. Erreur : " .$e);
                                fclose($fichier);
        
                                /*Fermeture de la connexion à la base de données*/
                                $sth = null;
                                $conn = null;
                            }





                          
                        }
                        catch(PDOException $e){
                            
                        date_default_timezone_set('Europe/Paris');
                        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                        $format1 = '%A %d %B %Y %H:%M:%S';
                        $date1 = strftime($format1);
                        $fichier = fopen('./../log/error_log_back_jeux.txt', 'c+b');
                        fseek($fichier, filesize('./../log/error_log_back_jeux.txt'));
                        fwrite($fichier, "\n\n" .$date1. " - Erreur import liste jeux. Erreur : " .$e);
                        fclose($fichier);

                        /*Fermeture de la connexion à la base de données*/
                        $sth = null;
                        $conn = null;

                        }
                            
                        
                        
                        
                        
                        echo '</div>';























              }else{
                    
                echo   '<div class="container">
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
        <script src="./../js/back_console.js"></script> 
        <script src="./../js/tri_tableau.js"></script> 
    </body>
    
</html>


<div class="modal fade" id="Modif_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modification d'une console</h5>
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