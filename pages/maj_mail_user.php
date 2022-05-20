<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    require $lien.'pages/fonctions.php';

    if(!empty($_POST)){

        $ident_user = $_SESSION['user'];
        $ancien_mail = $_POST['ancien_mail'];
        $nouveau_mail = $_POST['nouv_mail'];
        $conf_mail = $_POST['conf_mail'];

        if ($nouveau_mail==$conf_mail) {

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

                    //On met à jour les données reçues dans la table utilisateurs
                    $sth = $conn->prepare("UPDATE utilisateurs set Mail_user=:nouveau_mail WHERE Ident_user = :ident_user");
                    $sth->bindParam(':nouveau_mail', $nouveau_mail);    
                    $sth->bindParam(':ident_user', $ident_user);
                    $sth->execute();

                    /*Fermeture de la connexion à la base de données*/
                    $sth = null;
                    $conn = null;

                    header("Location:./../pages/profil.php");

                }
                catch(PDOException $e){
                                    
                date_default_timezone_set('Europe/Paris');
                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                $format1 = '%A %d %B %Y %H:%M:%S';
                $date1 = strftime($format1);
                $fichier = fopen('./../log/error_log_maj_mail_user.txt', 'c+b');
                fseek($fichier, filesize('./../log/error_log_maj_mail_user.txt'));
                fwrite($fichier, "\n\n" .$date1. " - Erreur maj mail user. Erreur : " .$e);
                fclose($fichier);

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;
                
                echo   'Erreur maj mail utilisateur - annulation';
                }

            }
            /*On capture les exceptions et si une exception est lancée, on écrit dans un fichier log
            *les informations relatives à celle-ci*/
            catch(PDOException $e){
            date_default_timezone_set('Europe/Paris');
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
            $format1 = '%A %d %B %Y %H:%M:%S';
            $date1 = strftime($format1);
            $fichier = fopen('./../log/error_log_maj_mail_user.txt', 'c+b');
            fseek($fichier, filesize('./../log/error_log_maj_mail_user.txt'));
            fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données - Erreur : " .$e);
            fclose($fichier);
                                    
            echo   'Erreur connexion bdd - annulation';
            }

        } else {

            echo 'Nouvelle adresse et adresse de confirmation différentes - annulation';

        }

    } else {

        echo 'Erreur données transmises - annulation';

    } 

?>