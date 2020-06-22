<?php
	if($erno) die();
	$kar_id = _USER;
	$byr_no	= _TOKN;
	$kopel	= $_SESSION['kp_ket'];
	
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
			$cetak 		= true;
			if($cetak){
				$db->beginTransaction();
				$j 		= 0;
				$l		= 0;
				$erno	= false;
				if($kembalian>=0){				
					if(!is_array($pel_no)){
						$tmp_no 	= $pel_no;
						$tmp_nama 	= $pel_nama;
						$tmp_alamat	= $pel_alamat;
						unset($pel_no);
						unset($pel_nama);
						unset($pel_alamat);
						for($i=0;$i<=count($pilih);$i++){
							$pel_no[$i] 	= $tmp_no;
							$pel_nama[$i] 	= $tmp_nama;
							$pel_alamat[$i]	= $tmp_alamat;
						}
					}

					for($i=0;$i<=count($pilih);$i++){
						if($db && $pilih[$i]==1){
							// Pengambilan rincian tarif air
							$que0	= "SELECT a.* FROM tm_kode_tarif a WHERE ".substr($rek_nomor[$i],0,6).">=a.tar_bln_mulai AND ".substr($rek_nomor[$i],0,6)."<=a.tar_bln_akhir AND a.gol_kode='".$rek_gol[$i]."'";
							/** getParam 
								memindahkan semua nilai dalam array POST ke dalam
								variabel yang bersesuaian dengan masih kunci array
							*/
							$res0 	= mysql_query($que0,$link);
							$nilai	= mysql_fetch_array($res0);
							$konci	= array_keys($nilai);
							for($k=0;$k<count($konci);$k++){
								$$konci[$k]	= $nilai[$konci[$k]];
							}
							/* getParam **/
							$o_awalA	= " ";
							$o_akhirA	= " ";
							$o_hargaA	= " ";
							$o_uangairA	= " ";
							$o_awalB	= " ";
							$o_akhirB	= " ";
							$o_hargaB	= " ";
							$o_uangairB	= " ";
							$o_awalC	= " ";
							$o_akhirC	= " ";
							$o_hargaC	= " ";
							$o_uangairC	= " ";
							$pemakaian	= $rek_pakai[$i];
							if($pemakaian>$tar_sd2){
								$o_awalC	= $tar_sd2+1;
								$o_akhirC	= $pemakaian;
								$o_hargaC	= $tar_3;
								$o_uangairC	= ($pemakaian-$tar_sd2)*$tar_3;
								$pemakaian 	= $tar_sd2;
							}
							if($pemakaian>$tar_sd1){
								$o_awalB	= $tar_sd1+1;
								$o_akhirB	= $pemakaian;
								$o_hargaB	= $tar_2;
								$o_uangairB = ($pemakaian-$tar_sd1)*$tar_2;
								$pemakaian 	= $tar_sd1;
							}
							if($pemakaian>0){
								$o_awalA	= 0;
								$o_akhirA	= $pemakaian;
								$o_hargaA	= $tar_1;
								$o_uangairA = $pemakaian*$tar_1;
							}
						
							try {
								$que	= "UPDATE tm_rekening SET rek_denda=$rek_denda[$i],rek_byr_sts=1 WHERE rek_nomor=$rek_nomor[$i] AND rek_sts=1 AND rek_byr_sts=0 AND (rek_total+$rek_denda[$i])=$rek_bayar[$i]";
								$st 	= $db->exec($que);
								if($st>0){
									errorLog::logDB(array($que));
								}
								else{
									$erno 	= true;
									$mess	= "Gagal update tabel rekening";
									$i		= count($pilih) + 1;
								}
								$que	= "INSERT INTO tm_pembayaran(byr_no,byr_tgl,byr_serial,rek_nomor,kar_id,lok_ip,byr_loket,byr_total,byr_cetak,byr_upd_sts,byr_sts) VALUES($byr_no,NOW(),'$noresi',$rek_nomor[$i],'$kar_id','$ipClient','$loket',$rek_bayar[$i],0,NOW(),1)";
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
								$j++;
							}
							catch (PDOException $err){
								$erno 	= true;
								$i		= count($pilih) + 1;
								$mess 	= "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses pembayaran tidak bisa dilakukan. Tekan tombol <b>Esc</b> untuk menutup pesan ini";
								$klas	= "error";
								errorLog::errorDB(array($que));
							}
							$l++;
						}
					}

					if(!$erno){
						try{
							$que	= "INSERT INTO tm_bayar_rek(byr_no,byr_tgl,kar_id,byr_loket,byr_total,byr_dibayar,byr_kembali,byr_sts) VALUES($byr_no,NOW(),'$kar_id','$loket',$bayar,$dibayar,$kembalian,1)";
							$st 	= $db->exec($que);
							if($st>0){
								errorLog::logDB(array($que));
							}
							else{
								$erno 	= true;
								$mess	= "Gagal insert tabel bayar rek";
								$i		= count($pilih) + 1;
							}
						}
						catch (PDOException $err){
							$erno 	= true;
							errorLog::errorDB(array($que));
							$mess 	= "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses pembayaran tidak bisa dilakukan. Tekan tombol <b>Esc</b> untuk menutup pesan ini";
						}
					}
					
					// commit status proses transaksi
					if($erno){
						$db->rollBack();
						$j=0;
					}
					else{
						//$db->rollBack();
						$db->commit();
					}

					if($j>0){
						$mess	= "$j transaksi telah dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman semula";
						$klas 	= "success";
					}
					else{
						$mess	= "$j transaksi telah dilakukan. Tekan tombol <b>B</b> untuk kembali ke halaman semula";
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
