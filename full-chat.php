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

	if(DB::query("SELECT id FROM users WHERE id = :reciever",array(":reciever"=>$_GET['reciever']))){

		DB::query("INSERT INTO messages VALUES ('', :body, :sender, :reciever, 0)", array(":body"=>$_POST['body'], ":sender"=>$userid, ":reciever"=>htmlspecialchars($_GET['reciever'])));

	}else{
		echo "User doesn't exist";
		die;
	}
}

if(isset($_GET['username'])){
	$otherId = DB::query("SELECT id FROM users WHERE username=:username", array(":username"=>$_GET['username']))[0]['id'];
	$Username = DB::query("SELECT username FROM users WHERE id=:loginId", array(":loginId"=>Login::isLoggedIn()))[0]['username'];
	$messages = DB::query("SELECT * FROM messages WHERE (sender=:sender AND reciever=:reciever) OR (reciever=:sender AND sender=:reciever) ", array(":reciever"=>Login::isLoggedIn(), ":sender"=>$otherId));

	echo "<div class='chatbox'>";
	echo "<div class='chatlogs' id='yourdiv'>";

	foreach ($messages as $message) {
		if($message['sender'] == Login::isLoggedIn()){
			?>
			<div class='chat self'>
			<span class='user-name'><?php echo substr($Username, 0, 1) ?></span>
			<p class='chat-message'><?php echo htmlspecialchars($message['body']); ?></p></div>
			<?php
		}else{
			?>
			<div class='chat friend'>
			<div class='user-name'><?php echo substr($_GET['username'], 0, 1); ?></div>
			<p class='chat-message'><?php echo htmlspecialchars($message['body']);?><p></div>	
			<?php
		}
	}
	echo "</div>";
}



 ?>
 <head>
 	<title>Chat</title>
 	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" type="text/css" href="classes/style.css">
 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
      <script src="//twemoji.maxcdn.com/twemoji.min.js"></script>


 </head>
<body>
	<form method="post" class="chat-form" action="full-chat.php?reciever=<?php echo $otherId; ?>&username=<?php echo $_GET['username']; ?>">
	<textarea name="body" class="form-control"></textarea>
	<br>
	<button type="submit" name="send">Send </button>
</form>
<a href="full-chat.php?username=<?php echo $_GET['username']; ?>" class="button"><i class='material-icons'>refresh</i></a>
<a href="index.php" class='btn btn-link'>Go to timeline!</a>
</div>
</body>


