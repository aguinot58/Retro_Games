<?php

    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
        $page = "index.php";
    } else {
        $lien = "./../";
        $page = "./../index.php";
    }

    echo '<footer>
            <div class="menu_footer">
                <div class="banniere">
                    <a href="'.$page.'"><img title="Retro-Games" src="'.$lien.'img/banniere.png" alt="Logo Retro-Games"></a>
                </div>
                <div class="copyright">
                    <p class="copyright texte-couleur-gris-bleu">Tous droits réservés @Retro-Games.com</p>
                    <a href="'.$lien.'pages/form_contact.php"><p class="copyright texte-couleur-gris-bleu">Nous contacter</p>
                </div>
            </div>
         </footer>';

?>