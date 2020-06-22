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
			$que	= "UPDATE db_status SET _sts=1 WHERE _var='TRN'";
			$st 	= $db->exec($que);
			//$db->rollBack();
			$db->commit();
			$klas	= "success";
			$mess	= "Informasi dashboard telah direset, tekan tombol <b>F5</b> pada jendela dashboard untuk memperbaharui informasi transaksi.";
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