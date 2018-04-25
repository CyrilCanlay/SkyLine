<?php include 'function.php' ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link href="css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            [class*="col"] { margin-bottom: 20px; }
            img { width: 100%; }
            body { margin-top: 10px; }
        </style>
    </head>
    <body>
        <div class="container">
            <section class="row">
                <form action = "index.php" method = "post">
                    <div class="col-lg-4">
                        <h1>Formulaire : </h1>
                        <input type = "submit" value = "Valider" />
                    </div>

                    <div class="col-lg-4">
                        <h2> Poids (sur 100) : </h2>

                        <h3> Prix : </h3>
                        <input type = "text" name = "Poids_Prix" />

                        <h3> Distance : </h3>
                        <input type = "text" name = "Poids_Distance" />

                        <h3> Étoiles : </h3>
                        <input type = "text" name = "Poids_NbEt" />
                    </div>
                    <div class="col-lg-4">

                        <h2> Préférences : </h2>

                        <h3> Prix : </h3>
                        <select name = "Pref_Prix" id = "Prix">
                            <option value = "NoPref">NoPref</option>
                            <option value = "Min">Min</option>
                            <option value = "Max">Max</option>
                        </select>

                        <h3> Distance : </h3>
                        <select name = "Pref_Distance" id = "Distance">
                            <option value = "NoPref">NoPref</option>
                            <option value = "Min">Min</option>
                            <option value = "Max">Max</option>
                        </select>

                        <h3> Étoiles : </h3>
                        <select name = "Pref_NbEt" id = "NbEt">
                            <option value = "NoPref">NoPref</option>
                            <option value = "Min">Min</option>
                            <option value = "Max">Max</option>
                        </select>
                    </div>
                </form>
            </section>
            <section class="row">
                <div class="col-lg-4">
                    <h1>Vos préférences</h1>
                    <p>Poids_Prix : <?php echo $_POST['Poids_Prix']; ?></p>
                    <p>Poids_Distance : <?php echo $_POST['Poids_Distance']; ?></p>
                    <p>Poids_NbEt : <?php echo $_POST['Poids_NbEt']; ?></p>
                    <p>Preference de Prix : <?php echo $_POST['Pref_Prix']; ?></p>
                    <p>Preference de Distance : <?php echo $_POST['Pref_Distance']; ?></p>
                    <p>Preference de NbEt : <?php echo $_POST['Pref_NbEt']; ?></p>
                </div>
                <div class="col-lg-8">
                    <h1>Resultat</h1>
                    <?php Lecture_BD_SQL(); ?>
                </div>
            </section>
        </div>
    </body>
</html>