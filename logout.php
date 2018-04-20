<?php
include('classes/db.php');
include('classes/Login.php');

if(isset($_POST['confirm'])){
	if(isset($_POST['alldevices'])){
		DB::query('DELETE FROM login_tokens WHERE user_id=:user_id', array(':user_id'=>Login::isLoggedIn()));
	}else{
		if(isset($_COOKIE['SNID'])){
		DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
		}
		setcookie('SNID', '1', time()-3600);
		setcookie('SNID_', '1', time()-3600);
	}
}

if(!Login::isLoggedIn()){
	header("LOCATION: login.php");

}



?>
<!DOCTYPE html>
<html>
<head>
	<title>Logout</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
</head>
<body>
	<section class="hero is-primary is-fullheight">
		<div class="hero-body">
			<div class="cloumns">
				<div class="cloumn">
				<h1 class="title">Logout of your account</h1>
				<h1 class="sub-title">Are you sure you wanna logout?</h1>
				</div>
				<div class="column">
					<form action="logout.php" method="post">
						<label class="checkbox">Logout of all devices?
						<input type="checkbox" name="alldevices" value="alldevices">
						</label>
						<br>
						<input type="submit" class="button is-rounded is-danger is-focused" name="confirm" value="Confirm">
					</form>
				</div>
			</div>
		</div>
	</section>
</body>
</html>