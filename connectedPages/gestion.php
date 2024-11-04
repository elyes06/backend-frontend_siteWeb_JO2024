<?php
// Démarrer la session
    session_start();
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION["isConnected"])) {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        header("Location: ../route/login.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer</title>
</head>

<body>
    <style>
        /* Utilisation de styles en ligne pour définir l'arrière-plan de l'image de profil */
        .pdp__user{
            /* Récupérer l'URL de l'image de profil à partir de la session */
            background-image: url(<?php
                    $dest=$_SESSION["avatarIMG"];
                    echo $dest;
                ?>);
        }
    </style>
    <!-- En-tête -->
    <header class="header">
        <div class="header__logo logo">
            <img src="../assets/images/logo_JO4.png" class="logo__media"/>
        </div>
        <nav class="header__menu menu">
            <ul class="menu__items">
                <li class="menu__item"><a class="menu__link" href="me.php">Accueil</a></li>
                <li class="menu__item"><a class="menu__link" href="search.php">Recherche</a></li>
                <li class="menu__item"><a class="menu__link" href="gestion.php">Gérer</a></li>
                <a class="menu__link pdp__user" href="./donnees.php"></a>
                <a href="./logout.php" class="menu__link button">Déconnexion</a>
            </ul>
        </nav>
    </header>
    <!-- Section principale -->
    <section class="hero gestion__hero">
        <div class="gestion__container">
            <div class="table__chose">
                <!-- Formulaire pour choisir le tableau à modifier -->
                <form class="form__chose" action="gestion.php" method="post">
                    <label for="radio">Quel tableau souhaitez-vous modifier ?</label>
                    <div class="radio__chose1">
                        <div class="search__filters alter__table">
                            <input type="radio" name="table" value="arbitre" checked><label for="filters" >Arbitres</label>
                            <input type="radio" name="table" value="assigne"><label for="filters">Assignations</label>
                            <input type="radio" name="table" value="inscrisE"><label for="filters">Inscriptions Equipes</label>
                            <input type="radio" name="table" value="inscrisA"><label for="filters">Inscriptions Athlètes</label>
                            <input type="radio" name="table" value="competition"><label for="filters">Compétitions</label>
                        </div>
                    </div>  
                    <div class="radio__chose2">
                        <div class="search__filters alter__table">    
                            <input type="radio" name="table" value="equipe"><label for="filters">Équipes</label>
                            <input type="radio" name="table" value="personnel"><label for="filters">Personnel</label>
                            <input type="radio" name="table" value="athletes"><label for="filters">Athlètes</label>
                            <input type="radio" name="table" value="entraineurs"><label for="filters">Entraîneurs</label>
                            <input type="radio" name="table" value="participants"><label for="filters">Participants</label>
                        </div>
                    </div>
                    <div class="buttons__alter">
                        <input class="gestion__alter button" type="submit" name="submitADD" value="Ajouter">
                        <input class="gestion__alter button" type="submit" name="submitALTER" value="Modifier">
                        <input class="gestion__alter button" type="submit" name="submitDELETE" value="Supprimer">
                    </div>
                    
                </form>
            </div>
            <div class="table__load">
            <?php
    $arbitre=array("IdPersonnel","IdCategorie");
    $assignation=array("IdPersonnel","IdVille","DateDeb (JJ/MM/AAAA)","DateFin (JJ/MM/AAAA)");
    $inscrisE=array("IdEquipe","IdCompetition","ResultatEquipe","ClassementEquipe","IdMedaille","RecordBattu");
    $inscrisA=array("IdAthlete","IdCompetition","ResultatAthlete","ClassementAthlete","IdMedaille","RecordBattu");
    $competition=array("IdCompetition","Date","Heure","Phase","IdVille","IdDiscipline");
    $equipe=array("IdEquipe","IdEntraineur");
    $personnel=array("IdPersonnel","IdParticipant","Fonction");
    $athlete=array("IdAthlete","IdParticipant","Poids","Taille","IdEntraineur");
    $entraineur=array("IdEntraineur","IdParticipant","Diplome");
    $participant=array("IdParticipant","Nom","Prenom","Nationalite","DateNaissance");
    include '../myparam.inc.php';

    if(isset($_POST['submitADD'])){

        if($_POST['table'] == "arbitre"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $arbitre;
        }
        if($_POST['table'] == "assigne"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $assignation;
        }
        if($_POST['table'] == "inscrisE"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $inscrisE;
        }
        if($_POST['table'] == "inscrisA"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $inscrisA;
        }
        if($_POST['table'] == "competition"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $competition;
        }
        if($_POST['table'] == "equipe"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $equipe;
        }
        if($_POST['table'] == "personnel"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $personnel;
        }
        if($_POST['table'] == "athletes"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $athlete;
        }
        if($_POST['table'] == "entraineurs"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $entraineur;
        }
        if($_POST['table'] == "participants"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $participant;
        }
        $_SESSION['fields'] = $table_fields;

        echo "<h3>{$_SESSION["table"]}</h3>";
        echo'<form class="add__form" method="post" action="gestion.php">
                <table class="table__add">
                    <tr>';
                    foreach($table_fields as $value){
                        echo "<td>$value</td>";
                    }
                    echo '</tr>';
                    echo '<tr>';
                    for($i=0; $i<count($table_fields);$i++){
                        echo '<td><input name="inp[]" type="text" placeholder="..."></td>';
                    }
                    echo '</tr>';

        echo '
                </table>
            <input name="add__submit-result" class="gestion__alter button insidealter" type="submit" value="Ajouter">
            </form>';
    }
    if(isset($_POST["add__submit-result"])){
        $table = $_SESSION["table"];
        $connex = oci_connect(MYUSER,MYPASS,MYHOST);

        if($connex){
            $fields = "";
            $values = "";
            foreach($_SESSION['fields'] as $field){
                $fields.= $field.',';
            }
            foreach($_POST['inp'] as $input){
                $values.= ", '$input'";
            }
            $fields = rtrim($fields, ',');
            $pattern = "/('\d{2}\/\d{2}\/\d{4}')/";
            $pattern2 = "/('\d{2}:\d{2}:\d{2}')/";

            $replacement = "TO_DATE($1,'DD/MM/YYYY')";
            $replacement2 = "TO_DATE($1,'HH24:MI:SS')";
               
            $values = ltrim($values, ',');
            $values = preg_replace($pattern, $replacement, $values); 
            $values = preg_replace($pattern2, $replacement2, $values);

            $sql = "INSERT INTO $table ($fields) VALUES ($values)";
            $stid = oci_parse($connex, $sql);
            $result = @oci_execute($stid);
            if ($result) {
                echo "Insertion réussie";
            } else {
                echo "Insertion echouée";
            }   

        }
    }
    if(isset($_POST['submitALTER'])){
        $connex = oci_connect(MYUSER,MYPASS,MYHOST);

        if($_POST['table'] == "arbitre"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $arbitre;
        }
        if($_POST['table'] == "assigne"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $assignation;
        }
        if($_POST['table'] == "inscrisE"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $inscrisE;
        }
        if($_POST['table'] == "inscrisA"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $inscrisA;
        }
        if($_POST['table'] == "competition"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $competition;
        }
        if($_POST['table'] == "equipe"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $equipe;
        }
        if($_POST['table'] == "personnel"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $personnel;
        }
        if($_POST['table'] == "athletes"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $athlete;
        }
        if($_POST['table'] == "entraineurs"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $entraineur;
        }
        if($_POST['table'] == "participants"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $participant;
        }
        $_SESSION['fields'] = $table_fields;

        $sql = "select * from {$_SESSION["table"]}";
        $stid = oci_parse($connex, $sql);
        $result = oci_execute($stid);
        if($row = oci_fetch_row($stid)){
        echo '<form class="add__form" method="post" action="gestion.php">';
            echo '<select class="list__id" name="id">';
            do {
                 echo '<option value='.$row[0].'>'.$row[0].'</option>';
            
            } while ($row = oci_fetch_row($stid));
            echo '</select>';
            echo '<input name="alter__submit-result" class="gestion__alter button insidealter" type="submit" value="Modifier">';
        echo '</form>';
        }
    }
    if(isset($_POST['alter__submit-result'])){
        $connex = oci_connect(MYUSER,MYPASS,MYHOST);
        $table = $_SESSION['table'];
        $id = $_POST['id'];
        $fields = $_SESSION['fields'];
        $field = $fields[0];
        $_SESSION['field'] = $field;
        $_SESSION['id'] = $id;

        $sql = "select * from {$_SESSION["table"]} where $field LIKE '$id'";
        $stid = oci_parse($connex, $sql);
        $result = oci_execute($stid);
        if($row = oci_fetch_row($stid)){
            echo "<h3>{$_SESSION["table"]}</h3>";
            echo'<form class="add__form" method="post" action="gestion.php">
                <table class="table__add">
                    <tr>';
                    foreach($fields as $value){
                        echo "<td>$value</td>";
                    }
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>'.$id.'</td>';
                    for($i=0; $i<count($fields)-1;$i++){
                        echo '<td><input name="inp[]" type="text" placeholder="..."></td>';
                    }
                    echo '</tr>';

        echo '
                </table>
            <input name="alterfinal__submit-result" class="gestion__alter button insidealter" type="submit" value="Modifier">
            </form>';
        }
    }
    if(isset($_POST["alterfinal__submit-result"])){
        $table = $_SESSION["table"];
        $connex = oci_connect(MYUSER,MYPASS,MYHOST);
        $id = $_SESSION['id'];
        $field = $_SESSION['field'];

        if($connex){
            $sql = "DELETE FROM $table WHERE $field LIKE '$id'";
            $stid = oci_parse($connex, $sql);
            $result = @oci_execute($stid);
            if ($result) {

                $fields = "";
                $values = "";
                foreach($_SESSION['fields'] as $field){
                    $fields.= $field.',';
                }
                foreach($_POST['inp'] as $input){
                    $values.= ", '$input'";
                }
                $fields = rtrim($fields, ',');
                $pattern = "/('\d{2}\/\d{2}\/\d{4}')/";
                $pattern2 = "/('\d{2}:\d{2}:\d{2}')/";

                $replacement = "TO_DATE($1,'DD/MM/YYYY')";
                $replacement2 = "TO_DATE($1,'HH24:MI:SS')";
                
                $values = ltrim($values, ',');
                $values = preg_replace($pattern, $replacement, $values); 
                $values = preg_replace($pattern2, $replacement2, $values);
                $id="'$id',".$values;
                $values=$id;
                $sql = "INSERT INTO $table ($fields) VALUES ($values)";
                $stid = oci_parse($connex, $sql);
                $result = @oci_execute($stid);
                if ($result) {
                    echo "Modification réussie";
                } else {
                    echo "Modification echouée";
                }   

            }
        }
    }
    if(isset($_POST['submitDELETE'])){
        $connex = oci_connect(MYUSER,MYPASS,MYHOST);

        if($_POST['table'] == "arbitre"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $arbitre;
        }
        if($_POST['table'] == "assigne"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $assignation;
        }
        if($_POST['table'] == "inscrisE"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $inscrisE;
        }
        if($_POST['table'] == "inscrisA"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $inscrisA;
        }
        if($_POST['table'] == "competition"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $competition;
        }
        if($_POST['table'] == "equipe"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $equipe;
        }
        if($_POST['table'] == "personnel"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $personnel;
        }
        if($_POST['table'] == "athletes"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $athlete;
        }
        if($_POST['table'] == "entraineurs"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $entraineur;
        }
        if($_POST['table'] == "participants"){
            $_SESSION["table"] = $_POST["table"];
            $table_fields = $participant;
        }
        $_SESSION['fields'] = $table_fields;

        $sql = "select * from {$_SESSION["table"]}";
        $stid = oci_parse($connex, $sql);
        $result = oci_execute($stid);
        if($row = oci_fetch_row($stid)){
        echo '<form class="add__form" method="post" action="gestion.php">';
            echo '<select class="list__id" name="id">';
            do {
                 echo '<option value='.$row[0].'>'.$row[0].'</option>';
            
            } while ($row = oci_fetch_row($stid));
            echo '</select>';
            echo '<input name="delete__submit-result" class="gestion__alter button insidealter" type="submit" value="Supprimer">';
        echo '</form>';
        }
    }
    if(isset($_POST['delete__submit-result'])){
        $fields = $_SESSION['fields'];
        $field = $fields[0];
        $id = $_POST['id'];
        $table = $_SESSION["table"];
        $connex = oci_connect(MYUSER,MYPASS,MYHOST);
        

        if($connex){
            $sql = "DELETE FROM $table WHERE $field LIKE '$id'";
            $stid = oci_parse($connex, $sql);
            $result = @oci_execute($stid);
            if ($result) {
                echo "Suppression réussie.";
            }else{
                echo "Suppression echouée.";
            }
        }
    }
?>
            </div>
        </div>
            
    </section>

</body>
</html>
