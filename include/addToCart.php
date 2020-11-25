<?php

	if(isset($_POST['add-submit'])){
		require_once 'functions.php';
		require_once '../config/config.php';
		session_start();

		$itemId = $_POST['itemId'];
		$qty = $_POST['qty'];

		addToCart($itemId, $qty);

		header("Location: ".ROOT_PATH."public/item.php?itemId=".$itemId."&status=success");
		exit();
	}else{
		header("Location: ".ROOT_PATH."public/home.php");
		exit();
	}