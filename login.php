<?php 
	session_start();
	require "include/db.php";
	include 'include/header.html';
?>
 <script>
	function seepwd(pwd){
		pwd.type='text';
		this.onmouseup = function(){
			pwd.type='password';
		};
	}
	function changeStyle(name,classname){
		var ele = document.getElementsByName(name)[0];
		ele.className = classname;
	}
	function checkpwd(){
		var pwd = document.getElementsByName('pwd');
		var btnsubmit = document.getElementsByName('sign')[0];
		if(pwd[0].value.length<4){
			alert("Your password length is too short! Please type more than 3 word");
			pwd[0].className = 'input-error';
			pwd[0].value = '';
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
  </script>
  <body>
    <div class='log-container'>
	<img src='images/IMG_4476.jpg' alt='' id='mainimg'/>
	  <div class='log-leftpart'>	
		<div id='dbmlogo'>Dutch Baby Milk</div>
<?php
	if(isset($_GET['admin'])&&!isset($_GET['sign'])){
		$admin = 'admin';
?>
		  <div id='adminlogo'>Administration System</div>
	  </div>
	  <a href='login.php'><div class='admin_lable'>Customer</div></a>
<?php
	}else{
?>
		  <a href='index.php?new'><div class='sign-log'>Log in Later</div></a>
		  <a href='login.php?sign'><div class='sign-log' id='btn-signin'>Sign in</div></a>
	  </div>
	  <a href='login.php?admin'><div class='admin_lable'>Admin</div></a>
 <?php
	}
      if(isset($_GET['sign'])){
		echo "<script>var signin = document.getElementById('btn-signin');
					signin.innerHTML='Log in';
					signin.parentNode.href='login.php';
			</script>";
			$sql_username = "SELECT username FROM customer";
			$res = $mysql->query($sql_username);
			$ary_username=[];
			while($row = $mysql->fetch($res)){
				$ary_username[]=$row['username'];
			}
  ?>
		<div class='signin-form'>
			<h1>Sign in DBM</h1>
			<form action='' method='post'>
				Username:&nbsp;<span class='req'>*</span><input type='text' name='username' maxlength=10 placeholder='David' required /><br/>
				Password:&nbsp;<span class='req'>*</span><input type='password' name='pwd' onchange='checkpwd()' maxlength=20 placeholder='1234' required /><kbd class='seepwd' onmousedown="seepwd(document.getElementsByName('pwd')[0])">o</kbd><br/>
				Password Again:&nbsp;<span class='req'>*</span><input type='password' name='pwd' onchange='checkpwd()'  maxlength=20 placeholder='1234' required /><kbd class='seepwd' onmousedown="seepwd(document.getElementsByName('pwd')[1])" onclick='checkpwd()'>o</kbd><br/>
				Phone:&nbsp;<span class='req'>*</span><input type='tel' name='phone' maxlength=22 placeholder='1234567890' required /><br/>
				Address:&nbsp;<span class='req'>*</span><input type='text' name='address' maxlength=100 placeholder='CDUT_Str.123' required /><br/>
				Email:&nbsp;<input type='email' name='email' maxlength=50 placeholder='David@gmail.com' /><br/>
				<br/><button type='submit' class='btn-red' name='sign' disabled />Sign in</button>
			</form>
<?php
	if(isset($_POST['sign'])){
			$username = ucfirst(strtolower($_POST['username']));
			$password = preg_replace("/\s/","",$_POST['pwd']);
			$phone = $_POST['phone'];
			$address = $_POST['address'];
			$email = $_POST['email'];
			if(in_array($username,$ary_username)){
				echo "Username already exists!";
			}else{
				if(strlen($password)>3){
					$sql_signin = "INSERT customer (username,password,phone,email,address) VALUES ('$username',md5('$password'),'$phone','$email','$address')";
					$mysql->query($sql_signin);
					$_SESSION['user'] = $username;
					$_SESSION['userid'] = mysqli_insert_id($mysql->conn);
					echo "Sign in Successfully";
				}else{
					echo "Password must longer than 3!";
				}
			}
		}
?>
		</div>
<?php
    }else{
?>
	  <div class='login-form'>
		<h1>Login in DBM</h1>
		<form action='' method='post'>
			Username:&nbsp;<input type='text' name='user' maxlength=20 placeholder='David' required /><br/>
			Password:&nbsp;<input type='password' name='pwd' maxlength=20 placeholder='1234' required /><kbd class='seepwd' onmousedown="seepwd(document.getElementsByName('pwd')[0])">o</kbd><br/><br/>
			<button type='submit' class='btn-red' name='log'>Log in</button>
		</form>
<?php
	}
	if(isset($admin)){
		echo "<script>document.getElementsByName('user')[0].placeholder='admin';</script>";
	}
	if(isset($_POST['log'])){
		if(isset($admin)){
			$table = 'staff';
		}else{
			$table = 'customer';
		}
			$user = mysqli_real_escape_string($mysql->conn, $_POST['user']);
			$inputpwd = mysqli_real_escape_string($mysql->conn,$_POST['pwd']);
			$sql_userinfo = "SELECT id, username, password FROM $table WHERE username = '$user'";
			$res = $mysql->fetch($mysql->query($sql_userinfo));
			$pwd = $res['password'];
			$userid = $res['id'];
/**display username and password after submit*/
			echo "<script>document.getElementsByName('user')[0].value='{$_POST['user']}';document.getElementsByName('pwd')[0].value='$inputpwd';</script>";
			if(!empty($pwd)){
				if($pwd == md5($inputpwd)){
					echo "Login Successfully";
					echo "<script>changeStyle('user','input-success');changeStyle('pwd','input-success');</script>";
					$_SESSION['user'] = $res['username'];
					$_SESSION['userid'] = $userid;
				}else{
					echo "Wrong Password";
					echo "<script>changeStyle('pwd','input-error');changeStyle('user','input-success');</script>";
				}
			}else{
				echo "Username not found";
				echo "<script>changeStyle('user','input-error');</script>";
			}
	}
	if(isset($_SESSION['user'])){
		echo "<br/>Go to DBM...";
		if(isset($admin)){
			$_SESSION['admin']=true;
			header("refresh:1;url='admin/index.php'");
		}else{
			header("refresh:1;url='index.php'");
		}
	}
?>
		<script>
				var allinput = document.getElementsByTagName('input');
				for(var i=0;i < allinput.length;i++){
				  allinput[i].oninput = function(){
					 this.value=this.value.replace(' ','');
				  }
				}
		</script>
	  </div>
	</div>
  </body>
</html>