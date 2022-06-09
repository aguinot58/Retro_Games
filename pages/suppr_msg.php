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

    $id_msg = $_POST['id_msg'];

    try{

        //On efface les données reçues dans la table messages
        $sth = $conn->prepare("DELETE FROM messages WHERE Id_msg = :id_msg"); 
        $sth->bindParam(':id_msg', $id_msg);
        $sth->execute();

        /*Fermeture de la connexion à la base de données*/
        $sth = null;
        $conn = null;

        $_SESSION['suppr_msg'] = true;

        header("Location:./../pages/back_msg.php");

    }
    catch(PDOException $e){
                            
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $format1 = '%A %d %B %Y %H:%M:%S';
        $date1 = strftime($format1);
        $fichier = fopen('./../log/error_log_suppr_msg.txt', 'c+b');
        fseek($fichier, filesize('./../log/error_log_suppr_msg.txt'));
        fwrite($fichier, "\n\n" .$date1. " - Erreur suppression message. Erreur : " .$e);
        fclose($fichier);

        /*Fermeture de la connexion à la base de données*/
        $sth = null;
        $conn = null;
        
        echo   'Erreur suppression message - annulation';
    }

?>