<?php

	if(isset($_POST['remove-submit'])){
		require_once 'functions.php';
		require_once '../config/config.php';
		session_start();

		$itemId = $_POST['itemId'];
		$qty = $_POST['qty'];

		removeFromCart($itemId, $qty);

		header("Location: ".ROOT_PATH."public/cart.php?status=success");
		exit();
	}else{
		header("Location: ".ROOT_PATH."public/home.php");
		exit();
	}