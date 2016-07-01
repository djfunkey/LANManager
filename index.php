<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/global.css"/>
		<link rel="stylesheet" type="text/css" href="css/index.css"/>
		<title>index</title>
		<?php
			
			//START THE BROWSER SESSION AND CONNECT TO THE DATABASE
			session_start();
			require("Includes/connection.php");
			include("Includes/usercheck.php");
			
			
			if (!isset($_SESSION['uid'])) {
				header ("Location: login.php");
			}
			
		?>
		
	</head>
	<body>
		<header>
			<div id="HeadItems">
				<img class="logo" src="images/AvalancheLogo.png"/>
			</div>
		</header>
		<nav>
			<ul id="Nav">
				<li><a href="index.php" class="current">Home</a></li>
				<li><a href="schedules.php">Schedule</a></li>
				<li><a href="food.php">Food</a></li>
				<li><a href="servers.php">Servers</a></li>
				<li><a href="Includes/logout.php">Log Out</a></li>
			</ul>
		</nav>
		<main>
			<div id="Head">
				<h2></h2>
			</div>
			<div id="Main">
			<?php				
			
				//GRAB POSTS FROM DATABASE AND JOINS IT TO PROFILES THEN ORDERS THE POSTS BY DESCENDING DATE ORDER
				$querycheck = "SELECT posts.title, posts.message, posts.date, profiles.displayname, profiles.picurl FROM posts INNER JOIN profiles ON posts.id=profiles.id ORDER BY  posts.date DESC";
				$queryresult = mysqli_query($conn, $querycheck);
				
				While($row = mysqli_fetch_array($queryresult)) {
					
					$dates = preg_split('/ +/', $row['date']);
				?> 
				<div id="Post">
					<div id="User">
						<img src="<?php echo $row['picurl']?>" alt="Profile Picture"/>
						<p class="name"><?php echo $row['displayname']?></p>
						<i>
							<p class="date"><?php echo $dates[0]?></p>
							<p class="date"><?php echo $dates[1]?></p>
						</i>
					</div>
					<div id="Content">
						<p class="title"><?php echo $row['title']?></p>
						<p class="body"><?php echo $row['message']?></p>
					</div>
				</div>
				<?php
											
				}
				
			mysqli_close($conn);
			?>
			</div>
		</main>
	</body>
</html>