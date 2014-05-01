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
						<li><a href="public_search.php">Browse</a></li>
						<li><a href="public_top_played.php">Top Played</a></li>
						<li class="active"><a href="public_top_rated.php">Top Rated</a></li>
						<li><a href="signup.php">Sign up</a></li>
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
				<div class="col-sm-12 main">
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
			url: "public_top_rated_helper.php",
			data: {search: JSON.stringify(searchData)},
			success: function(data){
				$('#searchBtn').prop('disabled', false);
				$("#results").html(data);
			}
	});
	elem.preventDefault();
});
</script>
