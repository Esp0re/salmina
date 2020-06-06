
<html>
    <head>
        <title>Sale mine aaah </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href=<?php echo $actualv->css ?>> 
        <meta name="viewport" content="width=device-width, initial-scale=1">


    </head>

    <body>
        
        <h1 id="title1">
        <?php 
        echo $sante;
        if($sante != true){
            echo "Salmina";
        }else{ 
            echo "";
        }
        ?>
        </h1>
        <h1 id="title2"> Salmina </h1>
        <h1 id="title3"> Salmina </h1>
        <h1 id="title4"> Salmina </h1>

        <a href="drinksandparty.php?v=happycolors">click here to party</a>
        
        <a href="index.php?action=logout">logout</a>
        <a href="admin.php">admin</a>
        <form method="post" action="drinksandparty.php">
            
                <?php           
                    $products[0]->_name = "Houblon";
                    $products[1]->_name = "Eau";  
                    foreach ($persons as $person){
                        echo '<div class="person">';
                            echo '<span class="nametitle">'.$person->_name.'</span>';
                            echo '<table><tr>';
                            foreach($products as $product){
                                echo '<th class="count">'.$person->_drinks->{$product->_id}.'</th>';
                                echo '<th>';
                                echo '<input class="button" type="submit" name="button_'.$product->_id.'_'.$person->_id.'_'.$product->_name.'_'.$person->_name.'" value="'.$product->_name.'">';       
                                echo '</th>';
                            }
                            echo '<th>'.$person->_bloodalc.'‰ </th>';
                            echo '</tr></table>';
                        echo '</div>';
                    }
                ?> 
            </table>
        </form>
    </body>
</html>