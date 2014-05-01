<?php
	# Description:
	# this script is purely for servicing AJAX requests to see if a user_id is available

	# no need to check login status on this one
	# check to make sure this is a GET and has the params we want
	if($_SERVER["REQUEST_METHOD"] !== "GET" or !isset($_GET["user_id"])){
		header( $_SERVER['SERVER_PROTOCOL']." 404 Not Found", true, 404 );
		echo "<h1>404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}

	# if we're good, move on..
	header('content-type: application/json');

	# read in the request parameters
	$user_id = trim($_GET["user_id"]);

	# connect to the database
	if(!mysql_connect("cs445sql", "motsuka", "EL611mot"))
		die ("Couldn't connect to mysql server!<br>");
	if(!mysql_select_db("gow"))
		die ("Couldn't select a database!<br>");

	$query = "SELECT user_id FROM Users WHERE user_id='$user_id';";
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0)
		echo json_encode(array("available" => false));
	else
		echo json_encode(array("available" => true));

?>