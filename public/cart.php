<?php 
	require_once 'header.php';
?>

	<main>
		<!--Display some products-->
		<div class="container mt-5">
			<?php
				$cart = getCartItems();
				if(sizeof($cart) > 0){
					displayCartItems($cart);
				}else{
					echo "<h5 class=\"text-center\">Cart is empty</h5>";
				}

				//displayItems($cart);
				
			?>
		</div>
		
	</main>

<?php
//require("footer.php");
?>
</body>