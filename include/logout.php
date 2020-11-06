<?php
	if(isset($_POST['logout-submit'])){
		require_once '../config/config.php';
		require_once 'functions.php';
		logout();
		header("Location: ".ROOT_PATH."public/home.php");
	}else{
		header("Location: ".ROOT_PATH."public/home.php");
	}