<?php 
require("header.php");
?>

	<main role="main" class="my-5">
		<!--Display some products-->
		<div class="container" >
			<?php
				//require_once "../include/functions.php";
				$itemId = $_GET['itemId'];
				$item = getItem($itemId);
				if($item != null){
					//Display item
					echo "	
					";
					
				}else{
					//Item does not exists in db
					echo "No item";
				}

			?>
		</div>
	</main>
	<?php
		//require("footer.php");
	?>
</body>

