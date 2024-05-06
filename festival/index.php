<?php

$db = require(__DIR__ . "/db.php");
$statement = $db->query("select * from festivals order by id desc limit 1");
$festival = $statement->fetch();

session_start();

if (isset($_POST["new_registration"])) unset($_SESSION["id"]);

$isRegistered = isset($_SESSION["id"]);

if ($isRegistered) {
    $statement = $db->prepare("select * from festival2024 where id = ?");
    $statement->execute([$_SESSION["id"]]);
    $registration = $statement->fetch();
    if (!$registration) {
        unset($_SESSION["id"]);
        $isRegistered = false;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
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
    <h1><span>Salmina</span><span>Festival</span><span>2024</span></h1>

    <div class="info">
        <p>19 &ndash; 20 juillet</p>
    </div>
    <?php if ($isRegistered) require "registration.php"; else require "form.php"; ?>
</main>

</body>
</html>
