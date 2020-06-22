<?php
	if($erno) die();
	$kar_id 	= _USER;
	$errorId	= false;
	
	/** koneksi ke database */
	$db		= false;
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
	
	$j = 0;
	for($i=1;$i<=count($pel_no);$i++){
		if($db && $ubah[$i]==1 && strlen($sm_kini[$i])>0){
			try {
				$db->beginTransaction();
				$que	= "UPDATE tm_stand_meter SET sm_tgl=NOW(),sm_sts=1,kwm_kd=$kwm_kd[$i],kl_kd=$kl_kd[$i],sm_kini=$sm_kini[$i],kar_id='$kar_id',remark_id=".getToken()." WHERE sm_nomer=$sm_nomer[$i]";
				$st 	= $db->exec($que);
				$db->commit();
				errorLog::logDB(array($que));
				if($st>0){
					$mess = "Data DSML: $pel_no[$i] telah di simpan";
					errorLog::logMess(array($mess));
					$j++;
				}
			}
			catch (PDOException $err){
				$mess = $err->getTrace();
				errorLog::errorDB(array($mess[0]['args'][0]));
				errorLog::logDB(array($que));
				$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses simpan DSML SL: $pel_no[$i] tidak bisa dilakukan.";
			}
		}
	}
	$mess 	= $j." Data DSML telah disimpan";
	//$mess	= $que;
	errorLog::logMess(array($mess));
	echo "<input id=\"$errorId\" type=\"hidden\" value=\"$mess\"/>";
	echo $mess;
	unset($db);
?>
