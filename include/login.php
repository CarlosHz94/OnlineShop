<?php

	// Check if access legitimately
	if(isset($_POST['login-submit'])){
		require_once '../config/config.php';
		require_once 'functions.php';

		$uId = $_POST['mailuid'];
		$password = $_POST['pwd'];

		if(empty($uId) || empty($password)){
			//Empty fields
			header("Location: ".ROOT_PATH."public/login.php?error=emptyFields&uId=".$username);
			exit();

		}else{

			if(checkEmail($uId) || checkUser($uId)){
				if(login($uId, $password)){		
					header("Location: ".ROOT_PATH."public/home.php?login=success");
					exit();
				}else{
					header("Location: ".ROOT_PATH."public/home.php?error=wrongPwd");
					exit();
				}
			}else{
				header("Location: ".ROOT_PATH."public/home.php?error=noUser");
				exit();
			}
		}
	}else{
		header("Location: ".ROOT_PATH."public/home.php");
		exit();
	}