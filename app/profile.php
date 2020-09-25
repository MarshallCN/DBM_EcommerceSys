<?php
function procdata($name){
	global $userinfo;
	if(isset($_POST[$name])){
		if($_POST[$name]==''){
			$$name=$userinfo[$name];
		}else{
			$$name=$_POST[$name];
		}
	}else{
		$$name='';
	}
	return $$name;
}
function changesubmit($sql){
	global $mysql;
	$mysql->query($sql);
	echo "<script>alert('Change Profile Successully');
				window.location.href='index.php?page=user&action=profile';
		</script>";
}
$sql_userinfo = "SELECT * FROM customer WHERE id = '$userid'";
$res = $mysql->query($sql_userinfo);
$userinfo = $mysql->fetch($res);
if(isset($_POST['changepwd'])){
	$sql = "SELECT password FROM customer WHERE id = '$userid'";
	$res=$mysql->query($sql);
	$pwd = $mysql->fetch($res)[0];
	if($pwd == md5($_POST['origpwd'])){
		$newpwd = $_POST['pwd'];
		$sql_cpwd = "UPDATE customer SET password = md5('$newpwd') WHERE id = '$userid'";
		$mysql->query($sql_cpwd);
		unset($_SESSION['user']);
		echo "<script>alert('Change password successfully! Please log in again with your new password');
					window.location.href='index.php';
			</script>";
	}else{
		echo "<script>alert('Wrong Original Password!');</script>";
	}
}
if(isset($_POST['changephone'])||isset($_POST['changeemail'])){
	if(isset($_POST['phone'])){$sql="UPDATE customer SET phone = '{$_POST['phone']}' WHERE id = '$userid'";}
	if(isset($_POST['email'])){$sql="UPDATE customer SET email = '{$_POST['email']}' WHERE id = '$userid'";}
	changesubmit($sql);
}

if(isset($_POST['changeperson'])){	
	$firstname=procdata('firstname');
	$lastname=procdata('lastname');
	$sex=procdata('sex');
	$birthdate=procdata('birthdate');
	$sql = "UPDATE customer SET firstname='$firstname',lastname='$lastname',sex='$sex',birthdate='$birthdate' WHERE id = '$userid'";
	changesubmit($sql);
}
if(isset($_POST['changeaddress'])){
	$province=procdata('province');
	$city=procdata('city');
	$address=procdata('address');
	$sql = "UPDATE customer SET province='$province',city='$city',address='$address' WHERE id = '$userid'";
	changesubmit($sql);
}
if(isset($_POST['changepic'])){
	if(is_uploaded_file($_FILES['userpic']['tmp_name'])){
		if(move_uploaded_file($_FILES['userpic']['tmp_name'], "./images/userlogo/$user$userid.jpg")){
			echo "<script>alert('Upload user logo successfully!');</script>";
			header("Location:index.php?page=user&action=profile");
		}else{
			echo "<script>alert('Upload failed');window.location.href='index.php?page=user&action=profile';</script>";
		}
	}else{
		echo "<script>alert('No file!');</script>";
	}
}
?>
	<table class="table-bordered" id='tbl-profile'>
		<th colspan='2'>
			<?php echo $user;?>'s Profle
		</th>
		<tr>
			<th>
				Edit User Logo
			</th>
			<td>
				<form method='post' enctype="multipart/form-data">
					<img src='<?php echo $userlogo;?>' id='thumblogo'/>
					<input type='file' name='userpic' onchange='selectpic();' required />
					<button type='submit' name='changepic'>Submit</button>
				</form>
			</td>
		</tr>
		<tr>
			<th>
				Personal Information
			</th>
			<td>
			  <form method='post' action=''>
				<div class='form-profile'>
					First Name&nbsp;<input type='text' name='firstname' value='<?php echo $userinfo['firstname'];?>' maxlength=20/><br/>
					Last Name&nbsp;<input type='text' name='lastname' value='<?php echo $userinfo['lastname'];?>' maxlength=20/><br/>
					<span id='glable'>Gender:</span>Male <input type='radio' name='sex' value='0' id='male'/>
					&nbsp; Female <input type='radio' name='sex' value='1' id='female'/><br/>
					<input type='hidden' id='genderbool' value='<?php if($userinfo['sex']!=''){echo $userinfo['sex'];}else{echo 3;}?>'/>
					<span id='blable'>Birthdate:</span><input type='date' name='birthdate' value='<?php echo $userinfo['birthdate'];?>' /><br/>
				</div>
					<button type='submit' name='changeperson'>Submit</button>
			  </form>
			</td>
		</tr>
		<tr>
			<th>
				Change Password
			</th>
			<td>
			  <form method='post' action=''>
				<div class='form-profile'>
					Original Password&nbsp;<input type='password' name='origpwd' required /><br/>
					New Password&nbsp;<input type='password' name='pwd' onchange='checkpwd()' required maxlength=20/><kbd class='seepwd' onmousedown="seepwd(document.getElementsByName('pwd')[0])">o</kbd><br/>
					New Password Again&nbsp;<input type='password' name='pwd' onchange='checkpwd()' required /><kbd class='seepwd' onmousedown="seepwd(document.getElementsByName('pwd')[1]);checkpwd()">o</kbd><br/>
				</div>
					<button type='submit' name='changepwd' onmouseover='checkpwd()' disabled>Submit</button>
		      </form>
			</td>
		</tr>
		<tr>
			<th>
				Change Phone
			</th>
			<td>
			  <form method='post' action=''>
				<div class='form-profile'>
					Phone Number&nbsp;<input type='tel' name='phone' value='<?php echo $userinfo['phone'];?>' maxlength=30 required /><br/>
				</div>
					<button type='submit' name='changephone'>Submit</button>
			  </form>
			</td>
		</tr>
		<tr>
			<th>
				Change Address
			</th>
			<td>
			  <form method='post' action=''>
				<div class='form-profile'>
					Province&nbsp;<input type='text' name='province' value='<?php echo $userinfo['province'];?>' placeholder='Sichuan' maxlength=30 required /><br/>
					City&nbsp;<input type='text' name='city' value='<?php echo $userinfo['city'];?>' placeholder='ChengDu' maxlength=30 required /><br/>
					Address&nbsp;<input type='text' name='address' value='<?php echo $userinfo['address'];?>' maxlength=50 required /><br/>
				</div>
					<button type='submit' name='changeaddress'>Submit</button>
			  </form>
			</td>
		</tr>
		<tr>
			<th>
				Change Email
			</th>
			<td>
				<form method='post' action=''>
				<div class='form-profile'>
					Email&nbsp;<input type='email' name='email' value='<?php echo $userinfo['email'];?>' maxlength=50 required /><br/>
				</div>
					<button type='submit' name='changeemail'>Submit</button>
			  </form>
			</td>
		</tr>
		<!--<tr>
		  <td colspan=2>
			<form method='post' action=''>
				<button type='primary' name='saveall' id='btn-saveall'>Save All</button>
			</form>
		  </td>
		</tr>-->
	</table>
	<script>
		if(document.getElementById('genderbool').value==0){
			document.getElementById('male').checked = true;
		}else if(document.getElementById('genderbool').value==1){
			document.getElementById('female').checked = true;
		}
		var allinput = document.getElementsByTagName('input');
			for(var i=0;i < allinput.length;i++){
				allinput[i].oninput = function(){
				this.value=this.value.replace(' ','');
			}
		}
		function checkpwd(){
			var pwd = document.getElementsByName('pwd');
			var origpwd = document.getElementsByName('origpwd')[0].value;
			var btnsubmit = document.getElementsByName('changepwd')[0];
			if(origpwd == pwd[0].value){
				alert('The new password cannot be same as original one!');
				pwd[0].value = '';
				pwd[1].className = 'input-error';
				btnsubmit.disabled = true;
			}else{
				if(pwd[0].value.length<4){
					alert("Your password length is too short! Please type more than 3 word");
					pwd[0].value = '';
					pwd[0].className = 'input-error';
					btnsubmit.disabled = true;
				}else{
					if(pwd[0].value == pwd[1].value){
						pwd[0].className = 'input-success';
						pwd[1].className = 'input-success';
						btnsubmit.disabled = false;
					}else{
						pwd[1].className = 'input-error';
						pwd[1].value = '';
						btnsubmit.disabled = true;
					}
				}
			}
		}
		function selectpic(){
			var file = document.getElementsByName('userpic')[0].files[0];
			if (file) {
				if(file.size > 2*1024*1024){
					alert('Warning: User Logo must be less than 2 MB!');
					window.location.href='index.php?page=user&action=profile';
				}
				var ext=file.name.substring(file.name.lastIndexOf('.'),file.name.length).toUpperCase();
				if(ext!='.BMP'&&ext!='.GIF'&&ext!='.JPG'&&ext!='.JPEG'&&ext!='.PNG'){
					alert('Please upload image file!(png,gif,jpg,bmp)');
					window.location.href='index.php?page=user&action=profile';
				}
			}
		}
		function seepwd(pwd){
			pwd.type='text';
			this.onmouseup = function(){
				pwd.type='password';
			};
		}
	</script>