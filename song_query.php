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
	if(!mysql_connect("cs445sql", "motsuka", "EL611mot"))
		die ("Couldn't connect to mysql server!<br>");
	if(!mysql_select_db("gow"))
		die ("Couldn't select a database!<br>");

	# read in the request parameters
	$replace = str_replace("+", " ", $_POST["search"]);
	$replace = str_replace("\"", "", $replace);
	$split = explode("=", $replace);
	$song = explode("&", $split[1]);
	$song = $song[0];
	$artist = explode("&", $split[2]);
	$artist = $artist[0];
	$album = explode("&", $split[3]);
	$album = $album[0];
	$lowerYear = explode("&", $split[4]);
	$lowerYear = $lowerYear[0];
	$upperYear = explode("&", $split[5]);
	$upperYear = $upperYear[0];

	if($lowerYear == ""){
		$lowerYear = 1800;
	}
	if($upperYear == ""){
		$upperYear = 2200;
	}

	$result = "<h2 class='sub-header'>Search Results</h2>";

	$songQuery = "SELECT DISTINCT s.title, a.name, al.name, s.duration, s.year, s.song_id FROM Songs s, Albums al, Artists a WHERE (1=1";
		# "s.title LIKE '%".$song."%' OR a.name LIKE '%".$artist."%' OR al.name LIKE '%".$album."%'"
	if($song != ""){
		$songQuery = $songQuery . " AND s.title LIKE '%".$song."%'";
	}
	if($artist != ""){
		$songQuery = $songQuery . " AND a.name LIKE '%".$artist."%'";
	}
	if($album != ""){
		$songQuery = $songQuery . " AND al.name LIKE '%".$album."%'";
	}
	$songQuery = $songQuery . ") AND s.year BETWEEN '$lowerYear' AND '$upperYear' AND s.album_id=al.album_id AND s.artist_id=a.artist_id LIMIT 50;";

	$songResult = mysql_query($songQuery);
	$result = $result . "<div class='table-responsive'>
	<table class='table table-striped'><thead>
		<tr>
			<th>Name</th>
			<th>Artist</th>
			<th>Album</th>
			<th>Duration</th>
			<th>Year</th>
			<th class='col-sm-1'>Rate</th>
			<th></th>
		</tr>
	</thead><tbody>";
	$songRow = mysql_fetch_array($songResult);
	while($songRow != ""){
		if($songRow[3]%60 < 10){
			$zero = "0";
		}else{
			$zero = "";
		}
		$result = $result . "<tr>
				<td>". $songRow[0] ."</td>
				<td>". $songRow[1] ."</td>
				<td>". $songRow[2] ."</td>
				<td>". floor($songRow[3]/60) . ":" . $zero . $songRow[3]%60 ."</td>
				<td>". $songRow[4] ."</td>
				<td>". "<form role='form'>
				  <div class='form-group'>
					  <select class='rating' id='$songRow[5]'>
						  <option>none</option>
						  <option>1</option>
						  <option>2</option>
						  <option>3</option>
						  <option>4</option>
						  <option>5</option>
						</select>
						</div>
					</form>" ."</td>
				<td>". "<button type='button' class='btn btn-primary' id='$songRow[5]' onClick='playTheSong(this)'>Play Song</button>" ."</td>
		</tr>";
		$songRow = mysql_fetch_array($songResult);
	}

	$result = $result . "</tbody></table></div><script>$('.rating').change(function(event){
				var id = event.target.id;
				var rate = event.target.value;
				if(rate != 'none'){
					$.ajax({type: 'POST',
						url: 'updateRating.php',
						data: {song_id: id, rating: rate},
						success: function(data){

						}
					});
				}
		});</script>";

	echo $result;

?>