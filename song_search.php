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

	# lookup user in database
	$user_id = $_COOKIE["user_id"];
	$query = "SELECT username FROM Users WHERE user_id='$user_id';";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$username = $row[0];
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
	<title>Music Net</title>
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
					<li><a href="profile.php">Profile</a></li>
					<li class="active"><a href="song_search.php">Song Search</a></li>
					<li><a href="user_search.php">User Search</a></li>
					<li><a href="top_played.php">Top Played</a></li>
					<li><a href="top_rated.php">Top Rated</a></li>
				</ul>
			</div>
			<div class="col-sm-7 col-sm-offset-2 main">
				<h1 class="page-header">What would you like to search for?</h1>

				<div class="row placeholders">
					<form action="" role="form" id="searchForm" class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label">Song</label>
								<div class="col-sm-6">
									<input type="text" placeholder="Song" class="form-control" name="song" id="song">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Artist</label>
								<div class="col-sm-6">
									<input type="text" placeholder="Artist" class="form-control" name="artist" id="artist">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Album</label>
								<div class="col-sm-6">
									<input type="text" placeholder="Album" class="form-control" name="album" id="album">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Lower Year</label>
								<div class="col-sm-6">
									<input type="text" placeholder="1900" class="form-control" name="lowerYear" id="lowerYear">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Upper Year</label>
								<div class="col-sm-6">
									<input type="text" placeholder="2100" class="form-control" name="upperYear" id="upperYear">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-1 col-sm-offset-3">
									<button class='btn btn-primary' type='submit' id='searchBtn'>Search</button>
								</div>
							</div>
					</form>
				</div>
			</div>
			<div class="col-sm-10 col-sm-offset-2 main">
				<div id="results"></div>
				<div id="music"></div>
			</div>
		</div>
	</div>
</body>
</html>
<script>
// 'Logout' button
$( '#logout' ).on('click', function(){
        $('#logout-form').submit();
});

// search form submit
$( '#searchForm' ).off(); // this should theoretically stop it from submitting the form normally, but it doesn't for some reason
$( '#searchForm' ).on('submit', function(elem){
	$('#searchBtn').prop('disabled', true);
	var searchData = $('#searchForm').serialize();
	$.ajax({type: "POST",
			url: "song_query.php",
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
	$.ajax({type: "POST",
		url: "play_song.php",
		data: {song_id: id},
		success: function(){}
	});
}
</script>
