<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : '';

//mysql link
$mysql_link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db_name);
if($mysql_link) {
} else {
	//echo "Error: Unable to connect to MySQL." . PHP_EOL;
	//echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    //echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
	exit;
}

//main function execute
$user_query = "DELETE FROM user_device WHERE u_openid = '$openID' AND device_id = '$device_id';";
$query_result = mysqli_query($mysql_link, $user_query);
$array_result = ($query_result == FALSE) ? array('result' => '0') : array('result' => '1');
//output result
exit(json_encode($array_result));
?>