<?php
	if($erno) die();
	define("_KOTA",$_SESSION['Kota_c']);
	if($proses=="cetak"){
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
		$client->cetak($stringCetak,$stringFile);
	}
	else{
		$formId 	= getToken();
		$kar_nama	= $_SESSION['Name_c'];
		$kopel		= $_SESSION['kp_ket'];

		switch($tr_sts){
			case "3":
				$stsLoket 	= "Buka";
				break;
			default:
				$stsLoket	= "Tutup";
		}
		
		switch($pilihan){
			case 1:
				if(_KOTA=='00'){
					$que0 		= "SELECT a.kar_id,a.kar_nama,a.periode,a.gol_kode,a.kar_nama AS gol_ket,COUNT(a.rek_nomor) AS lembar,SUM(a.pemakaian) AS pemakaian,SUM(a.rek_uangair) AS rek_uangair,SUM(a.beban_tetap) AS beban_tetap,SUM(a.rek_angsuran) AS rek_angsuran,SUM(a.rek_denda) AS rek_denda,SUM(a.rek_materai) AS rek_materai,SUM(a.byr_total) AS byr_total FROM v_lpp a WHERE DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') AND a.byr_sts>=1 GROUP BY a.periode,a.kar_id ORDER BY a.periode DESC,a.kar_id ASC";
					$tittle		= "LPP Rekap Seluruh Kasir";
					$kat		= "Kasir";
				}
				else{
					// $que0 	= "SELECT a.kar_id,a.kar_nama,a.periode,a.gol_kode,a.kar_nama AS gol_ket,COUNT(a.rek_nomor) AS lembar,SUM(a.pemakaian) AS pemakaian,SUM(a.rek_uangair) AS rek_uangair,SUM(a.beban_tetap) AS beban_tetap,SUM(a.rek_angsuran) AS rek_angsuran,SUM(a.rek_denda) AS rek_denda,SUM(a.rek_materai) AS rek_materai,SUM(a.byr_total) AS byr_total FROM v_lpp a WHERE DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') AND a.byr_sts=1 AND a.kp_kode='"._KOTA."' GROUP BY a.periode,a.kar_id ORDER BY a.periode DESC,a.kar_id ASC";
					$que0 	= "SELECT a.kar_id,a.kar_nama,a.periode,a.gol_kode,a.kar_nama AS gol_ket,COUNT(a.rek_nomor) AS lembar,SUM(a.pemakaian) AS pemakaian,SUM(a.rek_uangair) AS rek_uangair,SUM(a.beban_tetap) AS beban_tetap,SUM(a.rek_angsuran) AS rek_angsuran,SUM(a.rek_denda) AS rek_denda,SUM(a.rek_materai) AS rek_materai,SUM(a.byr_total) AS byr_total FROM v_lpp a WHERE DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') AND a.byr_sts>=1 AND a.kar_id='".$_SESSION['User_c']."' GROUP BY a.periode,a.kar_id ORDER BY a.periode DESC,a.kar_id ASC";
					$tittle	= "LPP Rekap Seluruh Kasir - ".$kopel;
					$kat	= "Kasir";
				}
				break;
			default:
				if(_KOTA=='00'){
					$que0 	= "SELECT a.kar_id,a.kar_nama,a.periode,a.gol_kode,a.gol_ket AS gol_ket,COUNT(a.rek_nomor) AS lembar,SUM(a.pemakaian) AS pemakaian,SUM(a.rek_uangair) AS rek_uangair,SUM(a.beban_tetap) AS beban_tetap,SUM(a.rek_angsuran) AS rek_angsuran,SUM(a.rek_denda) AS rek_denda,SUM(a.rek_materai) AS rek_materai,SUM(a.byr_total) AS byr_total FROM v_lpp a WHERE DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') AND a.byr_sts>=1 GROUP BY a.periode,a.gol_kode ORDER BY a.periode DESC,a.gol_kode ASC";
					$tittle	= "LPP Rekap Seluruh Golongan";
					$kat	= "Golongan";
				}
				else{
					$que0 	= "SELECT a.kar_id,a.kar_nama,a.periode,a.gol_kode,a.gol_ket AS gol_ket,COUNT(a.rek_nomor) AS lembar,SUM(a.pemakaian) AS pemakaian,SUM(a.rek_uangair) AS rek_uangair,SUM(a.beban_tetap) AS beban_tetap,SUM(a.rek_angsuran) AS rek_angsuran,SUM(a.rek_denda) AS rek_denda,SUM(a.rek_materai) AS rek_materai,SUM(a.byr_total) AS byr_total FROM v_lpp a WHERE DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') AND a.byr_sts>=1 AND a.kp_kode='"._KOTA."' GROUP BY a.periode,a.gol_kode ORDER BY a.periode DESC,a.gol_kode ASC";
					$tittle	= "LPP Rekap Seluruh Golongan - ".$kopel;
					$kat	= "Golongan";
				}
		}
		try{
			$res0 = $link->query($que0);
			while($row0 = $res0->fetch_assoc()){
				$data[$row0['periode']][] = $row0;
			}
		}
		catch (Exception $e){
			errorLog::errorDB(array($que0));
			$mess = $e->getMessage();
		}
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input type="hidden" id="norefresh" 	value="1"/>
<input type="hidden" id="keyProses0" 	value="1"/>
<input type="hidden" id="tutup" 		value="<?php echo $formId; ?>"/>
<input type="hidden" class="cetak" name="targetUrl" value="cetak_lpp_rekap.php"/>
<input type="hidden" class="cetak" name="targetId" 	value="targetId"/>
<input type="hidden" class="cetak" name="proses" 	value="cetak"/>
<div id="targetId"></div>
<div class="pesan pull-4 span-22 prepend-top">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<table width="100%" class="prn_table">
  <tr>
	<td colspan="4" class="center"><h3><?php echo $tittle; ?></h3></td>
  </tr>
  <tr>
    <td width="20%">Penerima</td>
    <td width="40%">: <?php echo $kar_nama; ?></td>
    <td width="15%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
  <tr>
    <td width="20%">Periode</td>
    <td width="40%">: <?php echo $dibayar; ?></td>
    <td width="15%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
  <tr>
    <td width="20%">Tanggal Cetak</td>
    <td width="40%">: <?php echo $tanggal; ?> <?php echo $jam; ?></td>
    <td width="15%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
  <tr>
    <td width="20%">Status Loket</td>
    <td width="40%">: <?php echo $stsLoket; ?></td>
    <td width="15%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<hr/>
<?php
		// line untuk ff continous paper
		$stringCetak  = chr(27).chr(67).chr(11);
		// enable paper out sensor
		$stringCetak .= chr(27).chr(57);
		// draft mode
		$stringCetak .= chr(27).chr(120).chr(48);
		// line spacing x/72
		$stringCetak .= chr(27).chr(65).chr(12);
		$stringCetak .= strtoupper($tittle).chr(10);
		$stringCetak .= "PENERIMA      : ".$kar_nama.chr(10);
		$stringCetak .= "PERIODE       : ".$dibayar.chr(10);
		$stringCetak .= "TANGGAL CETAK : ".$tanggal." ".$jam.chr(10);
		$stringCetak .= "STATUS LOKET  : ".$stsLoket.chr(10);
		$stringCetak .= str_repeat("_",80).chr(10);
		$stringCetak .= printRight(strtoupper($kat),14);
		$stringCetak .= printRight("JML",5);
		$stringCetak .= printRight("PAKAI",7);
		$stringCetak .= printRight("UANG AIR",12);
		$stringCetak .= printRight("BEBAN",10);
		$stringCetak .= printRight("ANGS.",8);
		$stringCetak .= printRight("DENDA",11);
		$stringCetak .= printRight("TOTAL",12).chr(10);
?>
<table width="100%" class="prn_table">
	<tr class="table_cont_btm center">
		<td class="center prn_cell"><?php echo $kat; ?></td>
		<td class="center prn_cell">Jumlah</td>
		<td class="center prn_cell">Pakai</td>
		<td class="center prn_cell">Uang Air</td>
		<td class="center prn_cell">Pemeliharaan</td>
		<td class="center prn_cell">Angsuran</td>
		<td class="center prn_cell">Denda</td>
		<td class="center prn_cell">Total</td>
	</tr>
<?php
		if(count($data)>0){
			$level1_val 	= $data;
			$level1_key 	= array_keys($level1_val);
			/* order by level 1 pelanggan */
			for($i=0;$i<count($level1_val);$i++){
				// resume bisa ditambahkan di sini
				switch($level1_key[$i]){
					case 0:
						$periode = "-";
						break;
					case 1:
						$periode = "Bulan Berjalan";
						break;
					case 2:
						$periode = "Bulan Lalu";
						break;
					default:
						$periode = "2 Bulan lalu";
				}
				$stringCetak .= strtoupper($periode).chr(10);
?>
	<tr><th colspan="13" class="prn_left"><?=$periode?></th></tr>
<?php
				$level2_val		= $level1_val[$level1_key[$i]];
				$level2_key		= array_keys($level2_val);
				/* order by level 2 rincian tunggakan */
				for($k=0;$k<count($level2_val);$k++){
					$warna	= "black";
					$nilai	= $level2_val[$level2_key[$k]];
					$klas 	= "table_cell1";
					if(($k%2) == 0){
						$klas = "table_cell2";
					}
					$kunci	= array_keys($nilai);
					for($m=0;$m<count($kunci);$m++){
						if(PHP_VERSION < 7){
							$$kunci[$m] = $nilai[$kunci[$m]];
						}else{
							${$kunci[$m]} = $nilai[$kunci[$m]];
						}
					}
					$l0_lembar[] 	= $lembar;
					$l0_pakai[] 	= $pemakaian;
					$l0_uangair[]	= $rek_uangair;
					$l0_angsuran[]	= $rek_angsuran;
					$l0_beban[]		= $beban_tetap;
					$l0_denda[] 	= $rek_denda;
					$l0_total[] 	= $byr_total;
					
					$l1_lembar[$level1_key[$i]][] 	= $lembar;
					$l1_pakai[$level1_key[$i]][] 	= $pemakaian;
					$l1_uangair[$level1_key[$i]][]	= $rek_uangair;
					$l1_angsuran[$level1_key[$i]][]	= $rek_angsuran;
					$l1_beban[$level1_key[$i]][]	= $beban_tetap;
					$l1_denda[$level1_key[$i]][] 	= $rek_denda;
					$l1_total[$level1_key[$i]][] 	= $byr_total;
					
					$stringCetak .= printRight(strtoupper($gol_ket),14);
					$stringCetak .= printRight(number_format($lembar),5);
					$stringCetak .= printRight(number_format($pemakaian),7);
					$stringCetak .= printRight(number_format($rek_uangair),12);
					$stringCetak .= printRight(number_format($beban_tetap),10);
					$stringCetak .= printRight(number_format($rek_angsuran),8);
					$stringCetak .= printRight(number_format($rek_denda),11);
					$stringCetak .= printRight(number_format($byr_total),12).chr(10);
					if($pilihan==1){
						$gol_ket = "<a onclick=\"buka('$kar_id')\">$gol_ket</a>";
					}
?>
	<tr class="<?php echo $klas; ?>">
		<td class="right prn_cell prn_left" style="color: <?=$warna?>">
			<input type="hidden" class="<?php echo $kar_id; ?>" name="targetUrl"	value="cetak_lpp_rinci.php" />
			<input type="hidden" class="<?php echo $kar_id; ?>" name="targetId" 	value="<?php echo $formId; 	?>" />
			<input type="hidden" class="<?php echo $kar_id; ?>" name="formId" 		value="<?php echo $formId; 	?>" />
			<input type="hidden" class="<?php echo $kar_id; ?>" name="dibayar" 		value="<?php echo $dibayar; ?>" />
			<input type="hidden" class="<?php echo $kar_id; ?>" name="kar_id" 		value="<?php echo $kar_id; 	?>" />
			<input type="hidden" class="<?php echo $kar_id; ?>" name="kar_nama"		value="<?php echo $kar_nama;?>" />
			<input type="hidden" class="<?php echo $kar_id; ?>" name="pilihan" 		value="1" />
			<?php echo $gol_ket; ?>
		</td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($lembar);	 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($pemakaian); 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($rek_uangair); 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($beban_tetap); 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($rek_angsuran);	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($rek_denda); 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($byr_total); 	?></td>
	</tr>
<?php
				}
			}
			$level1_val 	= $l1_pakai;
			$level1_key 	= array_keys($level1_val);
			/* order by level 1 pelanggan */
			for($i=0;$i<count($level1_val);$i++){
				switch($level1_key[$i]){
					case 0:
						$periode = "-";
						break;
					case 1:
						$periode = "Bulan Berjalan";
						break;
					case 2:
						$periode = "Bulan Lalu";
						break;
					default:
						$periode = "2 Bulan lalu";
				}
				$c1_lembar		= array_sum($l1_lembar[$level1_key[$i]]);
				$c1_pakai		= array_sum($l1_pakai[$level1_key[$i]]);
				$c1_uangair		= array_sum($l1_uangair[$level1_key[$i]]);
				$c1_angsuran	= array_sum($l1_angsuran[$level1_key[$i]]);
				$c1_beban		= array_sum($l1_beban[$level1_key[$i]]);
				$c1_denda		= array_sum($l1_denda[$level1_key[$i]]);
				$c1_total		= array_sum($l1_total[$level1_key[$i]]);
				
				$stringCetak .= printLeft(strtoupper($periode),14);
				$stringCetak .= printRight(number_format($c1_lembar),5);
				$stringCetak .= printRight(number_format($c1_pakai),7);
				$stringCetak .= printRight(number_format($c1_uangair),12);
				$stringCetak .= printRight(number_format($c1_beban),10);
				$stringCetak .= printRight(number_format($c1_angsuran),8);
				$stringCetak .= printRight(number_format($c1_denda),11);
				$stringCetak .= printRight(number_format($c1_total),12).chr(10);
?>
	<tr class="table_cont_btm">
		<td class="prn_cell">Total <?=$periode?> :</td>
	 	<td class="right prn_cell"><?php echo number_format($c1_lembar); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_pakai);	 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_uangair); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_beban); 	?></td>
		<td class="right prn_cell"><?php echo number_format($c1_angsuran); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_denda); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_total); 	?></td>
	</tr>
<?php
			}
			$c0_lembar		= array_sum($l0_lembar);
			$c0_pakai		= array_sum($l0_pakai);
			$c0_uangair		= array_sum($l0_uangair);
			$c0_angsuran	= array_sum($l0_angsuran);
			$c0_beban		= array_sum($l0_beban);
			$c0_denda		= array_sum($l0_denda);
			$c0_total		= array_sum($l0_total);
			
			$stringCetak .= printLeft("GRAND TOTAL",14);
			$stringCetak .= printRight(number_format($c0_lembar),5);
			$stringCetak .= printRight(number_format($c0_pakai),7);
			$stringCetak .= printRight(number_format($c0_uangair),12);
			$stringCetak .= printRight(number_format($c0_beban),10);
			$stringCetak .= printRight(number_format($c0_angsuran),8);
			$stringCetak .= printRight(number_format($c0_denda),11);
			$stringCetak .= printRight(number_format($c0_total),12).chr(12);
?>
    <tr class="table_cont_btm">
    	<td class="prn_cell">Grand Total :</td>
	 	<td class="right prn_cell"><?php echo number_format($c0_lembar);	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_pakai);	 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_uangair); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_beban); 	?></td>
		<td class="right prn_cell"><?php echo number_format($c0_angsuran); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_denda); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_total); 	?></td>
	</tr>
<?php
		}
?>
</table>
<?php
		$stringFile	  = "_data/"._TOKN.".txt";
		$openFile 	  = fopen($stringFile, 'w');
		fwrite($openFile, $stringCetak);
		fclose($openFile);
?>
<input type="hidden" class="cetak" name="stringCetak" value="<?php echo base64_encode($stringCetak); ?>"/>
</div>
</div>
<?php
	}
?>