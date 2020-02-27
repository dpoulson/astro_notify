<html>
<head>
<title>Confirmation</title>
</head>

<body>
<?php

include "settings.php";

echo "<h1>".$config->site_name." - Details</h1>";

$id = $_REQUEST['id'];

if ($id != "") {
	$sql = "SELECT * FROM users WHERE confirmation = \"$id\";";
	$result = $conn->query($sql);
	if ($result->num_rows != 1) {
		echo "Error with confirmation ID. Please contact an admin";
	} else {
		$row = $result->fetch_assoc();
		$sql = "SELECT * FROM locations WHERE uid = \"".$row['location_uid']."\";";
                $result = $conn->query($sql);
		$location = $result->fetch_assoc();
		echo "<h2>Welcome, ".$row['full_name']."</h2>";
		echo "<br />";
		echo "Your notification details are as follows:<br />";
		echo "<p>";
		echo "<table>";
		echo "<tr><td>Max Wind Speed (mph)</td><td>".$row['wind_speed']."</td></tr>";
                echo "<tr><td>Max Cloud Cover (%)</td><td>".$row['cloud_cover']."</td></tr>";
                echo "<tr><td>Minimum consecutive hours</td><td>".$row['min_hours']."</td></tr>";
                echo "<tr><td>Days Ahead</td><td>".$row['days_ahead']."</td></tr>";
		echo "</table>";
		echo "<p>";
		echo "Location: ".$location['name'].", ".$location['country']."<br />";
		echo "<a href=check_location.php?location=".$location['uid'].">View Raw Data</a>";


	}
} else {
	echo "No confirmation ID received";
}
?>



</body>
</html>
