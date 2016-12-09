<?php

$host = "localhost";
$mysql_user = "root";
$mysql_pass = "e2263mysql";
$mysql_dbName = "mirna";

//$dbConnect = new mysqli($host, $mysql_user, $mysql_pass, $mysql_dbName);
$dbConnect = mysqli_connect($host, $mysql_user, $mysql_pass, $mysql_dbName) or die ("could not connect to mysql");

?>