<?php
include('classes/db.php');
include('classes/Login.php');
include('classes/Post.php');
include('classes/notify.php');

if(isset($_GET['topic'])){

	echo "<h1 class='title'>EverVibe</h1>";
	echo "<h2 class='sub-head'>All posts with #" . $_GET['topic'] . "</h2>";
	echo "<div class='container-fluid pattern'>";

	if(DB::query("SELECT topics FROM posts WHERE FIND_IN_SET(:topic, topics)", array(":topic"=>$_GET['topic']))){

		$posts = DB::query("SELECT * FROM posts WHERE FIND_IN_SET(:topic, topics)", array(":topic"=>$_GET['topic']));

		foreach ($posts as $post) {
			$username = DB::query("SELECT username FROM users WHERE id=:id", array(":id"=>$post['user_id']))[0]['username'];
			//echo "<pre>";
			//print_r($post);
			//echo "</pre>";
			echo $post['body'] . " - <a href='profile.php?username=" . $username . "'>".$username."</a><hr>";
		}


	}

}
?>
<!DOCTYPE html>
<html>
<head>
	 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:500i|Roboto+Condensed:700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
	<title><?php echo $_GET['topic'] ?></title>
	<style type="text/css">
		.Title{
			font-family: 'IBM PLex sans', sans-serif; 
			text-align: center;
			background-color: #325c81;
			color: white;
			font-size: 50px;
			margin:0;
		}			
		.sub-head{
			font-family: 'PT sans', sans-serif;
			text-align: center;
			background-color: #3c6e9a;
			color:white;
		}

		.pattern{
			background-image: url("icons/gaming-pattern.png");
		}
	</style>
</head>
<body>
	<a href="index.php" class="btn btn-block" style="background-color: #4681b4;color:white;">Go to timeline!</a>
</body>
</html>