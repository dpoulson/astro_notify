<?php

include "settings.php";

echo "<option>Select State</option>";
$sql = "SELECT DISTINCT(subcountry) FROM locations WHERE country = \"".$_REQUEST['country']."\" ORDER BY subcountry;";
$subcountries = $conn->query($sql);
while($row = $subcountries->fetch_assoc()) {
	echo "<option value=\"".$row['subcountry']."\">".$row['subcountry']."</option>";
}

?>
