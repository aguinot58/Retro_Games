<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    require $lien.'pages/fonctions.php';

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

        if(!empty($_POST) && array_key_exists("id_user", $_POST)){
            $id_user = $_POST['id_user'];
    
            $sth = $conn->prepare("SELECT Ident_user FROM utilisateurs WHERE Id_user=:id_user");
            $sth->bindParam(':id_user', $id_user);
            $sth->execute();
            //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
            $ident_user = $sth->fetchColumn();
    
        } else {
            $ident_user = $_SESSION['user'];
        }

        try{

            //On efface les données reçues dans la table utilisateurs
            $sth = $conn->prepare("DELETE FROM utilisateurs WHERE Ident_user = :ident_user"); 
            $sth->bindParam(':ident_user', $ident_user);
            $sth->execute();

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;

            $_SESSION['suppr_user'] = true;

            if(!empty($_POST) && array_key_exists("id_user", $_POST)){
                header("Location:./../pages/back_utilisateurs.php");
            } else {
                header("Location:./../pages/logout.php");
            }

        }
        catch(PDOException $e){
                            
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $format1 = '%A %d %B %Y %H:%M:%S';
        $date1 = strftime($format1);
        $fichier = fopen('./../log/error_log_suppr_user.txt', 'c+b');
        fseek($fichier, filesize('./../log/error_log_suppr_user.txt'));
        fwrite($fichier, "\n\n" .$date1. " - Erreur suppression utilisateur. Erreur : " .$e);
        fclose($fichier);

        /*Fermeture de la connexion à la base de données*/
        $sth = null;
        $conn = null;
        
        echo   'Erreur suppression utilisateur - annulation';
        }

    }
    /*On capture les exceptions et si une exception est lancée, on écrit dans un fichier log
    *les informations relatives à celle-ci*/
    catch(PDOException $e){
    date_default_timezone_set('Europe/Paris');
    setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
    $format1 = '%A %d %B %Y %H:%M:%S';
    $date1 = strftime($format1);
    $fichier = fopen('./../log/error_log_suppr_user.txt', 'c+b');
    fseek($fichier, filesize('./../log/error_log_suppr_user.txt'));
    fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données - Erreur : " .$e);
    fclose($fichier);
                            
    echo   'Erreur connexion bdd - annulation';
    }

?>