<?php 
require("header.php");
?>

	<main>
		<!--Display some products-->
		<div class="container">
			<?php
				$cart = getCartItems();
				displayItems($cart);
				
			?>
		</div>
		
	</main>

<?php
//require("footer.php");
?>
</body>