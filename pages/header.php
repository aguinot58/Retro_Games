<?php
    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

    if ($curPageName == "index.php") {
        $lien = "./";
        $page = "index.php";
    } else {
        $lien = "./../";
        $page = "./../index.php";
    }

    echo '<header>
            <div class="menu_header">
                <div class="banniere">
                    <a href="'.$page.'"><img title="Retro-Games" src="'.$lien.'img/banniere.png" alt="Logo Retro-Games"></a>
                </div>';
                
            if ($_SESSION['logged'] == 'oui') {

                echo '<nav>
                        <div id="barre-nav">
                            <ul>
                                <li id="Super Nintendo" class="menu-bouton2" onclick="page_console(event)">Super Nintendo</li>
                                <li id="Megadrive" class="menu-bouton2" onclick="page_console(event)">Megadrive</li>
                                <li id="Playstation" class="menu-bouton2" onclick="page_console(event)">Playstation</li>
                            </ul>
                            <ul>';
                        if ($_SESSION['admin'] == 'oui') {
                            echo '<li class="menu-bouton"><a href="'.$lien.'pages/profil.php">Mon Compte</a></li>
                                <li class="menu-bouton"><a href="'.$lien.'pages/back_office.php">Administration</a></li>
                                <li class="menu-bouton"><a href="'.$lien.'pages/logout.php" id="deconnexion">Déconnexion</a></li>';
                        } else {
                            echo '<li class="menu-bouton"><a href="'.$lien.'pages/profil.php">Mon Compte</a></li>
                                <li class="menu-bouton"><a href="'.$lien.'pages/logout.php" id="deconnexion">Déconnexion</a></li>';
                        }
                echo       '</ul>
                        </div>
                        <div id="menuToggle">
                            <input type="checkbox"/>
                            <span></span>
                            <span></span>
                            <span></span>
                            <ul id="menu">
                                <li id="Super Nintendo" class="menu-bouton2" onclick="page_console(event)">Super Nintendo</li>
                                <li id="Megadrive" class="menu-bouton2" onclick="page_console(event)">Megadrive</li>
                                <li id="Playstation" class="menu-bouton2" onclick="page_console(event)">Playstation</li>
                                <li></li>';
                        if ($_SESSION['admin'] == 'oui') {
                            echo '<li class="menu-bouton"><a href="'.$lien.'pages/profil.php">Mon Compte</a></li>
                                <li class="menu-bouton"><a href="'.$lien.'pages/back_office.php">Administration</a></li>
                                <li class="menu-bouton"><a href="'.$lien.'pages/logout.php" id="deconnexion">Déconnexion</a></li>';
                        } else {
                            echo '<li class="menu-bouton"><a href="'.$lien.'pages/profil.php">Mon Compte</a></li>
                                <li class="menu-bouton"><a href="'.$lien.'pages/logout.php" id="deconnexion">Déconnexion</a></li>';
                        }
                    echo    '</ul>
                        </div>
                    </nav>';            

            } else {

                echo '<nav>
                        <div id="barre-nav">
                            <ul id="menu-connexion">
                                <li class="menu-bouton"><a href="'.$lien.'pages/inscription.php">Inscription</a></li>
                                <li class="menu-bouton"><a href="'.$lien.'pages/connexion.php">Connexion</a></li>
                            </ul>
                        </div>
                        <div id="menuToggle">
                            <input type="checkbox"/>
                            <span></span>
                            <span></span>
                            <span></span>
                            <ul id="menu">
                                <li class="menu-bouton"><a href="'.$lien.'pages/inscription.php">Inscription</a></li>
                                <li class="menu-bouton"><a href="'.$lien.'pages/connexion.php">Connexion</a></li>
                            </ul>
                        </div>
                    </nav>';
            }
    echo '</div>
        </header>';
?>