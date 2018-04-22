<?php
include('classes/db.php');
function password_verif($Logpassword, $dataPass){
	if($Logpassword == $dataPass){
		return TRUE;
	}else{
		return FALSE;
	}
}
$message = "";
if(isset($_POST['login'])){
	$username =$_POST['username'];
	$password = $_POST['password'];

	if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))){

		if(password_verif(sha1($password), DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])){
			echo "Logged in";
			$cstrong = True;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
			$user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
			DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));

			setcookie("SNID", $token, time() + 60*60*24*7, '/', NULL, NULL, TRUE);
			setcookie("SNID_", '1', time() + 60*60*24*3, '/', NULL, NULL, TRUE);
			header("LOCATION: index.php");
		}else{
			$message =  "Incorrect Password!";
		}


	}else{
		echo "User is not registered";
	}

}

 ?>
 <head>
 	  <meta charset="utf-8">
 	 <meta name="viewport" content="width=device-width, initial-scale=1">
 	<title>Login</title>
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
 	<script src="http://code.jquery.com/jquery-3.3.1.js"></script>
 	<style>
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
 <body>
 <section class="hero is-danger is-bold is-fullheight">
 	<div class="hero-body">
 		<div class="container is-one-third">
 			<h1 class="title is-bold" style="font-family: 'ABeeZee', sans-serif;">EverVibe</h1>
			<h1 class="heading">Login</h1>
			<form action="login.php" method="post">
				<div class="column">
					<div class="field">
						<div class="control">
							<lable class="lable"><span style="font-family: 'Questrial', sans-serif;">Username</span></lable>
							<input type="text" class="input is-rounded is-primary" name="username" id='username' autocomplete="off"><br>
						</div>
					</div>
				</div>
				<div class="column">
						<div class="field">
							<div class="control">
								<lable class="lable"><span style="font-family: 'Questrial', sans-serif;">Password</span></lable>
								<input type="password" id='password' class="input is-rounded is-primary " name="password"><br>
						</div>
					</div>	
				</div>
				<input type="submit" class="button is-info" name="login" value="Login">
			</form>
			<span class="is-info"><?php echo $message; ?></span>
		</div>
	</div>	
</section>
<script>
	
</script>
</body>
