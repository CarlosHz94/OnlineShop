<?php
	require_once 'Product.php';

	/**
	* Gets a number of items based on the ITEMS_LIMIT to be showcased at the homepage
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
	* Displays products onto the page
	* @param $items An array of type Product to be displayed
	*/
	function displayItems(array $items){
		for($i=0;$i<sizeof($items);$i++){
			displayItem($items[$i]);
		}
	}

	function displayItem($item){
		echo 
			"<div class=\"card border-0 shadow-lg\">
				<a href=\"".ROOT_PATH."public/item.php?itemId=".$item->getId()."\">
					<ul class=\"list-group list-group-flush\">
						<li class=\"list-group-item\">
					    	<img src=\"".ROOT_PATH.$item->getImg()."\" class=\"card-img-top\">
						</li>
						<li class=\"list-group-item\">
					   		<div class=\"card-body\">
								<h5 class=\"card-title\">".$item->getName()."</h5>
								<p class=\"card-text\">Item description goes here</p>
							</div>
						</li>
						<div class=\"list-group-item\">
							<p class=\"card-text text-center\">CDN".$item->getPrice()."</p>
						</div>
					</ul>
				</a>
			</div>";
	}

	/** 
	* Searches if an item exists in the database
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
	* Adds item and quantity to the cart
	* @param $itemId The id of the item to be added to the cart
	* @param $qty The quantity of the item to be added to the cart
	*/
	function addToCart(int $itemId, int $qty){
		if(isset($_SESSION['idUser'])){
			//add to db
			require_once 'dbh.php';
			$dbh = new Dbh();
			$dbh->execute("INSERT INTO carts (idUsers, idProducts, qty) VALUES (?, ?, ?)", [$_SESSION['idUser'], $itemId, $qty]);

		}else{
			//add to cookie
			echo "not logged in, must use cookie";
		}
	}

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

	function getCartItems(){
		$productsArray = array();
		if(isset($_SESSION['idUser'])){
			//remove from db
			require_once 'dbh.php';
			$dbh = new Dbh();
			$dbh->execute("SELECT idProducts FROM carts WHERE idUsers=?", [$_SESSION['idUser']]);
			$result = $dbh->fetchAll();
			if($result){
				foreach($result as $row){
					$productsArray[] = getItem($row['idProducts']);
				}
			}
		}else{
			//return from cookie
			
		}
		return $productsArray;
	}

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

	function signup(string $username, string $email, string $pwd){
		require_once 'dbh.php';
		$dbh = new Dbh();
		$hashedhPwd = password_hash($pwd, PASSWORD_DEFAULT);
		return $dbh->execute("INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)", [$username, $email, $hashedhPwd]);
	}

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

	function logout(){
		session_start();
		session_unset();
		session_destroy();
	}