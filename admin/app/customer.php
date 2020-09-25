<?php
	$sql_cussex = "SELECT id,sex,COUNT(*) AS num, ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) AS percents FROM customer GROUP BY sex";
	$sql_cusprovince = "SELECT id,province,COUNT(*) AS num,(SELECT COUNT(*) FROM customer) AS totalnum,ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) AS percents FROM customer GROUP BY province";
	$sql_cuscity = "SELECT id,city,COUNT(*) AS num,ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) AS percents FROM customer GROUP BY city";
	$sql_cusage = "SELECT '0~10' AS age,COUNT(*) AS num,ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) AS percents FROM customer WHERE (YEAR(NOW())-YEAR(birthdate)) < 10 UNION SELECT '11~30',COUNT(*),ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) FROM customer WHERE (YEAR(NOW())-YEAR(birthdate)) BETWEEN 11 AND 30 UNION SELECT '31~50',COUNT(*),ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) FROM customer WHERE (YEAR(NOW())-YEAR(birthdate)) BETWEEN 31 AND 50 UNION SELECT '51~70',COUNT(*),ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) FROM customer WHERE (YEAR(NOW())-YEAR(birthdate)) BETWEEN 51 AND 70 UNION SELECT '71~ ',COUNT(*),ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) FROM customer WHERE (YEAR(NOW())-YEAR(birthdate)) > 71 UNION SELECT 'Unknown',COUNT(*),ROUND(COUNT(*)/(SELECT COUNT(*) FROM customer)*80,1) FROM customer WHERE birthdate IS NULL";
?>
<script>
	function vistd(tid){
		var tr = document.getElementById('td'+tid);
		tr.style.visibility = 'visible';
		this.onmouseout = function(){
			tr.style.visibility = 'hidden';
		}
	}
	function bigStyle(cate,bigid){
		document.getElementById(cate+bigid).style.fontWeight='bold';
		document.getElementById('bar_'+cate+bigid).style.background='#11FDFD';
	}
</script>
<div class='rep_customer'>
	<div class='cus_report'>
		<h3>Province</h3>
		<?php
		
			$res=$mysql->query($sql_cusprovince);
			$biggest =0;$bigid = '';
			while($row=$mysql->fetch($res)){
				if(empty($row['province'])){
					$row['province']='Unknown';
				}
				if($row['num']>$biggest){
					$biggest = $row['num'];
					$bigid = $row['id'];
				}
		?>
		  <span class='bar_lable' id='prov<?php echo $row['id'];?>'><?php echo $row['province'];?>:&nbsp;</span>
		  <div class='block_bar' id='bar_prov<?php echo $row['id'];?>'></div><?php echo $row['num'];?><br/><br/>
		  <script>document.getElementById('bar_prov<?php echo $row['id'];?>').style.width='<?php echo $row['percents'];?>%';</script>
		<?php
			}
			echo "<script>bigStyle('prov',$bigid);</script>";
		?>
	</div>
	<div class='cus_report'>
		<h3>City</h3>
		<?php
			$res=$mysql->query($sql_cuscity);
			$biggest =0;$bigid = '';
			while($row=$mysql->fetch($res)){
				if(empty($row['city'])){
					$row['province']='Unknown';
				}
				if($row['num']>$biggest){
					$biggest = $row['num'];
					$bigid = $row['id'];
				}
		?>
		  <span class='bar_lable' id='city<?php echo $row['id'];?>'><?php echo $row['city'];?>:&nbsp;</span>
		  <div class='block_bar' id='bar_city<?php echo $row['id'];?>'></div><?php echo $row['num'];?><br/><br/>
		  <script>document.getElementById('bar_city<?php echo $row['id'];?>').style.width='<?php echo $row['percents'];?>%';</script>
		<?php
			}
			echo "<script>bigStyle('city',$bigid);</script>";
		?>
	</div>
</div>
<div class='rep_customer'>
	<div class='cus_report' style='height:200px;'>
		<h3>Gender</h3>
		<?php
			$res=$mysql->query($sql_cussex);
			$biggest =0;$bigid = '';
			while($row=$mysql->fetch($res)){
				if($row['sex']==''){
					$row['sex']='Unknown';
				}else if($row['sex']==0){
					$row['sex']='Male';
				}else{
					$row['sex']='Female';
				}
				if($row['num']>$biggest){
					$biggest = $row['num'];
					$bigid = $row['id'];
				}
		?>
		  <span class='bar_lable' id='sex<?php echo $row['id'];?>'><?php echo $row['sex'];?>:&nbsp;</span>
		  <div class='block_bar' id='bar_sex<?php echo $row['id'];?>'></div><?php echo $row['num'];?><br/><br/>
		  <script>document.getElementById('bar_sex<?php echo $row['id'];?>').style.width='<?php echo $row['percents'];?>%';</script>
		<?php
			}
			echo "<script>bigStyle('sex',$bigid);</script>";
		?>
	</div>
	<div class='cus_report' style='height:340px'>
		<h3>Age</h3>
		<?php
			$res=$mysql->query($sql_cusage);
			$biggest =0;$bigid = '';
			while($row=$mysql->fetch($res)){
				if($row['num']>$biggest){
					$biggest = $row['num'];
					$bigid = $row['age'];
				}
		?>
		  <span class='bar_lable' id='sex<?php echo $row['age'];?>'><?php echo $row['age'];?>:&nbsp;</span>
		  <div class='block_bar' id='bar_sex<?php echo $row['age'];?>'></div><?php echo $row['num'];?><br/><br/>
		  <script>document.getElementById('bar_sex<?php echo $row['age'];?>').style.width='<?php echo ($row['percents']+1);?>%';</script>
		<?php
			}
			echo "<script>bigStyle('sex','$bigid');</script>";
		?>
	</div>
</div>
<table class='tbl-cus'>
	<tr>
		<td></td>
		<td>Username</td>
		<td>Real Name</td>
		<td>Gender</td>
		<td>Birthdate</td>
		<td>Phone</td>
		<td>Email</td>
		<td>Province</td>
		<td>City</td>
		<td>Address</td>
		<td>Paid</td>
	</tr>
<?php
	$sql_allusers = "SELECT c.id,username,firstname,lastname,sex,birthdate,phone,email,province,city,address,ROUND(SUM(od.quantity*p.price),1) AS paidtotal FROM customer AS c LEFT JOIN (SELECT * FROM orders WHERE paid=1) AS o ON o.customer_id=c.id LEFT JOIN order_detail AS od ON od.orders_id=o.id LEFT JOIN product AS p ON p.id=od.product_id GROUP BY c.id ORDER BY paidtotal DESC";
	$res = $mysql->query($sql_allusers);
	while($row = $mysql->fetch($res)){
		$sex = $row['sex']==0 ? 'M':'F';
		$paidtotal = $row['paidtotal']>0 ? $row['paidtotal']:0;
		echo "<tr onmouseover='vistd({$row['id']});'><form action='' method='post'>
			<td><kbd onclick=\"if(confirm('Do you want to delete this customer and all the information?')){this.nextSibling.click();}\"  id='td{$row['id']}' style='visibility:hidden;'>x</kbd><input type='submit' name='delcus' style='display:none'/>
				<input type='hidden' name='delcusid' value='{$row['id']}'/></td>
			<td>".$row['username']."</td>
			<td>".$row['firstname'].'&nbsp;'.$row['lastname']."</td>
			<td>".$sex."</td>
			<td>".$row['birthdate']."</td>
			<td>".$row['phone']."</td>
			<td>".$row['email']."</td>
			<td>".$row['province']."</td>
			<td>".$row['city']."</td>
			<td>".$row['address']."</td>
			<td>$&nbsp;".$paidtotal."</td>
			</form></tr>
		";
	}
	if(isset($_POST['delcus'])){
		$sql_delorditem = "DELETE FROM order_detail WHERE orders_id IN (SELECT id FROM orders WHERE customer_id = {$_POST['delcusid']});";
		$mysql->query($sql_delorditem);
		$sql_delord = "DELETE FROM orders WHERE customer_id = {$_POST['delcusid']}";
		$mysql->query($sql_delord);
		$sql_delcart = "DELETE FROM cart WHERE customer_id = {$_POST['delcusid']}";
		$mysql->query($sql_delcart);
		$sql_delcus = "DELETE FROM customer WHERE id =".$_POST['delcusid'];
		$mysql->query($sql_delcus);
		echo "<script>window.location.href='index.php?page=customer';</script>";
	}
?>	
</table>
<script>
	document.getElementsByClassName('main')[0].style.width='84%';
</script>