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
	if(!mysql_connect("cs445sql", "********", "********"))
		die ("Couldn't connect to mysql server!<br>");
	if(!mysql_select_db("gow"))
		die ("Couldn't select a database!<br>");

	$current_user_id = $_COOKIE["user_id"];

	# read in the request parameters
	$replace = str_replace("+", " ", $_POST["search"]);
	$replace = str_replace("\"", "", $replace);
	$split = explode("=", $replace);
	$user_id = explode("&", $split[1]);
	$user_id = $user_id[0];
	$username = explode("&", $split[2]);
	$username = $username[0];
	$min_age = explode("&", $split[3]);
	$min_age = is_numeric($min_age[0]) ? (int)$min_age[0] : 13;
	$max_age = explode("&", $split[4]);
	$max_age = is_numeric($min_age[0]) ? (int)$min_age[0] : 99;
	$gender = explode("&", $split[5]);
	$gender = $gender[0];
	$location = explode("&", $split[6]);
	$location = $location[0];

	$response = "<h2 class='sub-header'>Search Results</h2>";
	$response .= "<div class='table-responsive'>
		<table class='table table-striped'><thead>
		<tr>
			<th>User ID</th>
			<th>Name</th>
			<th>Age</th>
			<th>Gender</th>
			<th>Location</th>
			<th>Follow/Unfollow</th>
		</tr>
		</thead><tbody>";

	$query = "SELECT user_id, username, age, gender, location FROM Users WHERE (1=1";
	if($user_id != "")
		$query .= " AND user_id LIKE '%".$user_id."%'";
	if($username != "")
		$query .= " AND username LIKE '%".$username."%'";
	if($min_age > 13)
		$query .= " AND age >=$min_age";
	if($max_age < 99)
		$query .= " AND age <=$max_age";
	if($gender == "male" or $gender == "female")
		$query .= " AND gender='$gender'";
	if($location != "")
		$query .= " AND location LIKE '%".$location."%'";
	$query .= ") LIMIT 50;";

	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);
	while($row != ""){
		$response .= "<tr>
				<td>". $row[0] ."</td>
				<td>". $row[1] ."</td>
				<td>". $row[2] ."</td>
				<td>". $row[3] ."</td>
				<td>". $row[4] ."</td>";
		$is_follower = mysql_query("SELECT * FROM Followers where follower='$current_user_id' AND leader='".$row[0]."';") or die(mysql_error());
		$num_rows = mysql_num_rows($is_follower);
		if($num_rows > 0)
			$response .= "<td><button type='button' class='btn btn-danger' id='$row[0]' onClick='toggleFollow(this)'>Unfollow</button></td></tr>";
		else
			$response .= "<td><button type='button' class='btn btn-primary' id='$row[0]' onClick='toggleFollow(this)'>Follow</button></td></tr>";
		$row = mysql_fetch_array($result);
	}

	$response .= "</tbody></table></div>";

	echo $response;

?>
