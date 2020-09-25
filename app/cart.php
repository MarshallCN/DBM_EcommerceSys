<?php
	if(isset($_POST['proid'])){
		$proid = $_POST['proid'];
		$productquan = $_POST['productquan'];
		$sql_con = '';
	}
	if(isset($_POST['remove_lst'])){
		$remove_lst=[];
		if(!empty($_POST['remove_lst'])){
			$remove_lst = explode(',',$_POST['remove_lst']);
			for($i=0;$i<count($remove_lst)-1;$i++){
				$sql_del = "DELETE FROM cart WHERE product_id=$remove_lst[$i] AND customer_id = '$userid'";
				$mysql->query($sql_del);
			}
		}
	}
	if(isset($_POST['savecart']) && (count($remove_lst)-count($proid))<1){
		foreach($proid as $key => $value){
			$sql_con .= "WHEN $value THEN $productquan[$key] ";
		}
		$sql_upcart = "UPDATE cart SET quantity = CASE product_id ".$sql_con."END WHERE customer_id = '$userid'";
		$mysql->query($sql_upcart);
		echo "<script>alert('Save Cart Successfully!');</script>";
		
	}
	if(isset($_POST['buycart']) && (count($remove_lst)-count($proid))<1){
		$sql_newo = "INSERT orders(customer_id,daytime,paid,delivery_id) VALUES ('$userid',now(),0,2)";
		$mysql->query($sql_newo);
		$orderid = mysql_insert_id();
		foreach($proid as $key => $value){
			if(!in_array($value,$remove_lst)){
			$sql_odetail = "INSERT order_detail(orders_id,product_id,quantity) VALUES ('$orderid','$value','$productquan[$key]')";
			$mysql->query($sql_odetail);
			}
		}
		$sql_emptycart = "DELETE FROM cart WHERE customer_id = '$userid'";
		$mysql->query($sql_emptycart);
		echo "<script>window.location.href='index.php?page=user&action=order'</script>";
	}
	queryNot();
	$sql = "SELECT p.id,p.name,p.price,c.quantity,p.price*c.quantity as proprice FROM cart AS c INNER JOIN product AS p ON c.product_id = p.id WHERE customer_id = '$userid'";
	$result = $mysql->query($sql);
	$totalpri = 0;
?>
<div class='cart'>
  <table class='table-bordered' id='tbl-cart'>
    <form action='index.php?page=user&action=cart' method='post'>
	<th colspan=4><?php echo $user;?>'s Cart</th>
	<tr>
		<td id='proname'>Product Name</td>			
		<td id='sigpri'>Single Price</td>
		<td id='proquan'>Quantity</td>
		<td id='propri'>Price</td>
	</tr>
<?php
		while($row = $mysql->fetch($result)){
?>
	<tr id='tr<?php echo $row['id'];?>'>
		<td><?php echo $row['name'];?></td>
		<td>$ <span id='sigpri<?php echo $row['id'];?>'><?php echo $row['price'];?></span></td>
		<td onmousemove='vis(<?php echo $row['id'];?>)' onmouseout='hide(<?php echo $row['id'];?>)'>
			<div class='btn-cart-quan'>
				<kbd id='remove-cart<?php echo $row['id'];?>' onclick='removecart(<?php echo $row['id'];?>)'>X</kbd>
				<button id='l<?php echo $row['id'];?>' type='button' onclick='m(<?php echo $row['id'];?>)'><b>-</b></button>
				<input type='text' id='<?php echo $row['id'];?>' name='productquan[]' oninput='check(<?php echo $row['id'];?>);calcpri(<?php echo $row['id'];?>)' value='<?php echo $row['quantity'];?>' required />
				<button id='r<?php echo $row['id'];?>' type='button' onclick='a(<?php echo $row['id'];?>)'><b>+</b></button>
			</div>
		</td>
		<td>$ <span class='propris' id='propri<?php echo $row['id'];?>'><?php echo round($row['proprice'],2);?></span></td>
	</tr>
	<input type='hidden' name='proid[]' value='<?php echo $row['id'];?>'>
<?php
			$totalpri+= $row['proprice'];
		}
?>
	<tr>
		<td colspan=2>Total Price</td>
		<td colspan=2><samp>$&nbsp;<span id='totalpri'><?php echo $totalpri;?></span></samp></td>
	</tr>
	<tr>
		<td colspan=4 class='btn-cart'>
			<button type='primary' name='savecart' disabled>Save</button>
			<button type='submit' class='btn-red' name='buycart'>Buy</button>
		</td>
	</tr>
	<input type='hidden' value='' name='remove_lst'/>
	</form>
  </table>
</div>
<?php 
	if($totalpri==0){
			echo "<script>document.getElementsByClassName('btn-cart')[0].style.display='none';</script><mark><h3>You cart is empty!</h3></mark>";
		}
?>
<script>
/**function of add,minus,show,hide and check input number*/
	function a(fid){ 
		var x=document.getElementById(fid).value; 
		if(x.length==0){ x=0; }
		if(x < 999){
			document.getElementById(fid).value = parseInt(x)+1;
			calcpri(fid);
			check(fid);
		}
	}
	function m(fid){ 
		var x=document.getElementById(fid).value;
		if(x > 1){
			document.getElementById(fid).value = parseInt(x)-1;
			calcpri(fid);
		}else{
			removecart(fid);
		}
	}
	function vis(fid){
		document.getElementById('l'+fid).style.visibility = "visible";
		document.getElementById('r'+fid).style.visibility = "visible";
		document.getElementById('remove-cart'+fid).style.visibility = "visible";	
	}
	function hide(fid){
		document.getElementById('l'+fid).style.visibility = "hidden";
		document.getElementById('r'+fid).style.visibility = "hidden";
		document.getElementById('remove-cart'+fid).style.visibility = "hidden";	
	}
	function check(fid){
		var q = document.getElementById(fid)
		if (q.value < 0 || q.value > 999 || q.value != parseInt(q.value)){
			q.style.backgroundColor = "white";
			q.value = '1';
		}else if(q.value == 0 ){
			if(!removecart(fid)){
				q.value=1;
			}
		}else{
			document.getElementById(fid).style.backgroundColor = "rgba(10, 135, 84, 0.13)";
		}
	}
	function calcpri(fid){	
		var sigpri = document.getElementById('sigpri'+fid).innerHTML;
		var quan = document.getElementById(fid).value;
		var price = parseFloat(sigpri * quan);
		document.getElementById('propri'+fid).innerHTML = price.toFixed(1);
		calctotal();
	}
	function calctotal(){
		var allpri = document.getElementsByClassName('propris');
		var totalp = 0;
		for(var i=0;i<allpri.length;i++){
			totalp += parseFloat(allpri[i].innerHTML);
		}
		document.getElementById('totalpri').innerHTML=totalp.toFixed(1);
		document.getElementsByName('savecart')[0].disabled=false;
	}
	function removecart(fid){
		if(confirm("Do you really want to remove this item from your cart?")){
			document.getElementById('tr'+fid).style.display='none';
			var remove_lst = document.getElementsByName('remove_lst')[0];
			remove_lst.value += fid + ',';
			document.getElementsByName('savecart')[0].disabled=false;
			document.getElementById('propri'+fid).innerHTML=0;
			calctotal();
		}else{
			return false;
		}
	}
</script>