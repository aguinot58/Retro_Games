<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    require $lien.'pages/fonctions.php';
    require $lien.'pages/conn_bdd.php';

    if(!empty($_POST)){

        $ident_user = $_SESSION['user'];
        $ancien_mail = $_POST['ancien_mail'];
        $nouveau_mail = $_POST['nouv_mail'];
        $conf_mail = $_POST['conf_mail'];

        if ($nouveau_mail==$conf_mail) {

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

        } else {
            echo 'Nouvelle adresse et adresse de confirmation différentes - annulation';
        }

    } else {
        echo 'Erreur données transmises - annulation';
    } 

?>