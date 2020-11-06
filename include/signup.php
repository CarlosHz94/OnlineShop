<?php

	//Check if access legitimately
	if(isset($_POST['signup-submit'])){
		require_once '../config/config.php';
		require_once 'functions.php';

		$username = $_POST['uId'];
		$email = $_POST['email'];
		$password = $_POST['pwd'];
		$passwordCfm = $_POST['pwd-cfm'];

		if(empty($username) || empty($email) || empty($password) || empty($passwordCfm)){
			//Empty fields
			header("Location: ".ROOT_PATH."public/signup.php?error=emptyFields&uId=".$username."&email=".$email);
			exit();

		}else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)){
			//Invalid user and email
			header("Location: ".ROOT_PATH."public/signup.php?error=invaliduId-email");
			exit();

		}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			//Invalid email
			header("Location: ".ROOT_PATH."public/signup.php?error=invalidEmail&uId=".$username);
			exit();

		}else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
			//Invalid username
			header("Location: ".ROOT_PATH."public/signup.php?error=invaliduId&email=".$email);
			exit();

		}else if($password != $passwordCfm){
			//Password do not match
			header("Location: ".ROOT_PATH."public/signup.php?error=passwordCheck&uId=".$username."&email=".$email);
			exit();
		}else{

			if(checkEmail($email)){
				header("Location: ".ROOT_PATH."public/signup.php?error=emailExists&uId=".$username);
				exit();
			}else if(checkUser($username)){
				header("Location: ".ROOT_PATH."public/signup.php?error=userTaken&email=".$email);
				exit();
			}else{
				if(signup($username, $email, $password)){
					login($username, $password);
					header("Location: ".ROOT_PATH."public/home.php?signup=success");
					exit();
				}
			}
		}
	}else{
		require("../config/config.php");
		header("Location: ".ROOT_PATH."public/signup.php");
		exit();
	}