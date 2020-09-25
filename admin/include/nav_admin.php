<script>
/**print time and refresh in every 1s*/		
	function printTime(){
		var d = new Date();
		document.getElementById('time').innerHTML=d.toLocaleTimeString();
	}
	setInterval(printTime,1000);
	function logout(){
		if(confirm('Do you want to log out?')){
			window.location.href='index.php?logout';
		}else{
			return false;
		}
	}
	function visnav(){
		var navbtn = document.getElementsByClassName('updown')[0];
		navbtn.childNodes[0].style.visibility = "visible";
		navbtn.childNodes[1].style.visibility = "visible";
		navbtn.onmouseout = function(){
			navbtn.childNodes[0].style.visibility = "hidden";
			navbtn.childNodes[1].style.visibility = "hidden";
		}
	}
	function changenot(cartnum,ordernum){
		var notnum = document.getElementsByClassName('icon_num');
		notnum[0].style.color='#fff';
		notnum[1].style.color='#fff';
		notnum[0].innerHTML = cartnum;
		notnum[1].innerHTML = ordernum;
		function bignum(num,ele){
			if(num>0){
				ele.parentNode.style.visibility='visible';
				if(num>9){
					ele.style.marginLeft='-7px';
					ele.parentNode.style.width='28px';
				}
			}else{
				ele.parentNode.style.visibility='hidden';
			}
		}
		bignum(cartnum,notnum[0]);
		bignum(ordernum,notnum[1]);	
	}
	function hideleft(){
		main = document.getElementsByClassName('main')[0];
		btnhide = document.getElementsByClassName('btn-hideleft')[0];
		main.style.width='90%';
		main.style.marginLeft='7%';
		document.getElementsByClassName('left_nav')[0].style.width='0px';
		document.getElementsByClassName('fitout')[0].style.display='none';
		btnhide.innerHTML='>';
		btnhide.className='btn-hideleftact';
		btnhide.onclick=function(){
			main.style.width='80%';
			main.style.marginLeft='17%';
			document.getElementsByClassName('left_nav')[0].style.width='218px';
			document.getElementsByClassName('fitout')[0].style.display='inline';
			btnhide.innerHTML='<';
			btnhide.className='btn-hideleft';
			this.onclick=function(){
				hideleft();
			}
		}
	}
</script>
  <body>  
    <nav class="left_nav">
		<header>
          <div class='logo'><h2>DutchBabyMilk</h2></div>
        </header>
		<img src="<?php echo $userlogo;?>" class='fitout'>
		<div class='left_menu'>
		  <ul>
            <li>
				<a href="index.php?page=report&cate=sales" id='sales'>Sales Report
					<span class='icon_notification'><span class='icon_num'></span></span>
				</a>
			</li>
            <li>
				<a href="index.php?page=report&cate=delivery" id='delivery'>Logistics Overview
					<span class='icon_notification'><span class='icon_num'></span></span>
				</a>        
				</a>
			</li>
			<li>
				<a href="index.php?page=report&cate=customer" id='customer'>Customer Management
					<span class='icon_notification'><span class='icon_num'></span></span>
				</a>        
				</a>
			</li>
            <li style='cursor:pointer;'><a onclick='logout()'>Sign Out</a></li>
          </ul>  
		  <div class='btn-hideleft' onclick='hideleft()'><</div>
		</div>
    </nav>
	<nav class="top_menu">
		<a href="index.php?page=order">ORDERS</a>
		<a href="index.php?page=product">PRODUCT</a>
		<a href="index.php?page=comment" id='about'>COMMENT</a>
	  <span class='top_right'>	
		<p id='hello'>Hello <span id='username'></span></p>
		<p id='time'></p>
	  <span>
    </nav>
	<div class='updown'  onmouseover='visnav()'><a href='#' class='upicon'></a><a href='#footer' class='downicon'></a>
	</div>
	<div class="main">
<?php
/* 	function queryNot(){
		global $userid;
		global $mysql;
		$sql_cartnum = "SELECT COUNT(id) FROM cart WHERE customer_id = '$userid'";
		$cartnum = $mysql->fetch($mysql->query($sql_cartnum))[0];
		$sql_ordernum = "SELECT COUNT(*) FROM orders WHERE customer_id = '$userid' AND paid=0";
		$ordernum = $mysql->fetch($mysql->query($sql_ordernum))[0];
		echo "<script>changenot($cartnum,$ordernum);</script>";
	}
	queryNot(); */
	echo "<script>document.getElementById('username').innerHTML='$user';</script>";
	
?>