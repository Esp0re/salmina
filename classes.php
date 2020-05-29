<?php 

function submitsaledata($posted,$conn){
    $ids =[];
    $ids = explode('_',array_keys($posted)[0]); //first [1] id is product, second is person
        
    $sql = "INSERT INTO sales (buyer_id, product_id, sale_datetime, product_price, amount, total_price, alcoholblood_permil)
                VALUES (?,?,NOW(),(
                    SELECT current_price
                    FROM products
                    WHERE products.id = ?)
                    ,1,1*(
                    SELECT current_price
                    FROM products
                    WHERE products.id = ?),
                    0
                )"; 

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii",$ids[2],$ids[1],$ids[1],$ids[1]);
    $stmt->execute();
}

function calculalcohol($conn,$id){ /// calculates and returns actual alcohol in blood for person by his id
        
        $sql = "SELECT users.id as id, sales.sale_datetime as dtime, products.alcohol_grams as gram, users.alcohol_coef as coef
                FROM sales JOIN users on sales.buyer_id = users.id 
                JOIN products ON sales.product_id = products.id 
                WHERE sale_datetime >= NOW() - INTERVAL 1 DAY AND users.id =".$id."
                ORDER BY dtime ASC";
        
        $result = $conn->query($sql);

        $init = new stdClass;

        $init->blood = 0;
        $init->time = strtotime($result->fetch_assoc()["dtime"]);

        $result->data_seek(0);

        while ($row = $result->fetch_assoc()){
            $init->blood += $row["gram"]*$row["coef"];         
            $hoursdiff = (strtotime($row["dtime"]) - $init->time)/3600;
            $init->blood = max($init->blood - $hoursdiff*0.13,0);
            $init->time = strtotime($row["dtime"]);
            //print_r("<br/> time"); print_r(date("d/m/Y H:i",$init->time)); echo "alcool: "; echo $init->blood;
        } 
        $hoursdiff = (strtotime("now") - $init->time)/3600;
        $init->blood = max($init->blood - $hoursdiff*0.13,0);
        //echo "   now "; echo $init->blood;
        $init->blood = round($init->blood,2);

        return $init->blood; 
}    

function getdrinks($conn,$id){ // returns array of quantity of drinks in last 24h by drink by person (id)
    $drinks = new stdClass;

    for ($i = 1; $i <= $GLOBALS["amountofdrinks"]; $i++){ // to not miss any products just because they are no sales in database, to change!! 
        $drinks->{$i} = 0;
    }
    
    $sql = "SELECT buyer_id, product_id, COUNT(*) as amount
            FROM sales
            WHERE sale_datetime >= NOW() - INTERVAL 1 DAY AND buyer_id=".$id."
            GROUP BY product_id
            ORDER BY product_id";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()){
        $drinks->{$row["product_id"]} = $row["amount"];
    }

    return $drinks;
}

class Person
{
    // property declaration
    public $_id;
    public $_name;
    public $_drinks;
    public $_bloodalc;
    private $_coef;


    function __construct( $id,$name, $coef, $conn) {
        $this->_id = $id;
        $this->_name = $name;
        $this->_coef = $coef;

        $this->_bloodalc = calculalcohol($conn,$this->_id); // get the permill of alcohol from the calculator

        $this->_drinks = getdrinks($conn,$this->_id);
        
    }

    function setdrinks($drinks){
        $this->_drinks = $drinks;
    }
}

class Product
{
    public $_id;
    public $_name;

    function __construct($id,$name){
        $this->_id = $id;
        $this->_name = $name;
    }
}


    ////tests

   ///// Connection to Database

   $servername = "localhost";
   $username = "root";
   $passworddb = "";
   $dbname = "salmina";

   // Create connection
   $conn = new mysqli($servername, $username, $passworddb, $dbname);
   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   if (!$conn->set_charset("utf8")) {
       printf("Error loading character set utf8: %s\n", $conn->error);
   }





/// tests
/*
 $gabor = new Person(1,"Gabor",0.02,$conn);

print_r($gabor);


echo "<br />".$gabor->_name;

$persons = array();

$sql = "SELECT id,full_name as fname,alcohol_coef as coef
       FROM users";
$result = $conn->query($sql);


 while ($row = $result->fetch_assoc()) {
    //print_r($row);
    $persons[] = new Person($row['id'],$row['fname'],$row['coef'],$conn); 
} 

echo $persons[0]->_bloodalc;

print_r(
   );

echo "<br/>".$persons[1]->_bloodalc; */
?>