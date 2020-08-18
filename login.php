<?php

session_start();

////// Check login 
$password = "rouge";
$passwordadmin = "mercisarko";

// Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();

    // Unset cookies
    setcookie("logged_in", "", time() - 3600);
    setcookie("admin", "", time() - 3600);
}

// Check if cookies exist
if(isset($_COOKIE['logged_in'])){
    $_SESSION['logged_in'] = true;
}
if(isset($_COOKIE['admin'])){
    $_SESSION['admin'] = true;
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header("Location: drinksandparty.php");
}
if (isset($_POST['password'])) {

    // Normal user
    if ($_POST['password'] == $password) {
        $_SESSION['logged_in'] = true;

        // Set cookie
        setcookie("logged_in", true, time() + 3600*24*365);
        header("Location: drinksandparty.php");
    }
    // Admin user 
    else if ($_POST['password'] == $passwordadmin) {
        $_SESSION['logged_in'] = true;
        $_SESSION['admin'] = true;

        // Set cookies
        // Admin cookie only set for 24h
        setcookie("logged_in", true, time() + 3600*24*365);
        setcookie("admin", true, time() + 3600*24);
        header("Location: drinksandparty.php");
    }
}


?>

<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles/clean.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <form method="post" action="login.php">
        Login: <br />
        <input type="password" name="password" placeholder="Mot de passe"> <br>
        <label for="remember">Se souvenir de moi</label>
        <input type="checkbox" name="remember" id="remember" checked> <br>
        <input type="submit" value="Login">
    </form>
</body>

</html>