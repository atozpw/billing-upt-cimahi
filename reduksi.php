<?php
	if($erno) die();
	$kar_id = _USER;
	
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
	
	if($db){
		try {
			$db->beginTransaction();
			$que0	= "UPDATE tm_reduksi SET rd_sts=0 WHERE rek_nomor=$rek_nomor";
			$st 	= $db->exec($que0);
			$que1	= "INSERT INTO tm_reduksi(appl_tokn,pel_no,rek_nomor,rd_tgl,rd_stanlalu,rd_stankini,rd_uangair_awal,rd_nilai,rd_uangair_akhir,rd_rek_no_baru,gol_kode,rd_kode,rd_sts,kar_id) VALUES("._TOKN.",'$pel_no',$rek_nomor,NOW(),$rek_stanlalu,$rek_stankini,$rek_uangair,$reduksi,$rek_reduksiuangair,1,'$rek_gol',5,1,'$kar_id')";
			$st 	= $db->exec($que1);
			if($st>0){
				$db->commit();
				errorLog::logDB(array($que0));
				errorLog::logDB(array($que1));
				$mess = "Proses Reduksi SL: ".$pel_no." telah berhasil dilakukan ";
				$klas = "success";
			}
		}
		catch (PDOException $err){
			$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses simpan Reduksi SL: $pel_no tidak bisa dilakukan ";
			$klas = "error";
			errorLog::errorDB(array($err->getMessage()));
			errorLog::logDB(array($que));
			errorLog::logMess(array($mess));
		}
	}
	
	errorLog::logMess(array($mess));
	echo $mess;
	echo "<input type=\"button\" value=\"Kembali\" onclick=\"buka('kembali')\" />";
	unset($db);
?>
