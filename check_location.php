<?php

include("settings.php");

$location = $_REQUEST['location'];

$sql = "SELECT * FROM locations WHERE uid = ".$location;
$loc = $conn->query($sql)->fetch_assoc();

echo "<h1>Location: ".$loc['name'].", ".$loc['country']."</h1>";
echo "Latitude: ".$loc['lat']."<br />";
echo "Longitude: ".$loc['lon']."<br />";
echo "Timezone: ".$loc['timezone']."<br />";

// For each location, load in forecast and sun times
$sql = "SELECT * FROM forecast_data WHERE time > UNIX_TIMESTAMP(NOW()) AND location = ".$location;
$res = $conn->query($sql);
for($i = 0; $forecast_data[$i] = $res->fetch_assoc(); $i++) ;
array_pop($forecast_data);

$sql = "SELECT * FROM sun WHERE location = ".$location;
$res = $conn->query($sql);
for($i = 0; $sun[$i] = $res->fetch_assoc(); $i++) ;
array_pop($sun);

echo "<h2>Raw forecast data:</h2>";

$tz = new DateTimeZone($loc['timezone']);
for($d = 0; $d < 7; $d++) {
	foreach($forecast_data as $night) {
		$dt = new DateTime("now", $tz);
		$dt->setTimestamp($night['time']);
		if($night['time'] > $sun[$d]['sunset'] && $night['time']+3600 < $sun[$d+1]['sunrise']-3600) {
			echo "(".$dt->format('D d/m/y, H:i:s').") - Wind: ".$night['wind_speed']." | Cloud: ".($night['cloud_cover']*100)."%<br />";
		}
	}
	echo "<hr>";
}



?>

Forecast information provided by: <br />
<a href="https://darksky.net/poweredby/">
<img width=400 src="https://darksky.net/dev/img/attribution/poweredby-oneline.png">
</a>
