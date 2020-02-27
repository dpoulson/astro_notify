<?php

include("../includes/settings.php");

// Clear forecast data
//
$sql = "TRUNCATE forecast_data";
$result = $conn->query($sql);

$sql = "TRUNCATE sun";
$result = $conn->query($sql);

$sql = "SELECT * FROM locations WHERE lat != \"\"";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {

echo "Pulling in data for location ID: ".$row['uid']."<br />";
echo " - ".$row['name']."<br /><br />";

$json = file_get_contents("https://api.darksky.net/forecast/".$config->apikey."/".$row['lat'].",".$row['lon']."?exclude=currently,minutely,alerts,flags&extend=hourly");
$raw = json_decode($json);

foreach ($raw as $key=>$value) {
	if ($key == "hourly") {
		foreach ($value as $data=>$info) {
			if ($data == "data") {
				foreach($info as $forecast) {
					// echo "$forecast->time $forecast->precipProbability $forecast->windSpeed $forecast->cloudCover";
					$sql = "INSERT INTO forecast_data(time,location,cloud_cover,precip_prob,wind_speed) VALUES(?,?,?,?,?)";
					$stmt = $conn->prepare($sql);
    					$stmt->bind_param("iisss", $forecast->time, $row['uid'], $forecast->cloudCover, $forecast->precipProbability, $forecast->windSpeed);
    					$stmt->execute();
				}
			}
		}
	} elseif ($key == "daily") {
		foreach ($value as $data=>$info) {
                        if ($data == "data") {
                                foreach($info as $forecast) {
                                        $sql = "INSERT INTO sun(day,location,sunrise,sunset) VALUES(?,?,?,?)";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("iiii", $forecast->time, $row['uid'], $forecast->sunriseTime, $forecast->sunsetTime);
                                        $stmt->execute();
                                }
                        }
                }
	}
}


}
?>
