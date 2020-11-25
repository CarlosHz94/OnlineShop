<?php 
	require_once 'header.php';
?>

	<main role="main" >
		<div class="container-fluid">
			<div class="jumbotron text-center bg-white">
			  <h1 class="display-4">Hello!</h1>
			  <p class="lead">This is a basic online shop project.</p>
			  <hr class="my-4">
			  <p>Below you will see some of the products this mock shop has.</p>
			</div>
		</div>
		<!--Display some products-->
		<div class="container mt-5">
			<!--<div class="card-deck">-->
				<?php
					$items = getShowcaseItems();
					displayItemCards($items);
					
				?>
			<!--</div>-->
		</div>
		
	</main>
	

	<?php
		//require_once "footer.php";
	?>
</body>