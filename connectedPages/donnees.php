<?php
    // Démarrage de la session
    session_start();
    // Vérification de la connexion de l'utilisateur
    if (!isset($_SESSION["isConnected"])) {
        // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
        header("Location: ../route/login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Inclusion de jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Informations personnelles</title>
</head>

<body>
    <!-- Styles pour la photo de profil de l'utilisateur -->
    <style>
        .pdp__user {
            background-image: url(<?php
                    $dest=$_SESSION["avatarIMG"];
                    echo $dest;
                ?>);
        }
    </style>
    <!-- En-tête de la page -->
    <header class="header">
        <div class="header__logo logo">
            <img src="../assets/images/logo_JO4.png" class="logo__media"/>
        </div>
        <!-- Menu de navigation -->
        <nav class="header__menu menu">
            <ul class="menu__items">
                <li class="menu__item"><a class="menu__link" href="me.php">Accueil</a></li>
                <li class="menu__item"><a class="menu__link" href="search.php">Recherche</a></li>
                <li class="menu__item"><a class="menu__link" href="gestion.php">Gérer</a></li>
                <!-- Lien vers la page de données de l'utilisateur -->
                <a class="menu__link pdp__user" href="./donnees.php"></a>
                <!-- Bouton de déconnexion -->
                <a href="./logout.php" class="menu__link button">Déconnexion</a>
            </ul>
        </nav>
    </header>
    <!-- Section principale de la page -->
    <section class="hero">
        <div class="data__container ">
            <!-- Formulaires pour modifier le mot de passe et la photo de profil -->
            <div class="data__label" data-id="0">
                Modifier votre mot de passe
            </div>
            <div class="data__label" data-id="1">
                Modifier votre photo de profil
            </div>
            <!-- Formulaire de changement de mot de passe -->
            <form class="password__change inf" action="donnees.php" method="post">    
                <div class="mdp__input">
                    <label>Ancien mot de passe</label>
                    <input type="password" name="oldPassword" placeholder="Ancien mot de passe" required>
                </div>
                <div class="mdp__input">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="newPassword1" placeholder="Nouveau mot de passe" required>
                </div>         
                <div class="mdp__input">    
                    <label>Confirmer votre nouveau mot de passe</label>
                    <input type="password" name="newPassword2" placeholder="Confirmer votre nouveau mot de passe" required>
                </div>
                <!-- Bouton de soumission pour modifier le mot de passe -->
                <input class="change__button " type="submit" name="submitPassword" value="Modifier">
            </form>
            <!-- Formulaire de changement de photo de profil (initialement caché) -->
            <form class="pdp__change inf hidden" action="donnees.php" method="post" enctype="multipart/form-data">
                <label class="label__img"><span>Choisir une image</span><input class="pdp__input" type="file" name="avatar" accept="image/png, image/jpeg" required/></label>
                <!-- Bouton de soumission pour modifier la photo de profil -->
                <input class="change__button pdp__submit" type="submit" name="submitPdp" value="Modifier">
            </form>
        </div>
    </section>

    <?php
    // Définition des paramètres de connexion à la base de données
    define('MYUSER', 'votre_utilisateur');
    define('MYPASS', 'votre_mot_de_passe');
    define('MYHOST', 'votre_hôte');

    // Vérification de la soumission du formulaire de changement de mot de passe
    if(isset($_POST['submitPassword'])) {
        include '../myparam.inc.php';
        $idComite = filter_var($_SESSION["isConnected"], FILTER_SANITIZE_STRING);

        $connex = oci_connect(MYUSER,MYPASS,MYHOST);
        if($connex){
            $sql = "SELECT MdpCrypte FROM Comite WHERE idComite = :idComite";
            $stid = oci_parse($connex,$sql);
            oci_bind_by_name($stid, ':idComite', $idComite);
            oci_execute($stid);
                        
            if($row = oci_fetch_row($stid)){
                $_SESSION['passwordPopup'] = true;
                $mdp1 = strtoupper(md5($_POST["newPassword1"]));
                $mdp2 = strtoupper(md5($_POST["newPassword2"]));
                $old = strtoupper(md5($_POST["oldPassword"]));
                $actualPassword = $row[0];
                
                if($mdp1 == $mdp2){
                    if($actualPassword == $old){
                        $sql = "UPDATE Comite SET MdpCrypte = :newPassword WHERE IdComite = :idComite";
                        $stid = oci_parse($connex,$sql);
                        oci_bind_by_name($stid, ':newPassword', $mdp1);
                        oci_bind_by_name($stid, ':idComite', $idComite);
                        oci_execute($stid);
                        
                        if($result){
                            echo '<div class="popup popup-active-vert" id="myPopup">Votre mot de passe a été modifié !</div>';
                            echo '<script type="text/javascript">
                                    $(document).ready(function() {
                                        setTimeout(function() {
                                            $("#myPopup").fadeOut();
                                        }, 2000); // 2000 milliseconds = 2 seconds
                                    });
                                </script>';
                        }
                    } else {
                        echo '<div class="popup popup-active-rouge" id="myPopup">Ancien mot de passe incorrect !</div>';
                        echo '<script type="text/javascript">
                                $(document).ready(function() {
                                    setTimeout(function() {
                                        $("#myPopup").fadeOut();
                                    }, 2000); // 2000 milliseconds = 2 seconds
                                });
                            </script>';
                    }
                } else {
                    echo '<div class="popup popup-active-rouge" id="myPopup">Vos mots de passes ne sont pas identiques !</div>';
                    echo '<script type="text/javascript">
                            $(document).ready(function() {
                                setTimeout(function() {
                                    $("#myPopup").fadeOut();
                                }, 2000); // 2000 milliseconds = 2 seconds
                            });
                        </script>';
                }
            }
        }
    }

    // Vérification de la soumission du formulaire de changement de photo de profil
    if(isset($_POST['submitPdp'])) {
        $idComite = $_SESSION["isConnected"];
        $dest="../assets/images/users_image/".$idComite."_avatar.jpg";
        move_uploaded_file($_FILES["avatar"]["tmp_name"],$dest);
        $_SESSION["avatarIMG"]=$dest;
        echo '<script>var newAvatarPath = "'.$dest.'";</script>';
        echo '<div class="popup popup-active-vert" id="myPopup">Photo de profil modifiée !</div>';
        echo '<script type="text/javascript">
                $(document).ready(function() {
                    setTimeout(function() {
                        $("#myPopup").fadeOut();
                    }, 2000); // 2000 milliseconds = 2 seconds
                    if (typeof newAvatarPath !== \'undefined\') {
                        $(\'.pdp__user\').css(\'background-image\', \'url(\' + newAvatarPath + \')\');
                    }
                });
            </script>';
    }
?>


    
    <script src="../script.js">
    
      
    </script>
</body>
</html>
