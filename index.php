<!DOCTYPE html>
<html>
<head>

<title>Calorie Counter</title>
<style>
.title {text-align:left; color:white;}
.header {background-color:teal; width:800px; font-size:35px;}

table {font-size:35px;}

</style>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />

<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
<script src="calorie_functions.js"></script>
<script>
$(document).ready( fillAutoComplete );

</script>
</head>

<script type="text/javascript">

	var xmlhttp;

	function ajaxGet(serverAddress, f){
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
		xmlhttp.onreadystatechange=f;
		xmlhttp.open("GET", serverAddress, true);
		xmlhttp.send();
	}

        function ajaxPost(serverAddress, params, f){
$.ajaxSetup({
    type: 'POST',
    headers: { "cache-control": "no-cache" }
});
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange=f;
                xmlhttp.open("POST", serverAddress, true);

		//Send the proper header information along with the request
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Content-length", params.length);
		xmlhttp.setRequestHeader("Connection", "close");

                xmlhttp.send(params);
        }

	function loadTable(){
		ajaxGet("calorie_table.php", function()
			{
				if(xmlhttp.readyState == 4  && xmlhttp.status == 200){
					var results = xmlhttp.responseText;
					var calorieObj = obj = JSON.parse(results);
					var theTable = document.getElementById('myTable');
					var htmlString = "";
					for (var i = 0; i < calorieObj.length; i++) {

						htmlString += "<tr id='" + calorieObj[i].foodIndex + "'>"; 
						htmlString += "<td>" + calorieObj[i].Food + "</td>";
						htmlString += "<td>" + calorieObj[i].Calories + "</td>";
						htmlString += "<td>" +
							 "<img id='" + calorieObj[i].foodIndex + "' src='TestImage1.png' onclick='deleteItem(" + "this.id"+ ")' >"; 
							+ "</td>";
						htmlString += "</tr>";

					}//end for
					
					myTable.innerHTML = htmlString;
	                        }//end if
			});//end ajaxGet function and function parameter

	}//end function
        
	function deleteItem($a){
		var concatVariable = "#" + $a ;
		$(concatVariable ).hide();	
                
		ajaxPost("deleteitem.php", "q="+$a, function()
                        {                 
				if(xmlhttp.readyState == 4  && xmlhttp.status == 200){
					var results = xmlhttp.responseText.split(',');
					var remCalorieField = document.getElementById("cals_remaining");
					remCalorieField.innerHTML="Remaining: " + (2200 - results);
					var numcalories = document.getElementById("numcalories");
					var itemname = document.getElementById("itemname");
					numcalories.value = "";
					itemname.value = "";

				}
			  
          		});

          }

        function addItem($name, $calories){
                
		var result;
		var resArray;
		//Random added to the post request to avoid iOS from caching post request
                ajaxPost('additem.php', "key=" + $name + '&calories='+$calories +"Random=" + Math.random(),  function()
			{
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
					result = xmlhttp.responseText;
					resArray =  result.split(",");
					remCalorieField = document.getElementById("cals_remaining");
					remCalorieField.innerHTML= "Remaining: " + (2200 - resArray[0]);;
					var numcalories = document.getElementById("numcalories");
					var itemname = document.getElementById("food_name_field");
					numcalories.value = "";
					itemname.value = "";

					//add new item to end of table
					var table=document.getElementById("myTable");
					var row=table.insertRow(-1);
					var cell1=row.insertCell(0);
					var cell2=row.insertCell(1);
					var cell3=row.insertCell(2);
					cell1.innerHTML=$name;
					cell2.innerHTML=$calories;
					var innerHTML = "<img id='" + resArray[1] + "' src='TestImage1.png' onclick='deleteItem(" + "this.id"+ ")' >"; 
					cell3.innerHTML= innerHTML; 
					row.setAttribute("id", resArray[1] );                  

					fillAutoComplete();
				}
			  
          		});

          }
</script>

</head>
<body onload='loadTable()' >

<div class="header">
	<p class="title">Calorie Counter</p>
</div>


<form method="post" action="javascript:addItem(
			document.getElementById('food_name_field').value, 
			document.getElementById('numcalories').value)">

	<div class="ui-widget">
	    <label for="food_name_field">Food: </label>
	    <input spellcheck=false id="food_name_field" />
	</div>
	<br>
	<div class="ui-widget">
	    <label for="numcalories">Calories: </label>
	    <input id="numcalories" />
	</div>

	<br>
	<br>
	<input id='submit' type= "submit" style="font-face: 'Comic Sans MS'; font-size: larger; color: teal; background-color: #FFFFC0; border: 3pt ridge lightgrey" name = "submit" value="Submit" />
</form><br><br>
<button id='refresh' style="font-face: 'Comic Sans MS'; font-size: larger; color: teal; background-color: #FFFFC0; border: 3pt ridge lightgrey" value="Refresh" onclick="loadTable()">Refresh</button>

<?php
import_request_variables("p", "arg_");
include 'global.php';
include 'serverOps.php';

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$connection =& server_connect();
if(isset($arg_submit)){
	// This is where I validate the input
}

$totalCalories = totalCalories();
echo "<p id='cals_remaining'>Remaining: " . (2200- $totalCalories ) . "</p>";

$command = "Select * from Calories order by timeofadd"; 
$result = mysql_query($command, $connection);

echo "<table id='myTable' border = '1'>";
echo "</table>";

?>
</body></html>
