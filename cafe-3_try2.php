<?php
include "signIn.php";
function connectingIt(){
$servername = "mysql.cs.uky.edu";
$username = "iyse222";
$password = logIn();
// create coonecttion:
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
try {
  $conn = new PDO("mysql:host=$servername;dbname=iyse222", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected successfully";
} catch(PDOException $e) {
  //echo "Connection failed: " . $e->getMessage();
}
return $conn; // to retun the coonn
}
?>

<?php
// a post request is sent
if(isset($_POST['buttonNames']) ) { // gave it the wrong name, should buttonames as below
 // call those function
 // and connect to the server:
 //$conn = connectingIt();
$bigMenu = bigMenu($_POST['buttonNames']); // passing in a value !
// goes back as response 
echo $bigMenu;
}
?>

<?php
// section -  3

  // make function to pass in the posts:
  // add a comments what each does:
  // the bigMenu is the name and the parameter it passes is $items
      function bigMenu ($items) {
      // make it modular:
      // echo the header
      // remove echos echo "<h2> Details about $items </h2>";
      // example from dr. f's site
      // ie$sql = "SELECT price FROM menu WHERE category = ?;
      //$prepared = $pdo->prepare($sql);
      //$prepared->execute([$valueFromHTMLform]);
      // connect to the mysql from the above connection and the var $conn named it connectingIt
      $conn = connectingIt();
      // this is the prepare statement and coonect it to the ? marker to protect the site from attacks
      $stmt = $conn->prepare("select number from accesses where category = ?");
      $stmt->execute([$items]); // returns the row 
      // use the fetch to gather the data from the table
      $row =$stmt->fetch();
      // plus need to accumulate for it:
      $row= $row['number']; // comment: the class makes it red too
      // and tie the $row to the accumulation.

      // echo "<p class = 'lingual'>You have requested this information $row times</p>"; // row is the number from the DB
      // and update the accesses with the acculation with this:
      $sql = "update accesses set number = number + 1 where category = ?";
      // another prepared statement for the incrementation:
      $stmt = $conn->prepare($sql); // this adds it up.
      // this excutes the incrementation:
      $stmt->execute([$items]); // returns the row 
        $sql = "select item, description, price from menu where category = ?"; // needs to be moved
        $stmt = $conn->prepare($sql); // this adds it up. // prepare make the website safer
        $stmt->execute([$items]); // good - > check
        $myObj->category = $items;
        // pass row to tables
        $myObj->accesses = $row; 
        // another array: to store the array of the array
        $placeHolder = array();
        while($row = $stmt->fetch()) {
          // add the array here:
          $a=array("items"=>$row['item'], "descriptions"=>$row['description'], "prices"=>$row['price']);
          array_push($placeHolder, $a); // to add more to the array
         }
         $myObj->details =  $placeHolder;
         $myJSON = json_encode($myObj);
         return $myJSON; 
      }?>