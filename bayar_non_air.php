<?php
	if($erno) die();
	$kar_id 	= _USER;
	$byr_no		= _TOKN;
	$kopel		= $_SESSION['kp_ket'];
	$errorId	= false;
	
	/** koneksi ke database */
	$db		= false;
	try {
		$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		unset($mess);
	}
	catch (PDOException $err){
		$mess = $err->getTrace();
		errorLog::errorDB(array($mess[0]['args'][0]));
		$mess = "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan. Tekan tombol <b>Esc</b> untuk menutup pesan ini";
		$klas = "error";
	}

	switch($proses){
		case "setResi":
			$db->beginTransaction();
			if($db){
				try {
					$que	= "INSERT INTO system_parameter(sys_param,sys_value,sys_value1,sys_value2) VALUES('RESI','$kar_id','$noresi','-') ON DUPLICATE KEY UPDATE sys_value1='$noresi'";
					$st 	= $db->exec($que);
					if($st>0){
						$db->commit();
						errorLog::logDB(array($que));
						$mess = "Set resi: $noresi telah dilakukan. Tekan tombol <b>Esc</b> untuk menutup pesan ini";
					}
					else{
						$db->rollBack();
						$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses set resi tidak bisa dilakukan. Tekan tombol <b>Esc</b> untuk menutup pesan ini";
					}
				}
				catch (PDOException $err){
					$db->rollBack();
					$mess = $err->getTrace();
					errorLog::errorDB(array($mess[0]['args'][0]));
					$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses set resi tidak bisa dilakukan. Tekan tombol <b>Esc</b> untuk menutup pesan ini";
				}
			}
			break;
		case "bayar":
			try{
				$wsdl_url 	= "http://"._PRIN."/printClient/printServer.wsdl";
				$client   	= new SoapClient($wsdl_url, array('cache_wsdl' => WSDL_CACHE_NONE) );
				$cetak 		= true;
			}
			catch (Exception $e){
				$mess		= "Perangkat pencetak belum tersedia";
				errorLog::errorDB(array($mess));
				$mess		= $e->getMessage();
				$klas		= "error";
				$cetak 		= false;
			}

			if($cetak){
				$db->beginTransaction();
				$erno	= false;
				if($kembalian>=0){
					try {
						$kopel		  = "CABANG ".$_SESSION['kp_ket'];
						if($na_kode==1){
							$catatan  = "CATATAN  : TAGIHAN REKENING AIR MULAI DIBAYAR BULAN ".strtoupper($bulan[date('n')])." ".date('Y').". DAN HARGA SUDAH TERMASUK PAJAK.".chr(10);
						}
						else if($na_kode<5){
							$catatan  = "CATATAN  : TAGIHAN REKENING AIR MULAI DIBAYAR BULAN ".strtoupper($bulan[date('n')])." ".date('Y').chr(10);
						}
						else{
							$catatan  = chr(10);
						}
						
						$rayon = $dkd_kd."/".$pel_alamat;
						if($pel_no=='043001'){
							$rayon		= '';
							$dkd_kd 	= '';
							$pel_alamat	= '';
							$gol_kode	= '';
						}
						
						// line untuk ff continous paper
						$stringCetak  = chr(27).chr(67).chr(1);
						// enable paper out sensor
						$stringCetak .= chr(27).chr(57);
						// draft mode
						$stringCetak .= chr(27).chr(120).chr(48);
						// mode 10 cpi
						$stringCetak .= chr(27).chr(80);
						// mode condensed
						$stringCetak .= chr(15);// line spacing x/n

						$stringCetak .= chr(27).chr(65).chr(28);
						$stringCetak .= chr(10);
						$stringCetak .= chr(27).chr(65).chr(8);
						$stringCetak .= chr(10);

						if(isset($l) && $l>0){
							if($l % 2==0){
								// line spacing x/n
								$stringCetak .= chr(27).chr(65).chr(13);
								$stringCetak .= chr(10);
							}
							else{
								// line spacing x/n
								$stringCetak .= chr(27).chr(65).chr(14);
								$stringCetak .= chr(10);
							}
						}

						// line spacing x/n
						$stringCetak .= chr(27).chr(65).chr(10);
						$stringCetak .= str_repeat(' ',20).printLeft($noresi, 20).	str_repeat(" ", 21).printLeft($pel_no,37).str_repeat(' ', 21).printLeft($noresi, 16).chr(10);
						$stringCetak .= chr(27).chr(65).chr(12);
						$stringCetak .= str_repeat(' ',20).printLeft('-',20).str_repeat(' ', 21).printLeft($dkd_kd, 37).str_repeat(" ", 21).'-'.chr(10);
						$stringCetak .= str_repeat(' ',20).printLeft(substr($gol_kode,0,20),20).str_repeat(" ",21).printLeft('N', 37).str_repeat(' ', 21).printLeft(substr($gol_kode,0,16),16).chr(10);
						$stringCetak .= str_repeat(' ',20).printLeft(substr($pel_no,0,20),20).str_repeat(" ",21).printLeft($pel_nama,37).str_repeat(' ',21).printLeft('-',16).chr(10);
						$stringCetak .= str_repeat(' ',20).printLeft('N',20).str_repeat(" ",21).printLeft(substr($pel_alamat,0,37),37).str_repeat(' ',21).printLeft('-',16).chr(10);
						$stringCetak .= str_repeat(' ',61).printLeft(substr($pel_alamat,37,37),37).str_repeat(' ',21).printLeft('-',16).chr(10);
						$stringCetak .= str_repeat(' ',61).printLeft(substr($pel_alamat,74,37),37).str_repeat(' ',21).printLeft('-',16).chr(10);
						$stringCetak .= chr(10);
						$stringCetak .= '   '.printLeft($pel_nama,37).chr(10);
						$stringCetak .= '   '.printLeft(substr($pel_alamat,0,37),37).'   '.printCenter("-", 7).' '.printLeft(ucwords($na_ket),33).' '.printRight('1 ',9).printCenter("Period", 10).printRight(number_format($bayar),12).' '.printRight(number_format($bayar),17).chr(10);
						$stringCetak .= '   '.printLeft(substr($pel_alamat,37,37),37).'   '.chr(10);
						$stringCetak .= '   '.printLeft(substr($pel_alamat,74,37),37).'   '.chr(10);
						$stringCetak .= chr(10);
						$stringCetak .= chr(10);
						$stringCetak .= chr(27).chr(65).chr(28);
						$stringCetak .= chr(10);
						$stringCetak .= chr(10);
						$stringCetak .= chr(27).chr(65).chr(17);
						$stringCetak .= chr(10);
						$stringCetak .= str_repeat(' ',4)."TOTAL".printRight(number_format($bayar),30).str_repeat(' ',78).printRight(number_format($bayar),17).chr(10);

						// line spacing x/n
						$stringCetak .= chr(27).chr(65).chr(15);
						$terbilang    = ucwords(n2c($bayar,"Rupiah"));
						$stringCetak .= str_repeat(' ',10).printLeft($tgl_sekarang_full,30).str_repeat(' ',73).printCenter($tgl_sekarang_full,22).chr(10);
						$stringCetak .= chr(27).chr(65).chr(12);
						$stringCetak .= str_repeat(' ',44).substr($terbilang,0,60).chr(10);
						$start = 0;
						$start2 = 60;
						if (strlen($terbilang) > 60 && strlen($terbilang) <= 120) {
							$stringCetak .= str_repeat(' ',44).substr($terbilang,60,60).chr(10);
						} else {
							$start = 60;
							$start2 = 120;
							$stringCetak .= str_repeat(' ',44).substr($catatan,0,60).chr(10);
						}
						if (strlen($terbilang) > 120) {
							$stringCetak .= str_repeat(' ',44).substr($terbilang,120,60).chr(10);
						} else {
							$stringCetak .= str_repeat(' ',44).substr($catatan,$start,60).chr(10);
							$stringCetak .= str_repeat(' ',44).substr($catatan,$start2,60);
						}
						$stringCetak .= str_repeat(' ',3).printCenter($nama_direktur,33).str_repeat(' ',77).printRight($nama_direktur,22).chr(10);
						$stringCetak .= chr(10);

						$que	= "INSERT INTO tm_pembayaran_non_air(byr_no,byr_tgl,byr_serial,pel_no,kar_id,lok_ip,na_kode,byr_total,byr_cetak,byr_upd_sts,byr_sts) VALUES($byr_no,NOW(),'$noresi','$pel_no','$kar_id','$ipClient','$na_kode',$bayar,0,NOW(),1)";
						$st 	= $db->exec($que);
						if($st>0){
							errorLog::logDB(array($que));
							$noresi++;
						}
						else{
							$erno 	= true;
							$mess	= "Gagal insert tabel pembayaran";
							$i		= count($pilih) + 1;
						}

						$que	= "UPDATE system_parameter SET sys_value1='$noresi' WHERE sys_param='RESI' AND sys_value='$kar_id'";
						$st 	= $db->exec($que);
						if($st>0){
							errorLog::logDB(array($que));
						}
						else{
							$erno 	= true;
							$mess	= "Gagal update nomer resi";
						}
					}
					catch (PDOException $err){
						$erno 	= true;
						$mess 	= "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses pembayaran tidak bisa dilakukan. Tekan tombol <b>Esc</b> untuk menutup pesan ini";
						$klas	= "error";
						errorLog::errorDB(array($que));
					}

					$stringCetak .= chr(12);

					if(!$erno){
						try {
							$stringFile	  = $byr_no.".txt";
							$openFile 	  = fopen("_data/".$stringFile, 'w');
							fwrite($openFile, $stringCetak);
							fclose($openFile);
							$client->cetak(base64_encode($stringCetak),$stringFile);
						}
						catch (Exception $e) {
							//$erno 	= true;
							$mess	= "Proses cetak resi bayar gagal dilakukan";
							errorLog::errorDB(array($mess));
							$mess	= $e->getMessage();
							$klas	= "error";
						}
					}
					// commit status proses transaksi
					$j = 1;
					if($erno){
						$db->rollBack();
						$j = 0;
					}
					else{
						$db->commit();
					}

					if($j>0){
						$mess	= "$j transaksi telah dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman semula";
						$klas 	= "success";
					}
					else{
						$mess	= "Transaksi tidak dapat dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman semula";
						$klas 	= "error";
					}
				}
				else{
					$mess	= "Uang yang diterima tidak mencukupi. Tekan tombol <b>B</b> untuk kembali ke halaman semula";
					$klas 	= "error";
				}
			}
			break;
		default:
			$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses pembayaran tidak bisa dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman semula";
			$klas = "error";
	}
	errorLog::logMess(array($mess));
	echo "<input type=\"hidden\" id=\"$errorId\" value=\"$mess\"/>";
	unset($db);
?>
