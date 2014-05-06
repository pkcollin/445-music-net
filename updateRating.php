<?php
	# Description:
	# this script is purely for servicing AJAX requests to play a song

	# check login status first
	if(!isset($_COOKIE["user_id"])){
		header("Location: http://cs445.cs.umass.edu/php-wrapper/gow/max/login.php");
		exit();
	}
	
	# check to make sure this is a POST and has the params we want
	if($_SERVER["REQUEST_METHOD"] !== "POST" or !isset($_POST["song_id"])){
		header( $_SERVER['SERVER_PROTOCOL']." 404 Not Found", true, 404 );
		echo "<h1>404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}

	# if we're good, move on...
	header('content-type: application/json');

	# connect to the database
	if(!mysql_connect("cs445sql", "********", "********"))
		die ("Couldn't connect to mysql server!<br>");
	if(!mysql_select_db("gow"))
		die ("Couldn't select a database!<br>");

	# read in the request parameters
	$song_id = trim($_POST["song_id"]);
	$rating = trim($_POST["rating"]);
	$user_id = $_COOKIE["user_id"];

	# see if the song has been played by this user before
	$query = "SELECT * FROM Ratings WHERE song_id='$song_id' AND user_id='$user_id';";
	$result = mysql_query($query);

	# if so, update, otherwise insert a new row into Plays
	if($row = mysql_fetch_array($result)) {
		$update = "UPDATE Ratings SET rating = '$rating' WHERE song_id='$song_id' AND user_id='$user_id';";
		mysql_query($update);
	} else {
		$insert = "INSERT INTO Ratings(song_id, user_id, rating) VALUES('$song_id','$user_id', '$rating');";
		mysql_query($insert);
	}

	# and add an event object
	$event = "INSERT INTO Events(song_id, user_id, etype) VALUES('$song_id', '$user_id', 'rating')";
	mysql_query($event);

	# return the song_id and the updated play count
	$query = "SELECT song_id, plays FROM Plays WHERE song_id='$song_id' AND user_id='$user_id';";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	echo json_encode(array("song_id" => $row[0], "updated_play_count" => $row[1]));
?>
