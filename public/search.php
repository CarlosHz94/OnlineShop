<?php 
require("header.php");
?>

	<main class="container mt-5">
		<?php
			if(isset($_POST['search-submit'])){
				$searchInput = $_POST['search'];
				if(!empty($searchInput)){
					//require_once "../include/functions.php";
					$results = searchItem($searchInput);
					if(sizeof($results) > 0){
						echo "<h5 class=\"mb-5\">Results For '".$searchInput."'</h5>";
						displaySearchResults($results);
					}else{
						//no results
						echo "no results";
					}
				}else{
					//empty search
					header("Location: ".ROOT_PATH."public/home.php");
					exit();
				}
			}else{
				header("Location: ".ROOT_PATH."public/home.php");
				exit();
			}
		?>
		
	</main>
</body>

<?php
//require("footer.php");
?>