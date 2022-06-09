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

    $ident_user = valid_donnees($_POST["nom_user"]);
    $etat_user = valid_donnees($_POST["etat_user"]);
    $id_user = valid_donnees($_POST["id_user"]);
    $mail_user = valid_donnees($_POST["mail_user"]);
    $niv_admin = valid_donnees($_POST["niv_admin"]);

    if (!empty($ident_user) && !empty($id_user) && !empty($mail_user) && !empty($niv_admin) && 
        (filter_var($mail_user, FILTER_VALIDATE_EMAIL)) && preg_match("/^[A-Z]{1}[A-Za-z0-9]{6,39}$/", $ident_user) && 
        strlen($ident_user) <= 50 && strlen($mail_user) <= 255) {
    
        try{    

            //On met à jour les données reçues dans la table utilistauers
            $sth = $conn->prepare("UPDATE utilisateurs set Ident_user=:ident_user, Mail_user=:mail_user, Etat_user=:etat_user WHERE Id_user = :id_user");
            $sth->bindParam(':ident_user', $ident_user);    
            $sth->bindParam(':mail_user', $mail_user);
            $sth->bindParam(':etat_user', $etat_user); 
            $sth->bindParam(':id_user', $id_user); 
            $sth->execute();

            //On met à jour les données reçues dans la table utilistauers
            $sth = $conn->prepare("UPDATE admin set Niv_admin=:niv_admin WHERE Id_user = :id_user");
            $sth->bindParam(':niv_admin', $niv_admin);    
            $sth->bindParam(':id_user', $id_user); 
            $sth->execute();

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;

            $_SESSION['modif_user'] = true;

            //On renvoie l'utilisateur vers la page d'administration des utilisateurs
            header("Location:./../pages/back_utilisateurs.php");

        }
        catch(PDOException $e){

            //echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
            write_error_log("./../log/error_log_update_user.txt","Impossible de mettre à jour les données.", $e);
            echo 'Une erreur est survenue, mise à jour des données annulée.';

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;
        }

    } else {

        echo "Merci de vérifier les informations saisies";
    
    }

?>