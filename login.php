<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/global.css"/>
		<link rel="stylesheet" type="text/css" href="css/login.css"/>
		<title>Login</title>
		<?php
			
			//START THE BROWSER SESSION AND CONNECT TO THE DATABASE
			session_start();
			require("Includes/connection.php");
			
			//all g
			if (isset($_SESSION['uid']) != "") {
				header ("Location: index.php");
			}
			
			$errorlogin = ''; // Variable To Store Error Message
			$errorregister = ''; // Variable To Store Error Message
			
			if (isset($_POST['submit'])) {
				if ($_POST['submit'] == "Login") {
					if (empty($_POST['user']) || empty($_POST['pass'])) {
					
						$errorlogin = "Username or Password is invalid";
					
					}
					else {
						
						//$conn is in the connections.php *its the connection the the database*
						$user = mysqli_real_escape_string($conn, $_POST['user']);
						$pass = md5(mysqli_real_escape_string($conn, $_POST['pass']));
						//FUCKING CORRECT STRING TO PUT INTO A QUERY WITH VARIABLES!!!!!!!!!!!!!!!!
						$querycheck = "SELECT username, password, uid FROM users WHERE password='$pass' AND username='$user'";
						$queryresult = mysqli_query($conn, $querycheck);
						$queryarray = mysqli_fetch_array($queryresult);
						
						if ($queryarray['password'] == $pass) {
							$_SESSION['uid'] = $queryarray['uid'];
							header("Location: index.php");
						}
						
						else {
							$errorlogin = "Username or Password is invalid";
						}
					}
				}
				if ($_POST['submit'] == "Register") {
					
					$user = mysqli_real_escape_string($conn, $_POST['user']);
					$disp = mysqli_real_escape_string($conn, $_POST['disp']);
					$email = mysqli_real_escape_string($conn, $_POST['email']);
					
					if (empty($_POST['user']) || empty($_POST['pass'])) {
					
						$errorregister = "Username or Password is invalid";
					
					}
					else {
						
						$pass = md5(mysqli_real_escape_string($conn, $_POST['pass']));
						$passval = md5(mysqli_real_escape_string($conn, $_POST['passvalidate']));
						
						$bytes = openssl_random_pseudo_bytes(5);
						$uid = bin2hex($bytes);
						
						//Check matching
						if ($_POST['pass'] == $_POST['passvalidate']) {
							
							if ($_POST['email'] == $_POST['emailvalidate']) {
								
								$userid = '';
								
								$querycheck = "INSERT INTO users (id, username, password, level, email, uid) VALUES(NULL, '$user', '$pass', 'guest', '$email', '$uid')";
								if (mysqli_query($conn, $querycheck)) {
								}
								
								$querycheck = "SELECT id FROM users WHERE password='$pass'";
								if (mysqli_query($conn, $querycheck)) {
									$queryresult = mysqli_query($conn, $querycheck);
									$queryarray = mysqli_fetch_array($queryresult);
									$userid = $queryarray['id'];
								}
								
								$querycheck = "INSERT INTO profiles (id, displayname, picurl, steamid) VALUES('$userid', '$disp', DEFAULT, NULL)";
								if (mysqli_query($conn, $querycheck)) {
									$_SESSION['uid'] = $uid;
									header("Location: index.php");
								}
								
								else {
									$errorregister = "A current user has either the same: <br /> Username, Displayname or Email";
								}						
							}
							else {
								$errorregister = "Emails did not match";
							}
						}
						else {
							$errorregister = "Passwords did not match";
						}
					}
				}
			}
			
		?>
	</head>
	<body>
		<!--
		<header>
			<img src="images/AvalancheLogo.png"/>
		</header>
		<nav>
			<ul id="Nav">
			</ul>
		</nav>
		-->
		<main>
			<div id="Head">
				<h2>USER VERIFICATION</h2>
			</div>
			<div id="Main">
				<div id="tabs">
					<!--tabs-->
					<input id="tab1" type="radio" name="tabs" <?php if($errorlogin != '' || $errorregister == '') {echo "checked";} ?> />
					<label for="tab1">LOGIN</label>		
					
					<input id="tab2" type="radio" name="tabs" <?php if($errorregister != '') {echo "checked";} ?> />
					<label for="tab2">REGISTER</label>
					
					<!--content *Both sections need to be after the input tabs otherwise the format messes up*-->
					<section id="content1" class="tab-content">
						<p><b><?php echo $errorlogin ?></b></p>
						<form action="login.php" method="post">
							<input class="text" type="text" name="user" value="" placeholder="Username" pattern="[A-Za-z0-9_-.]{3,16}" title="Only Numbers 0-9 , Alphabet A-z , between 3 - 16 characters" required />
							<br />
							<input class="text" type="password" name="pass" value="" placeholder="Password" pattern="[A-Za-z0-9]{5,18}" title="Only Numbers 0-9 , Alphabet A-z , between 5 - 18 characters" required />
							<br />
							<input class="button" type="submit" name="submit" value="Login">
						</form>
					</section>
					
					<section id="content2" class="tab-content">
						<p><b><?php echo $errorregister ?></b></p>
						<form action="login.php" method="post">
							<input class="text" type="text" name="user" value="<?php if (isset($user)) { echo $user; }?>" placeholder="Username" pattern="[A-Za-z0-9_-.]{3,16}" title="Only Numbers 0-9 , Alphabet A-z , between 3 - 16 characters" required />
							<br />
							<input class="text" type="text" name="disp" value="<?php if (isset($disp)) { echo $disp; }?>" placeholder="Display Name" pattern="[A-Za-z0-9_-.]{3,16}" title="Only Numbers 0-9 , Alphabet A-z , between 3 - 16 characters" required />
							<br />
							<input class="text" type="password" name="pass" value="" placeholder="Password" pattern="[A-Za-z0-9]{5,18}" title="Only Numbers 0-9 , Alphabet A-z , between 5 - 18 characters" required />
							<br />
							<input class="text" type="password" name="passvalidate" value="" placeholder="Re-enter Password" pattern="[A-Za-z0-9]{5,18}" title="Only Numbers 0-9 , Alphabet A-z , between 5 - 18 characters" required />
							<br />
							<input class="text" type="email" name="email" value="<?php if (isset($email)) { echo $email; }?>" placeholder="Email" pattern="([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})" title="Example &ldquo;Joe.Blogs@gmail.com&rdquo;" required />
							<br />
							<input class="text" type="email" name="emailvalidate" value="" placeholder="Re-enter Email" pattern="([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})" title="Example &ldquo;Joe.Blogs@gmail.com&rdquo;" required />
							<br />
							<input class="button" type="submit" name="submit" value="Register">
						</form>
					</section>
				</div>
			</div>
		</main>
	</body>
</html>