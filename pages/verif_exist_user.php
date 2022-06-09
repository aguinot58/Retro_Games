<?php

    session_start();

    if(!empty($_POST) && array_key_exists("ident_user", $_POST)) {

        $ident_user = $_POST['ident_user'];

        $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

        if ($curPageName == "index.php") {
            $lien = "./";
        } else {
            $lien = "./../";
        }

        require $lien.'pages/fonctions.php';
        require $lien.'pages/conn_bdd.php';

        try{

            //On met à jour les données reçues dans la table jeux
            $sth = $conn->prepare("Select Id_user FROM utilisateurs WHERE Ident_user = :ident_user");  
            $sth->bindParam(':ident_user', $ident_user);
            $sth->execute;
            $id_user = $sth->fetchColumn();

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;

            echo $id_user;

            if ($id_user==null || $id_user=="") {
                echo 'Identifiant disponible';
            } else {
                echo 'Utilisateur déjà existant';
            }

        }
        catch(PDOException $e){
                                    
            date_default_timezone_set('Europe/Paris');
            setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
            $format1 = '%A %d %B %Y %H:%M:%S';
            $date1 = strftime($format1);
            $fichier = fopen('./../log/error_log_verif_exist_user.txt', 'c+b');
            fseek($fichier, filesize('./../log/error_log_verif_exist_user.txt'));
            fwrite($fichier, "\n\n" .$date1. " - Erreur verif existance identifiant. Erreur : " .$e);
            fclose($fichier);

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;
                    
            echo   'Erreur vérification existence identifiant - annulation';
            
        }

    } else {

        echo 'Erreur identifiant';

    }

?>