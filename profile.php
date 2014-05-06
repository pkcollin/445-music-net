<?php
	# check login status first
	if (!isset($_COOKIE["user_id"])){
		header("Location: http://cs445.cs.umass.edu/php-wrapper/gow/index.php");
		exit();
	}

	# connect to the database
	if(!mysql_connect("cs445sql", "********", "********"))
		die ("Couldn't connect to mysql server!<br>");
	if(!mysql_select_db("gow"))
		die ("Couldn't select a database!<br>");

	# lookup user in database
	$user_id = $_COOKIE["user_id"];
	$query = "SELECT username FROM Users WHERE user_id='$user_id';";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$username = $row[0];

	# get recent events from the database to display in news feed
	$event_play_query = "SELECT Events.user_id, Events.song_id, Events.tstamp FROM Events INNER JOIN Followers ON Followers.leader=Events.user_id WHERE follower='$user_id' AND etype='play' ORDER BY Events.tstamp DESC LIMIT 10;";
	$event_play_result = mysql_query($event_play_query);
	$event_play_row = mysql_fetch_array($event_play_result);
	$plays = $event_play_row[0];

	$event_ratings_query = "SELECT Events.user_id, Events.song_id, Events.tstamp FROM Events INNER JOIN Followers ON Followers.leader=Events.user_id WHERE follower='$user_id' AND etype='rating' ORDER BY Events.tstamp DESC LIMIT 10;";
	$event_ratings_result = mysql_query($event_ratings_query);
	$event_ratings_row = mysql_fetch_array($event_ratings_result);
	$ratings = $event_ratings_row[0];
?>

<!DOCTYPE html>
<?php # Everybody likes JQuery! ?>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>

<?php # Bootstrap core CSS ?>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<?php # Custom styles for this template ?>
<link href="http://getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet">
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="../../assets/ico/favicon.ico">
	<title>Music Net - Oh Yeah!</title>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand">Music Net</a>
			</div>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="admin.php" id="admin">Admin Page</a></li>
				<li><a href="profile.php">Logged in as <?php echo $username; ?></a></li>
				<li><a href="#" id="logout">Logout</a></li>
				<form id="logout-form" method="post" action="index.php">
					<input type="hidden" name="deletecookie" value="true" />
				</form>
			</ul>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">
					<li class="active"><a href="#">Profile</a></li>
					<li><a href="song_search.php">Song Search</a></li>
					<li><a href="user_search.php">User Search</a></li>
					<li><a href="top_played.php">Top Played</a></li>
					<li><a href="top_rated.php">Top Rated</a></li>
				</ul>
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<h2 class="sub-header">Recently Played</h2>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>User</th>
								<th>Song</th>
								<th>Artist</th>
								<th>Play Count</th>
								<th>Time</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								for($i = 0; $i < 10; $i++){
									if($event_play_row[0] == "")
										break;
									$plays_query = "SELECT plays FROM Plays WHERE user_id='$event_play_row[0]' and song_id='$event_play_row[1]' LIMIT 1";
									$plays_result = mysql_query($plays_query);
									$plays_row = mysql_fetch_array($plays_result);

									$songQuery = "SELECT * FROM Songs WHERE song_id='$event_play_row[1]' LIMIT 1;";
									$songResult = mysql_query($songQuery);
									$songRow = mysql_fetch_array($songResult);

									$artistQuery = "SELECT * FROM Artists WHERE artist_id='$songRow[6]' LIMIT 1;";
									$artistResult = mysql_query($artistQuery);
									$artistRow = mysql_fetch_array($artistResult);

									$userQuery = "SELECT * FROM Users WHERE user_id='$event_play_row[0]' LIMIT 1;";
									$userResult = mysql_query($userQuery);
									$userRow = mysql_fetch_array($userResult);

									echo "<tr>
									<td>". $userRow[1] ."</td>
									<td>". $songRow[3] ."</td>
									<td>". $artistRow[1] ."</td>
									<td>". $plays_row[0] ."</td>
									<td>". $event_play_row[2] ."</td>
									</tr>";

									$event_play_row = mysql_fetch_array($event_play_result);
								}
							?>
						</tbody>
					</table>
					<h2 class="sub-header">Recently Rated</h2>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>User</th>
								<th>Song</th>
								<th>Artist</th>
								<th>Rating</th>
								<th>Time</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								for($i = 0; $i < 10; $i++){
									if($event_ratings_row[0] == "")
										break;
									$ratings_query = "SELECT rating FROM Ratings WHERE user_id='$event_ratings_row[0]' and song_id='$event_ratings_row[1]' LIMIT 1";
									$ratings_result = mysql_query($ratings_query);
									$ratings_row = mysql_fetch_array($ratings_result);

									$songQuery = "SELECT * FROM Songs WHERE song_id='$event_ratings_row[1]' LIMIT 1;";
									$songResult = mysql_query($songQuery);
									$songRow = mysql_fetch_array($songResult);

									$artistQuery = "SELECT * FROM Artists WHERE artist_id='$songRow[6]' LIMIT 1;";
									$artistResult = mysql_query($artistQuery);
									$artistRow = mysql_fetch_array($artistResult);

									$userQuery = "SELECT * FROM Users WHERE user_id='$event_ratings_row[0]' LIMIT 1;";
									$userResult = mysql_query($userQuery);
									$userRow = mysql_fetch_array($userResult);

									echo "<tr>
									<td>". $userRow[1] ."</td>
									<td>". $songRow[3] ."</td>
									<td>". $artistRow[1] ."</td>
									<td>". $ratings_row[0] ."</td>
									<td>". $event_ratings_row[2] ."</td>
									</tr>";
									$event_ratings_row = mysql_fetch_array($event_ratings_result);
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script>
$( '#logout' ).on('click', function(){
	$('#logout-form').submit();
});
</script>
