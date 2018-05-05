<?php
class Comment{
	public static function MakeComment($commentbody, $postid, $userid){
			
			if(strlen($commentbody) > 160 || strlen($commentbody) < 1){
				die("Incorrect lenght!");
			}
	
			if(DB::query('SELECT id FROM posts WHERE id=:postid', array(':postid'=>$postid))){
				DB::query('INSERT INTO comments VALUES (\'\', :comment, :userid, NOW(), :postid)', array(':comment'=>$commentbody, ':userid'=>$userid, 'postid'=>$postid));
				$extra = ' { "Comment" : "'.htmlentities($commentbody).'"} ';
				$r = DB::query("SELECT user_id FROM posts WHERE id=:id", array(":id"=>$postid))[0]['user_id'];
				DB::query("INSERT INTO notification VALUES ('', :type, :reciever, :sender, :extra)", array(':type'=>5, ':reciever'=>$r, ':sender'=>$userid, ':extra'=>$extra));
			}else{
				echo "Invalid Post id";
			}

	}

	public static function displayComments($postid){

		$comments = DB::query('SELECT comments.comment, users.username FROM comments, users WHERE post_id = :postid AND comments.user_id = users.id ORDER BY comments.id DESC  ', array(':postid'=>$postid));
		 
		 foreach ($comments as $comment ) {
		 	echo "<div id='AllC'>";
		  	echo "<div class=' notification is-info comments'>".$comment['comment']." <div class='is-danger' style='padding:5px;'> ~<small class='username'><a href='profile.php?username=".$comment['username']."'>".$comment['username']."</a></small></div></div><br>";
		  	echo "</div>";
		  } 
		 // echo "<button type='button' id='ShowComments' class='light'>Show/Hide Comments</button>";
		  
	}	
}

?>