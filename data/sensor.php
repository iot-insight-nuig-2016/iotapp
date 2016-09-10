<?php
header('Content-type: application/json');

// FAKE DATA

$arr = array();

for($h = 0; $h<24; $h++){

	$hh = $h."h";


	$arr[$hh] = array();

	for($room = 10; $room <20; $room ++){
		$value = rand(5,100)/100;
		$roomname = "room-".$room;
		//$arr[$hh][$roomname] = $value;
		$arr[$hh][] = array("label"=>$roomname, "value"=>$value);
	}

}

echo json_encode($arr);

?>