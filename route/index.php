<?php include '../myparam.inc.php'?> <!-- Inclusion du fichier de paramètres -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css"> <!-- Inclusion de la feuille de style -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil</title>
</head>

<body>
    <header class="header">
        <div class="header__logo logo">
            <img src="../assets/images/logo_JO4.png" class="logo__media"/> <!-- Logo -->
        </div>
        <nav class="header__menu menu">
            <ul class="menu__items">
                <li class="menu__item"><a class="menu__link" href="index.php">Accueil</a></li> <!-- Lien vers la page d'accueil -->
                <a href="./login.php" class="menu__link button">Connexion</a> <!-- Lien vers la page de connexion -->
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="facts__container">
            <h3>FAQ</h3>
            <!-- Première question de la FAQ -->
            <div class="faq__items" onclick="showPopupResult('1')" >Liste des disciplines où une nation a remporté toutes les médailles.</div>
            <!-- Popup pour afficher la réponse à la première question -->
            <div id="1" class="popup-result" style="display:none;">
                <div class="popup-result-content">
                    <span class="close" onclick="closePopupResult('1')" >&times;</span>
                    <div id="table-result">
                        <?php
                            // Connexion à la base de données
                            $connex = oci_connect(MYUSER,MYPASS,MYHOST);
                            if($connex){
                                // Requête SQL pour obtenir les disciplines où une nation a remporté toutes les médailles
                                $sql = "SELECT Nationalite, NomDiscipline FROM Discipline, A_Palmares, Athletes, Participants
                                        WHERE Discipline.IdDiscipline = A_Palmares.IdDiscipline AND A_Palmares.IdAthlete =
                                        Athletes.IdAthlete AND A_Palmares.IdMedaille <> 4 AND Athletes.IdParticipant =
                                        Participants.IdParticipant GROUP BY Discipline.NomDiscipline, Participants.Nationalite HAVING
                                        COUNT(DISTINCT A_Palmares.IdMedaille) = 3";
                                
                                $stid = oci_parse($connex, $sql);
                                $result = oci_execute($stid);
                                
                                if($row = oci_fetch_row($stid)){
                                    // Affichage des résultats dans un tableau HTML
                                    echo '<div id="table-result" class="table__resultFAQ"><table><tr><td>Nationalité</td><td>Discipline</td></tr>';
                                    do {
                                        echo '<tr>';
                                        foreach ($row as $value) {
                                            echo '<td>'.$value.'</td>';
                                        }
                                        echo '</tr>';
                                    } while ($row = oci_fetch_row($stid));
                                    echo '</table></div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="faq__items" onclick="showPopupResult('2')" >Quel pays a remporté le plus de médailles ?</div>
            <!-- Lorsque cet élément est cliqué, la fonction showPopupResult('2') est déclenchée pour afficher la réponse -->

            <div id='2' class="popup-result" style="display:none;">
                <!-- Div contenant la réponse, initialement cachée -->
                <div class="popup-result-content">
                    <span class="close" onclick="closePopupResult('2')" >&times;</span>
                    <!-- Bouton pour fermer la réponse -->
                    <div id="table-result">
                        <?php
                            // Connexion à la base de données
                            $connex = oci_connect(MYUSER,MYPASS,MYHOST);
                            if($connex){
                                // Requête SQL pour obtenir le pays ayant remporté le plus de médailles
                                $sql = 'SELECT Nationalite FROM Participants, Athletes, A_Palmares WHERE Participants.IdParticipant =
                                        Athletes.IdParticipant AND Athletes.IdAthlete = A_Palmares.IdAthlete AND A_Palmares.IdMedaille
                                        <> 4 GROUP BY Nationalite HAVING COUNT(IdMedaille) = (SELECT MAX(COUNT(IdMedaille))
                                        FROM A_Palmares, Athletes, Participants WHERE A_Palmares.IdAthlete = Athletes.IdAthlete AND
                                        A_Palmares.IdMedaille <> 4 AND Athletes.IdParticipant = Participants.IdParticipant GROUP BY
                                        Nationalite)';
                                
                                $stid = oci_parse($connex, $sql);
                                $result = oci_execute($stid);
                                
                                if($row = oci_fetch_row($stid)){
                                    // Affichage des résultats dans un tableau HTML
                                    echo '<div id="table-result" class="table__resultFAQ"><table><tr><td>Nationalité</td></tr>';
                                    do {
                                        echo '<tr>';
                                        foreach ($row as $value) {
                                            echo '<td>'.$value.'</td>';
                                        }
                                        echo '</tr>';
                                    } while ($row = oci_fetch_row($stid));
                                    echo '</table></div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="faq__items" onclick="showPopupResult('3')" >Liste triée des pays en fonction du palmarès (nombre de médailles).</div>
            <!-- Lorsque cet élément est cliqué, la fonction showPopupResult('3') est déclenchée pour afficher la réponse -->

            <div id='3' class="popup-result" style="display:none;">
                <!-- Div contenant la réponse, initialement cachée -->
                <div class="popup-result-content">
                    <span class="close" onclick="closePopupResult('3')" >&times;</span>
                    <!-- Bouton pour fermer la réponse -->
                    <div id="table-result">
                        <?php
                            // Connexion à la base de données
                            $connex = oci_connect(MYUSER,MYPASS,MYHOST);
                            if($connex){
                                // Requête SQL pour obtenir la liste triée des pays en fonction du nombre de médailles remportées
                                $sql = 'SELECT Nationalite,COUNT(A_Palmares.IdMedaille) FROM Participants, Athletes, A_Palmares
                                        WHERE Participants.IdParticipant = Athletes.IdParticipant AND Athletes.IdAthlete =
                                        A_Palmares.IdAthlete AND A_Palmares.IdMedaille <> 4 GROUP BY Nationalite ORDER BY
                                        COUNT(A_Palmares.IdMedaille) DESC';
                                
                                $stid = oci_parse($connex, $sql);
                                $result = oci_execute($stid);
                                
                                if($row = oci_fetch_row($stid)){
                                    // Affichage des résultats dans un tableau HTML
                                    echo '<div id="table-result" class="table__resultFAQ"><table><tr><td>Nationalité</td><td>Nbr Médailles</td></tr>';
                                    do {
                                        echo '<tr>';
                                        foreach ($row as $value) {
                                            echo '<td>'.$value.'</td>';
                                        }
                                        echo '</tr>';
                                    } while ($row = oci_fetch_row($stid));
                                    echo '</table></div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="faq__items" onclick="showPopupResult('4')" >Quelles sont les disciplines où aucun record n'a été battu pendant ces jeux ?</div>
            <!-- Lorsque cet élément est cliqué, la fonction showPopupResult('4') est déclenchée pour afficher la réponse -->

            <div id='4' class="popup-result" style="display:none;">
                <!-- Div contenant la réponse, initialement cachée -->
                <div class="popup-result-content">
                    <span class="close" onclick="closePopupResult('4')" >&times;</span>
                    <!-- Bouton pour fermer la réponse -->
                    <div id="table-result">
                        <?php
                            // Connexion à la base de données
                            $connex = oci_connect(MYUSER,MYPASS,MYHOST);
                            if($connex){
                                // Requête SQL pour obtenir les disciplines où aucun record n'a été battu
                                $sql = "SELECT DISTINCT NomDiscipline FROM Discipline MINUS SELECT DISTINCT
                                        NomDiscipline FROM InscrisA, Competition, Discipline WHERE Discipline.IdDiscipline =
                                        Competition.IdDiscipline AND Competition.IdCompetition = InscrisA.IdCompetition AND
                                        InscrisA.RecordBattu LIKE 'O'";
                                
                                $stid = oci_parse($connex, $sql);
                                $result = oci_execute($stid);
                                
                                if($row = oci_fetch_row($stid)){
                                    // Affichage des résultats dans un tableau HTML
                                    echo '<div id="table-result" class="table__resultFAQ"><table><tr><td>Discipline</td></tr>';
                                    do {
                                        echo '<tr>';
                                        foreach ($row as $value) {
                                            echo '<td>'.$value.'</td>';
                                        }
                                        echo '</tr>';
                                    } while ($row = oci_fetch_row($stid));
                                    echo '</table></div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <script src="../script.js"></script> <!-- Inclusion du fichier JavaScript -->
</body>
</html>
