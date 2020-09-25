<?php
include "include/timecond.php";
$sql_report = "SELECT SUM(quantity) AS totalquan,ROUND(SUM(p.price*od.quantity),1) AS totalprice FROM order_detail AS od INNER JOIN orders AS o ON o.id=od.orders_id INNER JOIN product AS p ON p.id = od.product_id $condition AND o.paid = 1";
$res = $mysql->fetch($mysql->query($sql_report));
$totalquan = empty($res[0]) ? 0:$res[0];
$totalprice = empty($res[1]) ? 0:$res[1];
$sql_order = "SELECT COUNT(o.id) AS ordernum,COUNT(DISTINCT c.id) AS cusnum FROM orders AS o INNER JOIN customer AS c ON c.id=o.customer_id $condition";
$res1 = $mysql->fetch($mysql->query($sql_order));
$ordernum = $res1[0];
$cusnum = $res1[1];
$paidordnum = $mysql->fetch($mysql->query("SELECT COUNT(*) FROM orders $condition AND paid = 1"))[0];
$sql_cart = "SELECT SUM(ca.quantity) AS itemnum,ROUND(SUM(p.price*ca.quantity),1) AS totalpri FROM cart AS ca INNER JOIN product AS p ON p.id=ca.product_id";
$res2 = $mysql->fetch($mysql->query($sql_cart));
$cartnum = $res2[0];
$cartprice = $res2[1];
$sql_area = "SELECT c.province,ROUND(SUM(p.price*od.quantity),1) AS totalpri FROM order_detail AS od INNER JOIN orders AS o ON o.id=od.orders_id INNER JOIN product AS p ON p.id = od.product_id INNER JOIN customer AS c ON c.id = o.customer_id $condition AND o.paid =1 GROUP BY c.province ORDER BY totalpri";
$res3 = $mysql->query($sql_area);
$pribase=0;
$ary_areas =array('province'=>[],'totalpri'=>[]);
while($row=$mysql->fetch($res3)){
	array_push($ary_areas['province'],$row['province']);
	array_push($ary_areas['totalpri'],$row['totalpri']);
	$pribase += $row['totalpri'];
}
if(!empty($ary_areas['province'])){
	$leastarea = $ary_areas['province'][0];
	$mostarea = $ary_areas['province'][count($ary_areas['province'])-1];
	if($mostarea==$leastarea){
		$leastarea = 'No';
	}
}else{
	$leastarea = 'No';
	$mostarea = 'No';
}
?>
<script>
	document.getElementById('morecheck').style.display='none';
</script>
<div class='sales_report'><br/>
<?php 
	if(empty($res[0])){
		echo "<mark>No Sales in $timestamp</mark><br/><br/>";
	}else{
?>
	<p>The Revenue of <samp><?php echo $timestamp;?></samp> is <mark>$<?php echo $totalprice;?></mark>,</p>
	<p><samp><?php echo $totalquan;?></samp> products has been bought, and <samp><?php echo $paidordnum;?></samp> orders has been paid,</p>
	<p><samp><?php echo $ordernum;?></samp> orders has been created, which from <samp><?php echo $cusnum;?></samp> customers.</p>
	<p><samp><?php echo $mostarea;?></samp> Province generates the <b>Most</b> amount of revenue,</p>
	<p><samp><?php echo $leastarea;?></samp> Province create the <b>Least</b> amount of revenue.</p><br/>
<?php }
?>
	<p>There're still <samp><?php echo $cartnum;?></samp> items in customers' carts <b>now</b> in total, </p>
	<p>which have <mark>$<?php echo $cartprice;?></mark> potential revenue.</p>
</div>
<?php
	if(count($ary_areas['province']!==0)){
?>
<div class='sales_grap'>
	<div class='cus_report'>
		<h3>Province</h3>
		<?php
			for($i=0;$i<count($ary_areas['province']);$i++){
				$percents = ($ary_areas['totalpri'][$i]/$pribase)*80;
		?>
		  <span class='bar_lable' id='province<?php echo $ary_areas['province'][$i];?>' style='width:120px;'><?php echo $ary_areas['province'][$i];?>:&nbsp;</span>
		  <div class='block_bar' id='bar_province<?php echo $ary_areas['province'][$i];?>'></div>&nbsp;$<?php echo $ary_areas['totalpri'][$i];?><br/><br/>
		  <script>document.getElementById('bar_province<?php echo $ary_areas['province'][$i];?>').style.width='<?php echo $percents;?>%';</script>
		<?php
			}
		?>
	</div>
</div>
<?php
	$sql_products = "SELECT p.id,p.name,ROUND(SUM(p.price*od.quantity),1) AS totalprice FROM product AS p INNER JOIN order_detail AS od ON od.product_id=p.id INNER JOIN orders AS o ON o.id=od.orders_id $condition AND paid=1 GROUP BY p.id ORDER BY totalprice DESC";
?>
<div class='sales_grap'>
	<div class='cus_report'>
		<h3>Products</h3>
		<?php
			$res=$mysql->query($sql_products);
			$biggest=0;$bigid='';
			while($row=$mysql->fetch($res)){
				$percent = ($row['totalprice']/$totalprice)*80;
		?>
		  <span class='bar_lable' id='products<?php echo  $row['id'];?>' style='width:120px;'><?php echo $row['name'];?>:&nbsp;</span>
		  <div class='block_bar' id='bar_products<?php echo  $row['id'];?>'></div>&nbsp;$<?php echo  $row['totalprice'];?><br/><br/>
		  <script>document.getElementById('bar_products<?php echo $row['id'];?>').style.width='<?php echo $percent;?>%';</script>
  <?php
			}
	}
	?>
	</div>
</div>
