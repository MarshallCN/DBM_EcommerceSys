<script>
	function selectpic(){
		var file = document.getElementsByName('prodpic')[0].files[0];
		if (file) {
			if(file.size > 2*1024*1024){
				alert('Warning: User Logo must be less than 2 MB!');
				window.location.href='index.php?page=product';
			}else{
				var ext=file.name.substring(file.name.lastIndexOf('.'),file.name.length).toUpperCase();
				if(ext!='.BMP'&&ext!='.GIF'&&ext!='.JPG'&&ext!='.JPEG'&&ext!='.PNG'){
					alert('Please upload image file!(png,gif,jpg,bmp)');
					window.location.href='index.php?page=product';
				}else{
					document.getElementsByClassName('uppropic')[0].style.boxShadow='0px 0px 10px green';
					document.getElementsByClassName('uppropic')[0].style.border='1px solid green';
				}
			}
			document.getElementsByName('editpic')[0].value = 1;
		}
	}
	function changepic(){
		document.getElementsByName('prodpic')[0].click();
		var iseditpic = document.getElementsByName('editpic')[0];
		if(document.getElementsByName('prodpic')[0].files[0]){
			iseditpic.value = 1;
		}else{
			iseditpic.value = 0;
		}
	}
	function editprod(pid,name,price,info,imgpath){
		price=price.split(".");
		pricenum=price[0];pricedec=price[1];
		document.getElementById('btn_addnew').style.display='none';
		document.getElementById('btn_edit').style.display='inline';
		document.getElementsByClassName('btn-canceledit')[0].style.display='inline';
		document.getElementsByName('editprodid')[0].value=pid;
		document.getElementsByName('prodname')[0].value=name;
		document.getElementsByName('prodprice')[0].value=pricenum;
		document.getElementsByName('prodpricedec')[0].value=pricedec;
		document.getElementsByName('prodinfo')[0].value=info;
		document.getElementsByClassName('uppropic')[0].src=imgpath;
		window.location.href='#';
	}
	function vishide(objid){
		var ele = document.getElementById('delprod'+objid);
		ele.style.display = 'inline';
		ele.nextSibling.style.display = 'inline';
		this.onmouseout = function(){
			ele.style.display = 'none';
			ele.nextSibling.style.display = 'none';
		};
	}
</script>
<div class='addproduct'>
	<form action='' method='post' enctype="multipart/form-data">
		<img class='uppropic' src='../images/uploadpic.png' onclick="changepic()"/>
		<input type='file' name='prodpic' onchange='selectpic()' style='display:none;'/>
		<span>Product Name:&nbsp;<input type='text' name='prodname' maxlength='50' required/></span>
		<span>Price:&nbsp;$&nbsp;<input type='number' name='prodprice' min='1' max='999' required/>
		<b>.</b><input type='number' name='prodpricedec' min='0' max='9'/><br/></span>
		<textarea class='prodinfo' name='prodinfo' placeholder='Product Information...' maxlength='700' required></textarea>
		<button type='submit' class='btn-red btn-newprod' name='newprod' id='btn_addnew'>Add New</button>
		<button type='primary' class='btn-newprod' name='editprod' style='display:none;' id='btn_edit'>Edit</button>
		<button type='button' class='btn-canceledit' onclick="window.location.href='index.php?page=product'">Cancel</button>
		<input type='hidden' name='editprodid'/>
		<input type='hidden' name='editpic'/>
	</form>
</div>
<hr>
<div class='allitems'>
<?php
	if(isset($_POST['newprod'])||isset($_POST['editprod'])){
		$productName = mysqli_real_escape_string($mysql->conn, trim($_POST['prodname']));
		$productPrice = number_format(preg_replace("/\s/","",($_POST['prodprice'].'.'.$_POST['prodpricedec'])),1);
		$productInfo = mysql_real_escape_string($mysql->conn, trim($_POST['prodinfo']));
		if(!empty($productName)&&!empty($productInfo)){
			if(isset($_POST['newprod'])){
				$sql_newprod = "INSERT product(name,price,description) VALUES('$productName','$productPrice','$productInfo')";
			}elseif(isset($_POST['editprodid'])){					
				$editid = $_POST['editprodid'];
				$sql_newprod = "UPDATE product SET name='$productName',price='$productPrice',description='$productInfo' WHERE id=$editid";
			}
			$mysql->query($sql_newprod);
		}
		if($_POST['editpic']==1){
			$proid = $mysql->fetch($mysql->query("SELECT MAX(id) FROM product"))[0];
			if(is_uploaded_file($_FILES['prodpic']['tmp_name'])){
				if(move_uploaded_file($_FILES['prodpic']['tmp_name'], "../images/product/product$proid.jpg")){
					$mysql->query("UPDATE product SET imgpath = './images/product/product$proid.jpg' WHERE id = $proid");
					echo "<script>alert('Submit Product successfully!');window.location.href='index.php?page=product'</script>";	
				}else{
					echo "<script>alert('Upload picture failed');</script>";
				}
			}else{
				echo "<script>alert('No picture file!');</script>";
			}
		}
		echo "<script>alert('Submit Product successfully!');</script>";
	}
	$sql='SELECT * FROM product';
	$res=$mysql->query($sql);
	while($row=$mysql->fetch($res)){
?>
	<div class="item" onmouseover="vishide('<?php echo $row['id'];?>')">
		<img src='.<?php echo $row['imgpath'];?>'/>
		<div class="intro">
			<h3><?php echo $row['name'];?></h3>
			<p><?php echo $row['description'];?></p>
		</div>
		<div class="pri">
			<h3><samp>$<?php echo $row['price'];?></samp></h3>
		</div>
		<form action='' method='post'><input type='hidden' name='delprodid' value='<?php echo $row['id'];?>'>
		<input type='submit' name='delprod' style='display:none;'><kbd class='btn-delprod' id='delprod<?php echo $row['id'];?>' onclick="if(confirm('Do you want to Delete this product?')){this.previousSibling.click();}" style='cursor:pointer;'>x</kbd><button type='button' class='btn-add' onclick="editprod('<?php echo "{$row['id']}','{$row['name']}','{$row['price']}','{$row['description']}','.{$row['imgpath']}";?>')">Edit</button>
		</form>
	</div>
<?php
	}
	if(isset($_POST['delprod'])){
		$sql_delprod = "DELETE FROM product WHERE id = {$_POST['delprodid']}";
		$mysql->query($sql_delprod);
		echo "<script>window.location.href='index.php?page=product';alert('Delete product Successfully!');</script>";	
	}
?>
</div>
