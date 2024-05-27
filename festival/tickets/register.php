<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /festival");
    exit();
}

$db = require(__DIR__ . "/../db.php");

$db->beginTransaction();
$statement = $db->prepare(
    "insert into festival2024
    (name, ticket1, ticket2, meal1, meal2, meal3, meal4, conditions_accepted, conditions_read, message, hash)
    values (?,?,?,?,?,?,?,?,?,?,?)");

$statement->execute([
    $_POST["name"],
    isset($_POST["ticket1"]) ? 1 : 0,
    isset($_POST["ticket2"]) ? 1 : 0,
    isset($_POST["meal1"]) ? 1 : 0,
    isset($_POST["meal2"]) ? 1 : 0,
    isset($_POST["meal3"]) ? 1 : 0,
    isset($_POST["meal4"]) ? 1 : 0,
    isset($_POST["conditionsAccepted"]) ? 1 : 0,
    $_POST["conditionsRead"] === "true" ? 1 : 0,
    $_POST["message"] ?? null,
    "",
]);

$statement = $db->query("select last_insert_id() as id");
$id = $statement->fetch()["id"];

$hash = substr(hash("sha256", $id), 0, 10);
$statement = $db->query("update festival2024 set hash = '$hash' where id = $id");

$db->commit();

session_start();
$_SESSION["id"] = $id;

header("Location: /festival/tickets");
exit();
