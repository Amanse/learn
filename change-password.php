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
<h1>Change Password</h1>
<form action="change-password.php" method="post">
	<input type="password" name="oldpassword" placeholder="Current Password...."><p />
	<input type="password" name="newpassword" placeholder="New Password...."><p />
	<input type="password" name="newpasswordre" placeholder="Repeat Password...."><p />
	<input type="submit" name="changepassword" value="Change Passowrd">
</form>