<?php
    // Démarre ou reprend une session existante
    session_start();
    
    // Vérifie si la variable de session "isConnected" existe, et la supprime si c'est le cas
    if (isset($_SESSION["isConnected"])) {
        unset($_SESSION["isConnected"]);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Connexion</title>
</head>

<body>
    <!-- Entête -->
    <header class="header">
        <!-- Logo -->
        <div class="header__logo logo">
            <img src="../assets/images/logo_JO4.png" class="logo__media"/>
        </div>
        <!-- Menu de navigation -->
        <nav class="header__menu menu">
            <ul class="menu__items">
                <li class="menu__item"><a class="menu__link" href="./index.php">Accueil</a></li>
                <a href="./login.php" class="menu__link button">Connexion</a>
            </ul>
        </nav>
    </header>
    
    <!-- Section principale -->
    <section class="hero">
        <!-- Formulaire de connexion -->
        <form class="login__form" action="login.php" method="post">
            <h2>Connexion</h2>
            <!-- Champ d'identifiant -->
            <div class="form__input">
                <label for="idComite">Identifiant</label>
                <input type="text" name="idComite" placeholder="Votre identifiant" required>
            </div>
            <!-- Champ de mot de passe -->
            <div class="form__input">
                <label for="passwordComite">Mot de passe</label>
                <input name="password" type="password" name="passwordComite" placeholder="Votre mot de passe" required>
            </div>
            <!-- Bouton de soumission du formulaire -->
            <input name="submit" class="login__cta" type="submit" value="Se connecter">
            
            <!-- Affichage des erreurs de connexion -->
            <div class="login__error">
            <?php
            // Inclut le fichier de paramètres de connexion à la base de données
            include '../myparam.inc.php';

            // Vérifie si le formulaire a été soumis
            if(isset($_POST["submit"])){
                $idComite = $_POST["idComite"];
                $password = $_POST["password"];
                $password = strtoupper(md5($password)); // Convertit le mot de passe en majuscules et le hashe avec MD5

                // Établit une connexion à la base de données Oracle
                $connex = oci_connect(MYUSER,MYPASS,MYHOST);
                if($connex){
                    // Prépare une requête SQL pour récupérer le mot de passe hashé
                    $sql = "SELECT MdpCrypte FROM Comite WHERE idComite = :idComite";
                    $stid = oci_parse($connex,$sql);

                    // Lie les variables PHP aux paramètres de la requête SQL
                    oci_bind_by_name($stid, ":idComite", $idComite);

                    // Exécute la requête SQL
                    $result = oci_execute($stid);

                    if($row = oci_fetch_row($stid)){
                        $hashedPassword = $row[0];

                        // Vérifie si le mot de passe fourni correspond au mot de passe hashé en base de données
                        if($hashedPassword == $password){
                            // Si la connexion réussit, initialise la session et redirige l'utilisateur
                            $_SESSION["isConnected"] = $idComite;
                            $dest="../assets/images/users_image/".$idComite."_avatar.jpg";
                            $_SESSION["avatarIMG"] = $dest; 
                            header("Location: ../connectedPages/me.php");
                            exit();
                        }else{
                            // Si la connexion échoue, affiche un message d'erreur
                            echo '<div class="popup popup-active-rouge" id="myPopup">
                                    Mot de passe ou Identifiant incorrect(s).</div>';
                            echo '<script type="text/javascript">
                                    $(document).ready(function() {
                                        setTimeout(function() {
                                            $("#myPopup").fadeOut();
                                        }, 2000); // 2000 milliseconds = 2 seconds
                                    });
                                </script>';
                        }
                    }else{
                        // Si la connexion échoue, affiche un message d'erreur
                        echo '<div class="popup popup-active-rouge" id="myPopup">
                                Mot de passe ou Identifiant incorrect(s).</div>';
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
            ?>
            </div>
        </form>
    </section>
    
    <!-- Script JavaScript -->
    <script src="../script.js"></script>
</body>
</html>
