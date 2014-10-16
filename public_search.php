
<!DOCTYPE html>
<?php # Everybody likes JQuery! ?>

<!-- 'I'm a huge faggot' --->
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
<body style="padding-top:0px;">
	<div class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom:0px;">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Music Net</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Home</a></li>
					<li class="active"><a href="#">Browse</a></li>
					<li><a href="public_top_played.php">Top Played</a></li>
					<li><a href="public_top_rated.php">Top Rated</a></li>
					<li><a gref="signup.php">Sign up</a></li>
				</ul>
				<form method="post" action="index.php" class="navbar-form navbar-right" role="form">
					<div class="form-group">
						<input type="text" required="true" placeholder="Username" class="form-control" name="user_id">
					</div>
					<div class="form-group">
						<input type="password" required="true" placeholder="Password" class="form-control" name="password">
					</div>
					<input type="submit" class='btn btn-primary' value="Submit">
				</form>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-7 col-sm-offset-1 main">
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
			<div class="col-sm-12 main">
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
	var searchData = $('#searchForm').serialize();
	$.ajax({type: "POST",
			url: "public_song_query.php",
			data: {search: JSON.stringify(searchData)},
			success: function(data){
				$("#results").html(data);
			}
	});
	elem.preventDefault();
});
</script>
