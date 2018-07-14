<?php 
/* Author: Aizaz Ud Din (Aizaz.dinho)*/
	/* Made By: Meralesson.com*/
	$check = new Main;
	if(isset($_POST['username'],$_POST['password'])){
		@$username = $_POST['username'];
		@$password = $_POST['password'];
		
		if(empty($username) or empty($password)){
			echo "<div class='error'>Enter a Username and Password</div>";
		} else{
			$password = md5($password);
  			$check->login($username,$password);
			}
		}

	
?>

<div class="right">
		<form action="" method="post"/>
			<div class="right-email">
				<ul>
					<li class="white">Username</li>
					<li><input type="text" name="username"/></li>
				</ul>
			</div>
			<div class="right-pass">
				<ul>
					<li><span class="white">Password</span></li>
					<li><input type="Password" name="password"/></li>
					<li><span>Forgot your Password?</span></li>
				</ul>
			</div>
			<div class="right-btn">
				<input class="btn" type="submit" value="Login" />
			</div>
			</form>
			 
</div>