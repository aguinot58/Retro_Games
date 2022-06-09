<?php

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
        
    if ($curPageName == "index.php") {
        $lien_local = "../../";
        $lien_prod = "../../../";
    } else {
        $lien_local = "../../../";
        $lien_prod = "../../../../";
    }

    if (file_exists($lien_local.'etc/ini/retro.ini')){
        $base = "locale";
        $inifile = parse_ini_file($lien_local.'etc/ini/retro.ini',true);
        $servername = $inifile['Base_locale']['servername'];
        $username = $inifile['Base_locale']['username'];
        $password = $inifile['Base_locale']['password'];
        $db = $inifile['Base_locale']['database'];
    } else {
        $base = "prod";
        $inifile = parse_ini_file($lien_prod.'etc/ini/retro.ini',true);
        $servername = $inifile['Base_prod']['servername'];
        $username = $inifile['Base_prod']['username'];
        $password = $inifile['Base_prod']['password'];
        $db = $inifile['Base_prod']['database'];
    }

    $pepper = $inifile['Hash']['pepper'];

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$db;charset=UTF8", $username, $password);
        //On définit le mode d'erreur de PDO sur Exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        date_default_timezone_set('Europe/Paris');
        setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
        $format1 = '%A %d %B %Y %H:%M:%S';
        $date1 = strftime($format1);
        $fichier = fopen('./../log/error_log_connexion.txt', 'c+b');
        fseek($fichier, filesize('./../log/error_log_connexion.txt'));
        fwrite($fichier, "\n\n" .$date1. " - Impossible de se connecter à la base de données. Erreur : " .$e);
        fclose($fichier);
    }

?>