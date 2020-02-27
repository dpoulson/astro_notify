<html>
<head>
<title>Confirmation</title>
</head>

<body>
<?php

include "settings.php";

echo "<h1>".$config->site_name." - Confirmation $id</h1>";

$id = $_REQUEST['id'];

if ($id != "") {
	echo "Confirming.....<br />";
	$sql = "SELECT * FROM users WHERE confirmation = \"$id\";";
	$result = $conn->query($sql);
	if ($result->num_rows != 1) {
		echo "Error with confirmation ID. Please contact an admin";
	} else {
		echo "ID Confirmed, updating database. <br />";
		$row = $result->fetch_assoc();
		$sql = "UPDATE users SET confirmed=1 WHERE email = \"".$row['email']."\";";
		$result = $conn->query($sql);
		if ($result) {
			echo "Database updated. You will now receive notifications for upcoming clear skies<br />";
			$sql = "SELECT * FROM locations WHERE uid = \"".$row['location_uid']."\";";
			$result = $conn->query($sql);
			if ($result->num_rows != 1) {
				echo "Location update error!!! Please contact an admin";
			} else {
				$location = $result->fetch_assoc();
				if ($location['lat'] == "") {
					echo "Updating location data<br />";
					$url = "http://api.geonames.org/getJSON?username=".$config->geoapiuser."&geonameId=".$location['uid'];
					$json = file_get_contents($url);
					$raw = json_decode($json);
					$sql = "UPDATE locations SET lat=\"".$raw->lat."\", lon=\"".$raw->lng."\", timezone=\"".$raw->timezone->timeZoneId."\" WHERE uid=".$location['uid'];
					$result = $conn->query($sql);
					if ($result) {
						echo "Location data updated<br />";
					} else {
						echo "Location data failed to update. Please contact an admin";
					}
				}
			}
		} else {
			echo "Database update failed. Please contact an admin";
		}
	}
} else {
	echo "No confirmation ID received";
}
?>



</body>
</html>
