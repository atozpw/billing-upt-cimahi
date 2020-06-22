<?php
	if($erno) die();
	
	switch($proses){
		case "pilihSL":
			if(isset($_SESSION['nopel'][$pel_no])){
				unset($_SESSION['nopel'][$pel_no]);
			}
			if(isset($_SESSION['sptket'][$pel_no])){
				unset($_SESSION['sptket'][$pel_no]);
			}
			$_SESSION['nopel'][$pel_no] 	= $pilihan;
			$_SESSION['sptket'][$pel_no] 	= $spt_ket;
			if($pilihan==1){
				$mess = "SL $pel_no telah didaftarkan pada pilihan cetak SPT";
			}
			else{
				$mess = "SL $pel_no telah dihapus dari pilihan cetak SPT";
			}
			break;
		case "cetakSPT":
			/** koneksi ke database */
			$noQue		= false;

			if(isset($_SESSION['nopel'])){
				$nopel_val 	= $_SESSION['nopel'];
				$nopel_key 	= array_keys($nopel_val);
				for($i=0;$i<count($nopel_key);$i++){
					if($nopel_val[$nopel_key[$i]]==1){
						$nopel_list[] = $nopel_key[$i];
					}
				}
			}
			
			if(count($nopel_list)==0){
				$noQue	= true;
			}
			
			if(!$noQue){
				//$que0 = "SELECT a.pel_no,a.pel_nama,a.pel_alamat,a.gol_kode,SUM(IF(getBerjalan(CURDATE(),b.rek_bln,b.rek_thn)=1,b.rek_total,0)) AS total1,SUM(IF(getBerjalan(CURDATE(),b.rek_bln,b.rek_thn)=2,b.rek_total,0)) AS total2,SUM(IF(getBerjalan(CURDATE(),b.rek_bln,b.rek_thn)=3,b.rek_total,0)) AS total3,SUM(getDenda((b.rek_uangair+b.rek_meter+b.rek_adm),b.rek_bln,b.rek_thn)) AS rek_denda,SUM(b.rek_total) AS rek_total,COUNT(b.rek_nomor) AS rek_lembar,c.cab_ket,c.cab_alamat FROM tm_pelanggan a JOIN tr_cabang c ON(c.cab_kode=a.kp_kode) LEFT JOIN tm_rekening b ON(b.pel_no=a.pel_no AND b.rek_sts=1 AND b.rek_byr_sts=0) WHERE a.pel_no='".implode("' OR a.pel_no='",$nopel_list)."' GROUP BY a.pel_no ORDER BY a.pel_no DESC";
				$que0 = "SELECT a.pel_no,a.pel_nama,a.pel_alamat,a.gol_kode,SUM(IF(getBerjalan(CURDATE(),b.rek_bln,b.rek_thn)=1,b.rek_total,0)) AS total1,SUM(IF(getBerjalan(CURDATE(),b.rek_bln,b.rek_thn)=2,b.rek_total,0)) AS total2,SUM(IF(getBerjalan(CURDATE(),b.rek_bln,b.rek_thn)=3,b.rek_total,0)) AS total3,SUM(getDenda((b.rek_uangair+b.rek_meter+b.rek_adm),b.rek_bln,b.rek_thn)) AS rek_denda,SUM(b.rek_total) AS rek_total,COUNT(b.rek_nomor) AS rek_lembar FROM tm_pelanggan a LEFT JOIN tm_rekening b ON(b.pel_no=a.pel_no AND b.rek_sts=1 AND b.rek_byr_sts=0) WHERE a.pel_no='".implode("' OR a.pel_no='",$nopel_list)."' GROUP BY a.pel_no ORDER BY a.pel_no DESC";
				try{
					if(!$res0 = mysql_query($que0,$link)){
						throw new Exception($que0);
					}
					else{
						$i = 0;
						while($row0 = mysql_fetch_object($res0)){
							$data[] = $row0;
							$i++;	
					}
						$mess = false;
					}
				}
				catch (Exception $e){
					errorLog::errorDB(array($que0));
					$mess = $e->getMessage();
				}
			}
			/** koneksi ke database transaksi */
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
				// line untuk ff continous paper
				$stringCetak  = chr(27).chr(67).chr(1);
				// enable paper out sensor
				$stringCetak .= chr(27).chr(57);
				// draft mode
				$stringCetak .= chr(27).chr(120).chr(48);
        // mode 10 cpi
        $stringCetak .= chr(27).chr(80);
				// line spacing x/72
				$stringCetak .= chr(27).chr(65).chr(12);
				for($i=0;$i<count($data);$i++){
					$row0		= $data[$i];
					$proses		= "cetakSPT";
					$pel_no		= $row0->pel_no;
					$spt_ket	= $_SESSION['sptket'][$pel_no];
					try {
						$db->beginTransaction();
						$que 	= "INSERT INTO tm_surat_peringatan (sr_nomor,pel_no,surat_tgl,surat_tipe,surat_ket,kar_id)	VALUES("._TOKN.",'$pel_no',NOW(),'1','$spt_ket','"._USER."')";
						$st 	= $db->exec($que);
						$db->commit();
						errorLog::logDB(array($que));
						if($st>0){
							$mess = "SPT pelanggan:$pel_no telah dicetak";
							$klas = "success";
						}
					}
					catch (PDOException $err){
						$db->rollBack();
						$mess = $err->getTrace();
						errorLog::errorDB(array($mess[0]['args'][0]));
						$mess = "Mungkin telah terjadi kesalahan pada prosedur manual, sehingga proses cetak spt pelanggan:$pel_no tidak bisa dilakukan.";
						$klas = "error";
					}
					$stringCetak .= "PT WAR BESRENDI BIAK".chr(10);
					$stringCetak .= chr(10).chr(10);
					$stringCetak .= "Biak, ".date('d')." ".$bulan[date('n')]." ".date('Y').chr(10);
					$stringCetak .= printLeft("PERIHAL",18).		": TUNGGAKAN REKENING".chr(10);
					$stringCetak .= printLeft("NAMA PELANGGAN",18).	": ".$row0->pel_nama.chr(10);
					$stringCetak .= printLeft("NO. SAMBUNGAN",18).	": ".$row0->pel_no.chr(10);
					$stringCetak .= printLeft("ALAMAT",18).			": ".$row0->pel_alamat.chr(10);
					$stringCetak .= "Pelanggan yang terhormat.".chr(10);
					$stringCetak .= "Berdasarkan catatan yang ada pada kami per tanggal ".date('d-m-Y')." Bapak/Ibu/Sdr".chr(10);
					$stringCetak .= "Masih ada tunggakan (".number_format($row0->rek_lembar)." lembar) rekening air/non air sebesar :".chr(10);
					$stringCetak .= "     2 Bulan Lalu  : ".printRight(number_format($row0->total3),12).chr(10);
					$stringCetak .= "     Bulan Lalu    : ".printRight(number_format($row0->total2),12).chr(10);
					$stringCetak .= "     Bulan Ini     : ".printRight(number_format($row0->total1),12).chr(10);
					$stringCetak .= "     Denda         : ".printRight(number_format($row0->rek_denda),12).chr(10);
					$stringCetak .= "     Total         : ".printRight(number_format($row0->rek_total+$row0->rek_denda),12).chr(10);
					$stringCetak .= "Dengan ini kami mohon Bapak/Ibu/Sdr segera melunasi tunggakan tersebut pada".chr(10);
					$stringCetak .= "Bagian Keuangan setiap hari kerja :".chr(10);
					$stringCetak .= printLeft("- SENIN S/D JUMAT",18).": JAM 08.00 - 14.30".chr(10);
					$stringCetak .= printLeft("- SABTU",18).": JAM 08.00 - 12.00".chr(10);
					$stringCetak .= "Apabila dalam batas waktu 3 (tiga) hari setelah diterimanya pemberitahuan ini".chr(10);
					$stringCetak .= "tidak melunasi tunggakan, maka dengan terpaksa kami memutus sambungan air".chr(10);
					$stringCetak .= "(kalau sudah merasa membayar harap dibawa bukti kwitansi pembayarannya).".chr(10);
					$stringCetak .= "Demikian harap maklum".chr(10);
					$stringCetak .= chr(10);
					$stringCetak .= str_repeat(" ",25).printCenter("PETUGAS PENAGIH",25).printCenter("PT WAR BESRENDI BIAK",29).chr(10);
					$stringCetak .= printLeft("DITERIMA TGL",14).	":".str_repeat(" ",35).printCenter("DIREKTUR UTAMA",29).chr(10);
					$stringCetak .= printLeft("YANG MENERIMA",14).	":".chr(10).chr(10);
					$stringCetak .= printLeft("TANDA TANGAN",14).	":".str_repeat(" ",10).printCenter(str_repeat(".",15),25).printCenter(str_repeat(".",15),29).chr(10);
					$stringCetak .= str_repeat(" ",25).printCenter("NIK".str_repeat(" ",12),25).printCenter("NIK 906218",29).chr(10).chr(10).chr(10);
				}

				if($i==count($data) and $i%2>0){
					for($j=0;$j<42;$j++){
						$stringCetak .= chr(10);
					}
				}
				
				$stringFile	  = "_data/"._TOKN.".txt";
				$openFile 	  = fopen($stringFile, 'w');
				fwrite($openFile, $stringCetak);
				fclose($openFile);
				
				try{
					$wsdl_url	= "http://"._PRIN."/printClient/printServer.wsdl";
					$client   	= new SoapClient($wsdl_url, array('cache_wsdl' => WSDL_CACHE_NONE) );
					$stringFile	= _TOKN.".txt";
					$client->cetak(base64_encode($stringCetak),$stringFile);
				}
				catch (Exception $e){
					$mess 		= $e->getMessage();
				}
			}
?>
<input type="hidden" class="cetakin" name="targetUrl" 	value="cetak_surat_pemberitahuan.php"/>
<input type="hidden" class="cetakin" name="sr_nomor" 	value="<?php echo _TOKN; ?>"/>
<input type="button" value="Kembali" onclick="buka('kembali')"/>
&nbsp;<input type="submit" value="Cetak" onclick="nonghol('cetakin')"/>
<?php
			echo $i." Lembar SPT telah dibuat";
			break;
		default :
			$mess = "tidak ada operasi yang dijalankan";
	}
	errorLog::logMess(array($mess));
?>
<input id="norefresh" type="hidden" value="1"/>
