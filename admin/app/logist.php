<?php
include "include/timecond.php";
$sql_sendwhere = "SELECT c.id,c.province,COUNT(*) AS num, ROUND((COUNT(*)/(SELECT COUNT(*) FROM orders $condition AND paid=1))*80,1) AS percents FROM orders AS o JOIN customer AS c ON c.id = o.customer_id $condition AND paid=1 GROUP BY c.province";
$sql_delivery = "SELECT d.id,d.status,COUNT(*) AS num, ROUND(COUNT(*)/(SELECT COUNT(*) FROM orders  $condition AND paid=1)*80,1) AS percents FROM delivery AS d LEFT JOIN orders AS o ON o.delivery_id=d.id  $condition AND paid=1 GROUP BY d.id";
?>
<script>
	document.getElementById('morecheck').style.display='none';
</script>
<div class='rep_customer' style='width:96%;'>
	<div class='cus_report'>
		<h3>Province/Delivering Orders</h3>
		<?php
			$res=$mysql->query($sql_sendwhere);
			while($row=$mysql->fetch($res)){
		?>
		  <span class='bar_lable' id='prov<?php echo $row['id'];?>' style='width:200px;'><?php echo $row['province'];?>:&nbsp;</span>
		  <div class='block_bar' id='bar_prov<?php echo $row['id'];?>'></div>&nbsp;<?php echo $row['num'];?><br/><br/>
		  <script>document.getElementById('bar_prov<?php echo $row['id'];?>').style.width='<?php echo $row['percents'];?>%';</script>
		<?php
			}
			if(mysqli_num_rows($res)==0){
				echo "<mark>No Order $timestamp</mark>";
			}
		?>
	</div>
	<div class='cus_report'>
		<h3>Delivery Status</h3>
		<?php
			$res=$mysql->query($sql_delivery);
			while($row=$mysql->fetch($res)){
		?>
		  <span class='bar_lable' id='deli<?php echo $row['id'];?>' style='width:200px;'><?php echo $row['status'];?>:&nbsp;</span>
		  <div class='block_bar' id='bar_deli<?php echo $row['id'];?>'></div>&nbsp;<?php echo $row['num'];?><br/><br/>
		  <script>document.getElementById('bar_deli<?php echo $row['id'];?>').style.width='<?php echo $row['percents'];?>%';</script>
		<?php
			}
			if(mysqli_num_rows($res)==0){
				echo "<mark>No Order $timestamp</mark>";
			}
		?>
	</div>
</div>


