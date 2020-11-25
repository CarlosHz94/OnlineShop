<?php 
	require_once 'header.php';
?>

	<main>
		<div class="wrapper-main">
			<?php
				//Error messages
				if(isset($_GET['error'])){
					if($_GET['error'] == "emptyFields"){
						echo "<p>Fill in all fields!</p>";
					}else if($_GET['error'] == "invaliduId-email"){
						echo "<p>Invalid username and e-mail!</p>";
					}else if($_GET['error'] == "invalidEmail"){
						echo "<p>Invalid e-mail!</p>";
					}else if($_GET['error'] == "invaliduId"){
						echo "<p>Invalid username!</p>";
					}else if($_GET['error'] == "passwordCheck"){
						echo "<p>Password does not match!</p>";
					}else if($_GET['error'] == "userTaken"){
						echo "<p>Username already taken!</p>";
					}else if($_GET['error'] == "emailExists"){
						echo "<p>An account with this email already exists!</p>";
					}
				}
			?>
			<div class="container mt-5 ">
				<div class="d-flex align-content-center">
					<div class="card shadow-lg mx-auto text-center" style="width: 25rem;">
						<div class="card-header text-white bg-dark">
							<h1 class="text-center">Login</h1>
						</div>
						<div class="card-body mt-3 d-flex justify-content-center">
							<form class="form" action="<?php echo ROOT_PATH . "include/login.php"?>" method="post">
								<div class="form-row">
									<div class="form-group">
										<input class="form-control" type="text" name="mailuid" placeholder="Username or E-mail">
									</div>
								</div>
								<div class="form-row">
									<div class="form-group">
										<input class="form-control" type="password" name="pwd" placeholder="Password">
									</div>
								</div>
								<button class="btn btn-outline-dark" type="submit" name="login-submit">Login</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</main>

	<?php
		//require("footer.php");
	?>
</body>