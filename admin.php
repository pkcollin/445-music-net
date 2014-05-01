<?php
	# check login status first
	if (!isset($_COOKIE["user_id"])){
		header("Location: http://cs445.cs.umass.edu/php-wrapper/gow/index.php");
		exit();
	}

	if(isset($_POST['query'])){
		$link=mysqli_connect("cs445sql","dawillia","EL421daw","gow") or die("Error " . mysqli_error($link));
		$result=$link->query($_POST['query']);
		$out=array();
		while($row = $result->fetch_assoc()){
			array_push($out,$row);
		}
		print(json_encode($out));
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
	<title>Music Net - Oh Yeah!</title>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand">Music Net</a>
			</div>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="profile.php">Logged in as <?php echo $username; ?></a></li>
				<li><a href="#" id="logout">Logout</a></li>
				<form id="logout-form" method="post" action="index.php">
					<input type="hidden" name="deletecookie" value="true">
				</form>
			</ul>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">
					<li><a href="index.php">Profile</a></li>
					<li><a href="song_search.php">Song Search</a></li>
					<li><a href="user_search.php">User Search</a></li>
					<li><a href="top_played.php">Top Played</a></li>
					<li><a href="top_rated.php">Top Rated</a></li>
				</ul>
			</div>
			<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
				<h2 class="sub-header">Admin Query Interface</h2>
				<div class="table-responsive">
					<table class="table table-striped">
					<tbody>
						<tr>
							<td><label>Query:</label></td>
							<td><input id='queryTxt' class="form-control" /></td>
							<td><button type='submit' class='btn btn-primary' id='querySbmt'>Submit</button></td>
						</tr>
					</tbody>
					<table class="table table=striped" id="resultTable"></table>
					</table>
				</div>
			</div>
		</div>
	</div>


<script>
var data;
var renderResults=function(data){
	var html='<thead>';var fields={};
	for(k in data[0]){fields[k]=k;}//build key store
	for(k in fields){html+='<th>'+k+'</th>';}html+='</thead><tbody>';//column headers and body open
	for(var i=0;i<data.length;i++){html+='<tr>';//returned row
		for(k in fields){
			html+='<td>'+data[i][k]+'</td>';//field cell
		}html+='</tr>';
	}html+='</tbody>';//end returned row write
	$('#resultTable').html(html);
}
$( '#logout' ).on('click', function(){
	$('#logout-form').submit();
});
$('#querySbmt').click(function(event){
	event.preventDefault();
	$.post('admin.php',{query:$('#queryTxt').val()}).done(function(data){a=JSON.parse(data);renderResults(a);});
});
</script>
</div></body></html>
