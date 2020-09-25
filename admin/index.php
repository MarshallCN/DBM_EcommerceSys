<?php
	session_start();
	require "../include/db.php";
	if(isset($_GET['logout'])){
		unset($_SESSION['user']);
		unset($_SESSION['userid']);
		unset($_SESSION['admin']);
		session_destroy();
		header("Location: ../login.php");
	}
	if(isset($_SESSION['admin']) && isset($_SESSION['user'])){
			$user = $_SESSION['user'];
			$userid = $_SESSION['userid'];
	}else{
		header("Location: ../login.php?admin");
		unset($_SESSION['user']);
		unset($_SESSION['userid']);
		unset($_SESSION['admin']);
	}
	$userlogo = "../images/userlogo/admin.jpg";
	include "include/header.html";
	include "include/nav_admin.php";	
	if(isset($_GET['page'])){
		$page=$_GET['page'];
	}else{
		$page = 'order';
	}
	if($page=='product'){
		include "app/product.php";
	}else if($page=='order'){
		include "app/order.php";
	}else if($page=='comment'){
		include "../app/comment.php";
	}else if($page=='report'){
		if(isset($_GET['cate'])){
			$cate = $_GET['cate'];
			echo "<script>document.getElementById('{$_GET['cate']}').className='active'</script>";
		}else{
			$cate = 'sales';
			echo "<script>document.getElementById('sales').className='active'</script>";
		}
		if($cate=='sales'){
			include "app/sales.php";
		}else if($cate=='delivery'){
			include "app/logist.php";
		}else if($cate=='customer'){
			include "app/customer.php";
		}
	}
	include "../include/footer.html";
?>
