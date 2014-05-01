<?php
	# Description:
	# this script is purely for servicing AJAX requests to see if a user_id is available

	# check to make sure we're logged in
	if(!isset($_COOKIE["user_id"])){
		header( $_SERVER['SERVER_PROTOCOL']." 404 Not Found", true, 404 );
		echo "<h1>404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}
	# check to make sure this is a GET and has the params we want
	if($_SERVER["REQUEST_METHOD"] !== "GET" or !isset($_GET["leader_id"])){
		header( $_SERVER['SERVER_PROTOCOL']." 404 Not Found", true, 404 );
		echo "<h1>404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}

	# if we're good, move on..
	header('content-type: application/json');

	# read in the request parameters and get the data we need from the cookie
	$leader_id = trim($_GET["leader_id"]);
	$user_id = $_COOKIE["user_id"];

	# connect to the database
	if(!mysql_connect("cs445sql", "motsuka", "EL611mot"))
		die ("Couldn't connect to mysql server!<br>");
	if(!mysql_select_db("gow"))
		die ("Couldn't select a database!<br>");

	$query = "SELECT * FROM Followers WHERE follower='$user_id' AND leader='$leader_id';";
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	if($num_rows > 0){
		$result = mysql_query("DELETE FROM Followers WHERE follower='$user_id' AND leader='$leader_id';");
		echo json_encode(array("following" => false));
	}else{
		$result = mysql_query("INSERT INTO Followers(follower, leader) VALUES('$user_id','$leader_id');");
		echo json_encode(array("following" => true));
	}

?>