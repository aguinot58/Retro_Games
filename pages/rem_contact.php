<?php
    session_start();

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
    } else {
        $lien = "./../";
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="./../css/fonts.css">
        <link rel="stylesheet" href="./../css/header_footer.css">
        <link rel="stylesheet" href="./../css/rem_inscrip.css">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Retro-Games</title>
        <link rel="shortcut icon" type="image/ico" href="./../img/icone.png">
    </head>

    <body>
        <main>
            <?php
                /* importation header */
                include $lien.'pages/header.php';

            echo '<section id="section-1">
                        <img class="haut-etagere" src="'.$lien.'img/shelf-header.jpg" alt="haut étagère">
                        <article class="non-log">
                            <h3>Merci de votre message !</h3>
                            <p>Nous en prendrons connaissance au plus vite et reviendrons vers vous si besoin.</p>
                        </article>
                    </section>';

                /* imporation du footer */
                include $lien.'pages/footer.php';
            ?>

        </main>
        <script src="./../js/index.js"></script>
    </body>
</html>