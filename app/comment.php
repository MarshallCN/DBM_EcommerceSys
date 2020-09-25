<script>
	function replysay(floorid,replyname){
		var texts = document.getElementById('texts');
		var idbox = document.getElementsByName('replyid')[0];
		if(floorid !== ''){
			if(replyname == ''){
				replyname = 'DBM admin';
			}
			idbox.value = floorid;
			texts.placeholder = 'Reply '+replyname+' on Floor '+floorid+':';
			window.location.href = '#';
		}else{
			idbox.value = '';
			texts.placeholder = '';
		}
	}
	function checksay(){
		if(document.getElementById('texts').value.replace(/\s+/g,"").length<3){
			alert('Comments must longer than 3 word!');
		}
	}
	function vishide(objid){
		var ele = document.getElementById('btn-delcom'+objid);
		ele.style.display = 'inline';
		this.onmouseout = function(){
			ele.style.display = 'none';
		};
	}
</script> 
<div class="comment">
	<div class="say">
	  <form method='post' action='index.php?page=comment#footer'>
		<div class="userintro">
			<img src='<?php echo $userlogo;?>' class='userthumb'/>
			<p class='cusname'><?php echo (!isset($_SESSION['admin']) ? "<samp>$user</samp>" : "<mark>$user</mark>" );?></p>
			<button type='button' class='sendinfo' onclick="if(confirm('Do you want to reset?')){this.nextSibling.click();}">Reset</button><button type='reset' class='sendinfo' onclick="replysay('','')" style='display:none;'>Reset</button>
			<button type='primary' name='submit' class='sendinfo' onmousedown="checksay()">Send</button>
		</div>
		<textarea class="saycontent" id="texts" placeholder='' name='texts' maxlength=700></textarea>
		<input type='hidden' name='replyid'/>
	  </form>
	</div>
	<hr/>
<?php
	if(isset($_POST['submit'])){
		if(!empty($_POST['texts'])){
			$texts = mysqli_real_escape_string($mysql->conn,$_POST['texts']);
			if(!empty($_POST['replyid'])){
				$replyid = $_POST['replyid'];
			}else{
				$replyid = 'NULL';
			}
			if(isset($_SESSION['admin'])){
				$userid = 'NULL';
			}
			$sql_say = "INSERT comments (customer_id,texts,reply_id) VALUES ($userid,'$texts',$replyid)";
			$mysql->query($sql_say);
		}
	}
	if(strpos($_SERVER["REQUEST_URI"],'admin')){
		$logopath = "../images/userlogo/";
	}else{
		$logopath = "./images/userlogo/";
	}
	$sql_comments = "SELECT s.id,s.customer_id,s.texts,s.reply_id,c.username,c1.username AS replyname FROM comments AS s LEFT JOIN customer AS c ON c.id=s.customer_id LEFT JOIN comments AS s1 ON s.reply_id = s1.id LEFT JOIN customer AS c1 ON c1.id=s1.customer_id ORDER BY s.id;";
	$result = $mysql->query($sql_comments);
	while($row = $mysql->fetch($result)){
		if(file_exists($logopath.$row['username'].$row['customer_id'].".jpg")){
			$userthumb = $logopath.$row['username'].$row['customer_id'].".jpg";
		}else{
			$userthumb = $logopath."user.jpg";
		}
		if(empty($row['customer_id'])){
			$userthumb = (isset($_SESSION['admin']) ? $userlogo : "./images/userlogo/admin.jpg");
			$showname = '<mark>admin</mark>';
		}else{
			$showname = '<samp>'.$row['username'].'</samp>';
		}
		if(!empty($row['reply_id'])){
			$texts = "<a href='#floor{$row['reply_id']}'>Reply {$row['replyname']} on the Floor {$row['reply_id']}</a>: ".$row['texts'];
		}else{
			$texts = $row['texts'];
		}
?>	
	<div class="say" id="floor<?php echo $row['id'];?>">
		<div class="userintro">
			<p class='cusname'><?php echo $showname;?></p>
			<img src='<?php echo $userthumb;?>' class='userthumb'/>
			<button type='button' class='sendinfo' onclick="replysay(<?php echo $row['id'].",'".$row['username']."'";?>)">Reply</button>
			<form action='' method='post'>
			  <p class='floor'>Floor <?php echo $row['id'];?><kbd class='delcomment' id='btn-delcom<?php echo $row['id'];?>' onclick="if(confirm('Do you want to Delete this comment?')){this.nextSibling.click();}">x</kbd><input type='submit' style='display:none;'/><input type='hidden' name='delcom' value='<?php echo $row['id'];?>'/></p>
			</form>
		</div>
		<div class="saycontent"><?php echo $texts;?></div>
	</div>
	
<?php
		if($user==$row['username']){
			echo "<script>document.getElementById('floor{$row['id']}').onmouseover=function(){vishide({$row['id']});};
					document.getElementById('floor{$row['id']}').style.border='2px solid #619AC9';</script>";
		}else if(isset($_SESSION['admin'])){
			echo "<script>document.getElementById('floor{$row['id']}').onmouseover=function(){vishide({$row['id']});};</script>";
		}
	}
	if(isset($_POST['delcom'])){
		$sql_delcom = 'DELETE FROM comments WHERE id = '.$_POST['delcom'];
		$mysql->query($sql_delcom);
		echo "<script>window.location.href='index.php?page=comment';</script>";
	}
?>	
</div>
