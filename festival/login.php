<?php

session_start();

if (isset($_SESSION["logged"])) {
    header("Location: /festival");
    exit();
}

if (isset($_POST["pass"])) {
    $password = getenv("FESTIVAL_PASSWORD") ?: "1234";
    if ($_POST["pass"] === $password) {
        $_SESSION["logged"] = true;
        header("Location: /festival/admin.php");
        exit();
    } else
        $error = "wrong password";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Salmina - Login</title>
    <meta charset="UTF-8">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <meta name=theme-color content=#000000>
    <link rel="icon" href="/images/icon.svg">
</head>
<body>
<form method="post">
    <?php if (isset($error)): ?><p><?= $error ?></p><?php endif ?>
    <input type="password" name="pass" placeholder="Password" required minlength="1" autofocus>
    <button type="submit">Login</button>
</form>
</body>
</html>
