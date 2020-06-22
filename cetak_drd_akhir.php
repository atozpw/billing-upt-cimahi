<?php
	if($erno) die();
	$formId 	= getToken();
	$kp_kode	= $_SESSION['Kota_c'];
	$kp_ket		= $_SESSION['kp_ket'];
	$erno		= false;
	$rek_bln	= date('n');
	$rek_thn	= date('Y');
	
	if($rek_bln==1){
		$rek_bln=12;
		$rek_thn--;
	}
	else{
		$rek_bln--;
	}
	
	if($gol_kode=='KG'){
		$que0 = "SELECT a.pel_no,a.pel_nama,a.rek_gol AS gol_kode,a.rek_gol AS dkd_kd,a.rek_stanlalu,a.rek_stankini,(a.rek_stankini-a.rek_stanlalu) AS rek_pakai,a.rek_uangair,(a.rek_meter+a.rek_adm) AS rek_beban,a.rek_angsuran,a.rek_total,CONCAT('GOLONGAN : [',b.gol_kode,'] ',b.gol_ket) AS rayon FROM tm_rekening a JOIN tr_gol b ON(b.gol_kode=a.rek_gol) JOIN tm_pelanggan d ON(d.pel_no=a.pel_no) WHERE a.rek_sts=1 AND a.rek_bln=$rek_bln AND a.rek_thn=$rek_thn AND d.kp_kode='".$kp_kode."' AND a.dkd_kd='".$dkd_kd."' ORDER BY a.rek_gol,a.pel_no";
	}
	else{
		$que0 = "SELECT a.pel_no,a.pel_nama,a.rek_gol AS gol_kode,a.dkd_kd,a.rek_stanlalu,a.rek_stankini,(a.rek_stankini-a.rek_stanlalu) AS rek_pakai,a.rek_uangair,(a.rek_meter+a.rek_adm) AS rek_beban,a.rek_angsuran,a.rek_total,CONCAT('JALAN : [',b.dkd_kd,'] ',b.dkd_jalan) AS rayon FROM tm_rekening a JOIN tr_dkd b ON(b.dkd_kd=a.dkd_kd) JOIN tm_pelanggan d ON(d.pel_no=a.pel_no) WHERE a.rek_sts=1 AND a.rek_bln=$rek_bln AND a.rek_thn=$rek_thn AND d.kp_kode='".$kp_kode."' AND a.dkd_kd='".$dkd_kd."' ORDER BY a.dkd_kd,a.pel_no";
	}
	try{
		$res0 = mysql_query($que0,$link);
		while($row0 = mysql_fetch_array($res0)){
			$data[$row0['dkd_kd']][] = $row0;
		}
		$mess = "DRD rayon : $dkd_kd telah dicetak";
	}
	catch (Exception $e){
		errorLog::errorDB(array($que0));
		$mess = "Terjadi kesalahan pada sistem aplikasi";
		$erno = true;
	}
	// line untuk ff continous paper
	$stringCetak  = chr(27).chr(67).chr(1);
	// enable paper out sensor
	$stringCetak .= chr(27).chr(57);
	// draft mode
	$stringCetak .= chr(27).chr(120).chr(48);
	// mode 12 cpi
	$stringCetak .= chr(27).chr(77);
	// line spacing x/72
	$stringCetak .= chr(27).chr(65).chr(12);

	$halaman	= 1;
	if(!isset($data)){
		$data	= array();
	}
	if(count($data)>0){
		$level1_val 	= $data;
		$level1_key 	= array_keys($level1_val);
		/* order by level 1 pelanggan */
		for($i=0;$i<count($level1_val);$i++){
			// resume bisa ditambahkan di sini
			$rayon		  = $level1_val[$level1_key[$i]][0][11];
				
			$level2_val		= $level1_val[$level1_key[$i]];
			$level2_key		= array_keys($level2_val);
			/* order by level 2 rincian tunggakan */
			for($k=0;$k<count($level2_val);$k++){
				if($k%55==0 OR $k==0){
					if($k>0){
						$stringCetak .= chr(10).chr(10).chr(10).chr(10).chr(10);
					}
					$stringCetak .= printCenter("PT WAR BESRENDI BIAK",81).printLeft("TGL. ".date('d/m/Y'),15).chr(10);
					$stringCetak .= printCenter("DAFTAR REKENING DITAGIH (DRD-AIR) UNTUK BULAN : ".$bulan[$rek_bln]." ".$rek_thn,81).printLeft("HALAMAN ".$halaman,15).chr(10);
					$stringCetak .= printLeft("Cabang / Unit : ".$kp_ket,50).printRight($rayon,46).chr(10);
					$stringCetak .= str_repeat('=',96).chr(10);
					$stringCetak .= printCenter("NO",4);
					$stringCetak .= printLeft("NOSL",7);
					$stringCetak .= printLeft("NAMA",18);
					$stringCetak .= printRight("GOL",4);
					$stringCetak .= printRight("LALU",8);
					$stringCetak .= printRight("KINI",8);
					$stringCetak .= printRight("PAKAI",6);
					$stringCetak .= printRight("HARGA AIR",16);
					$stringCetak .= printRight("BEBAN",8);
					$stringCetak .= printRight("TOTAL",16).chr(10);
					$stringCetak .= str_repeat('=',96).chr(10);
					$halaman++;
				}
				/** getParam 
					memindahkan semua nilai dalam array POST ke dalam
					variabel yang bersesuaian dengan masih kunci array
				*/
				$nilai	= $level2_val[$level2_key[$k]];
				$konci	= array_keys($nilai);
				for($l=0;$l<count($konci);$l++){
					$$konci[$l]	= $nilai[$konci[$l]];
				}
				/* getParam **/
				$stringCetak .= printRight(($k+1),3);
				$stringCetak .= printLeft(" ".$pel_no,7);
				$stringCetak .= printLeft(substr(" ".$pel_nama,0,19),19);
				$stringCetak .= printRight($gol_kode,4);
				$stringCetak .= printRight(number_format($rek_stanlalu),8);
				$stringCetak .= printRight(number_format($rek_stankini),8);
				$stringCetak .= printRight(number_format($rek_pakai),5);
				$stringCetak .= printRight(number_format($rek_uangair),17);
				$stringCetak .= printRight(number_format($rek_beban),8);
				$stringCetak .= printRight(number_format($rek_total),17).chr(10);
				
				$l0_pakai[$i][]		= $rek_pakai;
				$l0_uangair[$i][]	= $rek_uangair;
				$l0_beban[$i][]		= $rek_beban;
				$l0_total[$i][]		= $rek_total;
			}
			$stringCetak .= str_repeat('=',96).chr(10);
			$stringCetak .= printRight("Jumlah Akhir :",81);
			$stringCetak .= printRight(number_format(array_sum($l0_total[$i])),15).chr(10);
		}
	}
	for($m=2;$m<(56-($k%56));$m++){
		$stringCetak .= chr(10);
	}
	$stringCetak .= chr(12);
	$stringFile	  = "_data/"._TOKN.".txt";
	$openFile 	  = fopen($stringFile, 'w');
	fwrite($openFile, $stringCetak);
	fclose($openFile);
		
	try{
		$wsdl_url 	= "http://"._PRIN."/printClient/printServer.wsdl";
		$client   	= new SoapClient($wsdl_url, array('cache_wsdl' => WSDL_CACHE_NONE) );
		$cetak 		= true;
	}
	catch (Exception $e){
		echo $e->getMessage();
		$cetak 		= false;
	}
	$stringFile	  = _TOKN.".txt";
	$client->cetak(base64_encode($stringCetak),$stringFile);
	
	echo $mess;
?>
