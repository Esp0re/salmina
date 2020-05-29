
<?php 


    session_start();


    ////// Check login 
    $password = "rouge";
    $passwordadmin = "mercisarko";

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)  {
        header("Location: drinksandparty.php");
    }
    if (isset($_POST['password'])){
        if($_POST['password'] == $password){
            $_SESSION['logged_in'] = true;
            header("Location: drinksandparty.php");
        }else if($_POST['password'] == $passwordadmin){
            $_SESSION['logged_in'] = true;
            $_SESSION['admin'] = true;
            header("Location: drinksandparty.php");
        }
    } 


?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="styles/clean.css">
    </head>

    <body>
        <form method="post" action="index.php">
            Login: <br/>
            <input type="password" name="password"> <br/>   
            <input type="submit" value="Login">
        </form>
    </body>
</html>