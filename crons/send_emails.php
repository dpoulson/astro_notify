<?php

include("../includes/settings.php");

$sql = "SELECT DISTINCT(location_uid) FROM users WHERE confirmed = 1 ORDER BY location_uid;";
$result = $conn->query($sql);
while($location = $result->fetch_array()) {
	echo "Location: ".$location['location_uid']."<br/>";
	$sql = "SELECT * FROM locations WHERE uid = ".$location['location_uid'];
	$loc = $conn->query($sql)->fetch_assoc();

	// For each location, load in forecast and sun times
	$sql = "SELECT * FROM forecast_data WHERE time > UNIX_TIMESTAMP(NOW()) AND location = ".$location['location_uid'];
	$res = $conn->query($sql);
	for($i = 0; $forecast_data[$i] = $res->fetch_assoc(); $i++) ;
	array_pop($forecast_data);

	$sql = "SELECT * FROM sun WHERE location = ".$location['location_uid'];
        $res = $conn->query($sql);
        for($i = 0; $sun[$i] = $res->fetch_assoc(); $i++) ;
	array_pop($sun);

	// Loop through each user in this location
	$sql = "SELECT * FROM users WHERE location_uid = ".$location['location_uid'];
	$users = $conn->query($sql);
	while($user = $users->fetch_assoc()) {
		$good_night = 0;
		$msg = "";
		echo "Checking for user: ".$user['full_name']."<br />";
		$tz = new DateTimeZone($loc['timezone']);
		if (isset($_REQUEST['test']))
			echo $tz->getOffset();
		for($d = 0; $d < $user['days_ahead']; $d++) {
			if (isset($_REQUEST['test']))
				echo "Days ahead: $d<br />";
			$consecutive = 0;
                        foreach($forecast_data as $night) {
				$dt = new DateTime("now", $tz);
				$dt->setTimestamp($night['time']);
				if($night['time'] > $sun[$d]['sunset'] && $night['time']+3600 < $sun[$d+1]['sunrise']-3600 && $user['wind_speed'] >= $night['wind_speed'] && $user['cloud_cover'] >= $night['cloud_cover']) {
					if (isset($_REQUEST['test']))
					echo "Checking: ".$dt->format('D d/m/y, H:i:s')." : Consecutive = $consecutive<br />";
					if ($consecutive != 1) {
						$start = $night['time'];
					}
					$consecutive = 1;
					if (isset($_REQUEST['test']))
                                               	echo "found an hour (".$dt->format('D d/m/y, H:i:s').") - Wind: ".$night['wind_speed']." | Cloud: ".$night['cloud_cover']."  : Consecutive = $consecutive<br/>";
				} elseif ($consecutive == 1) {
					$dtstart = new DateTime("now", $tz);
					$dtstart->setTimestamp($start);
					$start_string = $dtstart->format('D d/m/y, H:i:s');
					$hours = ($night['time'] - $start)/3600;
					if (isset($_REQUEST['test']))
						echo "Consecutive hours: $hours<br />";
					if($hours >= $user['min_hours']) {
						$msg .= "Potential good day starting at $start_string, for about $hours hours. <br/>";
						$good_night = 1;
					}
					$consecutive = 0;
				}
			}
		}

		if ($good_night != 0) {
			echo "Sending email to ".$user['email']."....<br/>";
			$unsub = $config->site_base."/unsub.php?id=".$user['confirmation'];
			$check = $config->site_base."/check_details.php?id=".$user['confirmation'];
			$rawdata = $config->site_base."/check_location.php?location=".$loc['uid'];
			$to = $user['email'];

			$headers = "From: ".$config->from_name." <".$config->from_email.">"."\r\n"."X-Mailer: PHP/".phpversion()."\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

			$subject = $config->site_name." - Possibly clear nights ahead";

			$searchArray = array("%FULLNAME%", "%SITENAME%", "%LOCATION%", "%LAT%", "%LON%", "%DATA%", "%UNSUBURL%", "%CHECKDETAILS%", "%RAWDATA%");
			$replaceArray = array($user['full_name'], $config->site_name, $loc['name'], $loc['lat'], $loc['lon'], $msg, $unsub, $check, $rawdata);
			$message = str_replace($searchArray, $replaceArray, $config->notify_email);

			if (!isset($_REQUEST['test'])) {
				$success = mail($to, $subject, $message, $headers);
			} else {
				echo "TEST RUN!!!!<br />";
				echo $message;
			}

		}
	}
	echo "<hr>";
}
?>
