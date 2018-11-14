<?php @session_start(); ?>
<?php 
if(isset($_POST['uname'])) : print_r($_SESSION);
	$con=mysqli_connect("mysql-educonet.alwaysdata.net","educonet","adminedc123","educonet_gcsa") or die(mysqli_error($con));
	$q=$con->query("select * from users where login='".$_POST['uname']."'");
	while($r=mysqli_fetch_array($q)):
		$result=$r;
	endwhile;
	if(isset($result)):
		if(password_verify($_POST['psw'],$result['pass'])):
			$_SESSION['gcsa']['gcsa_username']=$_POST['uname'];
			$_SESSION['gcsa']['gcsa_level']=$result['level'];
			$_SESSION['gcsa']['gcsa_email']=$result['email'];
			$_SESSION['gcsa']['gcsa_tel']=$result['tel'];
			echo '<script>location="index.php";</script>';
		else:
			echo '<div class="w3-panel w3-red" style="text-align:center;padding:10px 15px">Erreur Login/Password</div>';
		endif;
	else:
		echo '<div class="w3-panel w3-red" style="text-align:center;padding:10px 15px">Nom d\'utilisateur inconnu</div>';
	endif;
endif;
?>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="imgcontainer">
    <i class="fa fa-user" style="font-size:200px"></i>
  </div>
  <form action="#" method="post">
  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
        
    <button type="submit">Login</button>
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>
<style>
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
}

button:hover {
    opacity: 0.8;
}

.cancelbtn {
    width: auto;
    padding: 10px 18px;
    background-color: #f44336;
}

.imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
}

img.avatar {
    width: 40%;
    border-radius: 50%;
}

.container {
    padding: 16px;
}

span.psw {
    float: right;
    padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
    span.psw {
       display: block;
       float: none;
    }
    .cancelbtn {
       width: 100%;
    }
}
</style>
<script>
</script>