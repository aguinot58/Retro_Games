<?php

session_start();

$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    
    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    $nom_console = $_GET['console'];
?>




<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/categorie.css">
        <link rel="stylesheet" href="../css/Header_footer.css">
        <link rel="shortcut icon" type="image/ico" href="./../img/icone.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Catégorie</title>
    </head>

    <body>
        
        <?php
            /* importation header */
            include $lien.'pages/header.php';
            
            try{

                /* Connexion à une base de données en PDO */
                $configs = include($lien.'pages/config.php');
                $servername = $configs['servername'];
                $username = $configs['username'];
                $password = $configs['password'];
                $db = $configs['database'];

                $conn = new PDO("mysql:host=$servername;dbname=$db;charset=utf8", $username,$password);
                $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                try{

                    $sth = $conn->prepare("SELECT Id_cat FROM categories where Nom_cat=:nom_console");
                    $sth->bindParam(':nom_console', $nom_console);
                    $sth->execute();
                    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                    $categorie = $sth->fetchColumn();

                    $sth = $conn->prepare("SELECT * FROM categories where Id_cat=:categorie");
                    $sth->bindParam(':categorie', $categorie);
                    $sth->execute();
                    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                    $consoles = $sth->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <div class="meuble">
                            <div class="console_ici">    
                                <div class="console_logo">
                                    <div class="tele_logo">
                                        <img class="tele" src="../img/télé2.png">
                                        <img class="logo" src="../img/snes_logo.png" alt="Logo_super_nintendo">
                                    </div>
                                    <img class="console" src="../img/snes_console.png" alt="console_super_nintendo">
                                </div>
                                <div class="haut_meuble">
                                    <p>bonjour</p>
                                </div>
                            </div>
                            <div class="etagere">
                                <ul class="jeux_etagere">
                                    <li class="carte"><img src="../img/zelda_alttp_jackette.jpg" alt="jaquette_zelda"></li>
                                    <li class="carte"><img src="../img/street_fighter_jackette.jpg" alt="jaquette_street_fighter_2"></li>
                                    <li class="carte"><img src="../img/mario_world_jackette.jpg" alt="jaquette_super_mario_world"></li>
                                    <li class="carte"><img src="../img/super_metroid_jackette.jpg" alt="jaquette_super_metroid"></li>
                                </ul>
                            </div>

                        </div>


                    <?php

                    foreach ($consoles as $console){
    
                    }

                    //Fermeture de la connexion à la base de données
                    $sth = null;
                    $conn = null;
        
                }
                catch(PDOException $e){

                    date_default_timezone_set('Europe/Paris');
                                            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                            $format1 = '%A %d %B %Y %H:%M:%S';
                                            $date1 = strftime($format1);
                                            $fichier = fopen('./../log/error_log_categorie.txt', 'c+b');
                                            fseek($fichier, filesize('./../log/error_log_categorie.txt'));
                                            fwrite($fichier, "\n\n" .$date1. " - Erreur import jeux. Erreur : " .$e);
                                            fclose($fichier);
                
                }

            }
            catch(PDOException $e){
                echo "Erreur : " .$e->getMessage();

                date_default_timezone_set('Europe/Paris');
                                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                $format1 = '%A %d %B %Y %H:%M:%S';
                                $date1 = strftime($format1);
                                $fichier = fopen('./../log/error_log_categorie.txt', 'c+b');
                                fseek($fichier, filesize('./../log/error_log_categorie.txt'));
                                fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données. Erreur : " .$e);
                                fclose($fichier);

                                echo    '<article class="connexion-bdd-hs">

                                            <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                                                Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>

                                        </article>';
            }
            ?>

        <?php
        /* imporation du footer */
        include $lien.'pages/footer.php';
        ?>

        <script src="./../js/index.js"></script>
    </body>
</html>