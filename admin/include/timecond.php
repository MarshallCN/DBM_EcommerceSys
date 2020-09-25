<script>
/**if choose input week, show the input form, otherwise hide it*/
var ifweek = function (){
	var weekNumInput = document.getElementById('inputtime');
	var timestamp = document.getElementById('timestamp');
	if(timestamp.value =='input_week'){
		weekNumInput.style.display='inline';
		timestamp.onchange = function(){
			weekNumInput.style.display='none';
			timestamp.onchange = ifweek;
		};
	}
};
</script>
<form action='' method='POST' class='timecond'>
	<select name="timestamp" class="select-small" id='timestamp' onchange='ifweek()'>
		<option value='today'>Today</option>
		<option value='yesterday'>Yesterday</option>
		<option value='this_week'>This Week</option>
		<option value='this_month'>This Month</option>	
		<option value='last_month'>Last Month</option>	
		<option value='this_7_day'>In 7 Day</option>
		<option value='all' selected>All Time</option>
		<option value='input_week'>Input Week...</option>
	</select>
<?php
	$timeres = $mysql->fetch($mysql->query('SELECT YEAR(NOW()),WEEK(NOW(),1)'));
	$weeknum = $timeres[1];
	$yearnum = $timeres[0];
?>
	<div id='inputtime' style='display:none;'>
		Week:<input type='number' name='weeknum' id='weeknum' placeholder='Week' min='0' max='53' value='<?php echo $weeknum;?>'/>
		Year:<input type='number' name='yearnum' id='yearnum' placeholder='Year' min='2015' max='<?php echo $yearnum;?>' value='<?php echo $yearnum;?>'/>
	</div>
	<span id='morecheck'>
	 Unpaid only <input type='checkbox' name='unpaid'/>
	 Undelivered only <input type='checkbox' name='undelivered'/>
	</span>
	<button type='submit' class='btn-red' value='OK'>OK</button>
</form>
<?php
	/**some sql limit condition*/
	$all = 'WHERE daytime IS NOT NULL';
	$today ="WHERE DATE(daytime) =(CURRENT_DATE())";
	$yesterday ="WHERE DATE(daytime) = DATE_SUB(CURDATE(),INTERVAL 1 DAY)";
	$this_7_day = "WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= DATE(daytime)";
	$this_month = "WHERE MONTH(daytime) = MONTH(NOW())";
	$last_month = "WHERE MONTH(daytime) = MONTH(DATE_SUB(NOW(),INTERVAL 1 MONTH))";
	$this_week = "WHERE WEEK(daytime,1) = WEEK(NOW(),1)";
	if(isset($_POST['timestamp'])){
		if(isset($_POST['weeknum']) && $_POST['timestamp']=='input_week'){
/**get the week number, change it on the input form, and change sql condition*/		
			$timestamp = 'this_week';
			$condition = $this_week;
			echo "<script>document.getElementById('timestamp').value = '$timestamp'</script>";
			if(!empty($_POST['yearnum'])){$yearnum = $_POST['yearnum'];}
			if($_POST['weeknum']!=''){$weeknum = $_POST['weeknum'];}
			$timestamp = "week $weeknum of $yearnum";
			$condition = "WHERE WEEK(daytime,1) = $weeknum AND YEAR(daytime) = $yearnum";
			echo "<script>document.getElementById('timestamp').value = 'input_week';ifweek();document.getElementById('weeknum').value='$weeknum';</script>";
		}else{
			$timestamp=$_POST['timestamp'];
			$condition = $$timestamp;
			echo "<script>document.getElementById('timestamp').value = '$timestamp'</script>";
		}
	}else{
		$condition=$all;
		$timestamp='all';
	}
 	if(isset($_POST['unpaid'])){
		echo "<script>document.getElementsByName('unpaid')[0].checked = true</script>";
		$condition .= ' AND paid = 0';
		$checkboxAdj = 'unpaid ';
	}else{
		$checkboxAdj = '';
	}
	if(isset($_POST['undelivered'])){
		echo "<script>document.getElementsByName('undelivered')[0].checked = true</script>";
		$condition .= ' AND delivery_id > 1';
		$checkboxAdj .= 'undelivered ';
	}
?>