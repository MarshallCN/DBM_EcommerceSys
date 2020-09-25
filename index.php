<?php
	session_start();
	require "include/db.php";
	if(isset($_GET['new'])){
		$_SESSION['user'] = 'Anonymous';
		$_SESSION['userid'] = '';
	}
	if(isset($_SESSION['user'])&& !isset($_SESSION['admin'])){
		$user = $_SESSION['user'];
		$userid = $_SESSION['userid'];
	}else{
		unset($_SESSION['user']);
		unset($_SESSION['admin']);
		header("Location:login.php");
	}
	if(file_exists("./images/userlogo/$user$userid.jpg")){
		$userlogo = "./images/userlogo/$user$userid.jpg";
	}else{
		$userlogo = "./images/userlogo/user.jpg";
	}
	if(isset($_GET['logout'])){
		unset($_SESSION['user']);
		session_destroy();
		header("Location:login.php");
	}
	include "include/header.html";
	include "include/navigation.php";
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page = 'home';
	}
	if($page=='home'){
		include "include/home.html";
	}elseif($page=='product'){
		include 'app/product.php';
	}elseif($page=='comment'){
		include 'app/comment.php';
	}elseif($page=='about'){
		include 'include/about.html';
	}elseif($page=='user'){
		if($user=='Anonymous'){
			echo "<script>if(confirm('You can use this function after logging in. Do you want to log in/ sign in now?')){window.location.href='index.php?logout'}else{window.location.href='index.php'};</script>";
		}else{
			if(isset($_GET['action'])){
				$action = $_GET['action'];
				echo "<script>document.getElementById('{$_GET['action']}').className='active'</script>";
			}else{
				$action = 'cart';
				echo "<script>document.getElementById('cart').className='active'</script>";
			}
			if($action=='cart'){
				require "app/cart.php";
			}else if($action=='order'){
				require "app/order.php";
			}else if($action=='profile'){
				require "app/profile.php";
			}
		}
	}
	
	include "include/footer.html";
?>
