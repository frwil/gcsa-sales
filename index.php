<?php @session_start();  ?>
<?php if(!isset($_SESSION['gcsa']['gcsa_username'])) echo '<script>location="login.php"</script>'; ?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  </head>
<body>
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav('left')">&times;</a>
  <a href="index.php">Home</a>
  <a href="?page=fleet">Fleet Management</a>
  <a href="?page=sales">Sales</a>
  <a href="?page=contact">Contact</a>
</div>
<div id="rightSidenav" class="rightsidenav">
</div>
<!-- Use any element to open the sidenav -->
<span onclick="openNav('left')"><i class="fa fa-bars" style="font-size:36px;vertical-align:middle"></i>&nbsp;Menu</span>
<span style="float:right" onclick="openNav('right')"><?php echo $_SESSION['gcsa']['gcsa_username']; ?><i class="fa fa-user" style="font-size:36px;vertical-align:middle"></i></span>

<!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->
<div id="main">
  <?php if(isset($_GET['page']) && $_GET['page']!=""):
	  include($_GET['page'].".php");
	  else :
		
	  endif;
  ?>
</div>
<script>
/* Set the width of the side navigation to 250px */
function openNav(s) {
	if(s=='left')
    document.getElementById("mySidenav").style.width = "90%";
	else
		document.getElementById("rightSidenav").style.width = "150px";
}

/* Set the width of the side navigation to 0 */
function closeNav(s) {
    if(s=='left')
	document.getElementById("mySidenav").style.width = "0";
	else 
		document.getElementById("rightSidenav").style.width = "0";
}
</script>
</body>
</html>