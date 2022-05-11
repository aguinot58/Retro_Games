<?php

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    
    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }

    $nom_console = $_GET['console'];

    /* Connexion à une base de données en PDO */
    $configs = include($lien.'pages/config.php');
    $servername = $configs['servername'];
    $username = $configs['username'];
    $password = $configs['password'];
    $db = $configs['database'];

    $conn = new PDO("mysql:host=$servername;dbname=$db;charset=UTF8", $username, $password);
    //On définit le mode d'erreur de PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sth = $conn->prepare("SELECT Logo_cat FROM categories where Nom_cat='$nom_console'");
    $sth->execute();
    //Retourne un tableau associatif pour chaque entrée de notre table avec le nom des colonnes sélectionnées en clefs
    $console = $sth->fetchColumn();

    echo '<img src="'.$lien.'img/' .$console. '" height="150px" width="150px">';

?>