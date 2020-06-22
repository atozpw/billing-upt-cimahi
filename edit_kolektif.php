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
	
	switch($proses){
		case "tambahKolektif":
			try {
				$db->beginTransaction();
				$que	= "INSERT INTO tr_kel_kolektif(kel_nama,kel_ket,kel_kolektor) VALUES(CONCAT('"._KOTA."','$kel_nama'),'$keterangan','$kolektor')";
				$st 	= $db->exec($que);
				$db->commit();
				errorLog::logDB(array($que));
				if($st>0){
					$mess = "Kelompok: <b>$kel_nama</b> telah ditambahkan pada database.";
				}
				else{
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses penambahan kelompok kolektif: <b>$kel_nama</b> tidak bisa dilakukan.";
				}
			}
			catch (PDOException $err){
				$db->rollBack();
				errorLog::errorDB(array($que));
				errorLog::logMess(array($err->getMessage()));
				$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses penambahan kelompok kolektif: <b>$kel_nama</b> tidak bisa dilakukan.";
			}
			break;
		case "inputKolektif":
			try {
				$db->beginTransaction();
				$que	= "INSERT INTO tm_kolektif(kel_kode,pel_no) VALUES($kel_kode,'$pel_no') ON DUPLICATE KEY UPDATE kel_kode=$kel_kode,kel_sts=1";
				$st 	= $db->exec($que);
				$db->commit();
				errorLog::logDB(array($que));
				if($st>0){
					$mess = "SL: <b>$pel_no</b> telah diset pada kelompok kolektif: <b>$kolektor</b>.";
				}
				else{
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses input kolektif SL:<b>$pel_no</b> tidak bisa dilakukan.";
				}
			}
			catch (PDOException $err){
				$db->rollBack();
				errorLog::errorDB(array($que));
				errorLog::logMess(array($err->getMessage()));
				$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses input kolektif SL:<b>$pel_no</b> tidak bisa dilakukan.";
			}
			break;
		case "hapusKelKolektif":
			try {
				$db->beginTransaction();
				$que	= "DELETE FROM tr_kel_kolektif WHERE kel_kode = '$kel_kode' ";
				$st 	= $db->exec($que);
				$db->commit();
				errorLog::logDB(array($que));
				if($st>0){
					$mess = "Kelompok kolektif kolektor : <b>$kolektor</b> Telah Dihapus";
				}
				else{
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses hapus kelompok kolektif KODE:<b>$kel_kode</b> tidak bisa dilakukan.";
				}
			}
			catch (PDOException $err){
				$db->rollBack();
				errorLog::errorDB(array($que));
				errorLog::logMess(array($err->getMessage()));
				$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses input kolektif SL:<b>$pel_no</b> tidak bisa dilakukan.";
			}
			break;
		case "hapusKolektif":
			try {
				$db->beginTransaction();
				$que	= "DELETE FROM tm_kolektif WHERE kel_kode = '$kel_kode' and pel_no = '$pel_no' ";
				$st 	= $db->exec($que);
				$db->commit();
				errorLog::logDB(array($que));
				if($st>0){
					$mess = "Kolektif SL : <b>$pel_no</b> Telah Dihapus ";
				}
				else{
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses hapus kolektif SL:<b>$pel_no</b> tidak bisa dilakukan.";
				}
			}
			catch (PDOException $err){
				$db->rollBack();
				errorLog::errorDB(array($que));
				errorLog::logMess(array($err->getMessage()));
				$mess = "gagal";
			}
			break;
		default :
			$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses input kolektif SL:<b>$pel_no</b> tidak bisa dilakukan.";
	}
	errorLog::logMess(array($mess));
	unset($db);
?>
<div class="notice"><?php echo $mess; ?></div>