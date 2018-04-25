<?php
$multiple_order_by = 0;

function Lecture_BD_SQL() {

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

    //OLD SELECT
//    $Poids_Total = $Poids_Prix + $Poids_Distance + $Poids_NbEt;
//
//    if ($Poids_Total == 1) {
//        actualise_min_max();
//        if ($Pref_Prix == Min) {
//            $reponse = $bdd->query('SELECT MIN(Prix) FROM HOTEL');
//            while ($donnees = $reponse->fetch()) {
//                $MM_Prix = $donnees['MIN(Prix)'];
//            }
//        }
//        if ($Pref_Prix == Max) {
//            $reponse = $bdd->query('SELECT MAX(Prix) FROM HOTEL');
//            while ($donnees = $reponse->fetch()) {
//                $MM_Prix = $donnees['MAX(Prix)'];
//            }
//        }
//        if ($Pref_Prix == Min) {
//            $reponse = $bdd->query('SELECT MIN(Distance) FROM HOTEL');
//            while ($donnees = $reponse->fetch()) {
//                $MM_Distance = $donnees['MIN(Distance)'];
//            }
//        }
//        if ($Pref_Prix == Max) {
//            $reponse = $bdd->query('SELECT MAX(Distance) FROM HOTEL');
//            while ($donnees = $reponse->fetch()) {
//                $MM_Distance = $donnees['MAX(Distance)'];
//            }
//        }
//        if ($Pref_Prix == Min) {
//            $reponse = $bdd->query('SELECT MIN(NbEt) FROM HOTEL');
//            while ($donnees = $reponse->fetch()) {
//                $MM_NbEt = $donnees['MIN(NbEt)'];
//            }
//        }
//        if ($Pref_Prix == Max) {
//            $reponse = $bdd->query('SELECT MAX(NbEt) FROM HOTEL');
//            while ($donnees = $reponse->fetch()) {
//                $MM_NbEt = $donnees['MAX(NbEt)'];
//            }
//        }
//
//        $SQL_TEXT = $SQL_TEXT . " 
//        SELECT 	H.IdH
//        ,
//        ($Poids_Prix * $MM_Prix / Prix) Prix
//        ,
//        ($Poids_Distance * $MM_Distance / Distance) Distance
//        ,
//        ($Poids_NbEt * $MM_NbEt / NbEt) NbEt
//        ";
//    } else {
//        $Poids_Prix = 1 / 3;
//        $Poids_Distance = 1 / 3;
//        $Poids_NbEt = 1 / 3;
//    $SQL_TEXT = $SQL_TEXT . " 
//        SELECT H.IdH, H.Prix, H.Distance, H.NbEt
//        ";
//    }
    //FORM
    //WHERE
    $SQL_TEXT = $SQL_TEXT . " 
        SELECT H.IdH, H.Prix, H.Distance, H.NbEt, S.Score
        ";
    $SQL_TEXT = $SQL_TEXT . '
        FROM HOTEL H, HOTEL_SCORE S
        WHERE H.IdH = S.IdH
        ORDER BY S.Score Desc;
        ';

    //ORDER BY
//    add_order_by($SQL_TEXT, 'S.Score', 'Desc');
    if ($Pref_Prix == Min) {
//        $SQL_TEXT = $SQL_TEXT . add_order_by($SQL_TEXT, 'H.Prix', 'ASC');
        $MM1 = "MM.MM_Prix/HS.Prix";
    }
    if ($Pref_Prix == Max) {
//        $SQL_TEXT = $SQL_TEXT . add_order_by($SQL_TEXT, 'H.Prix', 'DESC');
        $MM1 = "HS.Prix/MM.MM_Prix";
    }
    if ($Pref_Distance == Min) {
//        $SQL_TEXT = $SQL_TEXT . add_order_by($SQL_TEXT, 'H.Distance', 'ASC');
        $MM2 = "MM.MM_Distance/HS.Distance";
    }
    if ($Pref_Distance == Max) {
//        $SQL_TEXT = $SQL_TEXT . add_order_by($SQL_TEXT, 'H.Distance', 'DESC');
        $MM2 = "HS.Distance/MM.MM_Distance";
    }
    if ($Pref_NbEt == Min) {
//        $SQL_TEXT = $SQL_TEXT . add_order_by($SQL_TEXT, 'H.NbEt', 'ASC');
        $MM3 = "MM.MM_NbEt/HS.NbEt";
    }
    if ($Pref_NbEt == Max) {
//        $SQL_TEXT = $SQL_TEXT . add_order_by($SQL_TEXT, 'H.NbEt', 'DESC');
        $MM3 = "HS.NbEt/MM.MM_NbEt";
    }

    //TEST SQL
    $SQL_TEXT_VIEW = "
CREATE OR REPLACE VIEW MIN_MAX
AS
SELECT $Pref_Prix(Prix) MM_Prix, $Pref_Distance(Distance) MM_Distance, $Pref_NbEt(NbEt) MM_NbEt
FROM HOTEL_SKY
;

CREATE OR REPLACE VIEW HOTEL_NORM
AS
SELECT 	HS.IdH
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
SELECT 	IdH
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
SELECT 	IdH
		,
		Prix_Pond
		,
		Distance_Pond
		,
		NbEt_Pond
		,
		(Prix_Pond+Distance_Pond+NbEt_Pond) Score
FROM HOTEL_POND
;";

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
                <th>NbEt</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $reponse = $bdd->query($SQL_TEXT);
            while ($donnees = $reponse->fetch()) {
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

function check_word_in_text($text, $word) {
    if (strpos($text, $word) !== false) {
        return 1;
    }
    return 0;
}

function add_order_by($SQL_TEXT, $attribute, $sort) {
    if (check_word_in_text($SQL_TEXT, 'ORDER BY') == 0) {
        $multiple_order_by = 1;
        return " ORDER BY $attribute $sort";
    }

    if (check_word_in_text($SQL_TEXT, 'ORDER BY') == 1) {
        return " , $attribute $sort";
    }
}

function actualise_min_max() {

    try {
        $bdd = new PDO('mysql:host=mysql-cyril-canlay.alwaysdata.net;dbname=cyril-canlay_test;charset=utf8', '127266', '127266');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $reponse = $bdd->query('SELECT MIN(IdH) FROM `HOTEL` WHERE 1');
    $Min_Prix = $donnees['Prix'];
    $reponse = $bdd->query('SELECT Min(Distance) FROM `HOTEL` WHERE 1');
    $Min_Distance = $donnees['Distance'];
    $reponse = $bdd->query('SELECT Min(NbEt) FROM `HOTEL` WHERE 1');
    $Min_NbEt = $donnees['NbEt'];
    $reponse = $bdd->query('SELECT Max(Prix) FROM `HOTEL` WHERE 1');
    $Max_Prix = $donnees['Prix'];
    $reponse = $bdd->query('SELECT Max(Distance) FROM `HOTEL` WHERE 1');
    $Max_Distance = $donnees['Distance'];
    $reponse = $bdd->query('SELECT Max(NbEt) FROM `HOTEL` WHERE 1');
    $Max_NbEt = $donnees['NbEt'];
}
?>

