<?php

    session_start();

    if (!isset($_SESSION['suppr_user'])){
        $_SESSION['suppr_user'] = false;
    }

    if ($_SESSION['suppr_user']== true){
        ?>
            <script type="text/javascript">
                alert ("Utilisateur supprimé avec succès !");
            </script>
        <?php
        $_SESSION['suppr_user']== false;
    }

    if (isset($_SESSION['Identifiant'])){
        unset($_SESSION['Identifiant']);
    }

    if (isset($_SESSION['mdp'])){
        unset($_SESSION['mdp']);
    }

    if (isset($_SESSION['logged'])){
        unset($_SESSION['logged']);
    }

    if (isset($_SESSION['admin'])){
        unset($_SESSION['admin']);
    }

    if (isset($_SESSION['user'])){
        unset($_SESSION['user']);
    }

    if (isset($_SESSION['niv_admin'])){
        unset($_SESSION['niv_admin']);
    }

    if (isset($_SESSION['suppr_jeu'])){
        unset($_SESSION['suppr_jeu']);
    }

    if (isset($_SESSION['modif_jeu'])){
        unset($_SESSION['modif_jeu']);
    }

    if (isset($_SESSION['ajout_jeu'])){
        unset($_SESSION['ajout_jeu']);
    }

    if (isset($_SESSION['suppr_user'])){
        unset($_SESSION['suppr_user']);
    }

    if (isset($_SESSION['modif_user'])){
        unset($_SESSION['modif_user']);
    }

    if (isset($_SESSION['suppr_msg'])){
        unset($_SESSION['suppr_msg']);
    }

    if (isset($_SESSION['suppr_rep'])){
        unset($_SESSION['suppr_rep']);
    }

    if (isset($_SESSION['envoi_rep'])){
        unset($_SESSION['envoi_rep']);
    }

    session_destroy();

    header("Location:../index.php");

?>