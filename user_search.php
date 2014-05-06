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
					<li><a href="song_search.php">Song Search</a></li>
					<li class="active"><a href="user_search.php">User Search</a></li>
					<li><a href="top_played.php">Top Played</a></li>
					<li><a href="top_rated.php">Top Rated</a></li>
				</ul>
			</div>
			<div class="col-sm-7 col-sm-offset-2 main">
				<h1 class="page-header">What would you like to search for?</h1>

				<div class="row placeholders">
					<form method="get" action="user_query.php" role="form" id="searchForm" class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-3 control-label">User ID</label>
								<div class="col-sm-6">
									<input type="text" placeholder="User ID" class="form-control" name="user_id" id="user_id">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Name</label>
								<div class="col-sm-6">
									<input type="text" placeholder="Name" class="form-control" name="username" id="username">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Minimum Age</label>
								<div class="col-sm-6">
									<input type="number" placeholder="13" class="form-control" min="13" max="99" name="min_age" id="min_age">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Maximum Age</label>
								<div class="col-sm-6">
									<input type="number" placeholder="99" class="form-control" min="13" max="99" name="max_age" id="max_age">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Gender</label>
								<div class="col-sm-6">
									<select class="form-control" required="true" name="gender">
										<option default>Please select a gender</option>
										<option value="Male">Male</option>
										<option value="Female">Female</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Location</label>
								<div class="col-sm-6">
									<input type="text" placeholder="Location" class="form-control" name="location" id="location">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-1 col-sm-offset-3">
									<button class='btn btn-primary' type="submit" id='searchBtn'>Search</button>
								</div>
							</div>
					</form>
				</div>
			</div>
			<div class="col-sm-10 col-sm-offset-2 main">
				<div id="results"></div>
				<div id="mus"></div>
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
	var searchData = $('#searchForm').serialize();
	$.ajax({type: "POST",
		url: "user_query.php",
		data: {search: JSON.stringify(searchData)},
		success: function(data){
			$("#results").html(data);
		}
	});
	elem.preventDefault();
});

// follow/unfollow button
function toggleFollow(elem){
	var lid = elem.getAttribute('id');
	$.ajax({type: "GET",
		url: "toggle_follow.php",
		data: {leader_id: lid},
		success: function(data){
			if(data.following)
				$('#'+lid).addClass("btn-danger").removeClass("btn-primary").html("Unfollow");
			else
				$('#'+lid).addClass("btn-primary").removeClass("btn-danger").html("Follow");
		}
	});
}
</script>
