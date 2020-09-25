<script>
	function paystyle(oid){
		var orderblock = document.getElementById('block'+oid);
		var orderlabel = document.getElementById('label'+oid);
		orderblock.style.boxShadow = '0px 0px 0px #f8f8f8';
		orderblock.style.border = '3px solid #82D7B5';
		orderlabel.innerHTML = 'Paid&nbsp;';
	}
	function getprostyle(oid){
		var orderblock = document.getElementById('block'+oid);
		orderblock.style.background = 'rgba(4,152,114,0.1)';
		orderblock.style.color = 'rgba(0,0,0,0.8)';
		document.getElementById('block_delivery'+oid).onmouseover='';
		var selebtn = document.getElementById('delivcode'+oid);
		selebtn.disabled=true;
		selebtn.style.backgroundColor='#D7E4E1';
		document.getElementById('label'+oid).innerHTML = 'Done&nbsp;';
	}
	function errorlogist(oid){
		var orderblock = document.getElementById('block'+oid);
		orderblock.style.background = 'rgba(243,102,90,0.4)';
		orderblock.style.borderColor = 'rgba(243,102,90,0.4)';
		orderblock.style.color = 'rgba(0,0,0,0.8)';
		var selebtn = document.getElementById('delivcode'+oid);
		selebtn.style.backgroundColor='rgba(255,255,255,0.3)';
		selebtn.style.borderColor='rgba(243,102,90,0.1)';
	}
	function visedit(oid){
		var logis = document.getElementById('logis'+oid);
		logis.style.display='inline';
		this.onmouseout = function(){
			logis.style.display='none';
		}
	}
	function showMoreRow(oid){
		document.getElementById('block'+oid).className='order_blockselect';
		rows = document.getElementsByClassName('hiderow'+oid);
		for(var i=0;i<rows.length;i++){
			rows[i].style.display='';
		}
		var btn = document.getElementById('btn-morerow'+oid);
		btn.innerHTML='Hide';
		btn.onclick=function(){
			document.getElementById('block'+oid).className='order_block';
			for(var i=0;i<rows.length;i++){
				rows[i].style.display='none';
				btn.innerHTML='More';
			}
			this.onclick=function(){
				showMoreRow(oid);
			}
		}
		
	}
</script>
<div class='order'>
<?php
	include "include/timecond.php";
	$ary_logiscode = array('id'=>[],'status'=>[]);
	$sql_logiscode = "SELECT * FROM delivery"; 
	$res_logiscode = $mysql->query($sql_logiscode);
	while($row_logis=$mysql->fetch($res_logiscode)){
		array_push($ary_logiscode['id'],$row_logis['id']);
		array_push($ary_logiscode['status'],$row_logis['status']);
	}
	$sql_orders ="SELECT o.id,c.username,c.city,o.daytime,o.paid,o.delivery_id,d.id AS logiscode,d.status,(SELECT ROUND(SUM(p.price*od.quantity),1) FROM product AS p INNER JOIN order_detail AS od ON od.product_id = p.id WHERE od.orders_id = o.id) AS totalpri FROM orders AS o INNER JOIN customer AS c ON c.id = o.customer_id INNER JOIN delivery AS d ON d.id = o.delivery_id $condition ORDER BY daytime DESC";
	$result = $mysql->query($sql_orders);
	while($row = $mysql->fetch($result)){
		$order_num = count($row);
?>
  <div class='order_block' id='block<?php echo $row['id'];?>'>
	<form method='post' action='index.php?page=user&action=order#block<?php echo $row['id'];?>' id='form<?php echo $row['id'];?>'>
	  <table>
		<tr>
			<th colspan='2'><?php echo $row['username'].'&nbsp;('.$row['city'].')';?></th>
			<th class='text-right' colspan='2'><?php echo $row['daytime'];?></th>
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
		$itemsCount = 0;
		while($row_de = $mysql->fetch($res_de)){
			$itemsCount++;
			if($itemsCount<3){	
?>
		<tr>
			<td><?php echo $row_de['name'];?></td>
			<td><span><?php echo $row_de['quantity'];?></span></td>
			<td>$ <span><?php echo $row_de['price'];?></span></td>
			<td>$ <span class='propri<?php echo $row['id'];?>'><?php echo round($row_de['propri'],1);?></span></td>
		</tr>
<?php
			}else{
?>
		<tr class='hiderow<?php echo $row['id'];?>' style='display:none;'>
			<td><?php echo $row_de['name'];?></td>
			<td><span><?php echo $row_de['quantity'];?></span></td>
			<td>$ <span><?php echo $row_de['price'];?></span></td>
			<td>$ <span class='propri<?php echo $row['id'];?>'><?php echo round($row_de['propri'],1);?></span></td>
		</tr>
<?php
			}
		}
		if($itemsCount==1){
			echo "<tr><td colspan=4>&nbsp;</td></tr>";
		}else if($itemsCount>2){
			echo "<a class='morerow' id='btn-morerow{$row['id']}' onclick='showMoreRow({$row['id']})'>More</a>";
		}
?>
	  </table>	
	</form>
	<div class='delivery' onmouseover='visedit(<?php echo $row['id'];?>)' id='block_delivery<?php echo $row['id'];?>'>
		<form action='' method='post'>
			<select name='deliverycode' class='editdelivery' id='delivcode<?php echo $row['id'];?>'>
			<?php
				for($i=0;$i<count($ary_logiscode['id']);$i++){
					echo "<option value='{$ary_logiscode['id'][$i]}'>{$ary_logiscode['status'][$i]}</option>";
				}
			?>
			</select>
			<input type='hidden' name='ordid' value='<?php echo $row['id'];?>'/>
			<button type='primary' class='btn-editdelivery' name='changeLogist' id='logis<?php echo $row['id'];?>' style='display:none;'/>Submit</button>
		</form>
	</div>
	<div class='pricelabel'><samp><span id='label<?php echo $row['id'];?>'></span>$<?php echo $row['totalpri'];?></samp></div>
  </div>		
  <script>
	document.getElementById('delivcode<?php echo $row['id'];?>').value = <?php echo $row['logiscode'];?>;
	if(<?php echo $row['paid'];?>==1){
		paystyle(<?php echo $row['id'];?>);
	}
	if(<?php echo $row['delivery_id'];?>==1){
		getprostyle(<?php echo $row['id'];?>);
	}else if(<?php echo $row['delivery_id'];?>>100){
		errorlogist(<?php echo $row['id'];?>);
	}
  </script>
<?php
	}
	if(!isset($order_num)){
		echo "<mark>No <b>$checkboxAdj</b>orders for <b>$timestamp</b></mark>";
	}
	if(isset($_POST['changeLogist'])){
		$sql_changeLogist = "UPDATE orders SET delivery_id = {$_POST['deliverycode']} WHERE id = {$_POST['ordid']}";
		$mysql->query($sql_changeLogist);
		echo "<script>window.location.href='index.php'</script>";
	}
?>
</div>
