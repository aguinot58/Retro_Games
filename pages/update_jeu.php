<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    if (!isset($_SESSION['img_jeu_modif'])){
        $_SESSION['img_jeu_modif'] = 'non';
    }

    require $lien.'pages/fonctions.php';
    require $lien.'pages/conn_bdd.php';

    $id_jeu = $_POST["id_jeux"];
    $nom_jeu = str_replace("'"," ",valid_donnees($_POST["nom_jeux2"]));
    $date_jeu = valid_donnees($_POST["date_jeux2"]);
    $dev_jeu = str_replace("'"," ",valid_donnees($_POST["dev_jeux2"]));
    $edit_jeu = str_replace("'"," ",valid_donnees($_POST["edit_jeux2"]));
    $desc_jeu = str_replace("'"," ",valid_donnees($_POST["desc_jeux2"]));
    $cat_jeu = valid_donnees($_POST["cat_jeux2"]);
    $etat_jeu = valid_donnees($_POST["etat_jeux"]);
    $gestion_jeu = valid_donnees($_POST['gest_jeux']);

    $img_jeu = valid_donnees($_POST["img_jeux3"]);
    $_SESSION['img_jeu_modif'] = 'non';
    $format_img = true;

    if ($img_jeu==""){
        $img_jeu = valid_donnees($_FILES['img_jeux2']['name']);
        $_SESSION['img_jeu_modif'] = 'oui';
        if(($_FILES['img_jeux2']['type']=="image/png" || $_FILES['img_jeux2']['type']="image/jpg" || $_FILES['img_jeux2']['type']="image/jpeg")){
            $format_img = true;
        }else{
            $format_img = false;
        }
    }

    if (!empty($nom_jeu) && !empty($date_jeu) && !empty($dev_jeu) && !empty($edit_jeu) && !empty($desc_jeu) && $format_img == true &&
        !empty($img_jeu) && !empty($cat_jeu) && preg_match("/^(0?\d|[12]\d|3[01])-(0?\d|1[012])-((?:19|20)\d{2})$/", $date_jeu) && 
        strlen($nom_jeu) <= 50 && strlen($dev_jeu) <= 50 && strlen($edit_jeu) <= 50 && strlen($desc_jeu) <= 500 && $gestion_jeu > 0) {

        // on remet la date dans l'autre sens au format aaaa-mm-jj
        $timestamp = strtotime($date_jeu); 
        $date_bon_format = date("Y-m-d", $timestamp );

        $identifiant = $_SESSION['user'];

        try{

            //On met ?? jour les donn??es re??ues dans la table jeux
            $sth = $conn->prepare("UPDATE jeux set Nom_jeux=:nom_jeu, Desc_jeux=:desc_jeu, Cat_jeux=:cat_jeu, Dev_jeux=:dev_jeu, Editeur_jeux=:edit_jeu, 
                                        Date_jeux=:date_jeu, Img_jeux=:img_jeu, Etat_jeux=:etat_jeu WHERE Id_jeux = :id_jeu");
            $sth->bindParam(':nom_jeu', $nom_jeu);    
            $sth->bindParam(':desc_jeu', $desc_jeu);
            $sth->bindParam(':cat_jeu', $cat_jeu); 
            $sth->bindParam(':dev_jeu', $dev_jeu); 
            $sth->bindParam(':edit_jeu', $edit_jeu); 
            $sth->bindParam(':date_jeu', $date_bon_format); 
            $sth->bindParam(':img_jeu', $img_jeu); 
            $sth->bindParam(':etat_jeu', $etat_jeu); 
            $sth->bindParam(':id_jeu', $id_jeu); 
            $sth->execute();

            //S??lectionne les valeurs dans les colonnes pour chaque entr??e de la table
            $sth = $conn->prepare("SELECT Id_user FROM gestion_jeux WHERE Id_jeux = $id_jeu");
            $sth->execute();
            //Retourne un tableau associatif pour chaque entr??e de notre table avec le nom des colonnes s??lectionn??es en clefs
            $gestion_id = $sth->fetchcolumn();

            if ($gestion_id != $gestion_jeu) {

                //On met ?? jour les donn??es re??ues dans la table gestion_jeux
                $sth = $conn->prepare("UPDATE gestion_jeux set Id_user=:id_user WHERE Id_jeux = :id_jeu");
                $sth->bindParam(':id_user', $gestion_jeu);    
                $sth->bindParam(':id_jeu', $id_jeu);
                $sth->execute();

            }

            /*Fermeture de la connexion ?? la base de donn??es*/
            $sth = null;
            $conn = null;

            if ($_SESSION['img_jeu_modif'] == 'oui') {

                // chemin complet de la jaquette choisie par l'utilisateur
                $file = $_FILES['img_jeux2']['tmp_name'];

                // dossier de sauvegarde de l'image apr??s traitement
                $folder_save = "./../img/";

                switch ($cat_jeu) {
                    case 1: // super nintendo
                        modifier_image($file, $_FILES['img_jeux2']['name'], $folder_save, 266, 194);
                        break;
                    case 2: // megadrive
                        modifier_image($file, $_FILES['img_jeux2']['name'], $folder_save, 196, 266);
                        break;
                    case 3: // playstation
                        modifier_image($file, $_FILES['img_jeux2']['name'], $folder_save, 266, 266);
                        break;
                }
            }else{
            }

            $_SESSION['modif_jeu'] = true;

            //On renvoie l'utilisateur vers la page d'administration des jeux
            header("Location:./../pages/back_jeux.php");

        }
        catch(PDOException $e){

            // rollback de la transaction
            $conn->rollBack();

            //echo 'Impossible de traiter les donn??es. Erreur : '.$e->getMessage();
            write_error_log("./../log/error_log_update_jeu.txt","Impossible de mettre ?? jour les donn??es.", $e);
            echo 'Une erreur est survenue, mise ?? jour des donn??es annul??e.';

            /*Fermeture de la connexion ?? la base de donn??es*/
            $sth = null;
            $conn = null;
        }

    } else {

        echo "Merci de v??rifier les informations saisies";
    
    }
?>