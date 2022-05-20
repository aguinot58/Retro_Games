<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    require $lien.'pages/fonctions.php';


   
    $nom_console = str_replace("'"," ",valid_donnees($_POST["nom_cat"]));
    $logo_console = valid_donnees($_FILES['logo_cat']['name']);
    $img_console = valid_donnees($_FILES['img_cat']['name']);


    if (!empty($nom_console) && !empty($logo_console) && !empty($img_console) &&
        ($_FILES['logo_cat']['type']=="image/png" || $_FILES['logo_cat']['type']="image/jpg" || $_FILES['logo_cat']['type']="image/jpeg") &&
        ($_FILES['img_cat']['type']=="image/png" || $_FILES['img_cat']['type']="image/jpg" || $_FILES['img_cat']['type']="image/jpeg") &&
        strlen($nom_console) <= 50) {


            // chemin complet du logo choisie par l'utilisateur
        $file_logo = $_FILES['logo_cat']['tmp_name'];

        
            // chemin complet de l'image de la console choisie par l'utilisateur
        $file_console = $_FILES['img_cat']['tmp_name'];

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

                 //On insère une partie des données reçues dans la table catégories
                 $sth = $conn->prepare("INSERT INTO categories (Nom_cat, Logo_cat, Img_cat, Etat_cat) VALUES
                                        (:nom_cat, :logo_cat, :img_cat, 1)");
                $sth->bindParam(':nom_cat', $nom_console);
                $sth->bindParam(':logo_cat', $logo_console);
                $sth->bindParam(':img_cat', $img_console);
                $sth->execute();

                $conn->commit();


                sauvegarder_image($file_logo, $_FILES['logo_cat']['name'], $folder_save);

                sauvegarder_image($file_console, $_FILES['img_cat']['name'], $folder_save);

               

                 /*Fermeture de la connexion à la base de données*/
                 $sth = null;
                 $conn = null;

                 $_SESSION["ajout_console"] = true ;

                  //On renvoie l'utilisateur vers la page d'administration des jeux
                header("Location:./../pages/back_consoles.php");


            } 
            catch(PDOException $e){

                // rollback de la transaction
                $conn->rollBack();

                //echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
                write_error_log("./../log/error_log_ajout_console.txt","Impossible d'injecter les données.", $e);
                echo 'Une erreur est survenue, injection des données annulée.';

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;
            }
        }
        catch(PDOException $e){
            // erreur de connexion à la bdd
            //echo "Erreur : " . $e->getMessage();
            write_error_log("./../log/error_log_ajout_console.txt","Impossible de se connecter à la base de données.", $e);
            echo 'Une erreur est survenue, connexion à la base de données impossible.';
            }

        } else {

            echo "Merci de vérifier les informations saisies";
    
        }

    ?>