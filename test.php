<?php
// Setting up the time zone
date_default_timezone_set('Asia/Kolkata');

// Host Name
$dbhost = 'localhost';

// Database Name
$dbname = 'doctoraz_lara352';


// Database Username
$dbuser = 'doctoraz_lara352';

// Database Password
$dbpass = '13]O1bp3(S';

// Defining base url
define("BASE_URL", "https://apps.onetravelteam.com/");

// Getting Admin url
define("ADMIN_URL", BASE_URL . "admin" . "/");

try {
	$pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
   	$mysqli =mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
}
catch( PDOException $exception ) {
	echo "Connection error :" . $exception->getMessage();
}

date_default_timezone_set('Asia/Kolkata');
$thedate=date("Y-m-d");


$sqlz="UPDATE doctors SET `date_picker` = DATE_ADD(`date_picker`, INTERVAL 1 MONTH) 
WHERE `date_wise_checkbox` = 1 AND `date_picker` < '$thedate'";
                    
$pdo->query($sqlz);




?>