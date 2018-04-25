<?php
$debug = false;

function Lecture_BD_SQL() {

    if ($debug) {
        var_dump($_POST);
    } else {
        error_reporting(E_ERROR | E_PARSE);
    }

    $Poids_Prix = $_POST['Poids_Prix'] / 100;
    $Poids_Distance = $_POST['Poids_Distance'] / 100;
    $Poids_NbEt = $_POST['Poids_NbEt'] / 100;
    $Pref_Prix = $_POST['Pref_Prix'];
    $Pref_Distance = $_POST['Pref_Distance'];
    $Pref_NbEt = $_POST['Pref_NbEt'];

    try {
        $bdd = new PDO('mysql:host=mysql-cyril-canlay.alwaysdata.net;dbname=cyril-canlay_test;charset=utf8', '127266', '127266');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $SQL_TEXT = " 
        SELECT H.IdH, H.Prix, H.Distance, H.NbEt, S.Score
        FROM HOTEL H, HOTEL_SCORE S
        WHERE H.IdH = S.IdH
        ORDER BY S.Score Desc;
        ";

    if ($Pref_Prix == Min) {
        $MM1 = "MM.MM_Prix/HS.Prix";
    }
    if ($Pref_Prix == Max) {
        $MM1 = "HS.Prix/MM.MM_Prix";
    }
    if ($Pref_Distance == Min) {
        $MM2 = "MM.MM_Distance/HS.Distance";
    }
    if ($Pref_Distance == Max) {
        $MM2 = "HS.Distance/MM.MM_Distance";
    }
    if ($Pref_NbEt == Min) {
        $MM3 = "MM.MM_NbEt/HS.NbEt";
    }
    if ($Pref_NbEt == Max) {
        $MM3 = "HS.NbEt/MM.MM_NbEt";
    }

    $SQL_TEXT_VIEW = "
    CREATE OR REPLACE VIEW MIN_MAX
    AS
    SELECT $Pref_Prix(Prix) MM_Prix, $Pref_Distance(Distance) MM_Distance, $Pref_NbEt(NbEt) MM_NbEt
    FROM HOTEL_SKY
    ;

    CREATE OR REPLACE VIEW HOTEL_NORM
    AS
    SELECT HS.IdH
    ,
    ($MM1) Prix_Norm
    ,
    ($MM2) Distance_Norm
    ,
    ($MM3) NbEt_Norm
    FROM HOTEL_SKY HS, MIN_MAX MM
    ;

    CREATE OR REPLACE VIEW HOTEL_POND
    AS
    SELECT IdH
    ,
    ($Poids_Prix*Prix_Norm) Prix_Pond
    ,
    ($Poids_Distance*Distance_Norm) Distance_Pond
    ,
    ($Poids_NbEt*NbEt_Norm) NbEt_Pond
    FROM HOTEL_NORM
    ;

    CREATE OR REPLACE VIEW HOTEL_SCORE
    AS
    SELECT IdH
    ,
    Prix_Pond
    ,
    Distance_Pond
    ,
    NbEt_Pond
    ,
    (Prix_Pond+Distance_Pond+NbEt_Pond) Score
    FROM HOTEL_POND
    ;
    ";

    $bdd->exec($SQL_TEXT_VIEW);

    //TEST SQL
    ?>
    <strong> Requete SQL envoyée : </strong>
    <?php
    echo $SQL_TEXT;
    ?>
    <br/>
    <br/>
    <table class="table table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <th>IdH</th>
                <th>Prix</th>
                <th>Distance</th>
                <th>Étoiles</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>

    <?php
    $reponse = $bdd->query($SQL_TEXT);
    while ($donnees = $reponse->fetch()) {
        if ($debug)
            var_dump($donnees);
        ?>
                <tr>
                    <td> <?php $donnees['IdH']; ?> </td>
                    <td> <?php echo $donnees['Prix']; ?> </td>
                    <td> <?php echo $donnees['Distance']; ?> </td>
                    <td> <?php echo $donnees['NbEt']; ?> </td>
                    <td> <?php echo $donnees['Score'] ?> </td>
                </tr>
        <?php
    }
    $reponse->closeCursor();
    ?>
        </tbody>
    </table>
    <?php
}
?>
