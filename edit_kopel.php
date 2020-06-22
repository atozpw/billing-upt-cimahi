<?php
	if($erno) die();
	$klas 	= "notice";
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
					$que	= "INSERT INTO tr_kota_pelayanan(kp_kode,kp_nama,kp_ket,cab_kode) VALUES('$kp_kode','$kp_nama','$kp_ket','$cab_kode')";
					$st 	= $db->exec($que);
					$db->commit();
					errorLog::logDB(array($que));
					if($st>0){
						$mess 	= "Informasi Kota Pelayanan: $kp_ket telah ditambahkan";
						$klas 	= "success";
					}
				}
				catch (PDOException $err){
					$db->rollBack();
					$mess = $err->getTrace();
					errorLog::errorDB(array($mess[0]['args'][0]));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga proses tambah kopel: $kp_nama tidak bisa dilakukan";
					$klas = "error";
				}
			}
			break;
		case "editRayon":
			if($db){
				$kopel = explode("_",$kopel);
				try {
					$db->beginTransaction();
					$que	= "UPDATE tr_kota_pelayanan SET kp_nama='$kp_nama',kp_ket='$kp_ket' WHERE kp_kode='$kp_kode'";
					$st 	= $db->exec($que);
					$db->commit();
					errorLog::logDB(array($que));
					if($st>0){
						$mess = "Informasi Kota Pelayanan: $kp_ket telah diperbaharui";
						$klas = "success";
					}
					else{
						$mess = "Tidak ada perubahan informasi Kota Pelayanan";
						$klas = "notice";
					}
				}
				catch (PDOException $err){
					$db->rollBack();
					$mess = $err->getTrace();
					errorLog::errorDB(array($mess[0]['args'][0]));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga proses edit kopel: $kp_nama tidak bisa dilakukan";
					$klas = "error";
				}
			}
			break;
		case "deleteRayon":
			if($db){
				try {
					$db->beginTransaction();
					$que	= "DELETE FROM tr_kota_pelayanan WHERE kp_kode='$kp_kode'";
					$st 	= $db->exec($que);
					$db->commit();
					errorLog::logDB(array($que));
					if($st>0){
						$mess = "Kota Pelayanan: $kp_ket telah dihapus dari database";
						$klas = "success";
					}
				}
				catch (PDOException $err){
					$db->rollBack();
					$mess = $err->getTrace();
					errorLog::errorDB(array($mess[0]['args'][0]));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga proses delete kopel : $kp_ket tidak bisa dilakukan";
					$klas = "error";
				}
			}
			break;
		default:
			$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga tidak ada proses yang bisa dijalankan";
			$klas = "notice";
	}
	errorLog::logMess(array($mess));
	echo "<div class=\"$klas left\">$mess</div>";
?>
