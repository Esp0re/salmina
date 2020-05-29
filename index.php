
<?php 


    session_start();


    ////// Check login 
    $password = "rouge";

    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']==true)  {
        header("Location: drinks.php");
    }
    if (isset($_POST['password'])){
        if($_POST['password'] == $password){
            $_SESSION['logged_in'] = true;
            header("Location: drinks.php");
        }
    } 

    

?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <form method="post" action="index.php">
            Login: <br/>
            <input type="password" name="password"> <br/>   
            <input type="submit" value="Login">
        </form>
    </body>
</html>