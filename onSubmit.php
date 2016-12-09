<?php 
//Header files
require_once('dbAcess.php');  // Connect to Database

$queryResult = array(); // Variable to store the query result
$counterID = $_POST["counterID"];

if($counterID == 1)
{
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
		FROM consensus_avg_pancancer 
		WHERE d1=d2 
		  AND d1= '".$disease."'
		  AND score<'".$max."'
		  AND score>'".$min."'
		ORDER by type DESC limit 500";

     $queryCSV = "SELECT m1 AS source, m2 AS target, score AS type FROM consensus_avg_pancancer WHERE d1=d2 AND d1='".$disease."' AND score<'".$max."' AND score>'".$min."' ORDER by type into outfile '/var/www/bnet.egr.vcu.edu/public_html/miRsig/CSV/network.csv' fields terminated by ','";
	
	 $queryResult = mysqli_query($dbConnect, $query);
     $queryResultCSV = mysqli_query($dbConnect, $queryCSV);

	 for ($x = 0; $x < mysqli_num_rows($queryResult); $x++) 
	  {
		$data[] = mysqli_fetch_assoc($queryResult);
	  }

	  echo json_encode($data);
	 }
	else 
	  echo ("ISSET condition failed!");
}

elseif($counterID == 2)
{
   if(ISSET($_POST["disSelected"]) and ISSET($_POST["disSelected2"]) and ISSET($_POST["minSelected"]) and ISSET($_POST["maxSelected"]))
	{
	 $disease = mysqli_real_escape_string($dbConnect, $_POST["disSelected"]);
	 $disease2 = mysqli_real_escape_string($dbConnect, $_POST["disSelected2"]);
	 $min = mysqli_real_escape_string($dbConnect, $_POST["minSelected"]);
	 $max = mysqli_real_escape_string($dbConnect, $_POST["maxSelected"]);
	 
	 $query = "select a.m1 as source, a.m2 as target, a.score as type from (select m1, m2, score from consensus_avg_pancancer where d1='".$disease."' and score<'".$max."' AND score>'".$min."')a inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease2."' and score<'".$max."' AND score>'".$min."')b using (m1,m2) order by type desc limit 250";
	 
	 $queryCSV = "select a.m1 as source, a.m2 as target, a.score as type from (select m1, m2, score from consensus_avg_pancancer where d1='".$disease."' and score<'".$max."' AND score>'".$min."')a inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease2."' and score<'".$max."' AND score>'".$min."')b using (m1,m2) order by type desc limit 250 into outfile '/var/www/bnet.egr.vcu.edu/public_html/miRsig/CSV/network.csv' fields terminated by ','";
	 
	 $queryResult = mysqli_query($dbConnect, $query);
	 $queryResultCSV = mysqli_query($dbConnect, $queryCSV);

	 for ($x = 0; $x < mysqli_num_rows($queryResult); $x++) 
	  {
		$data[] = mysqli_fetch_assoc($queryResult);
	  }

	  echo json_encode($data);
	 }
	else 
	  echo ("ISSET condition 1 failed!");

}

elseif($counterID == 3)
{
   if(ISSET($_POST["disSelected"]) and ISSET($_POST["disSelected2"]) and ISSET($_POST["disSelected3"]) and ISSET($_POST["minSelected"]) and ISSET($_POST["maxSelected"]))
	{
	 $disease = mysqli_real_escape_string($dbConnect, $_POST["disSelected"]);
	 $disease2 = mysqli_real_escape_string($dbConnect, $_POST["disSelected2"]);
	 $disease3 = mysqli_real_escape_string($dbConnect, $_POST["disSelected3"]);
	 $min = mysqli_real_escape_string($dbConnect, $_POST["minSelected"]);
	 $max = mysqli_real_escape_string($dbConnect, $_POST["maxSelected"]);
	
	 $query = "select a.m1 as source, a.m2 as target, a.score as type from (select m1, m2, score from consensus_avg_pancancer where d1='".$disease."' and score<'".$max."' AND score>'".$min."')a inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease2."' and score<'".$max."' AND score>'".$min."')b using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease3."' and score<'".$max."' AND score>'".$min."')c using (m1,m2) order by type desc limit 250";
	 
	 $queryCSV = "select a.m1 as source, a.m2 as target, a.score as type from (select m1, m2, score from consensus_avg_pancancer where d1='".$disease."' and score<'".$max."' AND score>'".$min."')a inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease2."' and score<'".$max."' AND score>'".$min."')b using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease3."' and score<'".$max."' AND score>'".$min."')c using (m1,m2) order by type desc limit 250 into outfile '/var/www/bnet.egr.vcu.edu/public_html/miRsig/CSV/network.csv' fields terminated by ','";
	 
	 $queryResult = mysqli_query($dbConnect, $query);
	 $queryResultCSV = mysqli_query($dbConnect, $queryCSV);

	 for ($x = 0; $x < mysqli_num_rows($queryResult); $x++) 
	  {
		$data[] = mysqli_fetch_assoc($queryResult);
	  }

	  echo json_encode($data);
	 }
	else 
	  echo ("ISSET condition 2 failed!");

}
elseif($counterID == 4)
{
   if(ISSET($_POST["disSelected"]) and ISSET($_POST["disSelected2"]) and ISSET($_POST["disSelected3"]) and ISSET($_POST["disSelected4"]) and ISSET($_POST["minSelected"]) and ISSET($_POST["maxSelected"]))
	{
	 $disease = mysqli_real_escape_string($dbConnect, $_POST["disSelected"]);
	 $disease2 = mysqli_real_escape_string($dbConnect, $_POST["disSelected2"]);
	 $disease3 = mysqli_real_escape_string($dbConnect, $_POST["disSelected3"]);
	 $disease4 = mysqli_real_escape_string($dbConnect, $_POST["disSelected4"]);
	 $min = mysqli_real_escape_string($dbConnect, $_POST["minSelected"]);
	 $max = mysqli_real_escape_string($dbConnect, $_POST["maxSelected"]);
			
	$query = "select a.m1 as source, a.m2 as target, a.score as type from (select m1, m2, score from consensus_avg_pancancer where d1='".$disease."' and score<'".$max."' AND score>'".$min."')a inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease2."' and score<'".$max."' AND score>'".$min."')b using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease3."' and score<'".$max."' AND score>'".$min."')c using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease4."' and score<'".$max."' AND score>'".$min."')d using (m1,m2) order by type desc limit 250";

    $queryCSV = "select a.m1 as source, a.m2 as target, a.score as type from (select m1, m2, score from consensus_avg_pancancer where d1='".$disease."' and score<'".$max."' AND score>'".$min."')a inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease2."' and score<'".$max."' AND score>'".$min."')b using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease3."' and score<'".$max."' AND score>'".$min."')c using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease4."' and score<'".$max."' AND score>'".$min."')d using (m1,m2) order by type desc limit 250 into outfile '/var/www/bnet.egr.vcu.edu/public_html/miRsig/CSV/network.csv' fields terminated by ','";	

	$queryResult = mysqli_query($dbConnect, $query);
    $queryResultCSV = mysqli_query($dbConnect, $queryCSV);
	
	 for ($x = 0; $x < mysqli_num_rows($queryResult); $x++) 
	  {
		$data[] = mysqli_fetch_assoc($queryResult);
	  }

	  echo json_encode($data);
	 }
	else 
	  echo ("ISSET condition 3 failed!");

}

elseif($counterID == 5)
{
   if(ISSET($_POST["disSelected"]) and ISSET($_POST["disSelected2"]) and ISSET($_POST["disSelected3"]) and ISSET($_POST["disSelected4"]) and ISSET($_POST["disSelected5"]) and ISSET($_POST["minSelected"]) and ISSET($_POST["maxSelected"]))
	{
	 $disease = mysqli_real_escape_string($dbConnect, $_POST["disSelected"]);
	 $disease2 = mysqli_real_escape_string($dbConnect, $_POST["disSelected2"]);
	 $disease3 = mysqli_real_escape_string($dbConnect, $_POST["disSelected3"]);
	 $disease4 = mysqli_real_escape_string($dbConnect, $_POST["disSelected4"]);
	 $disease5 = mysqli_real_escape_string($dbConnect, $_POST["disSelected5"]);
	 $min = mysqli_real_escape_string($dbConnect, $_POST["minSelected"]);
	 $max = mysqli_real_escape_string($dbConnect, $_POST["maxSelected"]);
	
	 $query = "select a.m1 as source, a.m2 as target, a.score as type from (select m1, m2, score from consensus_avg_pancancer where d1='".$disease."' and score<'".$max."' AND score>'".$min."')a inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease2."' and score<'".$max."' AND score>'".$min."')b using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease3."' and score<'".$max."' AND score>'".$min."')c using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease4."' and score<'".$max."' AND score>'".$min."')d using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease5."' and score<'".$max."' AND score>'".$min."')e using (m1,m2) order by type desc limit 250";	

	 $queryCSV = "select a.m1 as source, a.m2 as target, a.score as type from (select m1, m2, score from consensus_avg_pancancer where d1='".$disease."' and score<'".$max."' AND score>'".$min."')a inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease2."' and score<'".$max."' AND score>'".$min."')b using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease3."' and score<'".$max."' AND score>'".$min."')c using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease4."' and score<'".$max."' AND score>'".$min."')d using (m1,m2) inner join (select m1, m2, score from consensus_avg_pancancer where d1='".$disease5."' and score<'".$max."' AND score>'".$min."')e using (m1,m2) order by type desc limit 250";
	 
	 $queryResult = mysqli_query($dbConnect, $query);
	 $queryResultCSV = mysqli_query($dbConnect, $queryCSV);

	 for ($x = 0; $x < mysqli_num_rows($queryResult); $x++) 
	  {
		$data[] = mysqli_fetch_assoc($queryResult);
	  }

	  echo json_encode($data);
	 }
	else 
	  echo ("ISSET condition 3 failed!");

}
else echo ("All four conditions failed! Help!");


?>

