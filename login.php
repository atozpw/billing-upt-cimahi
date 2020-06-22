<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<?php
	require "model/setDB.php";
	require "model/logging.php";
	require "fungsi.php";
	include "lib.php";
	session_start();
	if(isset($_SESSION['User_c'])){
		header("Location: ./");
	}
?>
<html>
<head>
	<title><?php echo $application_nama; ?></title>
	<link rel="shortcut icon" type="image/ico" href="<?php echo $appl_logo; ?>"/>
	<link rel="Stylesheet" type="text/css" href="css/style.css" media="screen"/>
	<script>
		function formfocus() {
			document.getElementById('username').focus();
		}
	</script>
</head>
<body onload="formfocus()">
<?php
	$mess = "";
	if(isset($_POST['Submit'])){
		if($_POST['Submit']=='Login'){
			/** koneksi ke database */
			$db		= false;
			$mess	= false;
			try {
				$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch (PDOException $err){
				$mess = $err->getTrace();
				errorLog::errorDB(array($mess[0]['args'][0]));
				$mess = "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan";
				$klas = "error";
			}
			
			try {
				$l_name	= $_POST['username'];
				$l_pass	= md5($_POST['input_pass']);
				$que0	= "SELECT a.kar_id,a.kar_nama,a.kar_pass,a.grup_id,a.kp_kode,b.kp_ket,b.cab_kode,IFNULL(c.grup_nama,'Administrator') AS grup_nama,IF(a.ip_print=1,'"._HOST."',INET_NTOA(a.ip_print)) AS ip_print FROM tm_karyawan a join tr_kota_pelayanan b ON(a.kp_kode=b.kp_kode) LEFT JOIN tm_group c ON(a.grup_id=c.grup_id) WHERE a.kar_id = '$l_name'";
				$res0 	= $db->prepare($que0);
				$res0->execute();
				$row0 = $res0->fetch(PDO::FETCH_OBJ);
			}
			catch (PDOException $err){
				$mess = $err->getTrace();
				errorLog::errorDB(array($mess[0]['args'][0]));
				$mess = "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan";
				$klas = "error";
			}
			
			if($l_pass == $row0->kar_pass){
				$_SESSION['User_c']		= $row0->kar_id;
				$_SESSION['Name_c']		= $row0->kar_nama;
				$_SESSION['Grup_c']		= $row0->grup_id;
				$_SESSION['Kota_c']		= $row0->kp_kode;
				$_SESSION['Prin_c']		= $row0->ip_print;
				$_SESSION['kp_ket']		= $row0->kp_ket;
				$_SESSION['Group_c']	= $row0->grup_id;
				$_SESSION['c_group']	= $row0->cab_kode;
				$_SESSION['grup_nama']	= $row0->grup_nama;
				try {
					$db->beginTransaction();
					$que1	= "INSERT tr_trans_log(tr_id,tr_sts,tr_ip,kp_kode,kar_id) VALUES(".getToken().",1,INET_ATON('".$ipClient."'),'".$row0->kp_kode."','".$row0->kar_id."')";
					$res1 	= $db->exec($que1);
					if($res1>0){
						$db->commit();
						define("_TOKN",getToken());
						define("_KODE","000000");
						define("_USER",$row0->kar_id);
						define("_KOTA", 10);
						$mess = $row0->kar_nama." telah berhasil log in.";
						errorLog::logDB(array($que1));
						errorLog::logMess(array($mess));
						header('Location: ./');
					}
				}
				catch (PDOException $err){
					$mess = $err->getTrace();
					errorLog::errorDB(array($mess[0]['args'][0]));
					$mess = $err->getMessage();
				}
			}
			else{
				$mess	= "Mungkin username atau password anda salah.";
				$klas	= "error";
			}
		}
	}
?>
	<div class="span-24 height-3">&nbsp;</div>
	<div class="span-14 login push-1">
		<h2>Selamat Datang</h2>
<?php
	if($mess){
		echo "<div class=\"$klas\">Pesan : $mess</div>";
	}
?>
		<form id='form_login' method='POST' action="./login.php" style="padding:0px 15px;">
			<p>
				Gunakan nama user & password yang valid untuk mengakses aplikasi ini. [<?php echo $ipClient; ?>]<br />
				&nbsp;<br />
				<img src="images/lcd.png" style="float:left; width:128px;padding:20px 0px 0px 0px;" />
				<label>User ID:</label><br />
				<input type="text" id="username" name="username" size="12" maxlength="20" /><br />
				<label>Password:</label><br />
				<input type="password" name="input_pass" size="12" maxlength="20" /><br /><br />
				<input type="submit" class="form_button" name="Submit" value="Login" class="form_button" />
			</p>
		</form>
	</div>
</body>
</html>
