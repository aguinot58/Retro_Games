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

    if (isset($_GET['id_jeux'])){
        $id_jeux = $_GET['id_jeux'];
    }
?>



<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/jeux.css">
        <link rel="stylesheet" href="../css/Header_footer.css">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Retro-Games</title>
        <link rel="shortcut icon" type="image/ico" href="./../img/icone.png">
    </head>

    <body>
        <?php
            /* importation header */
            include $lien.'pages/header.php';

            if ($_SESSION['logged'] == 'oui'){

                echo '<img id="psykokwak_deco" src="'.$lien.'img/psykokwak_deco.png" alt="psykokwak">';
                echo '<img id="fantasia_deco" src="'.$lien.'img/fantasia_deco.png" alt="Mickey Fantasia">';
                echo '<img id="medievil_deco" src="'.$lien.'img/medievil_deco.png" alt="Medievil">';

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

                        $sth = $conn->prepare("SELECT Cat_jeux FROM jeux where Id_jeux=$id_jeux");
                        $sth->execute();
                        //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                        $categorie = $sth->fetchColumn();

                        $sth = $conn->prepare("SELECT DISTINCT(Nom_cat) FROM categories INNER JOIN jeux ON categories.Id_cat = jeux.Cat_jeux where jeux.Cat_jeux=$categorie");
                        $sth->execute();
                        //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                        $console = $sth->fetchColumn();

                        $sth = $conn->prepare("SELECT * FROM jeux where Id_jeux=$id_jeux");
                        $sth->execute();
                        //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                        $jeux = $sth->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($jeux as $jeu){?>

                            <div class="jeu_seul">
                                <img class="image_jeu" src="../img/<?php echo $jeu['Img_jeux'] ?>" alt="jaquette<?php  echo $jeu['Date_jeux'] ?>">
                                <ul class="info_jeu">
                                    <p class="nom_console"><?php  echo $jeu['Nom_jeux'] ?> &emsp;&emsp;&emsp;&emsp; <?php  echo $console?></p> 
                                    <li class="description_dev">Developpeur : <?php  echo $jeu['Dev_jeux'] ?></li>
                                    <li class="description_dev">Editeur : <?php  echo $jeu['Editeur_jeux'] ?></li>
                                    <li class="description_dev">Date de sortie : <?php  echo $jeu['Date_jeux'] ?></li>
                                </ul>
                            </div>

                            <div class="description">
                                <p class="description_jeu"><?php  echo $jeu['Desc_jeux'] ?></p>
                                <img class="donkey" src="../img/donkey_kong_deco2.png" alt="image donkey kong">
                            </div>
                            <?php
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
                        $fichier = fopen('./../log/error_log_jeux.txt', 'c+b');
                        fseek($fichier, filesize('./../log/error_log_jeux.txt'));
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
                    $fichier = fopen('./../log/error_log_jeux.txt', 'c+b');
                    fseek($fichier, filesize('./../log/error_log_jeux.txt'));
                    fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données. Erreur : " .$e);
                    fclose($fichier);

                    echo    '<article>
                                <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                                    Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>
                            </article>';
                }

                echo '<div class="donkey_tombe">
                        <img class="donkey2" src="../img/donkey_kong_deco3.png" alt="donkey kong qui tombe">
                        <img class="donkey3" src="../img/donkey_kong_deco4.png" alt="donkey kong pas content">
                    </div>';

            } else {

                echo '<img id="psykokwak_deco2" src="'.$lien.'img/psykokwak_deco.png" alt="psykokwak">';
                echo '<img id="fantasia_deco2" src="'.$lien.'img/fantasia_deco.png" alt="Mickey Fantasia">';
                echo '<img id="medievil_deco2" src="'.$lien.'img/medievil_deco.png" alt="Medievil">';

                echo '<section id="section-1">
                        <img class="haut-etagere" src="'.$lien.'img/shelf-header.jpg" alt="haut étagère">
                        <article class="non-log">
                            <h3>Bienvenue sur Retro-Games.com</h3>
                            <p>Vous trouverez sur notre site un catalogue de jeux retro-gaming, 
                                provenant de plusieurs générations de consoles.</p>
                            <p>Pour consulter notre catalogue, merci de vous inscrire / vous connecter à votre compte.</p>
                        </article>
                    </section>';

            }

            /* imporation du footer */
            include $lien.'pages/footer.php';
        ?>

        <script src="./../js/index.js"></script>
    </body>
</html>