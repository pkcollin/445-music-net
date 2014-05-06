<?php
	# Description:
	# this script is purely for servicing AJAX requests to search

	# check login status first
	if(!isset($_COOKIE["user_id"])){
		header( $_SERVER['SERVER_PROTOCOL']." 404 Not Found", true, 404 );
		echo "<h1>404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}

	# connect to the database
	$db_connection = mysql_pconnect("cs445sql", "********", "********");
	if(!$db_connection)
		die ("Couldn't connect to mysql server!<br>");
	if(!mysql_select_db("gow", $db_connection))
		die ("Couldn't select a database!<br>");

	# read in the request parameters
	$replace = str_replace("+", " ", $_POST["search"]);
	$replace = str_replace("\"", "", $replace);
	$split = explode("=", $replace);
	$k = is_numeric($split[1]) ? (int) $split[1] : 10;

	$response = "";

	# $play_query = "SELECT s.title, a.name, al.name, s.duration, s.year, sum(p.plays), s.song_id FROM Songs s, Artists a, Albums al, Plays p WHERE s.song_id=p.song_id AND s.artist_id=a.artist_id AND s.album_id=al.album_id GROUP BY p.song_id ORDER BY sum(p.plays) DESC LIMIT ". $k .";";

	# this query takes FOREVER to run
	$temp_query = "CREATE TEMPORARY TABLE temp SELECT song_id, sum(plays) AS sum_plays FROM Plays GROUP BY song_id;";
	$temp_result = mysql_query($temp_query, $db_connection) or die($temp_query."<br/>".mysql_error());

	$play_query = "SELECT song_id, sum_plays FROM temp ORDER BY sum_plays DESC LIMIT $k;";
	$play_result = mysql_query($play_query, $db_connection) or die($play_query."<br/>".mysql_error());

	$response .= "<div class='table-responsive'>
	<table class='table table-striped'><thead>
		<tr>
			<th>Name</th>
			<th>Artist</th>
			<th>Album</th>
			<th>Duration</th>
			<th>Year</th>
			<th>Plays</th>
			<th></th>
		</tr>
	</thead><tbody>";
	$play_row = mysql_fetch_array($play_result);
	while($play_row != ""){
		$song_id = $play_row[0];
		$song_query = "SELECT title, Artists.name, Albums.name, duration, year FROM Songs NATURAL JOIN Artists INNER JOIN Albums ON Albums.album_id=Songs.album_id WHERE song_id='$song_id'";
		$song_result = mysql_query($song_query);
		$song_row = mysql_fetch_array($song_result);
		$zero = ($song_row[3]%60 < 10) ? "0" : "";
		$response .= "<tr>
				<td>". $song_row[0] ."</td>
				<td>". $song_row[1] ."</td>
				<td>". $song_row[2] ."</td>
				<td>". floor($song_row[3]/60) . ":" . $zero . $song_row[3]%60 ."</td>
				<td>". $song_row[4] ."</td>
				<td>". $play_row[1] ."</td>
				<td>". "<button type='button' class='btn btn-primary' id='$play_row[0]' onClick='playTheSong(this)'>Play Song</button>" ."</td>
		</tr>";
		$play_row = mysql_fetch_array($play_result);
	}

	echo $response;

?>