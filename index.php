<?php
include('classes/db.php');
include('classes/Login.php');
include('classes/Post.php');
include('classes/Comment.php');
include('classes/notify.php');
$showTimeline = False;
$searchResults = "";
$users= "";
$con = mysqli_connect("localhost", "root", "", "finale");
if(Login::isLoggedIn()){
	$userid = Login::isLoggedIn();
	$userName = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];
	$showTimeline = True;
}else{
	header('LOCATION: create-account.php');
}


if(isset($_GET['postid'])){
	$PosterId = DB::query('SELECT user_id FROM posts WHERE id=:id', array(":id"=>$_GET['postid']))[0]['user_id'];
	Post::LikePost($_GET['postid'], Login::isLoggedIn(), $PosterId);
}

if(isset($_POST['comment'])){
	Comment::MakeComment($_POST['commentbody'], $_GET['postsid'], $userid);
}

if(isset($_POST['search'])){
	$searchQuery = $_POST['searchQuery'];
	$tosearch = explode(" ", $searchQuery);
	if(count($tosearch) == 1){
		$tosearch = str_split($tosearch[0], 2);
	}
	$whereclause = "";
	$paramsarray = array(':username'=>'%'.$searchQuery.'%');
	for ($i = 0; $i < count($tosearch); $i++){
		$whereclause .= " OR username LIKE :u$i";
		$paramsarray[":u$i"] = $tosearch[$i];
	}
	$users = DB::query('SELECT username FROM users WHERE username LIKE :username '.$whereclause.'', $paramsarray);
}

?>

<head>
	 <meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
	<script src="http://code.jquery.com/jquery-3.3.1.js"></script>
<style type="text/css">
	@import url('https://fonts.googleapis.com/css?family=ABeeZee|Questrial|Ropa+Sans');

	.Title {
		font-size: 40px;
		color: black;
		text-align: center;
	}

	.post {
		margin-bottom: 0;
		padding: 0;
		padding-left: 10px;
		padding-bottom: 10px;
	}

	.comments {
		margin-bottom: 0;
		padding: 0;
		padding-left: 15px;
		font-size: 20px;
	}

	.username{
		padding-left: 20px;
		font-size: 14px;
		font-style: none;
	}

	.searchBox {
		width: 25%;

	}

</style>
<title>EverVibe - Homepage</title>
</head>
<body>
<h1 class="title Title is-bold is-info notification" style="font-family: 'ABeeZee', sans-serif; margin-bottom: 0;">EverVibe</h1>
<section style="margin-bottom: 0;" class="hero is-danger">	
	<div class="hero-body">
		<div class="columns">	
			<div class="clomumn">	
			<h1 style="font-size:30px;" >Timeline</h1>
			<h1 class="sub-title">Here you could see all the posts and comments on all the posts made by the people you follow!</h1>
			</div>	
		</div>
		<div class="cloumns">
			<div class="cloumn ">			
					<form action="index.php" method="post">
					<input type="text" name="searchQuery" class='input is-primary is-focused is-rounded searchBox' >		 
					<button type="submit" name="search" class="button is-dark">Search</button>
					</form>  
					<a href="profile.php?username=<?php echo $userName?>" class='button is-warning is-rounded' >My profile</a>
					<a href="logout.php" class="button is-info is-rounded">Logout</a>
					<a href="notify.php" class='button is-primary is-rounded'>Notifications</a>
			</div>
			<div class="cloumn">
			<?php	
				if($users != ""){
					echo "<span class='result'> Results </span><br>";
					echo "<ul style='list-style:none;'>";
				foreach ($users as $u ) {
					echo "<li><a class='results' href='profile.php?username=".$u['username']."'>".$u['username'] . "</a><br></li>";
				}
					echo "</ul>";
				}	
			?>		
			</div>
		</div>
	</div>
</section>
<hr>	
<?php

$followingposts =DB::query("SELECT posts.id, posts.body, posts.likes, users.username FROM users, posts, followers
WHERE posts.user_id = followers.user_id
AND users.id = posts.user_id
AND follower_id='$userid'
ORDER BY posts.posted_at DESC
 ");

//$followingposts .= "SELECT posts.id, posts.body, posts.likes, users.username, posts.posted_at FROM posts, users WHERE users.id='$userid' ORDER BY posts.posted_at DESC";
foreach ($followingposts as $posts) {
	//echo "<div style='background-color: #334'>";
	echo "<div class='container'><b><a href='profile.php?username='". $posts['username'] ."'>". $posts['username'] . "</a></b><div class='hero-body post'><h3 class='Title'>".Post::link_add($posts['body']) ."</div></h3>";
	echo "<form action='index.php?postid=".$posts['id']."' method='post'>";
if(!DB::query('SELECT user_id FROM post_likes WHERE user_id=:userid AND post_id=:postid', array(':postid'=>$posts['id'], ':userid'=>$userid))){
	echo"<input type='submit' name='like' class='button is-info' value='Like'>";
}else{
	echo"<input type='submit' name='like' class='button is-primary' value='Unlike'>";
}
			echo "<span>".$posts['likes']." Likes</span>
			</form>

			<form action='index.php?postsid=".$posts['id']."' method='post'>
			<label for='commentbody' class='label'>Comments</label>
			<textarea name='commentbody' class='input is-small is-rounded is-danger' rows='1' cols='20'></textarea><br>
			<input type='submit' name='comment' class='button is-warning' Value='Comment'>
			</form>";
			//echo "<button type='button' class='btn btn-link' style='color:white' data-toggle='modal' data-target='#myModal'>View all</button>";
		//	echo "<div id='commentsAllShown'></div>";
			Comment::displayComments($posts['id']);
			
			echo "</div>";
			echo "<hr >";
			//echo "</div>";
}



 ?>
</body>