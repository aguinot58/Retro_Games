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

    $id_rep = $_POST['id_rep'];

    try{

        // on extrait l'id du message de la réponse concernée
        $sth = $conn->prepare("SELECT Id_msg FROM reponses where Id_rep = :id_rep");
        $sth->bindParam(':id_rep', $id_rep);
        $sth->execute();
        $id_msg = $sth->fetchColumn();

        //On efface les données reçues dans la table reponses
        $sth = $conn->prepare("DELETE FROM reponses WHERE Id_rep = :id_rep"); 
        $sth->bindParam(':id_rep', $id_rep);
        $sth->execute();

        // on vérifie si il reste d'autre réponses à ce message dans la table
        $sth = $conn->prepare("SELECT COUNT(Id_rep) as nb_rep FROM reponses where Id_msg = :id_msg");
        $sth->bindParam(':id_msg', $id_msg);
        $sth->execute();
        $id_msg = $sth->fetchColumn();

        if ($id_msg == 0) {
            // si il n'y a plus de réponse pour ce message, on vient mettre à jour le champs indiquant 
            // qu'une réponse a été faites dans la table messages 

            $sth = $conn->prepare("UPDATE messages set Rep_eff_msg = 0 WHERE Id_msg = :id_msg"); 
            $sth->bindParam(':id_msg', $id_msg);
            $sth->execute();
        }

        /*Fermeture de la connexion à la base de données*/
        $sth = null;
        $conn = null;

        $_SESSION['suppr_rep'] = true;

        header("Location:./../pages/back_msg.php");

    }
    catch(PDOException $e){
                            
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $format1 = '%A %d %B %Y %H:%M:%S';
        $date1 = strftime($format1);
        $fichier = fopen('./../log/error_log_suppr_rep.txt', 'c+b');
        fseek($fichier, filesize('./../log/error_log_suppr_rep.txt'));
        fwrite($fichier, "\n\n" .$date1. " - Erreur suppression reponse. Erreur : " .$e);
        fclose($fichier);

        /*Fermeture de la connexion à la base de données*/
        $sth = null;
        $conn = null;
        
        echo   'Erreur suppression reponse - annulation';
    }

?>