<?php

$db = require(__DIR__ . "/db.php");
$statement = $db->query("select * from festivals order by id desc limit 1");
$festival = $statement->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $festival["name"] ?></title>
    <meta charset="UTF-8">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <meta name=theme-color content=#000000>
    <link rel="stylesheet" href="/styles/festival.css">
    <link rel="icon" href="/images/icon.svg">
</head>
<body>
<main>
    <?php require __DIR__ . "/components/title.html" ?>
    <div class="about">
        <p>Le <strong>Salmina Festival</strong> est un petit festival entre potes avec musique, pétanque et grillades.
        </p>

        <a class="button" href="/festival/tickets">Prendre des billets</a>

        <h2>Quand</h2>
        <p>Du <strong>vendredi 19 au samedi 20 juillet 2024</strong>, ainsi que le dimanche 21 durant la journée pour
            les motivés.</p>

        <h2>Où</h2>
        <p>Dans une cabane dans la forêt, entre Cheyres et Murist. Le lieu exact sera donné peu de temps avant le début
            de l'évènement.</p>

        <h2>Quoi</h2>
        <p><strong>Durant la journée</strong>&nbsp;: tournois de pétanque, ping-pong, grillades, musique, baignade (le
            lac est à 10 min en voiture, 1 heure à pied).</p>

        <p><strong>Durant la nuit</strong>&nbsp;: système son et table de mixage à disposition pour DJs de tous les
            horizons. Tous les
            styles de musique sont autorisés&nbsp;!
            Faites-nous savoir si vous êtes intéressés à mixer.</p>

        <img src="/festival/medias/petanque.webp" alt="pétanque">

        <h2>Manger</h2>
        <p>Plusieurs repas proposés par notre super chef cuisto tout au long du weekend. Tous les repas sont <strong>végétariens</strong> et chacun
            coute CHF 5.</p>
        <p>Pour ceux qui le souhaitent, un <strong>grand grill</strong> avec broches motorisées est à disposition.
            Chacun amène sa propre bidoche (possibilité de se coordonner à plusieurs).</p>

        <img src="/festival/medias/manger.webp" alt="manger">

        <h2>Boire</h2>
        <p>Bières, Pastis et autres jus seront proposés à des prix abordables.</p>

        <h2>Dormir</h2>
        <p>Le dortoir est assez petit et est donc <strong>réservé en priorité aux organisateurs et aux
                collaborateurs</strong> (cuisine, artistes, etc.).
            Il est possible d'y installer une tente dans le gazon. Il est évidemment possible de dormir dans une
            voiture, sur une table, sous un arbre, ne pas dormir, etc.</p>
        <p>Plus de détails sur les places disponibles seront donnés peu avant l'évènement, en fonction du nombre de
            personnes inscrites.</p>

        <h2>Alain Berset</h2>
        <p>Il est invité.</p>

        <a class="button" href="/festival/tickets">Prendre des billets</a>

        <video id="video" autoplay loop muted>
            <source src="/festival/medias/boumboum.mp4" type="video/mp4">
        </video>
    </div>
</main>
</body>
</html>
