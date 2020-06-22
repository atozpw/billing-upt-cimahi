<?php
	if($erno) die();
	
	/** koneksi ke database */
	$db		= false;
	try {
		$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $err){
		$mess = $err->getTrace();
		errorLog::errorDB(array($mess[0]['args'][0]));
		errorLog::logMess(array($err->getMessage()));
	}

	if($db){
		try {
			$db->beginTransaction();
			$que	= "UPDATE system_parameter SET sys_value='$tgl_denda' WHERE sys_param='DENDA'";
			$st 	= $db->exec($que);
			//$db->rollBack();
			$db->commit();
			$klas	= "success";
			$mess	= "Tanggal jatuh tempo telah di set ke : $tgl_denda";
		}
		catch (PDOException $err){
			$mess = $err->getTrace();
			errorLog::errorDB(array($mess[0]['args'][0]));
			errorLog::logMess(array($err->getMessage()));
			$mess = "Mungkin telah terjadi kesalahan pada sistem, sehingga transaksi tidak bisa dilakukan";
			$klas = "error";
		}
	}
	else{
		$mess = "Mungkin telah terjadi kesalahan pada sistem, sehingga transaksi tidak bisa dilakukan";
		$klas = "error";
	}
	//echo "<input type=\"hidden\" id=\"$errorId\" value=\"$mess\"/>";
	echo "<div class=\"$klas\">$mess</div>";
?>