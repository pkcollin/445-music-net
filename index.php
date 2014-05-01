<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" and isset($_POST["deletecookie"])) {
	setcookie("user_id", "", time()-3600);
	unset($_COOKIE["user_id"]);
} else if($_SERVER["REQUEST_METHOD"] === "POST"){
	if (isset($_POST["user_id"]) && $_POST["user_id"] != "" && isset($_POST["password"]) && $_POST["password"] != "") {
		# connect to the database
		if(!mysql_connect("cs445sql", "motsuka", "EL611mot"))
			die ("Couldn't connect to mysql server!<br>");
		if(!mysql_select_db("gow"))
			die ("Couldn't select a database!<br>");

		# read in the request parameters
		$user_id = trim($_POST["user_id"]);
		$password = trim($_POST["password"]);

		# find the user
		$query = "SELECT user_id, password, username FROM Users WHERE user_id='$user_id';";
		$result = mysql_query($query);
		if(!$result)
			die(mysql_error());
		if($row = mysql_fetch_array($result)) {
			# found user where user_id = $user_id
			$user = $row[0];
			$pass = $row[1];
			if ($pass == $_POST["password"]) {
				setcookie("user_id", $user, time()+3600);
				$_COOKIE["user_id"] = $user;
			} else
			$login_failure = "Incorrect username/password combination";
		} else {
		# no user where user_id = $user_id
			$login_failure = "Incorrect username/password combination";
		}
	} else {
		$login_failure = "Please enter both a username and password"; 
	}
}
if (isset($_COOKIE["user_id"])) {
	header("Location: http://cs445.cs.umass.edu/php-wrapper/gow/profile.php");
	exit();
} else {
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
<link href="http://getbootstrap.com/examples/carousel/carousel.css" rel="stylesheet">

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
<body style="padding-top:0px; background-image:url(http://wallko.com/wp-content/uploads/2014/04/Music-Background-Colerfull-Speaktrum-690x388.jpg); background-position:center;">
	<?php #NAVBAR
	#================================================== ?>
	<div class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom:0px;">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Music Net</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a>Home</a></li>
					<li><a href="public_search.php">Browse</a></li>
					<li><a href="public_top_played.php">Top Played</a></li>
					<li><a href="public_top_rated.php">Top Rated</a></li>
					<li><a href="signup.php">Sign up</a></li>
				</ul>
				<form method="post" action="index.php" class="navbar-form navbar-right" role="form">
					<?php if(isset($login_failure)) echo "<p style='color: red'>$login_failure</p>"; ?>
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

	<?php #CAROUSEL
	#================================================== ?>
	<div id="myCarousel" class="carousel slide" data-ride="carousel" style="height:100%;">
		<!-- Indicators -->
		<ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
		</ol>
		<div class="carousel-inner">
			<div class="item active">
				<img src="http://bamfbackgrounds.com/upload/za33n4pli0-music.jpg" style="opacity: 0.9;">
				<div class="container">
					<div class="carousel-caption">
						<h1>Welcome to Music Net</h1>
						<p>Music Net is the a social network for sharing music interests among other music enthusiasts.</p>
						<p><a class="btn btn-lg btn-primary" href="signup.php" role="button">Sign up today</a></p>
					</div>
				</div>
			</div>
			<div class="item">
				<img src="http://www.hdwallcloud.com/wp-content/uploads/2013/07/4038468054_50617b28fe_o.jpg" style="opacity: 0.9;">
				<div class="container">
					<div class="carousel-caption">
						<h1>The largest music collection on the web!</h1>
						<p>With over a million songs we sport a large collection of songs to play, share, and enjoy.</p>
						<p><a class="btn btn-lg btn-primary" href="public_search.php" role="button">Browse song collection</a></p>
					</div>
				</div>
			</div>
		</div>
		<a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
		<a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
	</div>
	<?php # END CAROUSEL ?>
</body>
</html>
<?php } ?>
