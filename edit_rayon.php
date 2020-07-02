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
		$mess = "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan";
		$klas = "error";
	}
	
	switch($proses){
		case "addRayon":
			if($db){
				try {
					$db->beginTransaction();
					$que	= "INSERT INTO tr_dkd(dkd_kd,dkd_no,dkd_jalan,dkd_loket,kar_id,dkd_tcatat) VALUES('".$dkd_kd."',SUBSTR('".$dkd_kd."',-2),'".trim($dkd_jalan)."',SUBSTR('".$dkd_kd."',1,1),'".$dkd_pembaca."',".$dkd_tcatat.")";
					$st 	= $db->exec($que);
					$db->commit();
					errorLog::logDB(array($que));
					if($st>0){
						$mess 	= "Informasi rayon: ".$dkd_kd." telah ditambahkan.";
						$klas 	= "success";
					}
				}
				catch (PDOException $err){
					$db->rollBack();
					$mess = $err->getTrace();
					errorLog::errorDB(array($mess[0]['args'][0]));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga proses tambah rayon: ".$dkd_kd." tidak bisa dilakukan.";
					$klas = "error";
				}
			}
			break;
		case "editRayon":
			if($db){
				$kopel = explode("_",$kopel);
				try {
					$db->beginTransaction();
					$que	= "UPDATE tr_dkd SET kar_id='".$kar_id."',dkd_tcatat='".$dkd_tcatat."',dkd_jalan='".trim($dkd_jalan)."',dkd_kd='".$dkd_kd."' WHERE dkd_kd='".$dkd_lama."'";
					$st 	= $db->exec($que);
					$db->commit();
					errorLog::logDB(array($que));
					if($st>0){
						$mess = "Informasi rayon: $dkd_kd telah diperbaharui";
						$klas = "success";
					}
					else{
						$mess = "Informasi rayon: $dkd_kd tidak bisa diperbaharui";
						$klas = "notice";						
					}
				}
				catch (PDOException $err){
					$db->rollBack();
					$mess = $err->getTrace();
					errorLog::errorDB(array($mess[0]['args'][0]));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga proses edit rayon:$dkd_kd tidak bisa dilakukan.";
					$klas = "error";
				}
			}
			break;
		case "deleteRayon":
			if($db){
				try {
					$db->beginTransaction();
					$que	= "DELETE FROM tr_dkd WHERE dkd_kd='".$dkd_lama."'";
					$st 	= $db->exec($que);
					$db->commit();
					errorLog::logDB(array($que));
					if($st>0){
						$mess = "Rayon: ".$dkd_lama." telah dihapus dari database.";
						$klas = "success";
					}
					else{
						$mess = "Rayon: ".$dkd_lama." tidak bisa dihapus.";
						$klas = "success";
					}
				}
				catch (PDOException $err){
					$db->rollBack();
					$mess = $err->getTrace();
					errorLog::errorDB(array($mess[0]['args'][0]));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga proses delete rayon:$dkd_kd tidak bisa dilakukan.";
					$klas = "error";
				}
			}
			break;
		default:
			$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga tidak ada proses yang bisa dijalankan.";
			$klas = "notice";
	}
	errorLog::logMess(array($mess));
	echo "<div class=\"$klas left\">$mess</div>";
?>
