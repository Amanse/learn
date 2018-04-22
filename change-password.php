 <?php
include('classes/db.php');
include('classes/Login.php');
function password_verif($Logpassword, $dataPass){
	if($Logpassword == $dataPass){
		return TRUE;
	}else{
		return FALSE;
	}
}

if(Login::isLoggedIn()){
	if(isset($_POST['changepassword'])){
		$oldpassword = sha1($_POST['oldpassword']);
		$newpassword = sha1($_POST['newpassword']);
		$newpasswordre = sha1($_POST['newpasswordre']);
		$user_id = Login::isLoggedIn();

		if(password_verif($oldpassword, DB::query('SELECT password FROM users WHERE id=:user_id', array(':user_id'=>$user_id))[0]['password'])){

			if($newpasswordre == $newpassword){

				if(strlen($newpassword) >= 6 && strlen($newpassword) <= 60){

					DB::query('UPDATE users SET password=:newpassword WHERE id=:user_id', array(':newpassword'=>($newpassword), ':user_id'=>Login::isLoggedIn()));
					echo "password changed";

				}else{
					echo "Invalid password";
				}
			}else{
				echo "Passwords don't match";
			}

		}else{
			echo "incorrect old password";
		}
	}
}else{
	die("Not logged in");
}
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 	<title>Change password</title>
 	<script src="http://code.jquery.com/jquery-3.3.1.js"></script>
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
 </head>
 <body>
 
 </body>
 </html>
<h1>Change Password</h1>
<form action="change-password.php" method="post">
	<input type="password" name="oldpassword" placeholder="Current Password...."><p />
	<input type="password" onkeyup="checkPass()" class="newPass" name="newpassword" id='newpassword' placeholder="New Password...."><p />
	<input type="password" onkeyup="checkPass()" class="newPass" name="newpasswordre" id='newpasswordre' placeholder="Repeat Password...."><p />
	<input type="submit" name="changepassword" id='submit' value="Change Password">
</form>
<script>
	$('#submit').attr('disabled', 'disabled');

	function checkPassSuper(){	
		if($('#newpassword').val() == $('#newpasswordre').val()){
			return true;
		}else if($('#newpassword').val() != $('#newpasswordre').val()){
			return false;
		}
	}	

	function checkPass(){
		if(checkPassSuper()){
			$('.newPass').css('color', 'green');
			$('#submit').removeAttr('disabled', 'disabled');
		}else {
			$('.newPass').css('color', 'red');
			$('#submit').attr('disabled', 'disabled');
		}
	}
</script>