<?php

    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    if (!isset($_SESSION['img_console_modif'])){
        $_SESSION['img_console_modif'] = 'non';
    }

    if (!isset($_SESSION['img_logo_modif'])){
        $_SESSION['img_logo_modif'] = 'non';
    }

    require $lien.'pages/fonctions.php';
    require $lien.'pages/conn_bdd.php';
    
    $id_console = $_POST["id_cat"];
    $nom_console = str_replace("'"," ",valid_donnees($_POST["nom_console2"]));
    $img_logo = valid_donnees($_POST['img_logo3']);
    $img_console = valid_donnees($_POST['img_console3']);
    $etat_cat = valid_donnees($_POST["etat_cat"]);

    $_SESSION['img_console_modif'] = 'non';
    $format_img = true;

    $_SESSION['img_logo_modif'] = 'non';
    $format_img2 = true;
    
    if ($img_logo==""){
        $img_logo = valid_donnees($_FILES['img_logo2']['name']);
        $_SESSION['img_logo_modif'] = 'oui';
        if(($_FILES['img_logo2']['type']=="image/png")){
            $format_img2 = true;
        }else{
            $format_img2 = false;
        }    
    }
    if ($img_console==""){
        $img_console = valid_donnees($_FILES['img_console2']['name']);
        $_SESSION['img_console_modif'] = 'oui';
        if(($_FILES['img_console2']['type']=="image/png")){
        $format_img = true;
        }else{
            $format_img = false;
        }    
    }
    
    if (!empty($nom_console) && !empty($img_logo) && !empty($img_console) && !empty($id_console) && $format_img == true && $format_img2 == true &&
        strlen($nom_console) <= 50) {

        $identifiant = $_SESSION['user'];

        try{

            //On met à jour les données reçues dans la table categories
            $sth = $conn->prepare("UPDATE categories set Nom_cat=:nom_cat, Img_cat=:img_cat, Logo_cat=:logo_cat, Etat_cat=:etat_cat WHERE Id_cat =:id_console");
            $sth->bindParam(':nom_cat', $nom_console);
            $sth->bindParam(':logo_cat', $img_logo);
            $sth->bindParam(':img_cat', $img_console);
            $sth->bindParam(':etat_cat', $etat_cat);
            $sth->bindParam(':id_console', $id_console);
            $sth->execute();

            if ($_SESSION['img_console_modif'] == 'oui') {

                // chemin complet du logo choisie par l'utilisateur
                $file_logo = $_FILES['img_logo2']['tmp_name'];

                // chemin complet de l'image de la console choisie par l'utilisateur
                $file_console = $_FILES['img_console2']['tmp_name'];

                // dossier de sauvegarde de l'image après traitement
                $folder_save = "./../img/";

                sauvegarder_image($file_logo, $_FILES['img_logo2']['name'], $folder_save);
                sauvegarder_image($file_console, $_FILES['img_console2']['name'], $folder_save);

                /*Fermeture de la connexion à la base de données*/
                $sth = null;
                $conn = null;

            }else{
            }

            $_SESSION["modif_console"] = true ;

            //On renvoie l'utilisateur vers la page d'administration des consoles
            header("Location:./../pages/back_consoles.php");

        }
        catch(PDOException $e){

            //echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
            write_error_log("./../log/error_log_update_console.txt","Impossible de mettre à jour les données.", $e);
            echo 'Une erreur est survenue, mise à jour des données annulée.';

            /*Fermeture de la connexion à la base de données*/
            $sth = null;
            $conn = null;
        }

    } else {

        echo "Merci de vérifier les informations saisies";
    
    }
?>
    