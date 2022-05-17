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

    require $lien.'pages/fonctions.php';
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="./css/fonts.css">
        <link rel="stylesheet" href="./css/index.css">
        <link rel="stylesheet" href="./css/header_footer.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Retro-Games</title>
        <link rel="shortcut icon" type="image/ico" href="./img/icone.png">
    </head>

    <body>
        <?php
            /* importation header */
            include $lien.'pages/header.php';
        ?>

        <?php

            if ($_SESSION['logged'] == 'oui'){

                echo '<img id="megaman_deco" src="'.$lien.'img/megaman_deco.png" alt="Mega Man">';
                echo '<img id="sonic_deco" src="'.$lien.'img/sonic_deco.png" alt="Sonic">';
                echo '<img id="snake_deco" src="'.$lien.'img/snake_deco.png" alt="Solid Snake">';


                echo '<section id="section-2">';
                        
                            
                        /* Connexion à une base de données en PDO */
                        $configs = include($lien.'pages/config.php');
                        $servername = $configs['servername'];
                        $username = $configs['username'];
                        $password = $configs['password'];
                        $db = $configs['database'];

                        //On établit la connexion
                        try{
                            $conn = new PDO("mysql:host=$servername;dbname=$db;charset=UTF8", $username, $password);
                            //On définit le mode d'erreur de PDO sur Exception
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            try{

                                $sth = $conn->prepare("SELECT COUNT(Id_jeux) FROM jeux WHERE Etat_jeux = 1");
                                $sth->execute();
                                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                $nb_jeux_tot = $sth->fetchColumn();

                                echo '<div class="nb_jeux">
                                        <!--<img class="haut-etagere" src="'.$lien.'img/shelf-header.jpg" alt="haut étagère">-->
                                        <p>Nombre de jeux dans le catalogue : '.$nb_jeux_tot.'</p>
                                    </div>';

                                $nb_jeux_integre = 0;

                                /*while ($nb_jeux_integre < $nb_jeux_tot) {

                                    //Sélectionne les valeurs dans les colonnes pour chaque entrée de la table
                                    $sth = $conn->prepare("SELECT Id_jeux, Nom_jeux, Img_jeux FROM jeux WHERE Etat_jeux = 1 ORDER BY Id_jeux ASC LIMIT $nb_jeux_integre, 4");
                                    $sth->execute();
                                    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                    $jeux = $sth->fetchAll(PDO::FETCH_ASSOC);

                                    echo '<article class="log">';

                                    foreach ($jeux as $jeu) {

                                        
                                        echo    '<article class="carte">
                                                    <figure>
                                                        <img id="'.$jeu['Id_jeux'].'" title="' .$jeu['Nom_jeux']. '" src="'.$lien.'img/' .$jeu['Img_jeux']. '" alt="Image '.$jeu['Nom_jeux'].'" onclick="page_jeux(event)">
                                                    </figure>
                                                </article>';

                                        $nb_jeux_integre += 1;

                                    };

                                    echo '</article>';

                                }*/

                                $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
                                $limite = 12;
                                $debut = ($page - 1) * $limite;
                                $nombreDePages = ceil($nb_jeux_tot / $limite);

                                $sth = $conn->prepare('SELECT * FROM jeux WHERE Etat_jeux = 1 ORDER BY Id_jeux LIMIT :limite OFFSET :debut');
                                $sth->bindValue('limite', $limite, PDO::PARAM_INT);
                                $sth->bindValue('debut', $debut, PDO::PARAM_INT); 
                                $sth->execute();
                                //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
                                $jeux = $sth->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($jeux as $jeu) {

                                    if ($nb_jeux_integre % 4 == 0 || $nb_jeux_integre == 0) {
                                        echo '<article class="log">';
                                    }
  
                                    echo    '<article class="carte">
                                                <figure>
                                                    <img id="'.$jeu['Id_jeux'].'" title="' .$jeu['Nom_jeux']. '" src="'.$lien.'img/' .$jeu['Img_jeux']. '" alt="Image '.$jeu['Nom_jeux'].'" onclick="page_jeux(event)">
                                                </figure>
                                            </article>';

                                    $nb_jeux_integre += 1;

                                    if ($nb_jeux_integre % 4 == 0 || $nb_jeux_integre == ($nb_jeux_tot-$debut)) {
                                        echo '</article>';
                                    }

                                };

                                echo '<div class="pagination">';
                                    if ($page > 1):
                                        ?><a href="?page=<?php echo $page - 1; ?>">Page précédente</a> — <?php
                                    endif;
                                    
                                    /* On va effectuer une boucle autant de fois que l'on a de pages */
                                    for ($i = 1; $i <= $nombreDePages; $i++):
                                        if ($i==$page){
                                            ?><a href="?page=<?php echo $i; ?>"><?php echo '<span class="page_active">'.$i.'</span>'; ?></a> <?php
                                        } else {
                                            ?><a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a> <?php
                                        }
                                    endfor;
                                    
                                    /* Avec le nombre total de pages, on peut aussi masquer le lien
                                    * vers la page suivante quand on est sur la dernière */
                                    if ($page < $nombreDePages):
                                        ?>— <a href="?page=<?php echo $page + 1; ?>">Page suivante</a><?php
                                    endif;
                                echo '</div>';

                                //Fermeture de la connexion à la base de données
                                $sth = null;
                                $conn = null;

                            }
                            catch(PDOException $e){
    
                                date_default_timezone_set('Europe/Paris');
                                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                $format1 = '%A %d %B %Y %H:%M:%S';
                                $date1 = strftime($format1);
                                $fichier = fopen('./log/error_log_index.txt', 'c+b');
                                fseek($fichier, filesize('./log/error_log_index.txt'));
                                fwrite($fichier, "\n\n" .$date1. " - Erreur import jeux. Erreur : " .$e);
                                fclose($fichier);

                                /*Fermeture de la connexion à la base de données*/
                                $sth = null;
                                $conn = null;
                                
                            }

                        }
                        /*On capture les exceptions et si une exception est lancée, on écrit dans un fichier log
                        *les informations relatives à celle-ci*/
                        catch(PDOException $e){
                        //echo "Erreur : " . $e->getMessage();
                        date_default_timezone_set('Europe/Paris');
                        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                        $format1 = '%A %d %B %Y %H:%M:%S';
                        $date1 = strftime($format1);
                        $fichier = fopen('./log/error_log_index.txt', 'c+b');
                        fseek($fichier, filesize('./log/error_log_index.txt'));
                        fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données. Erreur : " .$e);
                        fclose($fichier);

                        echo    '<article class="connexion-bdd-hs">

                                    <p>Une erreur est survenue lors de la connexion à la base de données.<br><br>
                                        Merci de rafraichir la page, et si le problème persiste, de réessayer ultérieurement.   </p>

                                </article>';

                        }
                echo    '</section>';

            } else {

                echo '<img id="megaman_deco2" src="'.$lien.'img/megaman_deco.png" alt="Mega Man">';
                echo '<img id="sonic_deco2" src="'.$lien.'img/sonic_deco.png" alt="Sonic">';
                echo '<img id="snake_deco2" src="'.$lien.'img/snake_deco.png" alt="Solid Snake">';

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

        ?>

        <?php
            /* imporation du footer */
            include $lien.'pages/footer.php';
        ?>

        <script src="./js/index.js"></script>
    </body>
</html>