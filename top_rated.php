<?php
	# check login status first
	if (!isset($_COOKIE["user_id"])){
		header("Location: http://cs445.cs.umass.edu/php-wrapper/gow/index.php");
		exit();
	}

	# connect to the database
	if(!mysql_connect("cs445sql", "motsuka", "EL611mot"))
		die ("Couldn't connect to mysql server!<br>");
	if(!mysql_select_db("gow"))
		die ("Couldn't select a database!<br>");

	# get recent events from the database
	$user_id = $_COOKIE["user_id"];

	$query = "SELECT username FROM Users WHERE user_id='$user_id';";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$username = $row[0];

	$event_play_query = "SELECT * FROM Events WHERE user_id='$user_id' AND etype='play' ORDER BY Events.tstamp DESC LIMIT 10;";
	$event_play_result = mysql_query($event_play_query);
	$event_play_row = mysql_fetch_array($event_play_result);
	$plays = $event_play_row[0];

	$event_ratings_query = "SELECT * FROM Events WHERE user_id='$user_id' AND etype='rating' ORDER BY Events.tstamp DESC LIMIT 10;";
	$event_ratings_result = mysql_query($event_ratings_query);
	$event_ratings_row = mysql_fetch_array($event_ratings_result);
	$ratings = $event_ratings_row[0];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="../../assets/ico/favicon.ico">

		<title>Music Net - Oh Yeah!</title>

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

		<!-- Custom styles for this template -->
		<link href="http://getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet">
	</head>

	<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand">Music Net</a>
				</div>
				<div class="navbar-collapse collapse">
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
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<ul class="nav nav-sidebar">
						<li><a href="profile.php">Profile</a></li>
						<li><a href="song_search.php">Song Search</a></li>
						<li><a href="user_search.php">User Search</a></li>
						<li><a href="top_played.php">Top Played</a></li>
						<li class="active"><a href="#">Top Rated</a></li>
					</ul>
				</div>
				<div class="col-sm-7 col-sm-offset-2 main">
					<div class="row placeholders">
						<form action="" role="form" id="rateForm" class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label">How many results?</label>
								<div class="col-sm-6">
									<input type="text" placeholder="10" class="form-control" name="k" id="k">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-1 col-sm-offset-3">
									<button class='btn btn-primary' type='submit' id='searchBtn'>Show Top Rated Songs</button>
								</div>
								<p>This may take some time</p>
							</div>
						</form>
					</div>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h2 class="sub-header">Top Rated</h2>
					<div id="results"></div>
				</div>
			</div>
		</div>

	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<script>
	$( '#logout' ).on('click', function(){
		$('#logout-form').submit();
	});
	</script>
	</body>
</html>

<script>
$( '#rateForm' ).on('submit', function(elem){
	$('#searchBtn').prop('disabled', true);
	var searchData = $('#rateForm').serialize();
	$.ajax({type: "POST",
			url: "top_rated_helper.php",
			data: {search: JSON.stringify(searchData)},
			success: function(data){
				$('#searchBtn').prop('disabled', false);
				$("#results").html(data);
			}
	});
	elem.preventDefault();
});

// 'Play Song' button
function playTheSong(elem){
	var id = elem.id;
	console.log(id);
	$.ajax({type: "POST",
		url: "play_song.php",
		data: {song_id: id},
		success: function(){
			
		}
	});
}
</script>
