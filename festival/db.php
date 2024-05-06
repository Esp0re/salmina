<?php

$dbhost = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$passworddb = getenv("DB_PASS") ?: "root";
$dbname = getenv("DB_NAME") ?: "salmina";

$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $passworddb);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

return $db;
