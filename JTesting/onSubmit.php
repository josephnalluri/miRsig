<?php 
//Header files
require_once('dbAcess.php');  // Connect to Database

$queryResult = array(); // Variable to store the query result

if(ISSET($_POST["disSelected"]) and ISSET($_POST["minSelected"]) and ISSET($_POST["maxSelected"]))
{
 $disease = mysqli_real_escape_string($dbConnect, $_POST["disSelected"]);
 $min = mysqli_real_escape_string($dbConnect, $_POST["minSelected"]);
 $max = mysqli_real_escape_string($dbConnect, $_POST["maxSelected"]);
 $query = 
	"SELECT 
			m1 AS source,
			m2 AS target,
			score AS type 
	FROM consensus_output 
	WHERE d1=d2 
	  AND d1= '".$disease."'
	  AND score<'".$max."'
	  AND score>'".$min."'
	limit 100";

 $queryResult = mysqli_query($dbConnect, $query);

 for ($x = 0; $x < mysqli_num_rows($queryResult); $x++) 
  {
	$data[] = mysqli_fetch_assoc($queryResult);
  }

  echo json_encode($data);
 }
 else 
  echo ("ISSET condition failed!");
?>

