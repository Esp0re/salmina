<?php

session_start();

if (!isset($_SESSION["logged"])) {
    header("Location: /festival/login.php");
    exit();
}

$db = require(__DIR__ . "/db.php");

if (isset($_POST["has_paid"])) {
    $statement = $db->prepare(
        "update festival2024 set has_paid = ?, paid_on = " . ($_POST["has_paid"] ? "now()" : "NULL") . " where id = ?");
    $statement->execute([$_POST["has_paid"], $_POST["id"]]);
}

if (isset($_POST["hash"])) {
    $hash = explode(" ", $_POST["hash"]);
    $hash = end($hash);
    $statement = $db->prepare("update festival2024 set has_paid = 1, paid_on = now() where hash = ?");
    $statement->execute([$hash]);
}

$statement = $db->query("select * from festival2024 where has_paid order by paid_on desc");
$paidRegistrations = $statement->fetchAll();

$statement = $db->query("select * from festival2024 where not has_paid order by registered_on desc");
$unpaidRegistrations = $statement->fetchAll();

$ticketPrice = 30;
$ticketDiscount = 20;
$mealPrice = 10;

for ($i = 0; $i < count($unpaidRegistrations); ++$i)
    $unpaidRegistrations[$i]["price"] =
        ($unpaidRegistrations[$i]["ticket1"] ? $ticketPrice : 0) +
        ($unpaidRegistrations[$i]["ticket2"] ? $ticketPrice : 0) +
        ($unpaidRegistrations[$i]["ticket1"] && $unpaidRegistrations[$i]["ticket2"] ? -$ticketDiscount : 0) +
        ($unpaidRegistrations[$i]["meal1"] ? $mealPrice : 0) +
        ($unpaidRegistrations[$i]["meal2"] ? $mealPrice : 0) +
        ($unpaidRegistrations[$i]["meal3"] ? $mealPrice : 0) +
        ($unpaidRegistrations[$i]["meal4"] ? $mealPrice : 0);

$ticket1sum = array_sum(array_map(fn($r) => $r["ticket1"], $paidRegistrations));
$ticket2sum = array_sum(array_map(fn($r) => $r["ticket2"], $paidRegistrations));

$unpaidTicket1sum = array_sum(array_map(fn($r) => $r["ticket1"], $unpaidRegistrations));
$unpaidTicket2sum = array_sum(array_map(fn($r) => $r["ticket2"], $unpaidRegistrations));

$meal1sum = array_sum(array_map(fn($r) => $r["meal1"], $paidRegistrations));
$meal2sum = array_sum(array_map(fn($r) => $r["meal2"], $paidRegistrations));
$meal3sum = array_sum(array_map(fn($r) => $r["meal3"], $paidRegistrations));
$meal4sum = array_sum(array_map(fn($r) => $r["meal4"], $paidRegistrations));

$unpaidMeal1sum = array_sum(array_map(fn($r) => $r["meal1"], $unpaidRegistrations));
$unpaidMeal2sum = array_sum(array_map(fn($r) => $r["meal2"], $unpaidRegistrations));
$unpaidMeal3sum = array_sum(array_map(fn($r) => $r["meal3"], $unpaidRegistrations));
$unpaidMeal4sum = array_sum(array_map(fn($r) => $r["meal4"], $unpaidRegistrations));

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Salmina - Inscriptions 2024</title>
    <meta charset="UTF-8">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <meta name=theme-color content=#000000>
    <link rel="icon" href="/images/icon.svg">
    <style>
        body {
            font-family: system-ui, sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid black;
            min-width: 1em;
            padding: 0.4em;
        }
    </style>
</head>
<body>
<h1>Inscriptions</h1>
<h2>Places</h2>
<table>
    <tr>
        <td></td>
        <th>Payé</th>
        <th>Potentiel</th>
    </tr>
    <tr>
        <th>Vendredi</th>
        <td><?= $ticket1sum ?> / 40</td>
        <td><?= $ticket1sum + $unpaidTicket1sum ?> / 40</td>
    </tr>
    <tr>
        <th>Samedi</th>
        <td><?= $ticket2sum ?> / 40</td>
        <td><?= $ticket2sum + $unpaidTicket2sum ?> / 40</td>
    </tr>
</table>

<h2>Repas</h2>
<table>
    <tr>
        <td></td>
        <th>Payé</th>
        <th>Potentiel</th>
    </tr>
    <tr>
        <th>Vendredi soir</th>
        <td><?= $meal1sum ?></td>
        <td><?= $meal1sum + $unpaidMeal1sum ?></td>
    </tr>
    <tr>
        <th>Samedi midi</th>
        <td><?= $meal2sum ?></td>
        <td><?= $meal2sum + $unpaidMeal2sum ?></td>
    </tr>
    <tr>
        <th>Vendredi soir</th>
        <td><?= $meal3sum ?></td>
        <td><?= $meal3sum + $unpaidMeal3sum ?></td>
    </tr>
    <tr>
        <th>Vendredi soir</th>
        <td><?= $meal4sum ?></td>
        <td><?= $meal4sum + $unpaidMeal4sum ?></td>
    </tr>
</table>
<h2>Inscriptions payées (<?= count($paidRegistrations) ?>)&nbsp;:</h2>
<form method="post" style="margin-bottom: 1.5em">
    <input type="text" name="hash" placeholder="Code d'inscription">
    <button type="submit">confirmer</button>
</form>
<table>
    <tr>
        <th>Nom</th>
        <th>Billets</th>
        <th>Repas</th>
        <th>Conditions acceptées</th>
        <th>Conditions lues</th>
        <th>Message</th>
        <th>Payé le</th>
        <th>Paiement</th>
    </tr>
    <?php foreach ($paidRegistrations as $registration): ?>
        <tr>
            <td><?= $registration["name"] ?></td>
            <td><?php for ($i = 1; $i <= 2; $i++) if ($registration["ticket" . $i]) echo $i . "&nbsp;"; ?></td>
            <td><?php for ($i = 1; $i <= 4; $i++) if ($registration["meal" . $i]) echo $i . "&nbsp;"; ?></td>
            <td><?= $registration["conditions_accepted"] ? "oui" : "" ?></td>
            <td><?= $registration["conditions_read"] ? "oui" : "" ?></td>
            <td><?= $registration["message"] ?></td>
            <td><?= explode(".", $registration["paid_on"])[0] ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="has_paid" value="0">
                    <input type="hidden" name="id" value="<?= $registration["id"] ?>">
                    <button type="submit">en fait non</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Inscriptions non-payées (<?= count($unpaidRegistrations) ?>)&nbsp;:</h2>
<table>
    <tr>
        <th>Nom</th>
        <th>Billets</th>
        <th>Repas</th>
        <th>Conditions acceptées</th>
        <th>Conditions lues</th>
        <th>Message</th>
        <th>Inscrit le</th>
        <th>Prix</th>
        <th>Paiement</th>
    </tr>
    <?php foreach ($unpaidRegistrations as $registration): ?>
        <tr>
            <td><?= $registration["name"] ?></td>
            <td><?php for ($i = 1; $i <= 2; $i++) if ($registration["ticket" . $i]) echo $i . "&nbsp;"; ?></td>
            <td><?php for ($i = 1; $i <= 4; $i++) if ($registration["meal" . $i]) echo $i . "&nbsp;"; ?></td>
            <td><?= $registration["conditions_accepted"] ? "oui" : "" ?></td>
            <td><?= $registration["conditions_read"] ? "oui" : "" ?></td>
            <td><?= $registration["message"] ?></td>
            <td><?= explode(".", $registration["registered_on"])[0] ?></td>
            <td>CHF <?= $registration["price"] ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="has_paid" value="1">
                    <input type="hidden" name="id" value="<?= $registration["id"] ?>">
                    <button type="submit">a payé</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
