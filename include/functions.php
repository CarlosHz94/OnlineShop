<?php
	require_once 'Product.php';
	require_once 'Cart.php';

	/**
	* Gets a number of items based on the ITEMS_LIMIT to be showcased at the homepage.
	* @return An array of Product objects
	*/
	function getShowcaseItems(){
		require_once 'dbh.php';
		$productsArray = array();
		$dbh = new Dbh();
		$dbh->execute("SELECT idProducts FROM products ORDER BY idProducts DESC LIMIT 1");
		$row = $dbh->fetch();
		$totalItems = $row['idProducts'];

		//Select random items Id's to display
		$array = array();
		$productId = rand(1, $totalItems);

		while(sizeof($array) < ITEMS_LIMIT){
			if(!array_search($productId, $array) && array_search($productId, $array) !== 0){
				$array[] = $productId;
			}
			$productId = rand(1, $totalItems);
		}

		//Get items from DB
		for($i = 0; $i < sizeof($array); $i++){
			$productsArray[] = getItem($array[$i]);
		}
		return $productsArray;
	}

	/**
	* Gets item from the database based on the item ID.
	* @param $itemsId Id of the item
	* @return $product A Product type object
	*/
	function getItem(int $itemId){
		require_once 'dbh.php';
		$product = null;
		$dbh = new Dbh();
		$dbh->execute("SELECT * FROM products WHERE idProducts=?", [$itemId]);
		$row = $dbh->fetch();
		if($row){
			$product = new Product($row['idProducts'], $row['nameProducts'], $row['priceProducts'], $row['imgProducts']);
		}
		return $product;
	}

	/**
	* Displays products onto the page.
	* @param $items An array of type Product to be displayed as bootstrap cards
	*/
	function displayItemCards(array $items){
		echo "<div class=\"row row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1\">";
		foreach($items as $item){
			displayItemCard($item);
		}
		echo "</div>";
	}

	/**
	* Displays item as a card for the home page.
	* @param $item A Product object to be displayed in the homepage as a bootstrap card
	*/
	function displayItemCard($item){
		echo "	<div class=\"col mb-4\">
					<div class=\"card h-100 p-3 border-0 shadow-lg\">
						<a href=\"".ROOT_PATH."public/item.php?itemId=".$item->getId()."\">
							<img src=\"".ROOT_PATH.$item->getImg()."\" class=\"card-img-top\">
						</a>
						<div class=\"card-body\">
							<a href=\"".ROOT_PATH."public/item.php?itemId=".$item->getId()."\">
								<h5 class=\"card-title\">".$item->getName()."</h5>
							</a>
							<p class=\"card-text\">Item description goes here...</p>
							<p class=\"card-text\">CND ".$item->getPrice()."</p>
						</div>
					</div>
				</div>";
	}

	/**
	* Displays the item information for the item page.
	* @param $item An object of type Product
	*/
	function displayItem($item){
		echo "	<div class=\"d-flex flex-wrap container\">
					<div class=\"col mt-5\">
						<img src=\"".ROOT_PATH.$item->getImg()."\" class=\"rounded shadow mx-auto d-block img-thumbnail\">
					</div>
					<div class=\"col mx-5\">
						<h5 class=\"mb-4\">".$item->getName()."</h5>
						<ul>
							<li>Really long description of a feature of this item. Really long description of a feature of this item. Really long description of a feature of this item. Really long description of a feature of this item.</li>
							<li>Really long description of a feature of this item. Really long description of a feature of this item. Really long description of a feature of this item. Really long description of a feature of this item.</li>
							<li>Short description of a feature of this item.</li>
							<li>This is a medium description of a feature of this item. This is a medium description of a feature of this item.</li>
						</ul>
					</div>
					<div class=\"col\">
						<div class=\"card shadow mx-auto pt-2\" style=\"width: 18rem\">
							<div class=\"card-body mx-auto\">
								<h5 class=\"text-center mb-5\">CND ".$item->getPrice()."</h5>
								<form class=\"form-group\" action=\"".ROOT_PATH."include/addToCart.php\" method=\"post\">
									<input name=\"itemId\" value=\"".$item->getId()."\" type=\"hidden\">
									<select class=\"custom-select\" name=\"qty\" style=\"width: 4rem\">
										<option selected=\"true\" value=\"1\">1</option>";
										for($i = 2; $i <= 10; $i++){
											echo "<option value=\"".$i."\">".$i."</option>";
										}
								echo "</select>
									<button class=\"btn btn-success\" type=\"submit\" name=\"add-submit\">Add to cart</button>
								</form>
							</div>
						</div>
					</div>
				</div>";
	}

	/** 
	* Searches if an item exists in the database.
	* @param $item The name of the item to be searched for
	* @return An array of Product objects 
	*/
	function searchItem(string $item){
		require_once 'dbh.php';
		$resultsArray = array();
		$dbh = new Dbh();
		$item = "%".$item."%";
		$dbh->execute("SELECT * FROM products WHERE nameProducts LIKE ?", [$item]);
		$result = $dbh->fetchAll();
		foreach($result as $row){
			$product = new Product($row['idProducts'], $row['nameProducts'], $row['priceProducts'], $row['imgProducts'],);
			$resultsArray[] = $product;
		}
		return $resultsArray;
	}

	/**
	* Displays the search results.
	* @param $resultsArray The array containing the results from the search query
	*/
	function displaySearchResults(array $resultsArray){
		displayItemCards($resultsArray);
	}

	/** 
	* Adds item and quantity to the cart.
	* @param $itemId The id of the item to be added to the cart
	* @param $qty The quantity of the item to be added to the cart
	*/
	function addToCart(int $itemId, int $qty){
		if(isset($_SESSION['idUser'])){
			//add to db
			require_once 'dbh.php';
			$dbh = new Dbh();
			$dbh->execute("SELECT qty FROM carts WHERE idUsers=? AND idProducts=?", [$_SESSION['idUser'], $itemId]);
			$result = $dbh->fetch();
			if($result){
				$newQty = $result['qty'] + $qty;
				$dbh->execute("UPDATE carts SET qty=? WHERE idUsers=? AND idProducts=?", [$newQty, $_SESSION['idUser'], $itemId]);
			}else{
				$dbh->execute("INSERT INTO carts (idUsers, idProducts, qty) VALUES (?, ?, ?)", [$_SESSION['idUser'], $itemId, $qty]);
			}
		}else{
			//add to cookie
			echo "not logged in, must use cookie";
		}
	}

	/**
	* Removes an item from the cart based on the qty selected.
	* @param $itemId The id of the item to be removed from * the cart
	* @param $qty The quantity of the products to be removed
	*/
	function removeFromCart(int $itemId, int $qty){
		if(isset($_SESSION['idUser'])){
			//remove from db
			require_once 'dbh.php';
			$dbh = new Dbh();
			$dbh->execute("SELECT qty FROM carts WHERE idUsers=? AND idProducts=?", [$_SESSION['idUser'], $itemId]);
			$result = $dbh->fetch();
			if($result){
				if($qty == $result['qty']){
					//remove row
					$dbh->execute("DELETE FROM carts WHERE idUsers=? AND idProducts=?", [$_SESSION['idUser'], $itemId]);
				}else{
					//decrement qty
					$newQty = $result['qty'] - $qty;
					$dbh->execute("UPDATE carts SET qty=? WHERE idUsers=? AND idProducts=?", [$newQty, $_SESSION['idUser'], $itemId]);
				}
			}
		}else{
			//remove from cookie
		}
	}

	/**
	* Gets the quantity of items the user has on his cart.
	* @return The total number of items in the user's cart
	*/
	function getCartQty(){
		if(isset($_SESSION['idUser'])){
			//remove from db
			require_once 'dbh.php';
			$dbh = new Dbh();
			$dbh->execute("SELECT sum(qty) FROM carts WHERE idUsers=?", [$_SESSION['idUser']]);
			$result = $dbh->fetch();
			if($result){
				if(!is_null($result['sum(qty)'])){
					return $result['sum(qty)'];
				}
				return 0;
			}else{
				//nothing returned
			}
		}else{
			//return from cookie
			return 0;
		}
	}

	/**
	* Gets the items from the users cart
	*/
	function getCartItems(){
		//$productsArray = array();
		$cartArray = array();
		if(isset($_SESSION['idUser'])){
			//remove from db
			require_once 'dbh.php';
			$dbh = new Dbh();
			$dbh->execute("SELECT idProducts, qty FROM carts WHERE idUsers=?", [$_SESSION['idUser']]);
			$result = $dbh->fetchAll();
			if($result){
				foreach($result as $row){
					$product = getItem($row['idProducts']);
					$cart = new Cart($product, $row['qty']);
					$cartArray[] = $cart;
					//$productsArray[] = getItem($row['idProducts']);
				}
			}
		}else{
			//return from cookie
			
		}
		return $cartArray;
	}

	/**
	* Displays the items in the user's cart for the cart page.
	* @param $cartArray An array that contains the user's cart items
	*/
	function displayCartItems(array $cartArray){
		foreach($cartArray as $cartItem){
			$price = (double)substr($cartItem->getProduct()->getPrice(), 1);
			echo "	<div class=\"card my-4\">
						<div class=\"row no-gutters\">
							<div class=\"col-4\">
								<a href=\"".ROOT_PATH."public/item.php?itemId=".$cartItem->getProduct()->getId()."\">
									<img src=\"".ROOT_PATH.$cartItem->getProduct()->getImg()."\" class=\"card-img-top\">
								</a>
							</div>
							<div class=\"col-8\">
								<div class=\"card-body\">
									<div class=\"row\">
										<div class=\"col-8\">
											<a href=\"".ROOT_PATH."public/item.php?itemId=".$cartItem->getProduct()->getId()."\">
												<h5 class=\"card-title\">".$cartItem->getProduct()->getName()."</h5>
											</a>
										</div>
										<div class=\"col-4\">
											<h5 class=\"card-title text-center\">CND$ ".$price*$cartItem->getQty()."</h5>
										</div>
									</div>
									<div class=\"row\">
										<form class=\"form-group ml-3\" action=\"".ROOT_PATH."include/removeFromCart.php\" method=\"post\">
											<input name=\"itemId\" value=\"".$cartItem->getProduct()->getId()."\" type=\"hidden\">
											<select class=\"custom-select\" name=\"qty\" style=\"width: 4rem\">
												<option selected=\"true\" value=\"".$cartItem->getQty()."\">".$cartItem->getQty()."</option>";
												for($i = $cartItem->getQty()-1; $i > 0; $i--){
													echo "<option value=\"".$i."\">".$i."</option>";
												}
												
									echo	"</select>
											<button class=\"btn btn-danger\" type=\"submit\" name=\"remove-submit\">Remove from cart</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>";
		}
	}

	/**
	* Checks if the user exists in the database.
	* @param $username The username that has to be checked for in the database
	* @return True if username exists in the database, otherwise False
	*/
	function checkUser(string $username){
		require_once 'dbh.php';
		$dbh = new Dbh();
		$dbh->execute("SELECT uidUsers FROM users WHERE uidUsers=?", [$username]);
		$result = $dbh->fetch();
		if($result){
			return true;
		}
		return false;
	}

	/**
	* Checks if the email exists in the database.
	* @param $email The email that has to be checked for in the database
	* @return True if the email exits in the database, otherwise False
	*/
	function checkEmail(string $email){
		require_once 'dbh.php';
		$dbh = new Dbh();
		$dbh->execute("SELECT uidUsers FROM users WHERE emailUsers=?", [$email]);
		$result = $dbh->fetch();
		if($result){
			return true;
		}
		return false;
	}

	/**
	* Signs up the user into the page and stores their data into the database.
	* @param $username Username of the user
	* @param $email Email of the user
	* @param $pwd Password of the user
	*/
	function signup(string $username, string $email, string $pwd){
		require_once 'dbh.php';
		$dbh = new Dbh();
		$hashedhPwd = password_hash($pwd, PASSWORD_DEFAULT);
		return $dbh->execute("INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)", [$username, $email, $hashedhPwd]);
	}

	/**
	* Logs the user in, and starts a session.
	* @param $uId The user ID
	* @param $pwd The password of the user
	* @return True if the user credentials match, otherwise False
	*/
	function login(string $uId, string $pwd){
		require_once 'dbh.php';
		$dbh = new Dbh();
		$dbh->execute("SELECT * FROM users WHERE uidUsers = ? OR emailUsers = ?", [$uId, $uId]);
		$row = $dbh->fetch();
		if(password_verify($pwd, $row['pwdUsers'])){
			session_start();
			$_SESSION['idUser'] = $row['idUsers'];
			$_SESSION['uidUser'] = $row['uidUsers'];
			return true;
		}
		return false;
	}

	/**
	* Logs the user out, and destroys the session.
	*/
	function logout(){
		session_start();
		session_unset();
		session_destroy();
	}