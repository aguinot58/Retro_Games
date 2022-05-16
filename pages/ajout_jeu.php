<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    require $lien.'pages/fonctions.php';

    $nom_jeu = str_replace("'"," ",valid_donnees($_POST["nom_jeux"]));
    $date_jeu = valid_donnees($_POST["date_jeux"]);
    $dev_jeu = str_replace("'"," ",valid_donnees($_POST["dev_jeux"]));
    $edit_jeu = str_replace("'"," ",valid_donnees($_POST["edit_jeux"]));
    $desc_jeu = str_replace("'"," ",valid_donnees($_POST["desc_jeux"]));
    $img_jeu = valid_donnees($_FILES['img_jeux']['name']);
    $cat_jeu = valid_donnees($_POST["cat_jeux"]);

    if (!empty($nom_jeu) && !empty($date_jeu) && !empty($dev_jeu) && !empty($edit_jeu) && !empty($desc_jeu) && 
        !empty($img_jeu) && !empty($cat_jeu) && preg_match("/^(0?\d|[12]\d|3[01])-(0?\d|1[012])-((?:19|20)\d{2})$/", $date_jeu) && 
        ($_FILES['img_jeux']['type']=="image/png" || $_FILES['img_jeux']['type']="image/jpg" || $_FILES['img_jeux']['type']="image/jpeg") &&
        strlen($nom_jeu) <= 50 && strlen($dev_jeu) <= 50 && strlen($edit_jeu) <= 50 && strlen($desc_jeu) <= 500) {

        // on remet la date dans l'autre sens au format aaaa-mm-jj
        $timestamp = strtotime($date_jeu); 
        $date_bon_format = date("Y-m-d", $timestamp );

        // chemin complet de la jaquette choisie par l'utilisateur
        $file = $_FILES['img_jeux']['tmp_name'];

        // dossier de sauvegarde de l'image après traitement
        $folder_save = "./../img/";

        $identifiant = $_SESSION['user'];

        try{

            /* Connexion à une base de données en PDO */
            $configs = include('config.php');
            $servername = $configs['servername'];
            $username = $configs['username'];
            $password = $configs['password'];
            $db = $configs['database'];
            // On établit la connexion
            $conn = new PDO("mysql:host=$servername;dbname=$db;charset=UTF8", $username, $password);
            // On définit le mode d'erreur de PDO sur Exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            try{

                $conn->beginTransaction();

                //On insère une partie des données reçues dans la table jeux
                $sth = $conn->prepare("INSERT INTO jeux (Nom_jeux, Desc_jeux, Cat_jeux, Dev_jeux, Editeur_jeux, Date_jeux, Img_jeux, Etat_jeux) VALUES
                        (:nom_jeu, :desc_jeu, :cat_jeu, :dev_jeu, :edit_jeu, :date_jeu, :img_jeu, 1)");
                $sth->bindParam(':nom_jeu', $nom_jeu);    
                $sth->bindParam(':desc_jeu', $desc_jeu);
                $sth->bindParam(':cat_jeu', $cat_jeu); 
                $sth->bindParam(':dev_jeu', $dev_jeu); 
                $sth->bindParam(':edit_jeu', $edit_jeu); 
                $sth->bindParam(':date_jeu', $date_bon_format); 
                $sth->bindParam(':img_jeu', $img_jeu); 
                $sth->execute();

                // on doit récupérer l'id nouvellement créée du jeu que l'on vient d'injecter pour alimenter la table gestion_jeux
                $sth = $conn->prepare("SELECT Id_jeux FROM jeux where Nom_jeux = '$nom_jeu'");
                $sth->execute();
                $id_jeux = $sth->fetchColumn();

                // on extrait l'id de l'utilisateur
                $sth = $conn->prepare("SELECT Id_user FROM utilisateurs where Ident_user = '$identifiant'");
                $sth->execute();
                $id_user = $sth->fetchColumn();

                //On insère les deux Id dans la table gestion-jeux
                $sth = $conn->prepare("INSERT INTO gestion_jeux (Id_user, Id_jeux) VALUES (:id_user, :id_jeu)");
                $sth->bindParam(':id_user', $id_user);    
                $sth->bindParam(':id_jeu', $id_jeux);
                $sth->execute();

                $conn->commit();
            
                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;

                switch ($cat_jeu) {
                    case 1: // super nintendo
                        modifier_image($file, $_FILES['img_jeux']['name'], $folder_save, 266, 194);
                        break;
                    case 2: // megadrive
                        modifier_image($file, $_FILES['img_jeux']['name'], $folder_save, 196, 266);
                        break;
                    case 3: // playstation
                        modifier_image($file, $_FILES['img_jeux']['name'], $folder_save, 266, 266);
                        break;
                }

                $_SESSION['ajout_jeu'] = true;

                //On renvoie l'utilisateur vers la page d'administration des jeux
                header("Location:./../pages/back_jeux.php");

            }
            catch(PDOException $e){

                // rollback de la transaction
                $conn->rollBack();

                //echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
                write_error_log("./../log/error_log_ajout_jeu.txt","Impossible d'injecter les données.", $e);
                echo 'Une erreur est survenue, injection des données annulée.';

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;
            }

        }
        catch(PDOException $e){
        // erreur de connexion à la bdd
        //echo "Erreur : " . $e->getMessage();
        write_error_log("./../log/error_log_ajout_jeu.txt","Impossible de se connecter à la base de données.", $e);
        echo 'Une erreur est survenue, connexion à la base de données impossible.';
        }

    } else {

        echo "Merci de vérifier les informations saisies";

    }

?>

