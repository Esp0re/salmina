<?php 

    session_start();


    if(!isset($_SESSION['admin']))  {
        header("Location: index.php");
    } 
    
    include("data.php");

    if($_GET){
        if($_GET["action"] == "newuser") //// insert new user
        {
            $name = $_POST["name"];
            $weight = $_POST["weight"];
            $sex = (int)isset($_POST["male"]);
            $alc = round(1000/($weight*1000*(0+0.68*$sex+0.55*(1-$sex))),4);

            $sql = "INSERT INTO users (full_name, weight, sex_male, alcohol_coef)
                    VALUES (?,?,?,?)"; 
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siid",$name,$weight,$sex,$alc);
            $stmt->execute(); 
        }
        elseif($_GET["action"] == "updateuser"){ //update user weight / sex /alcohol_coef
            $name = $_POST["updatename"];
            $weight = (int)$_POST["weightupdate"];
            $sex = (int)$_POST["sexupdate"];

            
            $alc = round(1000/($weight*1000*(0+0.68*$sex+0.55*(1-$sex))),4);

            $sql = "UPDATE users 
                    SET weight=".$weight.", alcohol_coef=".$alc.",sex_male=".$sex."
                    WHERE full_name='".$name."'";
     
            if(($conn->query($sql))==false){
                echo "PROBLEMEME";
            };
        }
        elseif($_GET["action"] == "deletesale"){
            $id = (int)$_POST["idsaletodelete"];
            $sql = "DELETE FROM sales WHERE id=".$id."";

            $conn->query($sql);
        }
    }

    $sql = "SELECT sales.id as id, users.full_name as name, sales.sale_datetime as time, products.product_name as product
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
        <form method="post" action="admin.php?action=newuser">
            New person: <br/>
            <label> Name </label>
            <input type="text" name="name" required> <br/>
            <label> Weight </label>
            <input type="number" name="weight" required> <br/> 
            <label> Male? </label>
            <input type="checkbox" name="male" value="1" required> <br/>  
            <input type="submit" value="Insert">
        </form>

        <form method="post" action="admin.php?action=updateuser">
            Update person: <br/>
            <label> Person </label>
            <select name="updatename">  
                <?php 
                    foreach($persons as $person){
                        echo "<option value=".$person->_name.">".$person->_name."</option>";
                    } 
                ?>
            </select>
            <br/>
            <label> weight: </label>
            <input type="number" name="weightupdate" required> <br/>
            <label> male? 1 = yes </label>
            <input type="number" name="sexupdate" required> <br/>
            <input type="submit" value="update">
        </form>

        <form method="post" action="admin.php?action=deletesale">
            <label> id of sale to delete </label>
            <input type="number" name="idsaletodelete" required> <br/>
            <input type="submit" value="delete">
        </form>

        <table>
                <?php                    
                    while($row = $drinks->fetch_assoc()){
                        $time = date("H:i",strtotime($row["time"]));
                        $date = date("d.m", strtotime($row["time"]));
                        echo '<tr>';
                        echo '<th>'.$row["id"].'</th>'.'<th>'.$row["name"].'<th>'.$row["product"].'</th>'.'</th>'.'<th>'.$time.'</th>'.'<th>'.$date.'</th>';
                        echo '</tr>';
                    }
                ?> 
        </table>


    </body>

</html>