<?php
class Post{

	public static function MakePost($postbody, $loggedinuserid, $userid){

			if(strlen($postbody) > 160 || strlen($postbody) < 1){
				die("Incorrect lenght!");
			}

			$topics = self::getTopics($postbody);	

			if($loggedinuserid == $userid){

				if(count(Notify::MakeNotify($postbody)) != 0){
					foreach (Notify::MakeNotify($postbody) as $key => $n) {
						$s = $loggedinuserid;
						$r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];
						if($r != 0){
							DB::query('INSERT INTO notification VALUES (\'\', :type, :reciever, :sender, :extra)', array(':type'=>$n['type'], ':reciever'=>$r, ':sender'=>$s, ":extra"=>$n['extra']));
						}
					}
				}

			DB::query('INSERT INTO posts VALUES (\'\', :userid, 0, :body, NOW(), :topics)', array(':userid'=>$userid, ':body'=>$postbody, ":topics"=>$topics));
			}else{
				die("Incorrect user!");
			}
	}

	public static function LikePost($postid, $loggedinuserid, $userid){
			if(DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid ', array(':postid'=>$postid, ':userid'=>$loggedinuserid))){
				DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array(':postid'=>$postid));
			DB::query('DELETE FROM post_likes WHERE user_id=:userid AND post_id=:postid', array(':postid'=>$postid, ':userid'=>$loggedinuserid));
			}else{
			DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array(':postid'=>$postid));
			DB::query('INSERT INTO post_likes VALUES (\'\', :postid, :userid)', array(':postid'=>$postid, ':userid'=>$loggedinuserid));
			DB::query("INSERT INTO notification VALUES ('', :type, :reciever, :sender, :extra)", array(":type"=>2, ":reciever"=>$userid, ":sender"=>$loggedinuserid, "extra"=>""));
			}
	}


	public static function getTopics($text){
			$topics = "";
			$text = explode(" ", $text);
			foreach ($text as $word) {
				if(substr($word, 0, 1) == "#"){
					$topics .= substr($word, 1).","; 
				}
			}
			return $topics;
		}


	public static function link_add($text){

		$text = explode(" ", $text);
		$newstring = "";
		foreach ($text as $word) {
			if(substr($word, 0, 1) == "@"){
				$newstring .= "<a href='profile.php?username=".substr($word, 1)."'>".htmlspecialchars($word)."</a> ";
			}elseif(substr($word, 0, 1) == "#"){
				$newstring .= "<a href='topics.php?topic=".substr($word, 1)."'>".htmlspecialchars($word)."</a> ";
			}else{
			$newstring .= htmlspecialchars($word)." ";
			}
		}

		return $newstring;
	}

	public static function displayPosts($userid, $username, $loggedinuserid){
		$dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC', array(':userid'=>$userid));
		$posts = "";
		foreach ($dbposts as $p) {

			if(!DB::query('SELECT user_id FROM post_likes WHERE user_id=:userid AND post_id=:postid', array(':postid'=>$p['id'], ':userid'=>$loggedinuserid))){

			//post and like button	
			$posts .= "<div class='hero-body'><div class='container '><h1 class='title'>".self::link_add($p['body'])."</h1><form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
			<input type='submit' name='like' class='button is-info' value='Like'>";
			if($userid == $loggedinuserid){
			$posts .= "<span><a href='likes.php?id=".$p['id']."'>".$p['likes']." Likes</a></span>";
			}else{
				$posts .= "<span>".$p['likes']." Likes</span>";
			}
			if($userid == $loggedinuserid){
				$posts .= " <input type='submit' class='button is-light' name='deletepost' value='x'>";
			}
			$posts .= "</form>";
			$posts .= "<a href='posts.php?id=".$p['id']."'>Comments</a>";
			$posts .= "</div></div><br>";

			}else{
				$posts .= "<div class='hero-body'><div class='container '><h1 class='title'>".self::link_add($p['body'])."</h1><form action='profile.php?username=$username&postid=".$p['id']."' method='post'>
			<input type='submit' name='like' class='button is-primary' value='Unlike'>
			<span> ".$p['likes']." Likes</span>";
			if($userid == $loggedinuserid){
				$posts .= " <input type='submit' class='button is-light' name='deletepost' value='X'>";
			}
			$posts .= "</form>";
			$posts .= "<a href='posts.php?id=".$p['id']."'>Comment</a>";
			$posts .= "</div></div><br>";
			}

		}
		return $posts;
	}

}

 ?>
