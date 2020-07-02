<?php
	if($erno) die();
	if(!isset($proses)){
		$proses 	= false;
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
			$kopel		= "";
		}
		else{
			$kopel		= "- ".$_SESSION['kp_ket'];
		}
		
		if($pilihan==1){
			if(_KOTA=="00"){
				$que0 		= "SELECT a.* FROM v_lpp a WHERE a.kar_id='$kar_id' AND DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') ORDER BY a.periode DESC,a.rek_thn ASC,a.rek_bln ASC,a.pel_no ASC";
			}
			else{
				//$que0 		= "SELECT a.* FROM v_lpp a WHERE a.kp_kode='".$_SESSION['Kota_c']."' AND a.kar_id='$kar_id' AND DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') ORDER BY a.periode DESC,a.rek_thn ASC,a.rek_bln ASC,a.pel_no ASC";
				$que0 		= "SELECT a.* FROM v_lpp a WHERE a.kar_id='$kar_id' AND DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') ORDER BY a.periode DESC,a.rek_thn ASC,a.rek_bln ASC,a.pel_no ASC";
			}
			$kembali	= "[<a onclick=\"buka('kembali')\">Kembali</a>]";
		}
		else{
			$que0 	= "SELECT a.* FROM v_lpp a WHERE a.kar_id='$kar_id' AND DATE(a.byr_tgl)=STR_TO_DATE('$dibayar','%Y-%m-%d') ORDER BY a.periode DESC,a.rek_thn ASC,a.rek_bln ASC,a.pel_no ASC";
			$kembali	= false;
		}
		try{
			$res0 = $link->query($que0);
			$halT = 0;
			while($row0 = $res0->fetch_assoc()){
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
<div class="span-14 right large cetak">
	<?php echo $kembali; ?>
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	<?php if($stsLoket=="Tutup"){ ?>[<a onclick="window.print()">Cetak</a>] <?php } else{ ?>
	<div class="notice small">Untuk melakukan pencetakan LPP, lakukan proses tutup loket terlebih dahulu.</div>
	<?php } ?>
</div>
<input type="hidden" class="kembali" name="targetUrl" 	value="cetak_lpp_rekap.php"/>
<input type="hidden" class="kembali" name="targetId" 	value="<?php echo $formId; 	?>" />
<input type="hidden" class="kembali" name="formId" 		value="<?php echo $formId; 	?>" />
<input type="hidden" class="kembali" name="dibayar" 	value="<?php echo $dibayar; ?>" />
<input type="hidden" class="kembali" name="pilihan" 	value="<?php echo $pilihan; ?>" />
<input type="hidden" class="cetak" name="targetUrl" value="cetak_lpp_rinci.php"/>
<input type="hidden" class="cetak" name="targetId" 	value="targetId"/>
<input type="hidden" class="cetak" name="proses" 	value="cetak"/>
<div id="targetId"></div>
<table width="100%" class="prn_table">
  <tr>
	<td colspan="4" class="center"><h3>LPP Rinci Per Pelaksana KAS <?php echo $kopel; ?></h3></td>
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
		$stringCetak .= "LPP RINCI PER PELAKSANA KAS ".$kopel.chr(10);
		$stringCetak .= "PENERIMA      : ".$kar_nama.chr(10);
		$stringCetak .= "PERIODE       : ".$dibayar.chr(10);
		$stringCetak .= "TANGGAL CETAK : ".$tanggal." ".$jam.chr(10);
		$stringCetak .= "STATUS LOKET  : ".$stsLoket.chr(10);
		$stringCetak .= printLeft("NO.",4);
		$stringCetak .= printLeft("BULAN",7);
		$stringCetak .= printLeft("NO.SL",7);
		$stringCetak .= printCenter("NAMA",14);
		$stringCetak .= printLeft("GOL",4);
		$stringCetak .= printLeft("LKT",4);
		$stringCetak .= printLeft("PAKAI",6);
		$stringCetak .= printCenter("BIAYA AIR",9);
		$stringCetak .= printRight("ANGS.",8);
		$stringCetak .= printCenter("DENDA",8);
		$stringCetak .= printCenter("TOTAL",9).chr(10);
?>
<table width="100%" class="prn_table">
	<tr class="table_cont_btm center">
		<td class="center prn_cell">No.</td>
		<td class="center prn_cell" width="60px">Bulan</td>
		<td class="center prn_cell">No Pel</td>
		<td class="center prn_cell">Nama</td>
		<td class="center prn_cell">Gol</td>
		<td class="center prn_cell" width="100px">Loket</td>
		<td class="center prn_cell">Pakai</td>
		<td class="center prn_cell">Uang Air</td>
		<td class="center prn_cell">Pemeliharaan</td>
		<td class="center prn_cell">Denda</td>
		<td class="center prn_cell">Total</td>
	</tr>
<?php
		$hal0	= 1;
		$halA	= 50;
		$halB	= 60;
		$halT	= 1 + ceil(($halT-$halA)/$halB);
		$nomer 	= 0;
		if(!isset($data)){
			$data	= array();
		}
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
						if(PHP_VERSION < 7){
							$$kunci[$m]	= $nilai[$kunci[$m]];
						}else{
							${$kunci[$m]} = $nilai[$kunci[$m]];
						}
					}
					$l0_pakai[] 	= $pemakaian;
					$l0_uangair[]	= $rek_uangair;
					$l0_angsuran[]	= $rek_angsuran;
					$l0_beban[]		= $beban_tetap;
					$l0_denda[] 	= $rek_denda;
					$l0_materai[] 	= $rek_materai;
					$l0_total[] 	= $byr_total;
					
					$l1_pakai[$level1_key[$i]][] 	= $pemakaian;
					$l1_uangair[$level1_key[$i]][]	= $rek_uangair;
					$l1_angsuran[$level1_key[$i]][]	= $rek_angsuran;
					$l1_beban[$level1_key[$i]][]	= $beban_tetap;
					$l1_denda[$level1_key[$i]][] 	= $rek_denda;
					$l1_materai[$level1_key[$i]][] 	= $rek_materai;
					$l1_total[$level1_key[$i]][] 	= $byr_total;
					
					$warna	= "black";
					if($byr_sts==0){
						$ket_loket 		= $ket_loket." [B]";
						$warna			= "red";
						
						$b0_pakai[] 	= $pemakaian;
						$b0_uangair[]	= $rek_uangair;
						$b0_angsuran[]	= $rek_angsuran;
						$b0_beban[]		= $beban_tetap;
						$b0_denda[] 	= $rek_denda;
						$b0_materai[] 	= $rek_materai;
						$b0_total[] 	= $byr_total;
						
						$b1_pakai[$level1_key[$i]][] 	= $pemakaian;
						$b1_uangair[$level1_key[$i]][]	= $rek_uangair;
						$b1_angsuran[$level1_key[$i]][]	= $rek_angsuran;
						$b1_beban[$level1_key[$i]][]	= $beban_tetap;
						$b1_denda[$level1_key[$i]][] 	= $rek_denda;
						$b1_materai[$level1_key[$i]][] 	= $rek_materai;
						$b1_total[$level1_key[$i]][] 	= $byr_total;
						
						$stringCetak .= printLeft($nomer,4);
						$stringCetak .= printLeft(substr($rek_nomor,0,6),7);
						$stringCetak .= printLeft($pel_no,7);
						$stringCetak .= printLeft(substr($pel_nama,0,14),14);
						$stringCetak .= printRight($gol_kode,4);
						$stringCetak .= printCenter($ket_loket,3);
						$stringCetak .= printRight(number_format($pemakaian),6);
						$stringCetak .= printRight(number_format($rek_uangair+$beban_tetap),9);
						$stringCetak .= printRight(number_format($rek_angsuran),8);
						$stringCetak .= printRight(number_format($rek_denda),8);
						$stringCetak .= printRight(0,9).chr(10);
					}
					else{
						$stringCetak .= printLeft($nomer,4);
						$stringCetak .= printLeft(substr($rek_nomor,0,6),7);
						$stringCetak .= printLeft($pel_no,7);
						$stringCetak .= printLeft(substr($pel_nama,0,14),14);
						$stringCetak .= printRight($gol_kode,4);
						$stringCetak .= printCenter($ket_loket,3);
						$stringCetak .= printRight(number_format($pemakaian),6);
						$stringCetak .= printRight(number_format($rek_uangair+$beban_tetap),9);
						$stringCetak .= printRight(number_format($rek_angsuran),8);
						$stringCetak .= printRight(number_format($rek_denda),8);
						$stringCetak .= printRight(number_format($byr_total),9).chr(10);
					}
?>
	<tr class="<?php echo $klas; ?>">
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo $nomer; 						?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo $tagihan;						?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo $pel_no;	 					?></td>
		<td class="left prn_cell prn_left" style="color: <?=$warna?>"><?php echo $pel_nama; 			?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo $gol_kode; 					?></td>
		<td class="center prn_cell" style="color: <?=$warna?>"><?php echo $ket_loket; 					?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($pemakaian); 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($rek_uangair); 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($beban_tetap); 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($rek_denda); 	?></td>
		<td class="right prn_cell" style="color: <?=$warna?>"><?php echo number_format($byr_total); 	?></td>
	</tr>
<?php
					if($nomer==$halA){
						$stringCetak .= "Halaman 1 dari ".$halT.chr(12);
						$stringCetak .= printLeft("NO.",4);
						$stringCetak .= printLeft("BULAN",7);
						$stringCetak .= printLeft("NO.SL",7);
						$stringCetak .= printCenter("NAMA",14);
						$stringCetak .= printLeft("GOL",4);
						$stringCetak .= printLeft("LKT",4);
						$stringCetak .= printLeft("PAKAI",6);
						$stringCetak .= printCenter("BIAYA AIR",9);
						$stringCetak .= printRight("ANGS.",6);
						$stringCetak .= printCenter("DENDA",8);
						$stringCetak .= printCenter("TOTAL",9).chr(10);
						$hal0++;
					}
					else if($nomer>$halA and ($nomer-$halA)%$halB==0){
						$stringCetak .= "Halaman ".$hal0." dari ".$halT.chr(12);
						$stringCetak .= printLeft("NO.",4);
						$stringCetak .= printLeft("BULAN",7);
						$stringCetak .= printLeft("NO.SL",7);
						$stringCetak .= printCenter("NAMA",14);
						$stringCetak .= printLeft("GOL",4);
						$stringCetak .= printLeft("LKT",4);
						$stringCetak .= printLeft("PAKAI",6);
						$stringCetak .= printCenter("BIAYA AIR",9);
						$stringCetak .= printRight("ANGS.",8);
						$stringCetak .= printCenter("DENDA",8);
						$stringCetak .= printCenter("TOTAL",9).chr(10);
						$hal0++;
					}
				}
			}
			$level1_val 	= $l1_pakai;
			$level1_key 	= array_keys($level1_val);

			$stringCetak .= printLeft("RESUME",20).":";
			$stringCetak .= printRight("LBR",5);
			$stringCetak .= printRight("PAKAI",7);
			$stringCetak .= printRight("BIAYA AIR",12);
			$stringCetak .= printRight("ANGSURAN",11);
			$stringCetak .= printRight("DENDA",11);
			$stringCetak .= printRight("TOTAL",12).chr(10);

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
					$c1_lembar		= count($l1_pakai[$level1_key[$i]]) - count($b1_pakai[$level1_key[$i]]);
					$c1_pakai		= array_sum($l1_pakai[$level1_key[$i]]) - array_sum($b1_pakai[$level1_key[$i]]);
					$c1_uangair		= array_sum($l1_uangair[$level1_key[$i]]) - array_sum($b1_uangair[$level1_key[$i]]);
					$c1_angsuran	= array_sum($l1_angsuran[$level1_key[$i]]) - array_sum($b1_angsuran[$level1_key[$i]]);
					$c1_beban		= array_sum($l1_beban[$level1_key[$i]]) - array_sum($b1_beban[$level1_key[$i]]);
					$c1_denda		= array_sum($l1_denda[$level1_key[$i]]) - array_sum($b1_denda[$level1_key[$i]]);
					$c1_materai		= array_sum($l1_materai[$level1_key[$i]]) - array_sum($b1_materai[$level1_key[$i]]);
					$c1_total		= array_sum($l1_total[$level1_key[$i]]) - array_sum($b1_total[$level1_key[$i]]);
				}
				else{
					$c1_lembar		= count($l1_pakai[$level1_key[$i]]);
					$c1_pakai		= array_sum($l1_pakai[$level1_key[$i]]);
					$c1_uangair		= array_sum($l1_uangair[$level1_key[$i]]);
					$c1_angsuran	= array_sum($l1_angsuran[$level1_key[$i]]);
					$c1_beban		= array_sum($l1_beban[$level1_key[$i]]);
					$c1_denda		= array_sum($l1_denda[$level1_key[$i]]);
					$c1_materai		= array_sum($l1_materai[$level1_key[$i]]);
					$c1_total		= array_sum($l1_total[$level1_key[$i]]);
				}
				$stringCetak .= printLeft($periode,20).":";
				$stringCetak .= printRight(number_format($c1_lembar),5);
				$stringCetak .= printRight(number_format($c1_pakai),7);
				$stringCetak .= printRight(number_format($c1_uangair+$c1_beban),12);
				$stringCetak .= printRight(number_format($c1_angsuran),11);
				$stringCetak .= printRight(number_format($c1_denda),11);
				$stringCetak .= printRight(number_format($c1_total),12).chr(10);
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="prn_cell">Total <?=$periode?> : </td>
	 	<td class="right prn_cell"><?php echo number_format($c1_lembar); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_pakai);	 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_uangair); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_beban); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_denda); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c1_total); 	?></td>
	</tr>
<?php
			}
			if(count($b0_pakai)>0){
				$c0_lembar		= count($l0_pakai) - count($b0_pakai);
				$c0_pakai		= array_sum($l0_pakai) - array_sum($b0_pakai);
				$c0_uangair		= array_sum($l0_uangair) - array_sum($b0_uangair);
				$c0_beban		= array_sum($l0_beban) - array_sum($b0_beban);
				$c0_denda		= array_sum($l0_denda) - array_sum($b0_denda);
				$c0_materai		= array_sum($l0_materai) - array_sum($b0_materai);
				$c0_total		= array_sum($l0_total) - array_sum($b0_total);
			}
			else{
				$c0_lembar		= count($l0_pakai);
				$c0_pakai		= array_sum($l0_pakai);
				$c0_uangair		= array_sum($l0_uangair);
				$c0_beban		= array_sum($l0_beban);
				$c0_denda		= array_sum($l0_denda);
				$c0_materai		= array_sum($l0_materai);
				$c0_total		= array_sum($l0_total);
			}
			$stringCetak .= printLeft("Grand Total",20).":";
			$stringCetak .= printRight(number_format($c0_lembar),5);
			$stringCetak .= printRight(number_format($c0_pakai),7);
			$stringCetak .= printRight(number_format($c0_uangair+$c0_beban),12);
			$stringCetak .= printRight(number_format($c0_denda),11);
			$stringCetak .= printRight(number_format($c0_total),12).chr(10);
?>
    <tr class="table_cont_btm">
    	<td colspan="5" class="prn_cell">Grand Total :</td>
	 	<td class="right prn_cell"><?php echo number_format($c0_lembar);	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_pakai);	 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_uangair); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_beban); 	?></td>
	 	<td class="right prn_cell"><?php echo number_format($c0_denda); 	?></td>
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