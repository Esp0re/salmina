<?php

$items = [];

$ticketPrice = $festival["ticket_price"];
$mealPrice = $festival["meal_price"];
$ticketDiscount = $festival["ticket_discount"];

if ($registration["ticket1"]) $items[] = ["name" => "Billet vendredi", "price" => $ticketPrice];
if ($registration["ticket2"]) $items[] = ["name" => "Billet samedi", "price" => $ticketPrice];
if ($registration["meal1"]) $items[] = ["name" => "Repas vendredi soir", "price" => $mealPrice];
if ($registration["meal2"]) $items[] = ["name" => "Repas samedi midi", "price" => $mealPrice];
if ($registration["meal3"]) $items[] = ["name" => "Repas samedi soir", "price" => $mealPrice];
if ($registration["meal4"]) $items[] = ["name" => "Repas dimanche midi", "price" => $mealPrice];
if ($registration["ticket1"] && $registration["ticket2"]) $items[] = ["name" => "Rabais deux jours", "price" => $ticketDiscount];

$total = array_sum(array_map(fn($item) => $item["price"], $items));

$hasPaid = $registration["has_paid"];
$twintCode = "salmina" . explode("-", $festival["start_date"])[0] . " " . $registration["hash"]
?>

<p>Tu es inscrit au festival!</p>
<?php if (!$hasPaid): ?>
    <p>Pour confirmer ton inscription et réserver ta place, il ne te reste
        plus qu'à payer le montant ci-dessous à un organisateur.</p>
<?php else: ?>
    <p>Tu as déjà payé et ton inscription est confirmée.</p>
<?php endif ?>

<table>
    <?php foreach ($items as $item): ?>
        <tr>
            <th><?= $item["name"] ?></th>
            <td><?= $item["price"] ?></td>
        </tr>
    <?php endforeach ?>
    <tr class="total">
        <th>Total</th>
        <td>CHF <?= $total ?></td>
    </tr>
</table>

<?php if (!$hasPaid): ?>
    <p>Tu peux payer immédiatemment via TWINT avec les coordonnées suivantes&nbsp;:</p>

    <table>
        <tr>
            <th>Montant</th>
            <td>CHF <?= $total ?></td>
        </tr>
        <tr>
            <th>Numéro</th>
            <td><?= $festival["twint_number"] ?></td>
        </tr>
        <tr>
            <th>Prénom, nom</th>
            <td><?= $festival["twint_name"] ?></td>
        </tr>
        <tr>
            <th>Message</th>
            <td><?= $twintCode ?></td>
        </tr>
    </table>
<?php endif ?>

<form method="post">
    <input type="hidden" name="new_registration">
    <button type="submit">Recommencer une inscription</button>
</form>
