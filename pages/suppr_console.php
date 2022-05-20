<?php

    require './../pages/fonctions.php';

    if(!empty($_POST) && array_key_exists("id_cat", $_POST)){

        $id_cat = $_POST['id_cat'];

        /* Connexion à une base de données en PDO */
        $configs = include('./../pages/config.php');
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

                $sth = $conn->prepare("UPDATE categories SET Etat_cat = 0 WHERE Id_cat = :id_cat");
                $sth->bindParam(':id_cat', $id_cat);
                $sth->execute();
            
                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;

                $_SESSION["suppr_console"] = true ;

                echo 'suppression reussie';

            }
            catch(PDOException $e){

                $conn->rollBack();

                date_default_timezone_set('Europe/Paris');
                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                $format1 = '%A %d %B %Y %H:%M:%S';
                $date1 = strftime($format1);
                $fichier = fopen('./../log/error_log_back_suppr_jeux.txt', 'c+b');
                fseek($fichier, filesize('./../log/error_log_back_suppr_jeux.txt'));
                fwrite($fichier, "\n\n" .$date1. " - Erreur suppression jeux. Erreur : " .$e);
                fclose($fichier);

                echo 'erreur suppression console';

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
            $fichier = fopen('./../log/error_log_back_suppr_jeux.txt', 'c+b');
            fseek($fichier, filesize('./../log/error_log_back_suppr_jeux.txt'));
            fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données - Erreur : " .$e);
            fclose($fichier);   

            echo 'echec connexion bdd';
        }
    } else {

        echo 'test if echec';

    }

?>