<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/global.css"/>
		<link rel="stylesheet" type="text/css" href="css/food.css"/>
		<title>index</title>
		<?php
			
			//START THE BROWSER SESSION AND CONNECT TO THE DATABASE
			session_start();
			require("Includes/connection.php");
			include("Includes/usercheck.php");
			
			if (!isset($_SESSION['uid'])) {
				header ("Location: login.php");
			}
			
			//GRAB PIZZAS FROM DATABASE
			$sql = "SELECT * FROM pizzaitems";
			$rs = mysqli_query($conn, $sql);
			
			$uid = $_SESSION['uid'];
			
			$sqli = "SELECT 'id', 'uid' FROM 'users' WHERE uid='$uid'";
			$rsi = mysqli_query($conn, $sql);
			$resi = mysqli_fetch_array($rsi);
			
			$userid = $resi['id'];
			
			$querycheck = "SELECT * FROM pizzaorders WHERE id='$userid'";
			$queryresult = mysqli_query($conn, $querycheck);
			$queryarray = mysqli_fetch_array($queryresult);
			
			//COUNTS THE COLUMNS
			$colCount = 0;
			$colCounter = 0;
			
			if (isset($_POST['action'])) {
				
				if ($_POST['action'] == "ADD TO ORDER") {
					
					$pizzaid = $_POST['pizzaid'];
					
					$querycheck = "INSERT INTO pizzaorders (id, pizzaid, quantity) VALUES('$userid', '$pizzaid', DEFAULT)";
					if (mysqli_query($conn, $querycheck)) {
						
					}
					
				}
				
				if ($_POST['action'] == "UPDATE") {
					
					$pizzaid = $_POST['pizzaid'];
					$quantity = $_POST['quantity'];
					
					$querycheck = "UPDATE pizzaorders SET quantity='$quantity' WHERE pizzaid='$pizzaid' AND id='$userid'";
					if (mysqli_query($conn, $querycheck)) {
						
					}
					
				}
				
				if ($_POST['action'] == "REMOVE") {
					
					$pizzaid = $_POST['pizzaid'];
					
					$querycheck = "DELETE FROM pizzaorders WHERE pizzaid='$pizzaid' AND id='$userid'";
					if (mysqli_query($conn, $querycheck)) {
						
					}
					
				}
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
				<li><a href="index.php">Home</a></li>
				<li><a href="schedules.php">Schedule</a></li>
				<li><a href="food.php" class="current">Food</a></li>
				<li><a href="servers.php">Servers</a></li>
				<li><a href="Includes/logout.php" >Log Out</a></li>
			</ul>
		</nav>
		<main>
			<div id="Head">
				<h2></h2>
			</div>
			<div id="Main">
					<!-- THERE CAN BE 4 ITEMS PER ROW (4 COLUMNS) WITH THE CURRENT CSS SETUP -->
				<table>
						<?php
					
							While($row = mysqli_fetch_array($rs)) {								
								$colCounter += 1;
								
								$rowid = $row['id'];
								
								$querycheck = "SELECT * FROM pizzaorders WHERE id='$userid' AND pizzaid='$rowid'";
								$queryresult = mysqli_query($conn, $querycheck);
								$queryarray = mysqli_fetch_array($queryresult);
								
								if ($colCounter >= ($colCount + 5)) {
									?> <tr> <?php
								}
								
								?>
									
									<td>
										<p class="title"><b><?php echo $row['name']; ?></b></p>
										<img src="<?php echo $row['img']; ?>" alt="<?php echo $row['name'] ?>"/>
										<p class="desc"><?php echo $row['description']; ?></p>
										<p class="price">$<?php echo $row['price']; if (isset($queryarray['quantity'])) { echo " x " . $queryarray['quantity']; } ?></p>
										<?php 
											if (mysqli_num_rows($queryresult) > 0) {
													?>
													<form action="food.php" class="amount" method="post" id="updateform">
														<input type="number" class="quantity" name="quantity" min="0" max="10" step="1" value="<?php  echo $queryarray['quantity']; ?>">
														<input type="submit" class="update" name="action" value="UPDATE" />
														<input type="submit" class="remove" name="action" value="REMOVE" />
														<input type="hidden" name="pizzaid" value="<?php echo $row['id']; ?>"/>
													</form>
													<?php
											}
											else {
												?>
												<form action="food.php" method="post" id="updateform">
													<input type="hidden" name="pizzaid" value="<?php echo $row['id']; ?>"/>
													<input class="order" type="submit" name="action" value="ADD TO ORDER"/>
												</form>
												<?php
											}										
										?>
									</td>
							
								<?php
								
								if ($colCounter >= ($colCount + 4)) {
									?> </tr> <?php
									$colCount = $colCounter + 4;
								}			
							}
				
						?>
				</table>
			</div>
		</main>
	</body>
</html>