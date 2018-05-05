<?php
include('classes/db.php');
if(isset($_POST['create-account'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];

	if(!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))){

		if(strlen($username) >= 3 && strlen($username) <= 32) {

			if(strlen($password) >= 6 && strlen($password) <= 60){

			if(preg_match('/[a-zA-Z0-9_]/', $username)){

			if(!DB::query('SELECT email FROM users WHERE email=:email', array(":email"=>$email))){

				DB::query('INSERT INTO `users` VALUES (\'\' ,:username, :password, :email)', array(':username'=>$username, ':password'=>sha1($password), ':email'=>$email));
				echo "Success";
				header("LOCATION: login.php");
			}else{
				echo "Email already exits";
			}

		}else{
			echo "Invalid username";
		}

		}else{
			echo "Invalid Password";
		}

	}else{
		echo "Invalid Username";
	}
}else{
	echo "User already exist";
}
}
?>
<head>
	<meta charset="utf-8">
 	 <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Create-Account</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css?family=ABeeZee|Questrial|Ropa+Sans');

		.heading{
			font-family: 'Ropa Sans', sans-serif;
			font-size: 30px;
		}

		.title {
			font-size: 60px;
		}

		.hero{

		}
	</style>
</head>
<section class="hero is-danger is-bold is-fullheight">
	<div class="hero-body">
		<div class="container is-one-third">
			<h1 class="title" style="font-family: 'ABeeZee', sans-serif; font-size:60px;">EverVibe</h1>
			<h1 class="heading">Create-account</h1>
				<form action="create-account.php" method="post">
					<lable class="lable"><span style="font-family: 'Questrial', sans-serif;">Username</span></lable>
					<input type="text" class="input is-rounded is-primary" name="username" ><br>
					<lable class="lable"><span style="font-family: 'Questrial', sans-serif;">Password</span></lable>
					<input type="password" class="input is-rounded is-primary" name="password" ><br>
					<lable class="lable"><span style="font-family: 'Questrial', sans-serif;">E-mail</span></lable>
					<input type="email" class="input is-rounded is-primary" name="email" ><br>	
					<br>
					<input type="submit" class="button is-info" name="create-account" value="Create-Account">
				</form>
				<a href="login.php">Already have an account?</a>
		</div>
	</div>
</section>
