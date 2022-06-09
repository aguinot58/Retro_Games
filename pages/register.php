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

    $identifiant = valid_donnees($_POST["identifiant"]);
    $mdp = valid_donnees($_POST["mdp"]);
    $conf_mdp = valid_donnees($_POST["conf_mdp"]);
    $email = valid_donnees($_POST["email"]);

    if (!empty($identifiant) && !empty($mdp) && !empty($conf_mdp) && !empty($email) && ($mdp === $conf_mdp) && 
        preg_match("/^[A-Z]{1}[A-Za-z0-9]{6,39}$/", $identifiant) && (filter_var($email, FILTER_VALIDATE_EMAIL)) &&
        preg_match("/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d][A-Za-z\d!@#$%^&*()_+]{7,19}$/", $mdp)) {

        try{
            $pwd_peppered = hash_hmac("sha256", $mdp, $pepper);
            $pwd_hashed = password_hash($pwd_peppered, PASSWORD_ARGON2ID);

            //On insère une partie des données reçues dans la table utilisateur
            $sth = $conn->prepare("INSERT INTO utilisateurs (Ident_user, Mdp_user, Mail_user, Etat_user) VALUES(:identifiant, :pwd, :mail, 1)");
            $sth->bindParam(':identifiant', $identifiant);    
            $sth->bindParam(':pwd', $pwd_hashed);
            $sth->bindParam(':mail', $email); 
            $sth->execute();
            
            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;

            //On renvoie l'utilisateur vers la page de remerciement
            header("Location:".$lien."pages/rem_inscrip.php");

            }
            catch(PDOException $e){

                //echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
                write_error_log("./../log/error_log_inscription.txt","Impossible d'injecter les données.", $e);
                echo 'Une erreur est survenue, merci de réessayer ultérieurement.';

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;

            }

    } else {
        //echo 'pb de données';
        echo 'Une erreur est survenue, merci de vérifier les informations saisies.';
    }
    
?>