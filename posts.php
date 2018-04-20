<?php 
include('classes/db.php');
include('classes/Login.php');
include('classes/Post.php');
include('classes/Comment.php');

if(isset($_GET['id'])){
	$posts = DB::query("SELECT * FROM posts WHERE id=:id", array(":id"=>$_GET['id']));

	foreach ($posts as $post) {
		echo "<h1 class='title'>".$post['body']."</h1>";
		echo "<br>";
		Comment::displayComments($_GET['id']);
	}

	$username = DB::query("SELECT username FROM users WHERE id=:id", array(":id"=>Login::isLoggedIn()))[0]['username'];
	echo "<br>";
	echo "<a href='profile.php?username=".$username."'>Back to profile</a>";
}
?>
<!DOCTYPE html>
<html>
<head>
	 <meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
	<title>Comments</title>
</head>
<body>

</body>
</html>