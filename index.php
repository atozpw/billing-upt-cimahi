<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	include "lib.php";
	include "fungsi.php";
	cek_pass();
?>
<html>
<head>
	<title><?php echo $application_name; ?></title>
	<link rel="shortcut icon" type="image/ico" href="<?php echo $appl_logo; ?>"/>
	<link rel="Stylesheet" type="text/css" href="css/style.css" media="screen"/>
	<link rel="Stylesheet" type="text/css" href="css/mainindex_print.css" media="print"/>
	<script type="text/javascript" src="js/prototype.js"></script>
	<script type="text/javascript" src="js/kontrol.js"></script>
	<script type="text/javascript" src="js/pintasan.js"></script>
	<script type="text/javascript" src="js/dsml.js"></script>
	<script>
		function resize(){
			var dim = Element.getHeight('load');
			var tin = dim - 117;
			Element.setStyle('content',({height: ''+tin+''}));
		}
	</script>
</head>
<body id="mainBody" onload="resize()" onkeydown="pintasan(event)">
<div id="load" class="load"></div>
<div id="peringatan" class="load"></div>
<div id="container" class="container">
	<div class="span-24 header cetak">
		<h1 class="app_title"><?php echo $application_name; ?></h1>
		<div class="info">Anda memiliki hak akses sebagai <b><?php echo $_SESSION['grup_nama']; ?></b> untuk <b><?php echo $_SESSION['kp_ket']; ?></b>. <a href="logout.php">Logout</a></div>
	</div>
	<div id="menu" class="span-24 menu cetak">
		<ul id="navmenu-h">
			<li><a href="../">H</a></li>
			<?php include "menu.inc.php";?>
		</ul>
	</div>
	<div id="content"></div>
</div>
<script>
	$('load').hide();
	$('peringatan').hide();
</script>
</body>
</html>