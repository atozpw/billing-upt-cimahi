<?php
	session_start();
	
	require "model/setDB.php";
	require "model/logging.php";
	require "fungsi.php";
	require "lib.php";

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
		$db->beginTransaction();
		$que1	= "INSERT tr_trans_log(tr_id,tr_sts,tr_ip,kp_kode,kar_id) VALUES(".getToken().",2,INET_ATON('".$ipClient."'),'".$_SESSION["Kota_c"]."','".$_SESSION["User_c"]."')";
		$res1 	= $db->exec($que1);
		$db->commit();
		errorLog::logDB(array($que1));
		if($res1>0){
			define("_TOKN",getToken());
			define("_KODE","000000");
			define("_USER",$_SESSION['User_c']);
			$mess = $_SESSION['Name_c']." telah berhasil log out.";
			errorLog::logMess(array($mess));
		}
	}
	catch (PDOException $err){
		$mess = $err->getTrace();
		errorLog::errorDB(array($mess[0]['args'][0]));
		$mess = false;
	}
	session_unset();	
	header ("Location: ./");
?>