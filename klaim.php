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
			$que	= "UPDATE tm_klaim SET cl_sts=0 WHERE rek_nomor=$rek_nomor";
			$st 	= $db->exec($que);
			$que	= "INSERT INTO tm_klaim(appl_tokn,pel_no,rek_nomor,cl_tgl,cl_stanlalu_awal,cl_stankini_awal,cl_stanlalu_akhir,cl_stankini_akhir,cl_uangair_akhir,gol_kode,cl_rek_no_baru,cl_kode,cl_sts,kar_id) VALUES("._TOKN.",'$pel_no',$rek_nomor,NOW(),$rek_stanlalu,$rek_stankini,$rek_stanlalu,$rek_stanklaim,$klaim_uangair,'$rek_gol',1,$kl_kode,1,'$kar_id')";
			$st 	= $db->exec($que);
			if($st>0){
				$db->commit();
				//$db->rollBack();
				errorLog::logDB(array($que));
				$mess = "Proses Klaim SL:$pel_no telah berhasil dilakukan.";
				$klas = "success";
			}
		}
		catch (PDOException $err){
			errorLog::errorDB(array($que));
			$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses simpan Klaim SL:$pel_no tidak bisa dilakukan.";
			//$mess = $que;
			$klas = "error";
		}
	}
	
	errorLog::logMess(array($mess));
	echo "<div class=\"$klas\">$mess</div>";
	echo "<input type=\"button\" value=\"Kembali\" onclick=\"buka('kembali')\" />";
	unset($db);
?>