<?php 
include('classes/db.php');
include('classes/Login.php');

if(Login::isLoggedIn()){
	$userid = Login::isLoggedIn();
}else{
	echo "not logged in";
	die;
}

if(isset($_GET['mid'])){
	$Message = DB::query("SELECT * FROM messages WHERE id=:mid", array(":mid"=>$_GET['mid']))[0];
	echo "<h1>View message</h1>";
	echo htmlspecialchars($Message['body']);
	echo "<hr>";

	if($Message['sender'] == $userid){
		$id = $Message['reciever'];
	}else{
		$id = $Message['sender'];
	if($Message['reading'] == 0){
		DB::query('UPDATE messages SET reading=1 WHERE id=:mid',array(':mid'=>$Message['id']));
	}
	}


	?>
	<form method="post" action="send-message.php?reciever=<?php echo $id; ?>">
	<textarea name="body" rows="3" cols="50" class="form-control"></textarea>
	<br>
	<input type="submit" class="btn btn-secondary" name="send" value="Send Message">
</form>	
	<?php
}else{

?>
<h1>My messages</h1>

<?php 
$messages = DB::query("SELECT messages.* FROM messages WHERE reciever=:reciever OR sender=:reciever", array(":reciever"=>$userid));
foreach ($messages as $message) {

	$username = DB::query("SELECT username FROM users WHERE id=:sender", array(":sender"=>$message['sender']))[0]['username'];

	if(strlen($message['body']) > 10){
		$m = substr($message['body'], 0, 10) . "...";
	}else{
		$m = $message['body'];
	}


	if($message['reading'] == 0){
		echo "<a href='my-messages.php?mid=".$message['id']."'><strong>".$m ."</strong> </a>- sent by -". $username ."<hr>";
	}else{
		echo "<a href='my-messages.php?mid=".$message['id']."'>".$m ." - </a>sent by -". $username ."<hr>";
	}
	
}
?>
	<h2>See complete chat with </h2>
	<form method="get" action="full-chat.php">
		<input type="text" name="username" placeholder="username">
		<input type="submit" name="See">
	</form>
<?php 
}?>
