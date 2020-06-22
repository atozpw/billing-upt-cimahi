<?php
	if($erno) die();
	$klas	= false;

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
			$kopel	= explode("_",$kopel);
			$mess	= $kopel[0];
			$db->beginTransaction();
			$st 	= $db->prepare("CALL p_penerbitan_drd(?)");
			$st->bindParam(1, $mess, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 4000); 
			$st->execute();
			//$db->rollBack();
			$mess	= "Proses penerbitan telah selesai";
			$db->commit();
		}
		catch (PDOException $err){
			errorLog::errorDB(array("Penerbitan DRD : ".$kopel[0]));
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
