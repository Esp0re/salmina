<?php


date_default_timezone_set('Europe/Zurich');


////// Check login 
$logged = "true";

if(!isset($_SESSION['logged_in']))  {
    header("Location: index.php");
} 


///// Connection to Database

$servername = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$passworddb = getenv("DB_PASS") ?: "";
$dbname = getenv("DB_NAME") ?: "salmina";

// Create connection
$conn = new mysqli($servername, $username, $passworddb, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!$conn->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $conn->error);
}


// global variables 
    $amountofdrinks = 2;
    
//include Classes
include("classes.php");


//// Get Persons from Database
$persons = array(); //list of persons

$sql = "SELECT id,full_name as fname,alcohol_coef as coef
       FROM users";
$result = $conn->query($sql);

 while ($row = $result->fetch_assoc()) {
    $persons[] = new Person($row['id'],$row['fname'],$row['coef'],$conn); //fill list of persons
} 

usort($persons,function($first,$second){ //sort list of persons by alcohol in blood
    return $first->_bloodalc < $second->_bloodalc;
});


//Get Drinks/products List from Database

$sql = "SELECT id,product_name as name FROM products";
$result = $conn->query($sql);
$products = [];

while($row = $result->fetch_assoc()) {    
    $products[] = new Product($row['id'],$row['name']);
}



?>