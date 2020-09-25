<script>
/**function of add,minus,show,hide and check input number*/
	function a(fid){ 
		var x=document.getElementById(fid).value; 
		if(x.length==0){ x=0; }
		if(x < 999){
			document.getElementById(fid).value = parseInt(x)+1;
		}
	}
	function m(fid){ 
		var x=document.getElementById(fid).value;
		if(x > 0){
			document.getElementById(fid).value = parseInt(x)-1;
		}
	}
	function vis(fid){
		document.getElementById('l'+fid).style.visibility = "visible";
		document.getElementById('r'+fid).style.visibility = "visible";
		document.getElementById(fid).style.visibility = "visible";
		document.getElementById('buy'+fid).style.visibility = "visible";
	}
	function hide(fid){
		document.getElementById('l'+fid).style.visibility = "hidden";
		document.getElementById('r'+fid).style.visibility = "hidden";
		var quan = document.getElementById(fid);
		if(quan.value==''){
			quan.style.visibility = "hidden";
			document.getElementById('buy'+fid).style.visibility = "hidden";
		}
	}
	function check(fid){
		var q = document.getElementById(fid).value;
		if(q!=''){
			if (q <= 0 || q > 999 || q != parseInt(q)){
				document.getElementById(fid).value = '';
				document.getElementById(fid).style.backgroundColor = "white";
			}else{
				document.getElementById(fid).style.backgroundColor = "rgba(10, 135, 84, 0.13)";
			}
		}
	}
	function buyone(fid){
		var q = document.getElementById(fid).value;
		if(q<1){
			a(fid);
		}
	}
	function moreinfo(fid){
		document.getElementById('pagemask').style.display='block';
		document.getElementById('btn-close'+fid).style.display='block';
		document.getElementById('block_item'+fid).className = 'itemselect';
	}
	function colsemore(fid){
		document.getElementById('block_item'+fid).className = 'item';
		document.getElementById('btn-close'+fid).style.display='none';
		document.getElementById('pagemask').style.display='none';
	}
</script>
<?php
	if(isset($_POST['buy'])){
		if($user!='Anonymous'){
			$sql_order = "INSERT orders VALUES('','$userid',now(),0,2)";
			$mysql->query($sql_order);
			$orderid = mysql_insert_id();
			$sql_detail = "INSERT order_detail VALUES('','$orderid','{$_POST['productid']}','{$_POST['quantity']}')";
			$mysql->query($sql_detail);
			echo "<script>alert('Buy one Successfully!');window.location.href='index.php?page=user&action=order'</script>";
		}else{
			echo "<script>if(confirm('You can buy product after logging in. Do you want to log in/ sign in now?')){window.location.href='index.php?logout'};</script>";
		}
	}elseif(isset($_POST['add'])){
		if($user!='Anonymous'){
			$sql_cart_search = "SELECT quantity FROM cart WHERE customer_id = '$userid' AND product_id = {$_POST['productid']}";
			$res_quan = $mysql->fetch($mysql->query($sql_cart_search))[0];
			if($res_quan > 0){
				$quantity = (int)$res_quan + (int)$_POST['quantity'];
				$sql_cart = "UPDATE cart SET quantity = '$quantity' WHERE customer_id = '$userid' AND product_id = {$_POST['productid']}";
			}else{
				$sql_cart = "INSERT cart VALUES ('','$userid','{$_POST['productid']}','{$_POST['quantity']}')";
			}
			$mysql->query($sql_cart);
			echo "<script>alert('Add Product to Cart Successfully');</script>";
			//header("Location:index.php?page=user&action=cart");
		}else{
			echo "<script>if(confirm('You can add product to cart after logging in. Do you want to log in/ sign in now?')){window.location.href='index.php?logout'};</script>";
		}
	}
	queryNot();
?>	
	<div class='orderopt'>
	<form action='index.php?page=product' method='post'>
		<label>Most Famous: <input type='radio' name='orderby' value='0'/></label>
		<label>Cheapest <input type='radio' name='orderby' value='1'/></label>
		<label>Most Expensive <input type='radio' name='orderby' value='2'/></label>
		<button type='submit' class='btn-red' value='OK' name='ordercond'>OK</button>
	</form>
	</div>
<?php
	if(isset($_POST['orderby'])){
		switch($_POST['orderby']){
			case 0: $orderby='ORDER BY totalquan DESC';break;
			case 1: $orderby='ORDER BY price';break;
			case 2: $orderby='ORDER BY price DESC';break;
			default: $orderby='';
		}
		echo "<script>document.getElementsByName('orderby')[{$_POST['orderby']}].checked=true;</script>";
	}else{
		$orderby = '';
	}
	$sql_productinfo="SELECT p.id,p.name,p.price,p.description,p.imgpath,SUM(od.quantity) AS totalquan FROM product AS p LEFT JOIN order_detail AS od ON od.product_id = p.id GROUP BY p.id $orderby";
	$res=$mysql->query($sql_productinfo);
	while($row=$mysql->fetch($res)){
?>
	<div class="item" onmousemove='vis(<?php echo $row['id'];?>)' onmouseout='hide(<?php echo $row['id'];?>)' id='block_item<?php echo $row['id'];?>'>
		<kbd class='closeinfo' id='btn-close<?php echo $row['id'];?>' onclick='colsemore(<?php echo $row['id'];?>)'>x</kbd>
		<img src='<?php echo $row['imgpath'];?>' title='Click to view more information' onclick='moreinfo(<?php echo $row['id'];?>)' id='itemimg<?php echo $row['id'];?>'>
		<div class="intro">
			<h3><?php echo $row['name'];?></h3>
			<p><?php echo $row['description'];?></p>
			<p>Sold <b><?php echo empty($row['totalquan'])? 0:$row['totalquan'];?></b></p>
		</div>
		<div class="pri">
			<h3><samp>$<?php echo $row['price'];?></samp></h3>
		</div>
		<form action='' method='post'>
		  <div class='btn-quan' onmouseout='check(<?php echo $row['id'];?>)'>
			<button id='l<?php echo $row['id'];?>' type='button' onclick='m(<?php echo $row['id'];?>)'><b>-</b></button>
			<input type='text' id='<?php echo $row['id'];?>' name='quantity'/>
			<button id='r<?php echo $row['id'];?>' type='button' onclick='a(<?php echo $row['id'];?>)'><b>+</b></button>
		  </div>
		  <input type='hidden' name='productid' value='<?php echo $row['id'];?>'>
		  <button type='primary' name='buy' class='btn-buy' id='buy<?php echo $row['id'];?>' oninput='check(<?php echo $row['id'];?>)' onmousedown='buyone(<?php echo $row['id'];?>)' outline>Buy Now!</button>
		  <button type='submit' name='add' class='btn-red btn-add' onmousemove='check(<?php echo $row['id'];?>)' onmousedown='buyone(<?php echo $row['id'];?>)' >Add to Cart</button>
		</form>
	</div>
<?php
	}
?>
