<?php 
include('classes/db.php');
include('classes/Login.php');

if(Login::isLoggedIn()){
	$userid = Login::isLoggedIn();
}else{
	echo "not logged in";
	die;
}

if(isset($_POST['send'])){

	$_GET['reciever'] = DB::query("SELECT id FROM users WHERE username=:username", array(":username"=>$_POST['username']))[0]['id'];

	if(DB::query("SELECT id FROM users WHERE id = :reciever",array(":reciever"=>$_GET['reciever']))){

		DB::query("INSERT INTO messages VALUES ('', :body, :sender, :reciever, 0)", array(":body"=>$_POST['body'], ":sender"=>$userid, ":reciever"=>htmlspecialchars($_GET['reciever'])));
		
		DB::query("INSERT INTO notification VALUES ('', :type, :reciever, :sender, :extra)", array(":type"=>4, ":reciever"=>$_GET['reciever'], ":sender"=>$userid, ":extra"=>""));

	}else{
		echo "User doesn't exist";
		die;
	}
}
?>
<h1>Message</h1>
<p>beta!New!</p>
<form method="post" action="send-message.php">
	<input type="text" name="username" placeholder="Whom to send?"><br>
	<textarea name="body" rows="3" cols="50" class="form-control"></textarea>
	<br>
	<input type="submit" class="btn btn-secondary" name="send" value="Send Message">
</form>