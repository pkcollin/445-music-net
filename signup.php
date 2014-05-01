<?php
	# GET  => signup form
	# POST => insert & redirect to sign in
	if($_SERVER["REQUEST_METHOD"] === "POST"){
		# form validations (this is gonna be long and ugly)
		$form_error = "";
		if($_POST["user_id"] !== "")
			$user_id = trim($_POST["user_id"]);
		else
			$form_error .= "Please enter a User ID<br />";
		if($_POST["password"] !== ""){
			$password = trim($_POST["password"]);
			if($_POST["password_confirm"] === "" or trim($_POST["password_confirm"]) !== $password)
				$form_error .= "Your passwords did not match<br />";
		}else
			$form_error .= "Please enter a password<br />";
		if($_POST["full_name"] !== "")
			$full_name = trim($_POST["full_name"]);
		else
			$form_error .= "Please enter your full name<br />";
		if(is_numeric($_POST["age"])){
			$age = (int) $_POST["age"];
			if($age < 13)
				$form_error .= "You must be 13 years or older to use this site<br />";
			if($age > 99)
				$form_error .= "If you are $age years old, you should not be using this site<br />";
		}else
			$form_error .= "Please enter an age between 13 and 35<br />";
		if($_POST["gender"] === "Male" or $_POST["gender"] === "Female")
			$gender = $_POST["gender"];
		else
			$form_error .= "Please specify a gender<br />";
		if($_POST["location"] !== "")
			$location = trim($_POST["location"]);
		else
			$form_error .= "Please enter your location<br />";

		# connect to the database
		if(!mysql_connect("cs445sql", "motsuka", "EL611mot"))
			die ("Couldn't connect to mysql server!<br>");
		if(!mysql_select_db("gow"))
			die ("Couldn't select a database!<br>");

		# check if the user_id already exists
		$query = "SELECT user_id FROM Users WHERE user_id='$user_id';";
		$result = mysql_query($query);
		if($row = mysql_fetch_array($result))
			$form_error .= "That username is not available<br />";

		# did we pass validations?
		if($form_error === ""){
			$insert = "INSERT INTO Users(user_id,password,username,age,gender,location) VALUES('$user_id','$password','$full_name','$age','$gender','$location');";
			mysql_query($insert);
			header("Location: index.php");
		}
	}
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
<body style="padding-top:0px;">
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
				<a class="navbar-brand" href="index.php">Music Net</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Home</a></li>
					<li><a href="#">Browse</a></li>
					<li><a href="public_top_played.php">Top Played</a></li>
					<li><a href="public_top_rated.php">Top Rated</a></li>
					<li class="active"><a>Sign up</a></li>
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

	<?php #Alert (if any)
	#================================================== ?>
	<?php if(isset($form_error) and $form_error !== "") { ?>
		<div class="alert alert-danger alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<?php echo $form_error; ?>
		</div>
	<?php } ?>

	<?php #Signup Form
	#================================================== ?>
	<div class="col-sm-9 main">
		<div class="col-sm-offset-3">
			<h2 class="sub-header">Greetings!</h2>
			<h4>Please provide us with some information about yourself:</h4>
		</div>
		<form method="post" action="signup.php" class="form-horizontal" role="form">
			<div class="form-group">
				<label for="full_name" class="col-sm-3 control-label">Full name:</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" required="true" name="full_name" <?php if(isset($full_name)) echo "value='$full_name'"; ?>/>
				</div>
			</div>
			<div class="form-group">
				<label for="user_id" class="col-sm-3 control-label">User ID:</label>
				<div class="col-sm-6">
					<input id="user-id" class="form-control" type="text" required="true" name="user_id" <?php if(isset($user_id)) echo "value='$user_id'"; ?>/>
					<span class="text-danger help-inline" id='user-id-check'></span>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-3 control-label">Password:</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" required="true" name="password" />
				</div>
			</div>
			<div class="form-group">
				<label for="password-confirm" class="col-sm-3 control-label">Confirm your password:</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" required="true" name="password_confirm" />
				</div>
			</div>
			<div class="form-group">
				<label for="age" class="col-sm-3 control-label">Age:</label>
				<div class="col-sm-6">
					<input class="form-control" type="number" required="true" min=13 max=99 name="age" value='<?php echo (isset($age) ? $age : 13); ?>'/>
				</div>
			</div>
			<div class="form-group">
				<label for="gender" class="col-sm-3 control-label">Gender:</label>
				<div class="col-sm-6">
					<select class="form-control" required="true" name="gender">
						<option default>Please select a gender</option>
						<option value="Male" <?php if(isset($gender) and $gender === "Male") echo "selected='selected'" ?>>Male</option>
						<option value="Female" <?php if(isset($gender) and $gender === "Female") echo "selected='selected'" ?>>Female</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="location" class="col-sm-3 control-label">Location:</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" required="true" name="location" <?php if(isset($location)) echo "value='$location'"; ?>/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-6">
					<button class="btn btn-primary" type="submit" value="Sign Me Up!">Sign Me Up!</button>
				</div>
			</div>
		</form>
	</div>
</body>
</html>
<script>
	$('#user-id').on('change', function(){
		$.ajax({type: "GET",
			url: "user_id_available.php",
			data: {user_id: $('#user-id').val()},
			success: function(data){
				if(data.available){
					$('#user-id-check').removeClass('text-danger').addClass('text-success');
					$('#user-id-check').html("That username is available!");
				}else{
					$('#user-id-check').removeClass('text-success').addClass('text-danger');
					$('#user-id-check').html("That username is not available...");
				}
			}
		});
	});
</script>