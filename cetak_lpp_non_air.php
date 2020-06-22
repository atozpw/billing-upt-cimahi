<?php
	if($erno) die();
	$kopel		= "- ".$_SESSION['kp_ket'];
	if(!isset($proses)){
		$proses	= false;
	}
	if(!isset($rek_nomor)){
		$rek_nomor	= false;
	}

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

		switch($tr_sts){
			case "3":
				$stsLoket 	= "Buka";
				break;
			default:
				$stsLoket	= "Tutup";
		}
		
		if(!isset($kar_id)){
			$kar_id		= _USER;
			$kar_nama	= $_SESSION['Name_c'];
		}
		
		if($pilihan==1){
			$que0 		= "SELECT a.* FROM v_lpp a WHERE a.kp_kode='".$_SESSION['Kota_c']."' AND a.kar_id='$kar_id' AND DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') ORDER BY a.periode DESC,a.rek_thn ASC,a.rek_bln ASC,a.pel_no ASC";
			$kembali	= "[<a onclick=\"buka('kembali')\">Kembali</a>]";
		}
		else{
			$que0 		= "SELECT a.* FROM v_lpp_non_air a WHERE a.kar_id='$kar_id' AND DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') ORDER BY a.periode DESC,a.rek_thn ASC,a.rek_bln ASC,a.pel_no ASC";
			$kembali	= "";
		}
		try{
			$res0 = mysql_query($que0,$link);
			$halT = 0;
			while($row0 = mysql_fetch_assoc($res0)){
				$data[$row0['periode']][] = $row0;
				$halT++;
			}
		}
		catch (Exception $e){
			errorLog::errorDB(array($que0));
			$mess = $e->getMessage();
		}
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input type="hidden" id="keyProses0" 	value="1"/>
<input type="hidden" id="tutup" 		value="<?php echo $formId; ?>"/>
<div class="pesan pull-4 span-22 prepend-top">
<div class="span-21 right large cetak">
	<?php echo $kembali; ?>
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	<?php if($stsLoket=="Tutup"){ ?>[<a onclick="window.print()">Cetak</a>] <?php } else{ ?>
	<div class="notice small">Untuk melakukan pencetakan LPP, lakukan proses tutup loket terlebih dahulu.</div>
	<?php } ?>
</div>
<input type="hidden" class="kembali" 	name="targetUrl" 	value="cetak_lpp_rekap.php"/>
<input type="hidden" class="kembali" 	name="targetId" 	value="<?php echo $formId; 	?>" />
<input type="hidden" class="kembali"	name="formId" 		value="<?php echo $formId; 	?>" />
<input type="hidden" class="kembali" 	name="dibayar" 		value="<?php echo $dibayar; ?>" />
<input type="hidden" class="kembali" 	name="pilihan" 		value="<?php echo $pilihan; ?>" />
<input type="hidden" class="cetak" 		name="targetUrl" 	value="cetak_lpp_rinci.php"/>
<input type="hidden" class="cetak" 		name="targetId" 	value="targetId"/>
<input type="hidden" class="cetak" 		name="proses" 		value="cetak"/>
<div id="targetId"></div>
<table width="100%" class="prn_table">
  <tr>
	<td colspan="4" class="center"><h3>LPP Non Air Rinci Per Pelaksana KAS <?php echo $kopel; ?></h3></td>
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
		$stringCetak .= "LPP NON AIR RINCI PER PELAKSANA KAS ".$kopel.chr(10);
		$stringCetak .= "PENERIMA      : ".$kar_nama.chr(10);
		$stringCetak .= "PERIODE       : ".$dibayar.chr(10);
		$stringCetak .= "TANGGAL CETAK : ".$tanggal." ".$jam.chr(10);
		$stringCetak .= "STATUS LOKET  : ".$stsLoket.chr(10);
		$stringCetak .= printLeft("NO.",4);
		$stringCetak .= printLeft("BULAN",7);
		$stringCetak .= printLeft("NO.SL",7);
		$stringCetak .= printLeft("NAMA",14);
		$stringCetak .= printCenter("GOL",4);
		$stringCetak .= printCenter("PEMBAYARAN",35);
		$stringCetak .= printCenter("TOTAL",9).chr(10);
?>
<table width="100%" class="prn_table">
	<tr class="table_cont_btm center">
		<td class="center prn_cell">No.</td>
		<td class="center prn_cell" width="60px">Bulan</td>
		<td class="center prn_cell">Nomer SR</td>
		<td class="center prn_cell">Nama</td>
		<td class="center prn_cell">Gol</td>
		<td class="center prn_cell">Pembayaran</td>
		<td class="center prn_cell">Rincian</td>
		<td class="center prn_cell">Jumlah</td>
	</tr>
<?php
		$hal0	= 1;
		$halA	= 50;
		$halB	= 60;
		$halT	= 1 + ceil(($halT-$halA)/$halB);
		$nomer = 0;
		if(!isset($data)){
			$data	= array();
		}
		if(count($data)>0){
			$level1_val 	= $data;
			$level1_key 	= array_keys($level1_val);
			if(!isset($l1_pakai)){
				$l1_pakai	= array();
			}
			if(!isset($b0_pakai)){
				$b0_pakai	= false;
			}
			if(!isset($b0_total)){
				$b0_total	= array();
			}
			if(!isset($b1_pakai)){
				$b1_pakai	= false;
			}
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
				$stringCetak .= $periode.chr(10);
?>
	<tr><th colspan="13" class="prn_left"><?=$periode?></th></tr>
<?php
				$level2_val		= $level1_val[$level1_key[$i]];
				$level2_key		= array_keys($level2_val);
				/* order by level 2 rincian tunggakan */
				for($k=0;$k<count($level2_val);$k++){
					$nomer++;
					$nilai	= $level2_val[$level2_key[$k]];
					$klas 	  = "table_cell1";
					if(($k%2) == 0){
						$klas = "table_cell2";
					}
					$kunci	= array_keys($nilai);
					for($m=0;$m<count($kunci);$m++){
						$$kunci[$m] = $nilai[$kunci[$m]];
					}
					
					$l0_total[] 					= $tna_jml;
					$l1_total[$level1_key[$i]][] 	= $tna_jml;
					
					$warna	= "black";
					if($byr_sts==0){
						$ket_loket 		= $ket_loket." [B]";
						$warna			= "red";
						
						$b0_total[] 					= $tna_jml;
						$b1_total[$level1_key[$i]][] 	= $tna_jml;
						
						$stringCetak .= printLeft($nomer,4);
						$stringCetak .= printLeft(substr($rek_nomor,0,6),7);
						$stringCetak .= printLeft($pel_no,7);
						$stringCetak .= printLeft(substr($pel_nama,0,14),14);
						$stringCetak .= printCenter($gol_kode,4);
						$stringCetak .= printLeft($na_rinci,35);
						$stringCetak .= printRight(0,9).chr(10);
					}
					else{
						$stringCetak .= printLeft($nomer,4);
						$stringCetak .= printLeft(substr($rek_nomor,0,6),7);
						$stringCetak .= printLeft($pel_no,7);
						$stringCetak .= printLeft(substr($pel_nama,0,14),14);
						$stringCetak .= printCenter($gol_kode,4);
						$stringCetak .= printLeft($na_rinci,35);
						$stringCetak .= printRight(number_format($tna_jml),9).chr(10);
					}
?>
	<tr class="<?php echo $klas; ?>">
		<td class="right prn_cell" style="color: <?=$warna?>">		<?php echo $nomer; 					?></td>
		<td class="prn_cell" style="color: <?=$warna?>">			<?php echo $tagihan;				?></td>
		<td class="prn_cell" style="color: <?=$warna?>">			<?php echo $pel_no;	 				?></td>
		<td class="prn_cell prn_left" style="color: <?=$warna?>">	<?php echo $pel_nama; 				?></td>
		<td class="prn_cell" style="color: <?=$warna?>">			<?php echo $gol_kode; 				?></td>
		<td class="prn_cell" style="color: <?=$warna?>">			<?php echo $na_ket; 				?></td>
		<td class="prn_cell" style="color: <?=$warna?>">			<?php echo $na_rinci;				?></td>
		<td class="right prn_cell" style="color: <?=$warna?>">		<?php echo number_format($tna_jml); ?></td>
	</tr>
<?php
					if($nomer==$halA){
						$stringCetak .= "Halaman 1 dari ".$halT.chr(12);
						$stringCetak .= printLeft("NO.",4);
						$stringCetak .= printLeft("BULAN",7);
						$stringCetak .= printLeft("NO.SL",7);
						$stringCetak .= printLeft("NAMA",14);
						$stringCetak .= printCenter("GOL",4);
						$stringCetak .= printCenter("PEMBAYARAN",35);
						$stringCetak .= printCenter("TOTAL",9).chr(10);
						$hal0++;
					}
					else if($nomer>$halA and ($nomer-$halA)%$halB==0){
						$stringCetak .= "Halaman ".$hal0." dari ".$halT.chr(12);
						$stringCetak .= printLeft("NO.",4);
						$stringCetak .= printLeft("BULAN",7);
						$stringCetak .= printLeft("NO.SL",7);
						$stringCetak .= printLeft("NAMA",14);
						$stringCetak .= printCenter("GOL",4);
						$stringCetak .= printCenter("PEMBAYARAN",35);
						$stringCetak .= printCenter("TOTAL",9).chr(10);
						$hal0++;
					}
				}
			}
			$level1_val   = $l1_pakai;
			$level1_key   = array_keys($level1_val);

			$stringCetak .= chr(10);

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
				if(count($b1_pakai[$level1_key[$i]])>0){
					$c1_total		= array_sum($l1_total[$level1_key[$i]]) - array_sum($b1_total[$level1_key[$i]]);
				}
				else{
					$c1_total		= array_sum($l1_total[$level1_key[$i]]);
				}
				$stringCetak .= printLeft($periode,67).":";
				$stringCetak .= printRight(number_format($c1_total),12).chr(10);
?>
	<tr class="table_cont_btm">
		<td colspan="7" class="prn_cell">Total <?=$periode?> : </td>
	 	<td class="right prn_cell"><?php echo number_format($c1_total); 	?></td>
	</tr>
<?php
			}
			if(count($b0_pakai)>0){
				$c0_total		= array_sum($l0_total) - array_sum($b0_total);
			}
			else{
				$c0_total		= array_sum($l0_total);
			}
			$stringCetak .= printRight("Grand Total :",68);
			$stringCetak .= printRight(number_format($c0_total),12).chr(10);
?>
    <tr class="table_cont_btm">
    	<td colspan="7" class="prn_cell">Grand Total :</td>
	 	<td class="right prn_cell"><?php echo number_format($c0_total); 	?></td>
	</tr>
<?php
			for($j=0;$j<$nomer%60;$j++){
				$stringCetak .= chr(10);
			}
			$stringFile	  = "_data/"._TOKN.".txt";
			$openFile 	  = fopen($stringFile, 'w');
			fwrite($openFile, $stringCetak);
			fclose($openFile);
		}
?>
</table>
<input type="hidden" class="cetak" name="stringCetak" value="<?php echo base64_encode($stringCetak); ?>"/>
</div>
</div>
<?php
	}
?>