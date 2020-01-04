<?php

@require_once 'config.php';
//get params
$openID = @$_GET['openID'] ? $_GET['openID'] : '';
$device_id = @$_GET['device_id'] ? $_GET['device_id'] : '';
$source = (strlen(@$_GET['source']) == 1) ? $_GET['source'] : '';
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
if($source == 2) {
	if(@$_GET['a_openID'] && @$_GET['a_access']) {
		$a_openID = $_GET['a_openID'];
		$a_access = $_GET['a_access'];
		$access_string = ($a_access == 0) ? "访问权限" : "访问和控制权限";
		$log = "$a_openID".'向'."$openID".'授予'."$access_string";
		$time = date('Y-m-d H:i:s');
		$change_log = "INSERT INTO user_log VALUES('$device_id', 3, '$log', '$time');";
		$query_log = mysqli_query($mysql_link, $change_log);
	} else {
		$array_result = array( 'result' => '0');
		exit(json_encode($array_result));
	}
} else {
	$a_openID = '';
	$a_access = 0;
}

$get_record = "SELECT * FROM user_device WHERE device_id = '$device_id' AND u_openid = '$openID';";
$get_record_query = mysqli_query($mysql_link, $get_record);
if($get_record_query -> num_rows == 0) {
	$user_query = "INSERT INTO user_device(u_openid, device_id, source, a_access, a_openid) VALUES('$openID', '$device_id', $source, $a_access, '$a_openID');";
} else {
	if($source == 2 && @$_GET['a_openID'] && @$GET['a_access']) {
		$user_query = "UPDATE user_device SET source = $source, a_access = $a_access, a_openid = '$a_openID' WHERE device_id = '$device_id' AND u_openid = '$openID';";
	} else {
		$array_result = array('resutl' => '1');
		exit(json_encode($array_result));
	}
}
$query_result = mysqli_query($mysql_link, $user_query);
if($query_result == FALSE) {
	$result = array('result' => '0');
} else {
	$result = array('result' => '1');
}

//output result
exit(json_encode($result));
?>
