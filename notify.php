<?php
include('classes/db.php');
include('classes/Login.php');
include('classes/notify.php');
$con = mysqli_connect("localhost", "root", "", "finale");
if(Login::isLoggedIn()){
	//echo "Logged in as " . Login::isLoggedIn() ;
}else{
	header('LOCATION: create-account.php');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
		<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:500i|Roboto+Condensed:700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
		<title>Notifications</title>
		<style>
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
		<h1 class="Title">EverVibe</h1>
<?php	
echo "<h2 class='sub-head'>Notifications</h2><br>";
echo "<h1 style='font-family:arial;'>We have cleared the posts database! Good luck!</h1> <br><br>";
echo "<div class='container-fluid pattern'>";
$userid = Login::isLoggedIn();
$userName = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];

if(DB::query('SELECT * FROM notification WHERE reciever=:userid', array(':userid'=>$userid))){
	$notifications = DB::query('SELECT * FROM notification WHERE reciever=:userid ORDER BY id DESC', array(':userid'=>$userid));	

	foreach ($notifications as $n) {

		if($n['type'] == 1){
			$senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(":senderid"=>$n['sender']))[0]['username'];
			if($n['extra'] == "") {
				echo "You got a notification<hr>";
			}else{
			$extra = (json_decode($n['extra'], true));
			echo "<b><a href='profile.php?username=$senderName'>".$senderName . "</a></b> mentioned you in a post - ". $extra['postbody'] . "<hr>";
			}
	} elseif($n['type'] == 2){
		 $senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(":senderid"=>$n['sender']))[0]['username'];
		 echo "<b><a href='profile.php?username=$senderName'>".$senderName."</a></b> liked your post!<hr>";
	}elseif($n['type'] == 3){
		$senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(":senderid"=>$n['sender']))[0]['username'];
		echo "<b><a href='profile.php?username=$senderName'>".$senderName."</a></b> started following you!<hr>";	
	}elseif ($n['type'] == 4) {
		$senderName = DB::query('SELECT username FROM users WHERE id=:senderid', array(":senderid"=>$n['sender']))[0]['username'];
		echo "<b><a href='profile.php?username=$senderName'>".$senderName."</a></b> sent you a message! see it <a href='full-chat.php?username=".$senderName."'> here </a> <hr>";
	}
}
}
echo "</div>";
?>

		<a href="index.php" class="btn btn-block" style="background-color: #4681b4;color:white;">Go to timeline!</a>
	</body>
</html>
