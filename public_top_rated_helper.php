<?php
	# Description:
	# this script is purely for servicing AJAX requests to search

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

	# $rate_query = "SELECT s.title, a.name, al.name, s.duration, s.year, avg(r.rating), s.song_id FROM Songs s, Artists a, Albums al, Ratings r WHERE s.song_id=r.song_id AND s.artist_id=a.artist_id AND s.album_id=al.album_id GROUP BY r.song_id ORDER BY avg(r.rating) DESC LIMIT ". $k .";";

	# this query takes FOREVER to run
	$temp_query = "CREATE TEMPORARY TABLE temp SELECT song_id, avg(rating) AS avg_rating FROM Ratings GROUP BY song_id;";
	$temp_result = mysql_query($temp_query, $db_connection) or die($temp_query."<br/>".mysql_error());

	$rate_query = "SELECT song_id, avg_rating FROM temp ORDER BY avg_rating DESC LIMIT $k;";
	$rate_result = mysql_query($rate_query, $db_connection) or die($rate_query."<br/>".mysql_error());

	$response .= "<div class='table-responsive'>
	<table class='table table-striped'><thead>
		<tr>
			<th>Name</th>
			<th>Artist</th>
			<th>Album</th>
			<th>Duration</th>
			<th>Year</th>
			<th>Average Rating</th>
			<th></th>
		</tr>
	</thead><tbody>";
	$rate_row = mysql_fetch_array($rate_result);
	while($rate_row != ""){
		$song_id = $rate_row[0];
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
				<td>". $rate_row[1] ."</td>
		</tr>";
		$rate_row = mysql_fetch_array($rate_result);
	}

	echo $response;

?>