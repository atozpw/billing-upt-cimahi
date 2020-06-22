<?php
	if($erno) die();
	$kar_id = _USER;
	if(strlen($dkd_kd) == 4){
		$dkd_kd = $_SESSION['Kota_c'].$dkd_kd;
	}
	
	/** koneksi ke database */
	$db		= false;
	try {
		$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $err){
		$mess = $err->getMessage();
		errorLog::errorDB(array($mess));
		$mess = "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan.";
		$klas = "error";
	}
	
	switch($proses){
		case 'updateSL':
			if($db){
				try {
					$db->beginTransaction();
					$st1	= 0;
					if($meterisasi==1){
						$que	= "UPDATE tm_pelanggan SET um_kode=".$um_kode.",met_tgl=NOW(),met_stdbaru=".$stan_pasang." WHERE pel_no='".$pel_no."'";
						$st1 	= $db->exec($que);
						errorLog::logDB(array($que));
					}
					$que	= "UPDATE tm_pelanggan SET pel_nama='".$pel_nama."',pel_alamat='".trim($pel_alamat)."',gol_kode='".$gol_kode."',kp_kode='".$kp_kode."',dkd_kd='".$dkd_kd."',kar_id='".$kar_id."',kps_kode=".$kps_kode." WHERE pel_no='".$pel_no."'";
					$st0 	= $db->exec($que);
					errorLog::logDB(array($que));
					$db->commit();
					
					if($st0>0 and $st1>0){
						$mess = "Perubahan data sekaligus meterisasi pelanggan: ".$pel_no." telah di simpan.";
						$klas = "success";
					}
					else if($st0>0 and $st1==0){
						$mess = "Perubahan data pelanggan: ".$pel_no." telah di simpan.";
						$klas = "success";
					}
					else if($st0==0 and $st1>0){
						$mess = "Meterisasi pelanggan: ".$pel_no." telah di simpan.";
						$klas = "success";
					}
					else{
						$mess = "Tidak ada perubahan data pelanggan: ".$pel_no;
						$klas = "success";
					}
				}
				catch (PDOException $err){
					$mess = $err->getMessage();
					errorLog::errorDB(array($mess));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses update SL: ".$pel_no." tidak bisa dilakukan.";
					$klas = "error";
				}
			}
			break;
		case 'tambahSL':
			if($db){
				try {
					$db->beginTransaction();
					$que	= "INSERT INTO tm_pelanggan(pel_no,pel_nama,pel_alamat,gol_kode,kp_kode,dkd_kd,um_kode,kar_id,met_tgl,met_stdbaru,kps_kode) VALUES('".$pel_no."','".$pel_nama."','".trim($pel_alamat)."','".$gol_kode."','".$kp_kode."','".$dkd_kd."',".$um_kode.",'".$kar_id."',NOW(),".$stan_pasang.",".$kps_kode.")";
					$st 	= $db->exec($que);
					errorLog::logDB(array($que));
					if($st>0){
						$db->commit();
						$mess = "Data pelanggan: ".$pel_no." telah di simpan.";
						$klas = "success";
					}
					else{
						$mess = "Data pelanggan: ".$pel_no." gagal di simpan.";
						$klas = "notice";
					}
				}
				catch (PDOException $err){
					$mess = $err->getMessage();
					errorLog::errorDB(array($mess));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses tambah SL: ".$pel_no." tidak bisa dilakukan.";
					$klas = "error";
				}
			}
			break;
		case 'register':
			if($db){
				try {
					$db->beginTransaction();
					$que	= "INSERT INTO tm_pemohon_bp(pem_reg,kp_kode,dkd_kd,pem_nama,pem_alamat,pem_tgl_reg,pem_tgl_entry,usr_id) VALUES('".$pem_reg."','".$kp_kode."','".$dkd_kd."','".$pem_nama."','".trim($pem_alamat)."',NOW(),NOW(),'".$kar_id."')";
					$st 	= $db->exec($que);
					errorLog::logDB(array($que));
					if($st>0){
						$db->commit();
						$mess = "Data pemohon: ".$pem_reg." telah di simpan.";
						$klas = "success";
					}
					else{
						$mess = "Data pemohon: ".$pem_reg." gagal di simpan.";
						$klas = "notice";
					}
				}
				catch (PDOException $err){
					$mess = $err->getMessage();
					errorLog::errorDB(array($mess));
					errorLog::logDB(array($que));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses registrasi: ".$pem_reg." tidak bisa dilakukan.";
					$klas = "error";
				}
			}
			break;
		case 'updatePemohon':
			if($db){
				try {
					$db->beginTransaction();
					$que	= "UPDATE tm_pemohon_bp SET dkd_kd='".$dkd_kd."',pem_tgl_entry=NOW(),usr_id='".$kar_id."',pem_sts=".$pem_sts." WHERE pem_reg='".$pem_reg."' AND pem_sts>11";
					$st 	= $db->exec($que);
					errorLog::logDB(array($que));
					if($st>0){
						$db->commit();
						$mess = "Perubahan data pemohon: ".$pem_reg." telah di simpan.";
						$klas = "success";
					}
					else{
						$mess = "Perubahan data pemohon: ".$pem_reg." gagal di simpan.";
						$klas = "notice";
					}
				}
				catch (PDOException $err){
					$mess = $err->getMessage();
					errorLog::errorDB(array($mess));
					errorLog::logDB(array($que));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses registrasi: ".$pem_reg." tidak bisa dilakukan.";
					$klas = "error";
				}
			}
			break;
		case 'inputBiaya':
			if($db){
				try {
					$db->beginTransaction();
					$que	= "INSERT INTO tm_rekening_bp(rek_nomor,rek_tgl,rek_update,pem_reg,gol_kode,um_kode,biaya_pasang,kar_id) VALUES("._TOKN.",NOW(),NOW(),'".$pem_reg."','".$gol_kode."','".$um_kode."',".$biaya_pasang.",'"._USER."') ON DUPLICATE KEY UPDATE rek_update=NOW(),gol_kode='".$gol_kode."',um_kode='".$um_kode."',biaya_pasang=".$biaya_pasang.",kar_id='"._USER."'";
					$st 	= $db->exec($que);
					errorLog::logDB(array($que));
					if($st>0){
						$db->commit();
						$mess = "Input biaya pasang pemohon: ".$pem_reg." telah di simpan.";
						$klas = "success";
					}
					else{
						$mess = "Input biaya pasang pemohon: ".$pem_reg." tidak bisa disimpan.";
						$klas = "notice";
					}
				}
				catch (PDOException $err){
					$mess = $err->getMessage();
					errorLog::errorDB(array($mess));
					errorLog::logDB(array($que));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses registrasi: ".$pem_reg." tidak bisa dilakukan.";
					$klas = "error";
				}
			}
			break;
		default:
			$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga tidak ada proses yang bisa dijalankan.";
			$klas = "notice";
	}
	errorLog::logMess(array($mess));
	echo "<div class='".$klas."'>".$mess."</div>";
?>
