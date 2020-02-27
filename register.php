<?

include "header.php";
include "settings.php";

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>

<script>

$('#submit').button("disable");

$('.fields').bind('keyup', function() {
var nameLength = $("#sub_first_name").length;
var emailLength = $("#sub_email").length;

if (nameLength > 0 && emailLength > 0)
{
   $('#yourButton').button("enable");
}

} );

function getState(val) {
	$.ajax({
	type: "POST",
	url: "ajax_get_subcountries.php",
	data:'country='+val,
	success: function(data){
		$("#state-list").html(data);
		getCity();
	}
	});
}


function getCity(val) {
	$.ajax({
	type: "POST",
	url: "ajax_get_cities.php",
	data:'subcountry='+val,
	success: function(data){
		$("#city-list").html(data);
	}
	});
}

function allowSubmit() {
}

</script>
 </head>


 <body>
  <h1>Register User</h1>
 <form action="submit.php">
 <label for=email>Email address</label>
 <input type=text name=email placeholder="Email Address" size=50><br />
 <label for=full_name>Your full name</label>
 <input type=text name=full_name placeholder="Your full name" size=50><br />
 <label for=wind_speed>Max wind speed in mph</label>
 <input type=text name=wind_speed value=10 placeholder="Max wind speed (10mph)" size=50><br />
 <label for=cloud_cover>Max cloud cover percentage</label>
 <input type=text name=cloud_cover value=5 placeholder="Max cloud cover percentage (5%)" size=50><br />
 <label for=days_ahead>How many days ahead should we notify?</label>
 <input type=text name=days_ahead value=6 placeholder="How many days ahead should we notify? (6)" size=50><br />
 <label for=min_hours>Minimum number of hours of clear skies?</label>
 <input type=text name=min_hours value=4 placeholder="Minimum number of hours of clear skies? (4)" size=50><br />
 <label for=address>Select closest city:</label><br />
<select name="country"
                id="country-list" class="demoInputBox"
                onchange="getState(this.value);">
                <option value="" disabled="" selected="">Select Country</option>
<?
$sql = "SELECT DISTINCT(country) FROM locations ORDER BY country";
$countries = $conn->query($sql);
while($row = $countries->fetch_assoc()) {
	echo "<option value=\"".$row['country']."\">".$row['country']."</option>";
}
echo "</select><br />";


?>
 <select name="state"
   id="state-list" class="demoInputBox"
   onChange="getCity(this.value);">
   <option value="">Select State</option>
 </select><br />
 <select name="city"
   id="city-list" class="demoInputBox"
   onChange="allowSubmit();">
   <option value="">Select City</option>
 </select><br />

 <label for=gdpr>Please tick this box to acknowledge you have read and agree to the
 <a href="gdpr.php">Terms and conditions, and the GDPR policy</a> for this site</lable><br />
 <input type=checkbox name=gdpr><br />
 <input id=submit name=submit type=submit value="Submit">
 </form>
</html>
