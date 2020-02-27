<html>
 <head>
  <title>Register</title>
  <link href="./css/style.css" rel="stylesheet" type="text/css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>
 </head>

<?

include "settings.php";

$email = test_input($_REQUEST["email"]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $emailErr = "Invalid email format";
}



$sql = "SELECT uid FROM users WHERE email = \"$email\";";
$check_email = $conn->query($sql);

if ($check_email->num_rows == 0) {
	// New user
	$sql = "INSERT INTO users(full_name, email, location_uid, wind_speed, cloud_cover, days_ahead, min_hours, confirmation) VALUES(?,?,?,?,?,?,?,?);";
	$confirmation = generateID(64);
	$days_ahead = $_REQUEST['days_ahead'] + 1;
	$cloud_cover = $REQEST['cloud_cover']/100;
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ssiiiiis", $_REQUEST['full_name'], $email, $_REQUEST['city'], $_REQUEST['wind_speed'], $cloud_cover, $days_ahead, $_REQUEST['min_hours'], $confirmation );
	$stmt->execute();
	if ($stmt->error == "") {
		echo "User created";
	} else {
		echo "There was an error, please check the input and if it still looks ok, contact an admin <br />";
		printf("Error: %s.\n", $stmt->sqlstate);
		printf("Error: %s.\n", $stmt->error);
	}
	$stmt->close();

	// Send confirmation email
        echo "Sending email to ".$email."....<br/>";
	$reglink = $config->site_base."/confirm.php?id=".$confirmation;
        $to = $email;

        $headers = "From: ".$config->from_name." <".$config->from_email.">"."\r\n"."X-Mailer: PHP/".phpversion()."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

        $subject = $config->site_name." - Registration Confirmation";

	$searchArray = array("%FULLNAME%", "%SITENAME%", "%REGLINK%");
	$replaceArray = array($_REQUEST['full_name'], $config->site_name, $reglink);
	$message = str_replace($searchArray, $replaceArray, $config->registration_email);


        $success = mail($to, $subject, $message, $headers);

        echo $message;

	echo "Success: ".$success;


} else {
	echo "Email already registered....";
}

?>
</html>
