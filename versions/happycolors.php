<html>

<head>
    <title>Sale mine, ah? </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🍻</text></svg>">
    <link rel="stylesheet" href=<?php echo $actualv->css ?>>
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>

    <h1 id="title">

        <?php
        if ($sante != true) {
            echo "Salmina";
        } else {
            echo $sante;
        }
        ?>
    </h1>
    <a href="login.php?action=logout">logout</a>
    <a href="admin.php">admin</a>
    
    <form method="post" action="drinksandparty.php">
        <table style="width:100%">
            <?php
            foreach ($persons as $person) {
                echo '<tr>';
                echo '<th>' . $person->_name . '</th>';
                foreach ($products as $product) {
                    echo '<th>';
                    echo '<div class="count">' . $person->_drinks->{$product->_id} . '</div>';
                    echo '<input type="submit" name="button_' . $product->_id . '_' . $person->_id . '_' . $product->_name . '_' . $person->_name . '" value="' . $product->_name . '">';
                    echo '</th>';
                }
                echo '<th>' . $person->_bloodalc . '‰ </th>';
                echo '</tr>';
            }
            ?>
        </table>
    </form>
    <a href="drinksandparty.php?v=officiallyclean">click her to get parents approval</a>
</body>

</html>