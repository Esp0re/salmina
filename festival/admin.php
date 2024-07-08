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

$statement = $db->query("select * from festivals order by id desc limit 1");
$festival = $statement->fetch();

$ticketPrice = $festival["ticket_price"];
$ticketDiscount = $festival["ticket_discount"];
$mealPrice = $festival["meal_price"];

function computePrice($registration, $festival): array
{
    $ticketPrice = $festival["ticket_price"];
    $ticketDiscount = $festival["ticket_discount"];
    $mealPrice = $festival["meal_price"];

    $totalTicketPrice =
        ($registration["ticket1"] ? $ticketPrice : 0) +
        ($registration["ticket2"] ? $ticketPrice : 0) +
        ($registration["ticket1"] && $registration["ticket2"] ? $ticketDiscount : 0);
    $totalMealPrice =
        ($registration["meal1"] ? $mealPrice : 0) +
        ($registration["meal2"] ? $mealPrice : 0) +
        ($registration["meal3"] ? $mealPrice : 0) +
        ($registration["meal4"] ? $mealPrice : 0);

    return [
        "tickets" => $totalTicketPrice,
        "meals" => $totalMealPrice,
        "total" => $totalTicketPrice + $totalMealPrice,
    ];
}

for ($i = 0; $i < count($paidRegistrations); ++$i)
    $paidRegistrations[$i]["price"] = computePrice($paidRegistrations[$i], $festival);

for ($i = 0; $i < count($unpaidRegistrations); ++$i)
    $unpaidRegistrations[$i]["price"] = computePrice($unpaidRegistrations[$i], $festival);

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

$totalIncome = array_sum(array_map(fn($r) => $r["price"]["total"], $paidRegistrations));
$unpaidTotalIncome = array_sum(array_map(fn($r) => $r["price"]["total"], $unpaidRegistrations));
$ticketsIncome = array_sum(array_map(fn($r) => $r["price"]["tickets"], $paidRegistrations));
$unpaidTicketsIncome = array_sum(array_map(fn($r) => $r["price"]["tickets"], $unpaidRegistrations));
$mealsIncome = array_sum(array_map(fn($r) => $r["price"]["meals"], $paidRegistrations));
$unpaidMealsIncome = array_sum(array_map(fn($r) => $r["price"]["meals"], $unpaidRegistrations));

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
            min-width: 1000px;
        }

        table {
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid black;
            min-width: 1em;
            padding: 0.4em;
        }

        td:not(.message) {
            white-space: nowrap;
        }

        .message > div {
            max-height: 4lh;
            overflow-y: scroll;
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

<h2>Recette</h2>
<table>
    <tr>
        <td></td>
        <th>Actuelle</th>
        <th>Potentielle</th>
    </tr>
    <tr>
        <th>Billets</th>
        <td>CHF <?= $ticketsIncome ?></td>
        <td>CHF <?= $ticketsIncome + $unpaidTicketsIncome ?></td>
    </tr>
    <tr>
        <th>Repas</th>
        <td>CHF <?= $mealsIncome ?></td>
        <td>CHF <?= $mealsIncome + $unpaidMealsIncome ?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <th>Total</th>
        <td>CHF <?= $totalIncome ?></td>
        <td>CHF <?= $totalIncome + $unpaidTotalIncome ?></td>
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
        <th colspan="2">Conditions acceptées / lues</th>
        <th>Message</th>
        <th>Payé le</th>
        <th>Prix</th>
        <th>Paiement</th>
    </tr>
    <?php foreach ($paidRegistrations as $registration): ?>
        <tr>
            <td><?= $registration["name"] ?></td>
            <td><?php for ($i = 1; $i <= 2; $i++) if ($registration["ticket" . $i]) echo $i . "&nbsp;"; ?></td>
            <td><?php for ($i = 1; $i <= 4; $i++) if ($registration["meal" . $i]) echo $i . "&nbsp;"; ?></td>
            <td><?= $registration["conditions_accepted"] ? "oui" : "" ?></td>
            <td><?= $registration["conditions_read"] ? "oui" : "" ?></td>
            <td class="message">
                <div><?= $registration["message"] ?></div>
            </td>
            <td><?= substr(explode(".", $registration["paid_on"])[0], 0, 16) ?></td>
            <td>CHF <?= $registration["price"]["total"] ?></td>
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
        <th colspan="2">Conditions acceptées / lues</th>
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
            <td class="message">
                <div><?= $registration["message"] ?></div>
            </td>
            <td><?= substr(explode(".", $registration["registered_on"])[0], 0, 16) ?></td>
            <td>CHF <?= $registration["price"]["total"] ?></td>
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
