<script>
	function updateTotal(oid){
		var propri = document.getElementsByClassName('propri'+oid);
		var prilable = document.getElementById('totalpri'+oid);
		var totalprice = 0;
		for(var i=0;i<propri.length;i++){
			totalprice += parseFloat(propri[i].innerHTML);
		}
		prilable.innerHTML = totalprice.toFixed(1);
	}
	function delOrd(oid){
		if(confirm("Do really want to delete this order?")){
			document.getElementById('delbtn'+oid).click();
		}else{
			return false;
		}
	}
	function calcpri(fid){	
		var sigpri = document.getElementById('sigpri'+fid).innerHTML;
		var quan = document.getElementById(fid);		
		var price = parseFloat(sigpri * quan.value);	
		document.getElementById('propri'+fid).innerHTML = price.toFixed(1);
		oid = quan.className.substring(5);
		updateTotal(oid);
	}
	function showedit(oid){
		var btnedit = document.getElementById('btnEdit'+oid);
		btnedit.style.display='none';
		btnedit.nextSibling.style.display='inline';
		var inputQuan = document.getElementsByClassName('quans'+oid);
		var origvalue = document.getElementsByClassName('origvalue'+oid);
		for(var a=0;a<inputQuan.length;a++){
			inputQuan[a].style.display='inline';
			inputQuan[a].previousSibling.style.display='none';
			btnedit.nextSibling.disabled = true;
			document.getElementById('paybtn'+oid).disabled = true;
		}
		var allblock = document.getElementsByClassName('order_block');
		for(var i=0;i<allblock.length;i++){
			if(allblock[i].id != ('block'+oid)){
				allblock[i].onclick = function(){
					for(var b=0;b<inputQuan.length;b++){
						inputQuan[b].value=origvalue[b].value;
						inputQuan[b].style.display='none';
						inputQuan[b].previousSibling.style.display='inline'; 
					}
					this.onclick='';
					btnedit.style.display='';
					btnedit.nextSibling.style.display='none'; 
					document.getElementById('paybtn'+oid).disabled = false;
				}
			}else{
				allblock[i].oninput = function(){
					var totalquan = 0;
					var isorig = true;
					for(var b=0;b<inputQuan.length;b++){
						if(inputQuan[b].value < 0 || inputQuan[b].value==''){
							inputQuan[b].value=origvalue[b].value;
							isorig = true;
						}else if(inputQuan[b].value!=origvalue[b].value){
							isorig = false;
						}
						totalquan += parseInt(inputQuan[b].value);
					}
					if(totalquan<1||isorig){
						if(totalquan<1){
							delOrd(oid);
						}
						btnedit.nextSibling.disabled = true;
						document.getElementById('paybtn'+oid).disabled = true;
					}else{
						btnedit.nextSibling.disabled = false;
						document.getElementById('paybtn'+oid).disabled = false;
					}
				}
			}
		}
	}
	function paystyle(oid){
		var orderblock = document.getElementById('block'+oid);
		orderblock.style.boxShadow = '0px 0px 0px #f8f8f8';
		orderblock.style.border = '3px solid #82D7B5';
		document.getElementById('btnall'+oid).style.display = 'none';
		document.getElementById('getp'+oid).style.display = 'inline';
	}
	function payord(oid){
		if(confirm('Do you want to pay this orders?')){
			document.getElementById('btnpay'+oid).click();
		}
	}
	function getprostyle(oid){
		document.getElementById('getp'+oid).style.display='none';
		document.getElementById('btnall'+oid).style.display = 'none';
		document.getElementById('done'+oid).style.display='inline';
		document.getElementById('block'+oid).style.background = 'rgba(4,152,114,0.1)';
		document.getElementById('block'+oid).style.color = 'rgba(0,0,0,0.8)';
	}
	function getproduct(oid){
		if(confirm('Have you taken over the product? It will close the order.')){
			document.getElementById('btnget'+oid).click();
		}
	}
</script>
<div class='order'>
<?php
	if(isset($_POST['delord'])){
		$delordid = $_POST['ordid'];
		$sql_delorde = "DELETE FROM order_detail WHERE orders_id = $delordid";
		$mysql->query($sql_delorde);
		$sql_delo = "DELETE FROM orders WHERE id = $delordid";
		$mysql->query($sql_delo);
	}
	if(isset($_POST['editsubmit'])||isset($_POST['payorder'])){
		$upordid = $_POST['ordid'];
		$ary_proid = $_POST['productid'];
		$ary_quan = $_POST['quan'];
		$sql_cond = '';
		foreach($ary_quan as $key=>$value){
			if($value > 0){
				$sql_cond .= "WHEN $ary_proid[$key] THEN $value ";
			}
		}
		$sql_updateord = "UPDATE order_detail SET quantity = CASE product_id ".$sql_cond."END WHERE orders_id = $upordid";	
		$mysql->query($sql_updateord);
		if(isset($_POST['payorder'])){
			$sql_queryaddr = "SELECT province, city FROM customer WHERE id = $userid";
			$res = $mysql->fetch($mysql->query($sql_queryaddr));
			if(strlen($res[0])>1&&strlen($res[1])>1){
				$sql_payord = "UPDATE orders SET paid = 1 WHERE id = {$_POST['ordid']}";
				$mysql->query($sql_payord);
			}else{
				echo "<script>alert('Please Fill Your Fully Address Information First');window.location.href='index.php?page=user&action=profile#footer'</script>";
			}
		}
	}
	if(isset($_POST['getprobtn'])){
		$sql_getord = "UPDATE orders SET delivery_id = 1 WHERE id = {$_POST['ordid']}";
		$mysql->query($sql_getord);
		echo "<script>if(confirm('Do you want to leave comments to us?')){
						window.location.href='index.php?page=comment';
					}else{window.location.href='';}</script>";
	}
	queryNot();
	$sql_orders ="SELECT o.id,o.daytime,o.paid,o.delivery_id,d.status FROM orders AS o INNER JOIN delivery AS d ON d.id = o.delivery_id WHERE o.customer_id = '$userid' ORDER BY daytime DESC";
	$result = $mysql->query($sql_orders);
	while($row = $mysql->fetch($result)){
		$order_num = count($row);
?>
  <div class='order_block' id='block<?php echo $row['id'];?>'>
	<form method='post' action='index.php?page=user&action=order#block<?php echo $row['id'];?>' id='form<?php echo $row['id'];?>'>
	<table>
		<tr>
			<th colspan='2'><?php echo $row['daytime'];?></th>
			<th class='text-right' colspan='2'>Total Price:&nbsp;<samp>$<span id='totalpri<?php echo $row['id'];?>'></span></samp></th>
		</tr>
		<tr class='order_lable'>
			<td>Food Name</td>
			<td>Quantity</td>
			<td>Single Price</td>
			<td>Price</td>
		</tr>
<?php	
		$sql_odetail = "SELECT od.id,p.id AS productid,p.name,od.quantity,p.price,(od.quantity*p.price) AS propri FROM order_detail AS od INNER JOIN product AS p ON od.product_id = p.id WHERE orders_id = {$row['id']}";
		$res_de = $mysql->query($sql_odetail);
		while($row_de = $mysql->fetch($res_de)){
			if($row_de['quantity']>0){
?>
		<tr>
			<td><?php echo $row_de['name'];?></td>
			<td><span><?php echo $row_de['quantity'];?></span><input type='number' name='quan[]' value='<?php echo $row_de['quantity'];?>' class='quans<?php echo $row['id'];?>' id='<?php echo $row_de['id'];?>' onchange='calcpri(<?php echo $row_de['id'];?>)' min=0 max=999 required /></td>
			<input type='hidden' class='origvalue<?php echo $row['id'];?>' value='<?php echo $row_de['quantity'];?>'>
			<input type='hidden' name='productid[]' value='<?php echo $row_de['productid'];?>'>
			<td>$ <span id='sigpri<?php echo $row_de['id'];?>'><?php echo $row_de['price'];?></span></td>
			<td>$ <span class='propri<?php echo $row['id'];?>' id='propri<?php echo $row_de['id'];?>'><?php echo round($row_de['propri'],1);?></span></td>
		</tr>
<?php
			}else{
				$sql_delempty = "DELETE FROM order_detail WHERE id = {$row_de['id']}";
				$mysql->query($sql_delempty);
			}
		}
?>
	</table>
	<div class='delivery'>
	<?php
		echo $row['status'];
	?>
	</div>
	  <nav class='btn-order' id='btnall<?php echo $row['id'];?>'>
		<button type='button' onclick="delOrd(<?php echo $row['id'];?>)" outline>Delete</button>
		<input type='submit' name='delord' id='delbtn<?php echo $row['id'];?>'/>
		<input type='hidden' name='ordid' class='ordid<?php echo $row['id'];?>' value="<?php echo $row['id'];?>"/>
		<span class='btn' type="primary" onclick="showedit(<?php echo $row['id'];?>)" id="btnEdit<?php echo $row['id'];?>">Edit</span><button type="primary" name='editsubmit' disabled>Save</button>
		<button type="button" class='btn-red' onclick="payord(<?php echo $row['id'];?>)" id='paybtn<?php echo $row['id'];?>'>Pay</button>
		<input type='submit' name='payorder' id='btnpay<?php echo $row['id'];?>'>
	  </nav>
	  <nav class='btn-order'>
		<button type="button" name='getpro' class='getpro' onclick="getproduct(<?php echo $row['id'];?>)" id='getp<?php echo $row['id'];?>'>Take Over Product</button>
		<input type='submit' name='getprobtn' id='btnget<?php echo $row['id'];?>'>
	  </nav>
		<div id='done<?php echo $row['id'];?>' class='donelable'>Order Done</div>
	</form>
  </div>
  	<script>
		updateTotal(<?php echo $row['id'];?>);
		if(<?php echo $row['paid'];?>==1){
			paystyle(<?php echo $row['id'];?>);
		}
		if(<?php echo $row['delivery_id'];?>==1){
			getprostyle(<?php echo $row['id'];?>);
		}
	</script>
<?php
	}
	if(!isset($order_num)){
		echo "<mark><h3>You Have No Orders!</h3></mark>";
	}
?>
</div>
