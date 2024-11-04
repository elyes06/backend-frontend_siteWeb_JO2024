<?php
// Démarre une session PHP pour gérer l'authentification de l'utilisateur
    session_start();
     // Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion
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
    <title>Résultat de recherches</title>
</head>

<body>
    <style>
        /* Définit le fond de l'élément .pdp__user avec l'image de profil de l'utilisateur */
        .pdp__user{
            background-image: url(<?php
             // Récupère l'URL de l'image de profil de l'utilisateur à partir de la session
                    $dest=$_SESSION["avatarIMG"];
                    echo $dest;
                ?>);
        }
    </style>
    <header class="header">
        <!-- Logo de l'application -->
        <div class="header__logo logo">
            <img src="../assets/images/logo_JO4.png" class="logo__media"/>
        </div>
        <!-- Menu de navigation -->
        <nav class="header__menu menu">
            <ul class="menu__items">
                <!-- Liens vers différentes pages de l'application -->
                <li class="menu__item"><a class="menu__link" href="me.php">Accueil</a></li>
                <li class="menu__item"><a class="menu__link" href="search.php">Recherche</a></li>
                <li class="menu__item"><a class="menu__link" href="gestion.php">Gérer</a></li>
                <a class="menu__link pdp__user" href="donnees.php"></a>
                 <!-- Lien de déconnexion -->
                <a href="../logout.php" class="menu__link button">Déconnexion</a>
            </ul>
        </nav>
    </header>
    <!-- Section principale pour afficher les résultats de recherche -->
    <section class="hero search__result">
            <div class="result__container">
                <div class="result__label">
                    <h2>Resultats de votre recherche...</h2>
                </div>
                <?php
                 // Définition des fonctions de recherche pour chaque catégorie de données
                function searchChambres($keyword, $conn) {
                    $query = "SELECT * FROM Chambre WHERE IdHabitation LIKE '%" . $keyword . "%' OR NbrLits LIKE '%" . $keyword . "%'";
                    $stmt = oci_parse($conn, $query);
                    oci_execute($stmt);
                    return $stmt;
                }

                function searchPersonnels($keyword, $conn) {
                    $query = "SELECT * FROM Personnel WHERE IdPersonnel LIKE '%" . $keyword . "%' OR Fonction LIKE '%" . $keyword . "%'";
                    $stmt = oci_parse($conn, $query);
                    oci_execute($stmt);
                    return $stmt;
                }

                function searchEquipes($keyword, $conn) {
                    $query = "SELECT * FROM Equipe WHERE IdEquipe LIKE '%" . $keyword . "%'";
                    $stmt = oci_parse($conn, $query);
                    oci_execute($stmt);
                    return $stmt;
                }

                function searchParticipants($keyword, $conn) {
                    $query = "SELECT * FROM Participants WHERE IdParticipant LIKE '%" . $keyword . "%' OR Nom LIKE '%" . $keyword . "%' OR Prenom LIKE '%" . $keyword . "%'";
                    $stmt = oci_parse($conn, $query);
                    oci_execute($stmt);
                    return $stmt;
                }

                function searchCompetitions($keyword, $conn) {
                    $query = "SELECT * FROM Competition WHERE IdCompetition LIKE '%" . $keyword . "%' OR PhaseCompetition LIKE '%" . $keyword . "%'";
                    $stmt = oci_parse($conn, $query);
                    oci_execute($stmt);
                    
                    return $stmt;
                }

                function searchDisciplines($keyword, $conn) {
                    $query = "SELECT * FROM Discipline WHERE IdDiscipline LIKE '%" . $keyword . "%' OR NomDiscipline LIKE '%" . $keyword . "%' OR RecordDiscipline LIKE '%" . $keyword . "%'";
                    $stmt = oci_parse($conn, $query);
                    oci_execute($stmt);
                    return $stmt;
                }
                function searchAthletes($keyword, $conn) {
                    $query = "SELECT * FROM Athletes WHERE IdAthlete LIKE '%" . $keyword . "%' OR Poids LIKE '%" . $keyword . "%' OR Taille LIKE '%" . $keyword . "%'";
                    $stmt = oci_parse($conn, $query);
                    oci_execute($stmt);
                    return $stmt;
                }   

                function searchEntraineurs($keyword, $conn) {
                    $query = "SELECT * FROM Entraineurs WHERE IdEntraineur LIKE '%" . $keyword . "%' OR Diplome LIKE '%" . $keyword . "%'";
                    $stmt = oci_parse($conn, $query);
                    oci_execute($stmt);
                    return $stmt;
                }
                    
                    include '../myparam.inc.php';
                    // Vérifie si le formulaire de recherche a été soumis
                    if(isset($_POST["submitSearch"])){
                        // Connexion à la base de données
                        $keyword = $_POST["searchBar"];
                        $connex = oci_connect(MYUSER,MYPASS,MYHOST);
                        
                        if($connex){
                            $searchCompetitions = searchCompetitions($keyword,$connex);
                            $searchAthlete = searchAthletes($keyword,$connex);
                            $searchDiscipline = searchDisciplines($keyword,$connex);
                            $searchEntraineur = searchEntraineurs($keyword,$connex);
                            $searchParticipant = searchParticipants($keyword,$connex);
                            $searchEquipe = searchEquipes($keyword,$connex);
                            $searchPersonnel = searchPersonnels($keyword,$connex);
                            $searchChambre = searchChambres($keyword,$connex);
                            
                            if ($row = oci_fetch_row($searchCompetitions)) {
                                echo '<div class="table__result"><table><th>Competitions</th>';
                                do {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>'.$value.'</td>';
                                    }
                                    echo '</tr>';
                                } while ($row = oci_fetch_row($searchCompetitions));
                                echo '</table></div>';
                            }
                            if($row = oci_fetch_row($searchAthlete)){
                                echo '<div class="table__result"><table><th>Athletes</th>';
                                do {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>'.$value.'</td>';
                                    }
                                    echo '</tr>';
                                } while ($row = oci_fetch_row($searchAthlete));
                                echo '</table></div>';
                            }
                            if($row = oci_fetch_row($searchEquipe)){
                                echo '<div class="table__result"><table><th>Equipes</th>';
                                do {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>'.$value.'</td>';
                                    }
                                    echo '</tr>';
                                } while ($row = oci_fetch_row($searchEquipe));
                                echo '</table></div>';
                            }
                            if($row = oci_fetch_row($searchDiscipline)){
                                echo '<div class="table__result"><table><th>Disciplines</th>';
                                do {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>'.$value.'</td>';
                                    }
                                    echo '</tr>';
                                } while ($row = oci_fetch_row($searchDiscipline));
                                echo '</table></div>';
                            }
                            if($row = oci_fetch_row($searchEntraineur)){
                                echo '<div class="table__result"><table><th>Entraineurs</th>';
                                do {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>'.$value.'</td>';
                                    }
                                    echo '</tr>';
                                } while ($row = oci_fetch_row($searchEntraineur));
                                echo '</table></div>';
                            }
                            if($row = oci_fetch_row($searchParticipant)){
                                echo '<div class="table__result"><table><th>Participants</th>';
                                do {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>'.$value.'</td>';
                                    }
                                    echo '</tr>';
                                } while ($row = oci_fetch_row($searchParticipant));
                                echo '</table></div>';
                            }
                            if($row = oci_fetch_row($searchPersonnel)){
                                echo '<div class="table__result"><table><th>Personnel</th>';
                                do {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>'.$value.'</td>';
                                    }
                                    echo '</tr>';
                                } while ($row = oci_fetch_row($searchPersonnel));
                                echo '</table></div>';
                            }
                            if($row = oci_fetch_row($searchChambre)){
                                echo '<div class="table__result"><table><th>Chambres</th>';
                                do {
                                    echo '<tr>';
                                    foreach ($row as $value) {
                                        echo '<td>'.$value.'</td>';
                                    }
                                    echo '</tr>';
                                } while ($row = oci_fetch_row($searchChambre));
                                echo '</table></div>';
                            }
                        }
                    }
                // Vérifie si le formulaire de filtrage a été soumis
                if(isset($_POST["filterSearch"])){
                    // Connexion à la base de données
                    $connex = oci_connect(MYUSER,MYPASS,MYHOST);
                    if(!isset($_POST['categories']) || !isset($_POST['filters'])){
                        header("Location: ./search.php");
                    }
                    if($_POST['categories'] == "participants" && $_POST['filters'] == "nationalite"){
                        $sort_type = $_POST['sort'];
                        $query='select nom, prenom, nationalite 
                        from participants order by nationalite '.$sort_type;
                        $stmt = oci_parse($connex, $query);
                        $result = oci_execute($stmt);

                        if($row = oci_fetch_row($stmt)){
                        
                            echo '<div class="table__result"><table><th>Participants</th>
                            <tr><td>Nom</td><td>Prenom</td><td>Nationalité</td></tr>';
                            do {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    echo '<td>'.$value.'</td>';
                                }
                                echo '</tr>';
                            } while ($row = oci_fetch_row($stmt));
                            echo '</table></div>';
                        }
                    }
                    if($_POST['categories'] == "participants" && $_POST['filters'] == "fonction"){
                        $sort_type = $_POST['sort'];
                        $query='select Nom, Prenom, fonction from participants, personnel 
                        where participants.idparticipant = personnel.idparticipant order by personnel.fonction '. $sort_type;
                        
                        $query2='select Nom, Prenom from participants, athletes 
                        where participants.idparticipant = athletes.idparticipant';

                        $stmt = oci_parse($connex, $query);
                        $result = oci_execute($stmt);

                        if($row = oci_fetch_row($stmt)){
                        
                            echo '<div class="table__result"><table><th>Personnel</th>
                            <tr><td>Nom</td><td>Prenom</td><td>Fonction</td></tr>';
                            do {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    echo '<td>'.$value.'</td>';
                                }
                                echo '</tr>';
                            } while ($row = oci_fetch_row($stmt));
                            echo '</table></div>';
                        }
                        $stmt = oci_parse($connex, $query2);
                        $result = oci_execute($stmt);

                        if($row = oci_fetch_row($stmt)){
                        
                            echo '<div class="table__result"><table><th>Athletes</th>
                            <tr><td>Nom</td><td>Prenom</td></tr>';
                            do {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    echo '<td>'.$value.'</td>';
                                }
                                echo '</tr>';
                            } while ($row = oci_fetch_row($stmt));
                            echo '</table></div>';
                        }
                    }
                    if($_POST['categories'] == "participants" && $_POST['filters'] == "discipline"){
                        $sort_type = $_POST['sort'];
                        $query='select distinct nom, prenom, discipline.nomdiscipline 
                        FROM participants, personnel, assigne, competition, discipline
                        WHERE participants.idparticipant = personnel.idparticipant 
                            AND personnel.idpersonnel = assigne.idpersonnel 
                            AND assigne.idcompetition = competition.idcompetition 
                            AND competition.iddiscipline = discipline.iddiscipline
                        UNION
                        SELECT DISTINCT nom, prenom, discipline.nomdiscipline 
                        FROM participants, athletes, specialise, discipline
                        WHERE participants.idparticipant = athletes.idparticipant 
                            AND athletes.idathlete = specialise.idathlete 
                            AND specialise.iddiscipline = discipline.iddiscipline
                        ORDER BY nomdiscipline '.$sort_type;
                        
                        

                        $stmt = oci_parse($connex, $query);
                        $result = oci_execute($stmt);

                        if($row = oci_fetch_row($stmt)){
                        
                            echo '<div class="table__result"><table><th>Participants</th>
                            <tr><td>Nom</td><td>Prenom</td><td>Discipline</td></tr>';
                            do {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    echo '<td>'.$value.'</td>';
                                }
                                echo '</tr>';
                            } while ($row = oci_fetch_row($stmt));
                            echo '</table></div>';
                        }
                    }

                    if($_POST['categories'] == "equipe" && $_POST['filters'] == "discipline"){
                        $sort_type = $_POST['sort'];
                        $query = 'select Equipe.IdEquipe, Discipline.nomDiscipline FROM 
                        Equipe, InscrisE, Competition, Discipline 
                        WHERE Equipe.IdEquipe = InscrisE.IdEquipe 
                        AND InscrisE.IdCompetition = Competition.IdCompetition 
                        AND Competition.IdDiscipline = Discipline.IdDiscipline';
                        $stmt = oci_parse($connex, $query);
                        $result = oci_execute($stmt);

                        if($row = oci_fetch_row($stmt)){
                        
                            echo '<div class="table__result"><table><th>Equipe</th>
                            <tr><td>Equipe</td><td>Discipline</td></tr>';
                            do {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    echo '<td>'.$value.'</td>';
                                }
                                echo '</tr>';
                            } while ($row = oci_fetch_row($stmt));
                            echo '</table></div>';
                        }
                    }
                    if($_POST['categories'] == "equipe" && $_POST['filters'] == "nationalite"){
                        $sort_type = $_POST['sort'];
                        $query = 'select DISTINCT Fait_Partie.IdEquipe, Participants.Nationalite
                        from Fait_Partie, Athletes, Participants 
                        where Fait_Partie.IdAthlete = Athletes.IdAthlete 
                        and Athletes.IdParticipant = Participants.IdParticipant';
                        $stmt = oci_parse($connex, $query);
                        $result = oci_execute($stmt);

                        if($row = oci_fetch_row($stmt)){
                        
                            echo '<div class="table__result"><table><th>Equipe</th>
                            <tr><td>Equipe</td><td>Nationalite</td></tr>';
                            do {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    echo '<td>'.$value.'</td>';
                                }
                                echo '</tr>';
                            } while ($row = oci_fetch_row($stmt));
                            echo '</table></div>';
                        }
                    }
                    if($_POST['categories'] == "competition" && $_POST['filters'] == "discipline"){
                        $sort_type = $_POST['sort'];
                        $query = 'select DISTINCT competition.idcompetition, discipline.nomdiscipline
                        from competition, discipline
                        where competition.iddiscipline = discipline.iddiscipline order by discipline.nomdiscipline '.$sort_type;
                        $stmt = oci_parse($connex, $query);
                        $result = oci_execute($stmt);

                        if($row = oci_fetch_row($stmt)){
                        
                            echo '<div class="table__result"><table><th>Competitions</th>
                            <tr><td>Competition</td><td>Discipline</td></tr>';
                            do {
                                echo '<tr>';
                                foreach ($row as $value) {
                                    echo '<td>'.$value.'</td>';
                                }
                                echo '</tr>';
                            } while ($row = oci_fetch_row($stmt));
                            echo '</table></div>';
                        }
                    }
                    
                }
                ?>
            </div>
    </section>

</body>
</html>
