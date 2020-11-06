<?php
	require_once '../include/functions.php';
	session_start();
?>
<!DOCTYPE html>
<html >
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
		<title>My Online Shop</title>

	</head>
	<body class="bg-light">
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
		<?php 
		require("../config/config.php");
		?>
		<header>
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
				<div class="container-fluid ">
					<div class="d-flex align-items-center row w-100">
						<div class="col-4">
							<a class="navbar-brand" href="home.php">
								<img class="d-inline-block align-center" src="assets/logo.png" width="50" height="50" alt="" loading="lazy"><span class="ml-2">Online Shop</span>
							</a>
						</div>
						<!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav2" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    						<span class="navbar-toggler-icon"></span>
  						</button>
  						<div class="collapse navbar-collapse" id="navbarNav2">-->
						<div class="col-4">
							<form class="form-inline" action="<?php echo ROOT_PATH."public/search.php"?>" method="post">
								<div class="input-group w-100 mb-3">
									<input type="text" class="form-control" name="search" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
									<div class="input-group-append">
										<button class="btn btn-outline-light" name="search-submit" type="submit" id="basic-addon2">
											<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">	<path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>	<path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
											</svg>
										</button>
									</div>
								</div>
							</form>
						</div>
						<div class="col-4 d-flex justify-content-end">
							<?php
								if(!isset($_SESSION['uidUser'])){
									echo "	<form class=\"form-inline\" action=".ROOT_PATH."include/login.php method=\"post\">
												<div class=\"d-flex align-items-center\">
													<a href=\"login.php\" class=\"btn btn-outline-light m-1\">Login</a>
													<a href=\"signup.php\" class=\"btn btn-outline-light m-1\">Sign up</a>
												</div>
											</form>";
								
								}else{
									echo "	<span class=\"d-flex align-items-center text-light\">Welcome ".$_SESSION['uidUser']."!
												<form class\"form-inline\" action=".ROOT_PATH."include/logout.php method=\"post\">
													<button class=\"btn btn-outline-light m-1\" type=\"submit\" name=\"logout-submit\">Logout</button>
												</form>
											</span>";
								}
								
							?>
							<form class="form-inline">
								<a class="btn btn-outline-light" href="cart.php">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cart2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
									</svg>
									
									<?php 
										$qty = getCartQty();
										if($qty > 0){
											echo "<span class=\"badge badge-pill badge-danger\">".$qty."</span>";
										}
									?>
									
								</a>
							</form>
						</div>
					</div>
				</div>
						<!--</div>-->
			</nav>
			
		</header>

