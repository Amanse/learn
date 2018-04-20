<?php
class Comment{
	public static function MakeComment($commentbody, $postid, $userid){
			
			if(strlen($commentbody) > 160 || strlen($commentbody) < 1){
				die("Incorrect lenght!");
			}
	
			if(DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$postid))){
				DB::query('INSERT INTO comments VALUES (\'\', :comment, :userid, NOW(), :postid)', array(':comment'=>$commentbody, ':userid'=>$userid, 'postid'=>$postid));
			}else{
				echo "Invalid Post id";
			}

	}

	public static function displayComments($postid){

		$comments = DB::query('SELECT comments.comment, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id ORDER BY comments.id DESC LIMIT 2 ', array(':postid'=>$postid));
		 echo "<div id='AllC'>";
		 foreach ($comments as $comment ) {
		  	echo "<div class=' notification is-info comments'>".$comment['comment']." <div class='is-danger' style='padding:5px;'> ~<small class='username'><a href='profile.php?username=".$comment['username']."'>".$comment['username']."</a></small></div></div>";
		  } 
		  echo "</div>";
	}	
}

?>