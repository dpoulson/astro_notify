<?php

include "../includes/config.php";

echo "<option>Select City</option>";
$sql = "SELECT name,uid FROM locations WHERE subcountry = \"".$_REQUEST['subcountry']."\" ORDER BY name;";
echo $sql;
$cities = $conn->query($sql);
while($row = $cities->fetch_assoc()) {
	echo "<option value=\"".$row['uid']."\">".$row['name']."</option>";
}

?>
