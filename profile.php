<?php
include('classes/db.php');
include('classes/Login.php');
include('classes/Post.php');
include('classes/notify.php');
include('classes/Comment.php');
$username = "";
$isFollowing = "";
$followCheck = "";
$isVerified = False;
$posts = "";
$message = "";
if(Login::isLoggedIn()){
if(isset($_GET['username'])){
	if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){

		$username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
		$userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
		$followerid = Login::isLoggedIn();
		$followCheck = DB::query('SELECT user_id FROM followers WHERE follower_id=:followerid AND user_id=:userid', array(':userid'=>$userid, ':followerid'=>$followerid));

		if(!$followCheck){
			$isFollowing = False;
		}else{
			$isFollowing = True;
		}

		if(DB::query('SELECT * FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>'10'))){
			$isVerified = True;
		}

		if(isset($_POST['follow'])){
				//$followCheck = DB::query('SELECT user_id FROM followers WHERE follower_id=:userid', array(':userid'=>$followerid));
				if(!$followCheck){
					DB::query('INSERT INTO followers VALUES (\'\', :userid, :followerid)', array(':userid'=>$userid, ':followerid'=>$followerid));
					$isFollowing = True;
					DB::query("INSERT INTO notification VALUES('', :type, :reciever, :sender, :extra)", array(":type"=>3, ":reciever"=>$userid, ":sender"=>$followerid,":extra"=>""));
				}else{
					DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
					$isFollowing = False;
				}
		}

		if(isset($_POST['unfollow'])){
			if($followerid != $userid){
				//$followCheck = DB::query('SELECT user_id FROM followers WHERE follower_id=:userid', array(':userid'=>$followerid));
					if($followCheck){
						DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$userid, ':followerid'=>$followerid));
						$isFollowing = False;
					}else{
						echo "Not Following";
					}
				}
			}

		if(isset($_POST['post'])){
			Post::MakePost($_POST['postbody'], Login::isLoggedIn(), $userid);
		}

		if(isset($_GET['postid'])){
			if(isset($_POST['deletepost'])){
				if(DB::query("SELECT id FROM posts WHERE id=:postid AND user_id=:user_id", array(':postid'=>$_GET['postid'], ':user_id'=>$followerid))){
					DB::query('DELETE FROM posts WHERE id=:postid AND user_id=:userid', array(':postid'=>$_GET['postid'], ':userid'=>$followerid));
					DB::query('DELETE FROM post_likes WHERE post_id=:postid', array(':postid'=>$_GET['postid']));
					$message = "<div class='alert alert-success alert-dismissiable'><button type='button' class='close' data-dismiss='alert'>&times;</button><strong>Post deleted</strong></div>";
				}
			}else{
			Post::LikePost($_GET['postid'], Login::isLoggedIn(), $userid);
			}		
		}

		$posts = Post::displayPosts($userid, $username, Login::isLoggedIn());

	}else{
		die('User Not Found');
	}
}
}else{
	die('Not logged in!');
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bulma-0.7.0/css/bulma.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <style type="text/css">
  	@import url('https://fonts.googleapis.com/css?family=ABeeZee|Questrial|Ropa+Sans');
  	.title-1{
  		font-size: 60px;
  		font-family: 'ABeeZee', sans-serif;
  	}

  	textarea {
  		resize: none;
  	}
  </style>
</head>

</body>
<div class="columns  is-mobile">
	<div class="column notification is-primary">
    <h1 class="title title-1"><?php echo $username; if($isVerified){echo "<i class='material-icons' style='font-size:40px;'>verified_user</i>";} ?></h1>  
    <div class="column is-pulled-right"> 
    	<p class="control">
    <a href="index.php" class=''>
              Home
            </a>
            <?php if($userid == $followerid){ ?>
            <a class='is-pulled-right is-warning button is-rounded' href="change-password.php">
              Change-Password
            </a>
            <a href="notify.php" class='button is-dark is-rounded' >
              Notification
            </a>
          
			<?php }else{ ?>
			<a href="full-chat.php?username=<?php echo $_GET['username']; ?>" class='is-pulled-right button is-warning is-rounded'>
              Chat
            </a>
            <a href="about.php?username=<?php echo $_GET['username']; ?>" class='button is-info is-rounded' disabled>
              About
            </a>
             <a href='logout.php' class="button is-primary is-inverted">
                Logout
              </a>	
			<?php } ?>
       	</p>
       	</div>
				<br>
				<div class="container">
					<form action="profile.php?username=<?php echo $username; ?>" method="post">
					<?php if($followerid != $userid){
					if(!$isFollowing){
					echo "<input type='submit' name='follow' class='button is-danger is-focused is-medium' value='Follow'>";
					}else{
					echo "<input type='submit' name='follow' class='button is-danger is-focused is-medium' value='Unfollow'>";
					 } }else{
					 	echo "<a class='button is-danger' href='about.php?username=".$_GET['username']."' disabled>Go To About</a>";
					 }?>
				 </form>
				</div>
			</div>
		</div>
<br>
<?php
if(Login::isLoggedIn() == $userid){
?>
	<div class="container-fluid">
<form action="profile.php?username=<?php echo $username ?>" method="post">
	<div class="cloumns">
		<center>
		<div class="column is-one-quarter">
			<label class="label">What's on your mind?</label>
		</div>
		<div class="column is-two-thirds">
			<textarea name="postbody" rows="5" cols="25" class="input is-rounded is-focused is-primary" maxlength="120"></textarea>
		</div>
		<div class="column">
			<input type="submit" class="button is-dark" name="post" value="Post">
		</div>
		</form>
	</div>
	</center>
</div>
<br>
<?php } ?>
</div>
	<?php
	if($posts != ""){
	 echo "<div class='col-lg-3'></div>";		
	 echo "<div class='col-lg-6'>";
	 echo $posts;
	 echo "</div>";
	 echo "<div class='col-lg-3'></div>";
	}else{
	echo "<h2>No posts Made by users!";
}
?>
</form>
   <script type="text/javascript">
        document.getElementById("nav-toggle").addEventListener ("click", toggleNav);
        function toggleNav() {
                var nav = document.getElementById("navbar-menu");
                var className = nav.getAttribute("class");
                if(className == "navbar-menu ") {
                    nav.className = " navbar-menu is-active";
                } else {
                    nav.className = "navbar-menu ";
                }
        }
    </script>
</html>