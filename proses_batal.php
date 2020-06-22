<?php
	if($erno) die();
	unset($erno);
	
	/** koneksi ke database */
	$db		= false;
	try {
		$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		unset($mess);
	}
	catch (PDOException $err){
		$mess 	= $err->getTrace();
		errorLog::errorDB(array($mess[0]['args'][0]));
		$mess 	= "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman awal.";
		$klas 	= "error";
	}
	switch($proses){
		case "valBatal":
			$db->beginTransaction();
			if($db){
				try {
					$que	= "UPDATE tm_pembatalan SET btl_tgl=NOW(),validator='"._USER."',btl_sts=1 WHERE ba_no=$ba_batal";
					$st 	= $db->exec($que);
					if($st>0){
						$db->commit();
						errorLog::logDB(array($que));
						$mess = "Proses validasi pembatalan telah selesai. Tekan tombol <b>Esc</b> kemudian <b>B</b> untuk kembali ke halaman awal.";
					}
					else{
						$db->rollBack();
						errorLog::errorDB(array($que));
						$mess 	= "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses validasi batal tidak bisa dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman awal.";
					}
				}
				catch (PDOException $err){
					errorLog::errorDB(array($que));
					$mess 	= "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses validasi batal tidak bisa dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman awal.";
				}
			}
			echo "<input type=\"hidden\" id=\"$errorId\" value=\"$mess\" />";
			break;
		case "cetakBA":
			$db->beginTransaction();
			$data_key = array_keys($byr_no);
			for($i=0;$i<count($data_key);$i++){
				if($db){
					try {
						$que	= "INSERT INTO tm_pembatalan(ba_tgl,byr_tgl,byr_no,ba_no,rek_nomor,byr_total,kar_id) VALUES(NOW(),CURDATE(),".$byr_no[$data_key[$i]].","._TOKN.",".$data_key[$i].",".$rek_bayar[$data_key[$i]].",'"._USER."')";
						$st 	= $db->exec($que);
						if($st>0){
							errorLog::logDB(array($que));
							//$mess = "Proses cetak berita acara pembatalan telah selesai. Tekan tombol <b>Cetak</b> atau <b>P</b> untuk mencetak. Kemudian tombol <b>B</b> untuk kembali ke halaman awal.";
							$mess = "Proses cetak berita acara pembatalan telah selesai. Tekan tombol <b>B</b> untuk kembali ke halaman awal.";
							$klas = "success";
						}
						else{
							errorLog::errorDB(array($que));
							$erno[] = true;
							$mess 	= "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses cetak berita acara batal tidak bisa dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman awal.";
							$klas 	= "notice";
						}
					}
					catch (PDOException $err){
						errorLog::errorDB(array($que));
						$erno[] = true;
						$mess 	= "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses cetak berita acara batal tidak bisa dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman awal.";
						$klas 	= "error";
					}
				}
			}
			if(count($erno)>0){
				$db->rollBack();
			}
			else{
				$db->commit();
			}
			echo "<div class=\"span-23 prepend-bottom cetak $klas\">".$mess."</div>";
			break;
		default:
			$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses cetak berita acara batal tidak bisa dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman awal.";
			$klas = "notice";
			echo "<div class=\"span-23 prepend-bottom cetak $klas\">".$mess."</div>";
	}
	errorLog::logMess(array($mess));
	unset($db);
?>