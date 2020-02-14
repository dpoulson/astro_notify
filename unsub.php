<html>
<head>
<title>Unsub</title>
</head>

<body>
<?php

include "includes/config.php";

echo "<h1>".$config->site_name." - Unsub $id</h1>";

$id = $_REQUEST['id'];

if ($id != "") {
	echo "Unsubbing.....<br />";
	$sql = "SELECT * FROM users WHERE confirmation = \"$id\";";
	$result = $conn->query($sql);
	if ($result->num_rows != 1) {
		echo "Error with confirmation ID. Please contact an admin";
	} else {
		echo "ID Confirmed, updating database. <br />";
		$row = $result->fetch_assoc();
		$sql = "DELETE FROM users WHERE email = \"".$row['email']."\";";
		$result = $conn->query($sql);
		if ($result) {
			echo "Database updated. You will now NOT receive notifications for upcoming clear skies<br />";
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
