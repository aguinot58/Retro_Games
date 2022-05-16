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

            echo '<img id="megaman_deco" src="'.$lien.'img/megaman_deco.png" alt="Mega Man">';
            echo '<img id="sonic_deco" src="'.$lien.'img/sonic_deco.png" alt="Sonic">';
            echo '<img id="snake_deco" src="'.$lien.'img/snake_deco.png" alt="Solid Snake">';
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
                    $consoles = $sth->fetchColumn();

                    $sth = $conn->prepare("SELECT * FROM categories where Id_cat=:categorie");
                    $sth->bindParam(':categorie', $categorie);
                    $sth->execute();
                    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                    $categorie= $sth->fetchAll(PDO::FETCH_ASSOC);

                    $sth = $conn->prepare("SELECT Logo_cat, Img_cat FROM categories where Id_cat=$consoles");
                    $sth->execute();
                    $logos = $sth->fetchAll(PDO::FETCH_ASSOC);

                    foreach($logos as $logo){
                    ?>

                  

                    <div class="meuble">
                        <div class="console_ici">    
                            <div class="console_logo">
                                <div class="tele_logo">
                                    <img class="tele" src="../img/télé2.png">
                                    <img class="logo" src="../img/<?php echo $logo['Logo_cat'] ?>" alt="Logo_super_nintendo">
                                </div>
                                <img class="console" src="../img/<?php echo $logo['Img_cat'] ?>" alt="console_super_nintendo">
                            </div>
                    <?php
                    }
                    try{

                            $sth = $conn->prepare("SELECT COUNT(Id_jeux) FROM jeux WHERE Cat_jeux = $consoles and Etat_jeux = 1");
                            $sth->execute();
                            //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                            $nb_jeux_tot = $sth->fetchColumn();
                                    
                            echo    '<div class="haut_meuble">
                                        <p>Nombre de jeux dans le catalogue : '.$nb_jeux_tot.'</p>
                                    </div>';
                            $nb_jeux_integre = 0;
                            while ($nb_jeux_integre < $nb_jeux_tot) {

                            //Sélectionne les valeurs dans les colonnes pour chaque entrée de la table
                            $sth = $conn->prepare("SELECT Id_jeux, Nom_jeux, Img_jeux FROM jeux WHERE Cat_jeux = $consoles and Etat_jeux = 1");
                            $sth->execute();
                             //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                            $jeux = $sth->fetchAll(PDO::FETCH_ASSOC);

                            echo '<article class="log">';
                                echo '<div class="etagere">';
                                    echo '<ul class="jeux_etagere">';
                             foreach ($jeux as $jeu) {

                               
                            
                                     echo      ' <li class="carte"><img id='.$jeu['Id_jeux'].'  src="../img/'.$jeu['Img_jeux'].' " alt="jaquette'.$jeu['Nom_jeux'].' "" onclick="page_jeux(event)"></li>';
                   ?>   
                   
                   <?php

                    
                            $nb_jeux_integre += 1;
                                }
                                    echo     '</ul>';
                                echo'</div>'; 
                        echo '</div>';
                    echo'</div>';
                            echo     '</article>';
                            }
                    ?>
                    <?php
                        
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
                        //Fermeture de la connexion à la base de données
                        $sth = null;
                        $conn = null;
                    }

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
                    //Fermeture de la connexion à la base de données
                    $sth = null;
                    $conn = null;
                       
                
                    
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