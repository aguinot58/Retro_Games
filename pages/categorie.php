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

    if (isset($_GET['console'])){
        $nom_console = $_GET['console'];
    }
?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="./../css/categorie.css">
        <link rel="stylesheet" href="./../css/header_footer.css">
        <link rel="shortcut icon" type="image/ico" href="./../img/icone.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Retro-Games</title>
    </head>

    <body>
        
        <?php

            /* importation header */
            include $lien.'pages/header.php';

            if ($_SESSION['logged'] == 'oui'){

                echo '<img id="kirby_deco" src="'.$lien.'img/kirby_deco.png" alt="Kirby">';
                echo '<img id="alex_deco" src="'.$lien.'img/alex_deco.png" alt="Alex Kidd">';
                echo '<img id="sora_deco" src="'.$lien.'img/sora_deco.png" alt="Sora de Kingdom Hearts">';
                
                require $lien.'pages/conn_bdd.php';

                    try{

                        echo '<div class="meuble">
                                <div class="console_ici">';

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
                                        
                            echo    '<div class="nb_jeux">
                                        <p>Nombre de jeux dans le catalogue : '.$nb_jeux_tot.'</p>
                                    </div>';
                            $nb_jeux_integre = 0;

                            $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
                            $limite = 12;
                            $debut = ($page - 1) * $limite;
                            $nombreDePages = ceil($nb_jeux_tot / $limite);

                            $sth = $conn->prepare('SELECT * FROM jeux WHERE Cat_jeux = :console AND Etat_jeux = 1 ORDER BY Id_jeux LIMIT :limite OFFSET :debut');
                            $sth->bindValue('console', $consoles, PDO::PARAM_INT);
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
                            echo '</div>
                                </div>
                            </div>';
                            
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
                
            } else {

                echo '<img id="kirby_deco2" src="'.$lien.'img/kirby_deco.png" alt="Kirby">';
                echo '<img id="alex_deco2" src="'.$lien.'img/alex_deco.png" alt="Alex Kidd">';
                echo '<img id="sora_deco2" src="'.$lien.'img/sora_deco.png" alt="Sora de Kingdom Hearts">';

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