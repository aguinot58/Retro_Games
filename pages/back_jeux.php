<?php

    session_start();

    if (!isset($_SESSION['logged'])){
        $_SESSION['logged'] = 'non';
    }

    if (!isset($_SESSION['admin'])){
        $_SESSION['admin'] = 'non';
    }

    if (!isset($_SESSION['suppr_jeu'])){
        $_SESSION['suppr_jeu'] = false;
    }

    if ($_SESSION['suppr_jeu']== true){
        ?>
            <script type="text/javascript">
                alert ("Jeu supprimé avec succès !");
            </script>
        <?php
        $_SESSION['suppr_jeu']= false;
    }

    if (!isset($_SESSION['modif_jeu'])){
        $_SESSION['modif_jeu'] = false;
    }

    if ($_SESSION['modif_jeu']== true){
        ?>
            <script type="text/javascript">
                alert ("Jeu modifié avec succès !");
            </script>
        <?php
        $_SESSION['modif_jeu']= false;
    }

    if (!isset($_SESSION['ajout_jeu'])){
        $_SESSION['ajout_jeu'] = false;
    }

    if ($_SESSION['ajout_jeu']== true){
        ?>
            <script type="text/javascript">
                alert ("Jeu ajouté avec succès !");
            </script>
        <?php
        $_SESSION['ajout_jeu']= false;
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

                    echo '<div class="container">
                        <h3 class="mt-5">Ajout de jeu dans la base de données</h3>
                        <!-- Formulaire d ajout de jeu dans la bdd -->
                        <form class="form-ajout" method="post" enctype="multipart/form-data" onsubmit="return valider_jeu()" action="./../pages/ajout_jeu.php">
                            <div class="row mt-4 mb-3">
                                <div class="col">
                                    <label for="nom_jeux" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom_jeux" name="nom_jeux">
                                    <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                </div>
                                <div class="col">
                                    <label for="date_jeux" class="form-label">Date sortie</label>
                                    <input type="text" class="form-control" id="date_jeux" name="date_jeux">
                                    <div id="emailHelp" class="form-text">Format jj-mm-aaaa</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="dev_jeux" class="form-label">Développeur</label>
                                    <input type="text" class="form-control" id="dev_jeux" name="dev_jeux">
                                    <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                </div>
                                <div class="col">
                                    <label for="edit_jeux" class="form-label">Editeur</label>
                                    <input type="text" class="form-control" id="edit_jeux" name="edit_jeux">
                                    <div id="emailHelp" class="form-text">50 caractères maximum.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="desc_jeux" class="form-label">Description</label>
                                    <textarea type="text" class="form-control" id="desc_jeux" name="desc_jeux"></textarea>
                                    <div id="emailHelp" class="form-text">500 caractères maximum.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="img_jeux" class="form-label">Jaquette</label>
                                    <input class="form-control" type="file" id="img_jeux" name="img_jeux">
                                    <div id="emailHelp" class="form-text">Pas d\'apostrophe dans le nom - remplacer les espaces par des underscores - format jpg/jpeg/png.</div>
                                </div>
                                <div class="col">
                                    <label for="cat_jeux" class="form-label">Plate-forme</label>
                                    <select id="cat_jeux" class="form-select" name="cat_jeux" aria-label="Default select example">
                                        <option selected>Choix</option>';
                                            require $lien.'pages/conn_bdd.php';

                                                try{

                                                    //Sélectionne les valeurs dans les colonnes pour chaque entrée de la table
                                                    $sth = $conn->prepare("SELECT Id_cat, Nom_cat FROM categories ORDER BY Id_cat ASC");
                                                    $sth->execute();
                                                    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                                    $categories = $sth->fetchAll(PDO::FETCH_ASSOC);

                                                    // on remplit la liste de sélection de console
                                                    foreach ($categories as $categorie) {
                                                        echo '<option value="'.$categorie['Id_cat'].'">'.$categorie['Nom_cat'].'</option>';
                                                    };

                                                    /*Fermeture de la connexion à la base de données*/
                                                    $sth = null;
                                                    $conn = null;
                                                }
                                                catch(PDOException $e){
                                                    date_default_timezone_set('Europe/Paris');
                                                    setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                                    $format1 = '%A %d %B %Y %H:%M:%S';
                                                    $date1 = strftime($format1);
                                                    $fichier = fopen('./../log/error_log_back_jeux.txt', 'c+b');
                                                    fseek($fichier, filesize('./../log/error_log_back_jeux.txt'));
                                                    fwrite($fichier, "\n\n" .$date1. " - Erreur import liste catégorie consoles. Erreur : " .$e);
                                                    fclose($fichier);
                            
                                                    /*Fermeture de la connexion à la base de données*/
                                                    $sth = null;
                                                    $conn = null;    
                                                }
                                            
                        echo       '</select>
                                </div>
                            </div>
                            <!-- Bouton d\'ajout -->
                            <div class="row d-flex justify-content-center mt-5 mb-3">
                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary">Ajouter</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Trait de séparation -->
                    <div class="container">
                        <div class="divider py-1 bg-dark"></div>
                    </div>

                    <!-- liste des jeux en fonction du niveau d\'admin -->
                    <div class="container mb-5 table-responsive">
                        <h3 class="mt-3 mb-4">Liste des jeux</h3>';

                        require $lien.'pages/conn_bdd.php';

                            try{

                                if ($niv_admin==3){

                                    $sth = $conn->prepare("SELECT COUNT(Id_jeux) FROM jeux WHERE Etat_jeux = 1");
                                    $sth->execute();
                                    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                    $nb_jeux_tot = $sth->fetchColumn();

                                    $sth = $conn->prepare("SELECT * FROM jeux LIMIT :limite OFFSET :debut");

                                } elseif ($niv_admin==2 or $niv_admin==1){

                                    $ident_user = $_SESSION['user'];

                                    $sth = $conn->prepare("SELECT Id_user FROM utilisateurs where Ident_user = $ident_user");
                                    $sth->execute();
                                    $id_user = $sth->fetchColumn();

                                    $sth = $conn->prepare("SELECT COUNT(jeux.Id_jeux) FROM jeux INNER JOIN gestion_jeux ON jeux.Id_jeux = gestion_jeux.Id_jeux and gestion_jeux.Id_user = $id_user WHERE Etat_jeux = 1 LIMIT :limite OFFSET :debut");
                                    $sth->execute();
                                    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                    $nb_jeux_tot = $sth->fetchColumn();

                                    $sth = $conn->prepare("SELECT * FROM jeux INNER JOIN gestion_jeux ON jeux.Id_jeux = gestion_jeux.Id_jeux and gestion_jeux.Id_user = $id_user LIMIT :limite OFFSET :debut");

                                }

                                $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
                                $limite = 12;
                                $debut = ($page - 1) * $limite;
                                $nombreDePages = ceil($nb_jeux_tot / $limite);

                                $sth->bindValue('limite', $limite, PDO::PARAM_INT);
                                $sth->bindValue('debut', $debut, PDO::PARAM_INT); 
                                $sth->execute();
                                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                $jeux = $sth->fetchAll(PDO::FETCH_ASSOC);

                                $total_pages = ceil($nb_jeux_tot/10);

                                echo '<table class="table table-striped" id="tableau_jeu">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center text-nowrap">Id <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                                <th scope="col" class="text-center text-nowrap">Nom <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                                <th scope="col" class="text-center text-nowrap">Cat. <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                                <th scope="col" class="text-center text-nowrap">Développeur <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                                <th scope="col" class="text-center text-nowrap">Editeur <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                                <th scope="col" class="text-center text-nowrap">Dte Sortie <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                                <th scope="col" class="text-center text-nowrap">Img <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                                <th scope="col" class="text-center text-nowrap">Visible <img class="fleches" src="'.$lien.'img/up-and-down-arrows.png" alt="flèches de tri"></th>
                                                <th scope="col" class="text-center text-nowrap">Outils</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pg-results">';

                                echo    '</tbody>
                                    </table>
                                    <div class="">
                                        <div class="pagination d-flex justify-content-center"></div>
                                    </div>';

                                /*Fermeture de la connexion à la base de données*/
                                $sth = null;
                                $conn = null;
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="//rawgit.com/botmonster/jquery-bootpag/master/lib/jquery.bootpag.min.js" type="text/javascript"></script>
        <script src="./../js/back_jeux.js"></script> 

        <script type="text/javascript">
            $(document).ready(function() {
                $("#pg-results").load("fetch_data.php");
                $(".pagination").bootpag({
                    total: <?php echo $total_pages; ?>,
                    page: 1,
                    maxVisible: 5,
                    wrapClass: 'pagination',
                    activeClass: 'active',
                    disabledClass: 'disabled',
                }).on("page", function(e, page_num){
                    e.preventDefault();
                    /*$("#results").prepend('<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>');*/
                    $("#pg-results").load("fetch_data.php", {"page": page_num});
                });
            });
        </script>
        <script src="./../js/tri_tableau.js"></script> 
    </body>
    
</html>




<div class="modal fade" id="Modif_Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modification d'un jeu</h5>
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


