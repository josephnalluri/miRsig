<?php
//Header files
require_once('dbAcess.php');  // Connect to Database
$query = "SELECT DISTINCT d1 from consensus_output";
$queryResult = mysqli_query($dbConnect, $query) or die("Error in the query" . mysqli_error($dbConnect));

$diseaseArray = array();
for($i = 0; $i < mysqli_num_rows($queryResult); $i++)
{
  $diseaseArray[] = mysqli_fetch_assoc($queryResult);
}
$diseaseDropdown = json_encode($diseaseArray);

?>

 
 
<!DOCTYPE html>
<html lang="en">
<head>
  
  <title>Disease-specific miRNA-miRNA networks</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="googleTableCss.css">
  <link rel="stylesheet" href="graphCSS.css"> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="drawGraph.js"></script>  
  <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
 
  
  

 </head>
 
<body onload="fillDropdown()">
<div class="container">
  <div class="jumbotron">
    <h1>Disease-specific miRNA-miRNA interaction networks</h1>
  </div>
  <div class="row">
    <div class="col-sm-10">
	
      <h3>This tool gives specific miRNA-miRNA interaction networks for each disease based on consenus-based network inference of miRNA-disease networks</h3>
	  <br><br>
	  <h4>Please select a disease below</h4>
	   <!-- <form action="onSubmit.php" method="post" id="form" name="form" style="width: 500px"> -->
		 <!--  <form ="" method="post" id="form" name="form" style="width: 500px">-->
		 <form id = "form">
		  <div id = "selectDiseaseform">
			<select name ="dis" id = "selectDropdown" class="form-control">  </select> <br>
		  </div>
            <br>		  
			<button  type="button" onclick = "addDisease()" class="btn btn-primary" id="btn-addDisease"> Select more diseases</button>
			<br><br>
			Maximum Score: <input type="text" id="max" name="max" size="4">  &nbsp;  &nbsp;
			Minimum Score: <input type="text" id="min" name="min" size="4"><br>
		
		    <hr>
			  <button onclick = "onSubmit()" type="button" class="btn btn-success" id="btn-submit"> SUBMIT</button>  &nbsp;  &nbsp;
			  
			   <input type="reset" class="btn btn-info" id="btn-reset" value="RESET" onClick="window.location.reload()"> </button>
	        <br><br>
	   </form>
	 
	   
	 
	  <!-- Placeholder for graph --> 
	  <div id="graph"></div>
	   
    </div>    
  </div>
</div>

<!-- PHP code to POST the form and run thequery -->


<?php
//Do it later if there are 3 diseases:		
//	$dis = $_POST["dis"];
//	$min = $_POST["min"];
//	$max = $_POST["max"];
	
if (!empty($_POST["min"]))
 {
    $min = $_POST["min"];
 }
else
{
    $min = 0.8;
}
if (!empty($_POST["max"]))
{
    $max = $_POST["max"];
}
else
{
    $max = 1;
}


if (!empty($_POST["dis"]))
{
   //$dis= '"' . $_POST["dis"] . '"';
   $dis = $_POST["dis"];
}

?> 




<!-- Script to send disease names to JavaScript and populate the dropdown   -->
<script type="text/javascript">
var diseaseList = <?php echo $diseaseDropdown; ?>;
var counterID = 1;



function fillDropdown()
{
  var selectDisease = document.getElementById("selectDropdown");
  var option = document.createElement("option");
  option.textContent = "Select Disease";
  option.value = "Select Disease";
  selectDisease.appendChild(option);
  
  for(var i = 0; i<diseaseList.length; i++)
   { 
	  var disName = diseaseList[i].d1;
	  var option = document.createElement("option");
	  option.textContent = disName;
	  option.value = disName;
	  selectDisease.appendChild(option);
    }
 }
   
 function addDisease() 
 {
   if(counterID < 3)
   {  
	   var lineBreak = document.createElement("br");
	   var addDisease = document.createElement("select");
	   var classAttr = document.createAttribute("class");
	   var nameAttr = document.createAttribute("name");
	   
	   classAttr.value = "form-control";
	   nameAttr.value = "dis" + counterID;
	   
	   addDisease.setAttributeNode(classAttr);
	   addDisease.setAttributeNode(nameAttr)
	   addDisease.id = "selectDropdown" + counterID;
	   addDisease.textContent = "Select Disease";
	   document.getElementById("selectDiseaseform").appendChild(addDisease);
	   document.getElementById("selectDiseaseform").appendChild(lineBreak);
	   counterID +=1;
	  	   
	   var selectDisease = document.getElementById(addDisease.id);
	   var option = document.createElement("option");
	   option.textContent = "Select Disease";
	   option.value = "Select Disease";
	   selectDisease.appendChild(option);
	  
	   for(var i = 0; i<diseaseList.length; i++)
		{ 
		  var disName = diseaseList[i].d1;
		  var option = document.createElement("option");
		  option.textContent = disName;
		  option.value = disName;
		  selectDisease.appendChild(option);
		}
	}
   else
    {	
	 var para = $("<input>", {id:"para", class: "alert alert-danger", value:"Cannot exceed 3 diseases"});
	 $("#form").append(para);
	 $("#btn-addDisease").attr("disabled","disabled");
	 
	}   	
 }
</script>
<script src="http://code.jquery.com/jquery-1.11.3.js"></script>
<script src="https://rawgit.com/gka/d3-jetpack/master/d3-jetpack.js"></script>
 
 <script type="text/javascript">
function onSubmit(){
	var disSelected = document.getElementById("selectDropdown").value;
	var minSelected = document.getElementById("min").value;
	var maxSelected = document.getElementById("max").value;
			
	$.ajax({
	type: "POST",
	url: "onSubmit.php",
	data: {'disSelected':disSelected,
	        'minSelected':minSelected,
			'maxSelected':maxSelected,
			'counterID':counterID},
			
	success: function(dataReceived) {
	  if(dataReceived)
		{ 
		    $("#graph").empty();
		   // Don't know what the deal is with this
		   //console.log("data.index of NULL is - ".concat(dataReceived.indexOf("null")));
		   if (dataReceived.indexOf("null")> -1)
		   {
			 alert("No results for this query");
		   }
		  else
		   {  //console.log(dataReceived);
			  createGraph(JSON.parse(dataReceived),"#graph", counterID); 
		   }
		 }
		 else
		   {
		     alert("No results from the selected specification"); 
		   }
	},
	error: function(jqXHR, textStatus, errorThrown) 
	{
	  console.log(jqXHR.responseText);
	  console.log(errorThrown);
	 }	
});
} 
</script>

</body>
</html>