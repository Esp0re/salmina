<?php

session_start();

/**
 * Comportement actuel chiant parce que si on est connecté, il faut aller en nav privée 
 * et se connecter avec le bon mdp pour accéder à l'admin
 * -> problème réglé
 * */
// Not admin
if (!isset($_SESSION['admin'])) {
    header("Location: index.php?action=logout");
}

include("data.php");

if ($_GET) {

    //// insert new user
    if ($_GET["action"] == "newuser") {
        $name = $_POST["name"];
        $weight = $_POST["weight"];
        $sex = (int) isset($_POST["male"]);
        $alc = round(1000 / ($weight * 1000 * (0 + 0.68 * $sex + 0.55 * (1 - $sex))), 4);

        $sql = "INSERT INTO users (full_name, weight, sex_male, alcohol_coef)
                    VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siid", $name, $weight, $sex, $alc);
        $stmt->execute();
    } 

    //update user weight / sex /alcohol_coef
    elseif ($_GET["action"] == "updateuser") { 
        $name = htmlspecialchars($_POST["updatename"]);
        $weight = (int) $_POST["weightupdate"];
        $sex = !isset($_POST["sexupdate"]) ? 0 : (int) $_POST["sexupdate"] ;
        
        $alc = round(1000 / ($weight * 1000 * (0 + 0.68 * $sex + 0.55 * (1 - $sex))), 4);

        $sql = "UPDATE users 
                    SET weight=" . $weight . ", alcohol_coef=" . $alc . ",sex_male=" . $sex . "
                    WHERE full_name='" . $name . "'";

        if (($conn->query($sql)) == false) {
            echo "PROBLEMEME";
        };
    } 
    
    // Delete sale
    elseif ($_GET["action"] == "deletesale") {
        $id = (int) $_GET["id"];
        $sql = "UPDATE sales SET deleted = 1 WHERE id=" . $id . "";

        $conn->query($sql);
    }
    // Undelete sale
    elseif ($_GET["action"] == "undeletesale") {
        $id = (int) $_GET["id"];
        $sql = "UPDATE sales SET deleted = 0 WHERE id=" . $id . "";

        $conn->query($sql);
    }
}

$sql = "SELECT sales.id as id, users.full_name as name, sales.deleted as del, sales.sale_datetime as time, products.product_name as product
            FROM sales JOIN users ON users.id = sales.buyer_id 
            JOIN products ON products.id = sales.product_id
            ORDER BY time DESC
            LIMIT 50";

$drinks = $conn->query($sql);
?>

<html>

<head>
    <title>admin</title>
    <link rel="stylesheet" href="styles/admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <nav>
        <li>
            <a href="drinksandparty.php">Drinks</a>
        </li>
        <li>
            <a href="index.php?action=logout">Logout</a>
        </li>
    </nav>

    <form method="post" action="admin.php?action=newuser">
        New person: <br />
        <label> Name </label>
        <input type="text" name="name" required> <br />
        <label> Weight </label>
        <input type="number" name="weight" required> <br />
        <label> Male? </label>
        <input type="checkbox" name="male" value="1"> <br />
        <input type="submit" value="Insert">
    </form>

    <form method="post" action="admin.php?action=updateuser">
        Update person: <br />
        <label> Person </label>
        <select name="updatename">
            <?php
            foreach ($persons as $person) {
                echo "<option value=" . $person->_name . ">" . $person->_name . "</option>";
            }
            ?>
        </select>
        <br />
        <label> Weight: </label>
        <input type="number" name="weightupdate" required> <br>
        <label> Male?</label>
        <input type="checkbox" name="sexupdate" value="1"> <br>
        <input type="submit" value="update">
    </form>
    <!--
    <form method="post" action="admin.php?action=deletesale">
        <label> id of sale to delete </label>
        <input type="number" name="idsaletodelete" required> <br />
        <input type="submit" value="delete">
    </form> 
        -->
    <br>

    <table>
        <th>id</th> <th>Nom</th> <th>Boisson</th> <th>Heure</th> <th>Date</th> <th>Supprimer</th>
        <?php
        while ($row = $drinks->fetch_assoc()) {
            $time = date("H:i", strtotime($row["time"]));
            $date = date("d.m", strtotime($row["time"]));
            $css_class = $row["del"] == 1 ? 'deleted' : '';

            echo '<tr class="' . $css_class . '">';
            echo '<td>' . $row["id"] . '</td>' . '<td>' . $row["name"] . '<td>' . $row["product"] . '</td>' . '</td>' . '<td>' . $time . '</td>' . '<td>' . $date . '</td>';
            echo '<td class="action_delete"><a class="delete" title="delete sale" href="admin.php?action=deletesale&id='. $row["id"] .'">❌</a>  <a class="undo" title="undelete sale" href="admin.php?action=undeletesale&id='. $row["id"] .'">✔</a></td>';
            echo '</tr>';
        }
        ?>
    </table>


</body>

</html>