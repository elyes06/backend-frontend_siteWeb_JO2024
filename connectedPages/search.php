<?php
    // Démarrage de la session pour gérer l'authentification de l'utilisateur
    session_start();
    // Vérification si l'utilisateur est connecté, sinon redirection vers la page de connexion
    if (!isset($_SESSION["isConnected"])) {
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
    <title>Page d'Accueil</title>
</head>

<body>
    <!-- Styling pour afficher l'image de profil de l'utilisateur connecté -->
    <style>
        .pdp__user{
            background-image: url(<?php
                    // Affichage de l'image de profil de l'utilisateur connecté
                    $dest=$_SESSION["avatarIMG"];
                    echo $dest;
                ?>);
        }
    </style>
    <header class="header">
        <div class="header__logo logo">
            <img src="../assets/images/logo_JO4.png" class="logo__media"/>
        </div>
        <nav class="header__menu menu">
            <ul class="menu__items">
                <!-- Liens vers différentes sections de l'application -->
                <li class="menu__item"><a class="menu__link" href="me.php">Accueil</a></li>
                <li class="menu__item"><a class="menu__link" href="search.php">Recherche</a></li>
                <li class="menu__item"><a class="menu__link" href="gestion.php">Gérer</a></li>
                <!-- Affichage de l'image de profil de l'utilisateur -->
                <a class="menu__link pdp__user" href="./donnees.php"></a>
                <!-- Bouton de déconnexion -->
                <a href="./logout.php" class="menu__link button">Déconnexion</a>
            </ul>
        </nav>
    </header>
    <section class="hero search__page">
            <div class="welcome__user">
            <?php
    // Inclusion du fichier de configuration de la base de données
    include '../myparam.inc.php';

    // Récupération de l'ID du comité de l'utilisateur connecté
    $idComite = filter_var($_SESSION["isConnected"], FILTER_SANITIZE_STRING);

    // Connexion à la base de données
    $connex = oci_connect(MYUSER, MYPASS, MYHOST);
    if ($connex) {
        // Requête SQL paramétrée pour récupérer le nom et le prénom du comité
        $sql = "SELECT Nom, Prenom FROM Comite, Participants 
                WHERE Participants.IdParticipant = Comite.IdParticipant
                AND idComite = :idComite";

        // Préparation de la requête
        $stid = oci_parse($connex, $sql);

        // Liaison des paramètres
        oci_bind_by_name($stid, ':idComite', $idComite);

        // Exécution de la requête
        $result = oci_execute($stid);

        // Vérification du résultat et affichage
        if ($row = oci_fetch_row($stid)) {
            $nom = $row[0];
            $prenom = $row[1];
            echo "<h2>Effectuez vos recherches $nom $prenom...</h2>";
        }
    }
?>

                
            </div> 
            <!-- Formulaire de recherche -->
            <div class="search__container">
                <div class="search__label">
                    <h2>Recherche par mot clé</h2>
                </div>
                <form class="form__search" action="searchResult.php" method="post">
                    <div class="search__bar">
                        <input type="search" name="searchBar" placeholder="Rechercher..." autofocus required>
                        <button type="submit" name="submitSearch"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: rgba(255, 250, 235, 1);transform: ;msFilter:;"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path></svg></button>
                    </div>
                </form>
                <!-- Formulaire de filtrage -->
                <form class="form__filter" action="./searchResult.php" method="post">
                    <div class="label__filters">
                        <h2>Recherche par filtre</h2>
                    </div>
                    <div class="tables">
                        <h3>Catégorie</h3>
                        <div class="search__filters">
                            <input type="radio" name="categories" value="participants"><label for="filters">Participant</label>
                            <input type="radio" name="categories" value="equipe"><label for="filters">Equipe</label>
                            <input type="radio" name="categories" value="competition"><label for="filters">Competition</label>
                        </div>
                    </div>
                    <div class="filters">
                        <h3>Filtrer par</h3>
                        <div class="search__filters">
                            <input type="radio" name="filters" value="nationalite"><label for="filters">Nationalité</label>
                            <input type="radio" name="filters" value="discipline"><label for="filters">Discipline</label>
                            <input type="radio" name="filters" value="fonction"><label for="filters">Fonction</label>
                        </div>
                    </div>
                    <div class="sort">
                        <h3>Trier par</h3>
                        <div class="search__filters">
                            <input type="radio" name="sort" value="asc" checked><label for="filters">Or. croissant</label>
                            <input type="radio" name="sort" value="desc"><label for="filters">Or. decroissant</label>

                        </div>
                    </div>
                    <input name="filterSearch" class="search__button" type="submit" value="Rechercher">
                </form>
            </div>
    </section>

</body>
</html>
