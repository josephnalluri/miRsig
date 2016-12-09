<?php
//Header files
require_once('dbAcess.php');  // Connect to Database
$query = "SELECT d1 from d1_from_consensus order by d1 asc";
$queryResult = mysqli_query($dbConnect, $query) or die("Error in the query" . mysqli_error($dbConnect));

$diseaseArray = array();
for($i = 0; $i < mysqli_num_rows($queryResult); $i++)
{
  $diseaseArray[] = mysqli_fetch_assoc($queryResult);
}
$diseaseDropdown = json_encode($diseaseArray);


if(file_exists('CSV/network.csv'))
{
unlink('CSV/network.csv'); // To delete the previous network CSV file
}
?>

 
 
<!DOCTYPE html>
<html lang="en">
<head>
  
  <title>miRsig: a consensus-based network inference methodology to identify pan-cancer miRNA-miRNA interaction signatures</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="googleTableCss.css">
  <link rel="stylesheet" href="graphCSS.css"> 
  <!--<link rel="stylesheet" href="newG.css">-->
  <!--<link rel="stylesheet" href="mSTG.css">-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="drawGraph.js"></script> 
  <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
 
 </head>
 
<body onload="fillDropdown()">
<div class="container">
  <div class="jumbotron">
    <h1><i>miRsig</i></h1><h2> <i> ~ <b><u>miR</u></b>NA <b><u>sig</u></b>nature</i>, a consensus-based network inference methodology to identify pan-cancer miRNA-miRNA interaction signatures</h2>
  </div>
  <div class="row">
    <div class="col-sm-10">
	
      <h3>This tool provides specific predicted miRNA-miRNA interaction networks for cancer-related diseases using a consenus-based network inference of miRNA-disease networks</h3>
	
	  
	  <div class="alert alert-success">
         <strong>Note:</strong> <br>
		 1. Users can view individual miRNA-miRNA predicted interactions for a specific disease <br>
		 2. Users can view miRNA-miRNA predicted interactions for a group of diseases by clicking <strong>Select more diseases</strong><br>
		 3. Some queries can take upto 1 minute to load based on the selection <br>
		 4. <strong>Maximum</strong> and <strong>Minimum</strong> input fields are <i>probablity scores</i> meant for user to specify the confidence-range.<br>
		 5. Upon <strong>Submit</strong> the miRNA-miRNA interactions will be displayed below. <br>
         
      </div>
  
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
			Minimum Score: <input type="text" id="min" name="min" size="4"> &nbsp;  &nbsp; <i>[<b>Default</b>: Max is 1 and Min is 0.5000]</i>		
		   
		    <hr>
			  <button onclick = "onSubmit()" type="button" class="btn btn-success" id="btn-submit"> SUBMIT</button>  &nbsp;  &nbsp;
			  
			   <input type="reset" class="btn btn-info" id="btn-reset" value="RESET" onClick="window.location.reload()"> </button>
	        <br><br>
	   </form>
	 
	<!-- Taken from: https://myzeroorone.wordpress.com/2015/03/02/creating-simple-please-wait-dialog-with-twitter-bootstrap/--> 
	<!-- Modal Start here-->
	<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1"
		role="dialog" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">
						<span class="glyphicon glyphicon-time">
						</span> Please Wait... loading results and visualization
					 </h5>
				</div>
				<div class="modal-body">
					<div class="progress">
						<div class="progress-bar progress-bar-info
						progress-bar-striped active"
						style="width: 100%">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal ends Here -->
     <a href="/miRsig/CSV/network.csv" id="downloadCSV" style="display:none;">Download the network (CSV)</a>
 	  <!-- Placeholder for graph --> 
	  <div id="graph" tabindex="0"></div>
	  <div id = "graph-bottom"> </div>
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
    $min = 0.5;
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
   if(counterID < 5)
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
	 var para = $("<input>", {id:"para", class: "alert alert-danger", value:"Cannot exceed 5 diseases"});
	 $("#selectDiseaseform").append(para);
	 $("#btn-addDisease").attr("disabled","disabled");
	 
	}   	
 }

function isEmpty(str) 
{ return (!str || 0 === str.length); }

function isBlank(str)
{ return (!str || /^\s*$/.test(str)); }

</script>
<!-- <script src="http://code.jquery.com/jquery-1.11.3.js"></script> -->
<script src="https://rawgit.com/gka/d3-jetpack/master/d3-jetpack.js"></script>
 
 <script type="text/javascript">

function onSubmit(){
   	var disSelected = document.getElementById("selectDropdown").value;
	var minSelected = document.getElementById("min").value;
	var maxSelected = document.getElementById("max").value;
    if(isEmpty(minSelected)||isBlank(minSelected)) {minSelected=0;}
    if(isEmpty(maxSelected)||isBlank(maxSelected)){maxSelected=1;}
	
	// To decide the AJAX request based on number of inputs.
	switch(counterID)
	{ 
	  case 1: var params = {'disSelected':disSelected,
	          'minSelected':minSelected,
			  'maxSelected':maxSelected,
			  'counterID':counterID};	
	          break;
			  
	  case 2: var params = {'disSelected':disSelected,
	          'disSelected2': document.getElementById("selectDropdown1").value,
	          'minSelected':minSelected,
			  'maxSelected':maxSelected,
			  'counterID':counterID};	
			  break;
	  case 3: var params = {'disSelected':disSelected,
	          'disSelected2': document.getElementById("selectDropdown1").value,
			  'disSelected3': document.getElementById("selectDropdown2").value,
	          'minSelected':minSelected,
			  'maxSelected':maxSelected,
			  'counterID':counterID};
	          break;
	  case 4: var params = {'disSelected':disSelected,
	          'disSelected2': document.getElementById("selectDropdown1").value,
			  'disSelected3': document.getElementById("selectDropdown2").value,
			  'disSelected4': document.getElementById("selectDropdown3").value,
	          'minSelected':minSelected,
			  'maxSelected':maxSelected,
			  'counterID':counterID};
	          break;
	  case 5: var params = {'disSelected':disSelected,
	          'disSelected2': document.getElementById("selectDropdown1").value,
			  'disSelected3': document.getElementById("selectDropdown2").value,
			  'disSelected4': document.getElementById("selectDropdown3").value,
			  'disSelected5': document.getElementById("selectDropdown4").value,
	          'minSelected':minSelected,
			  'maxSelected':maxSelected,
			  'counterID':counterID};
	          break;
	}
	
	$("#myPleaseWait").modal("show");
	//Ajax request
	$.ajax({
	type: "POST",
	url: "onSubmit.php",
	/*
	data: {'disSelected':disSelected,
	        'minSelected':minSelected,
			'maxSelected':maxSelected,
			'counterID':counterID},
	*/
   	data: params,
	success: function(dataReceived) {
	   $('#myPleaseWait').modal('hide');
	  if(dataReceived)
		{ 
		    $("#graph").empty();
            $("#downloadCSV").show();
		   // Don't know what the deal is with this
		   //console.log("data.index of NULL is - ".concat(dataReceived.indexOf("null")));
		   if (dataReceived.indexOf("null")> -1)
		   {
			 alert("No results for this selection. Please try reducing the number of diseases or widening the range of score. ");
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
	  $('#myPleaseWait').modal('hide');
	  console.log(jqXHR.responseText);
	  console.log(errorThrown);
	 }	
});
}

</script>

<br><br><br>
<div id="footer">
 <div class="container">
  <p class="muted credit"> <b>Citation</b>: Nalluri, J. J. <i>et al. miRsig</i>: a consensus-based network inference methodology to identify pan-cancer miRNA miRNA interaction signatures. <i>Sci. Rep.</i> <b>6</b>, 39684; doi: 10.1038/srep39684 (2016)</i></p>
 </div>
</div>
</body>

</html>
