<?php
	if($erno) die();
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
		
		$que0 	= "SELECT *FROM v_dsr WHERE pel_no='$pel_no' ORDER BY rek_thn ASC,rek_bln ASC";
		try{
			if(!$res0 = mysql_query($que0,$link)){
				throw new Exception($que0);
			}
			else{
				while($row0 = mysql_fetch_array($res0)){
					$data[] = $row0;
				}
				$mess = false;
			}
		}
		catch (Exception $e){
			errorLog::errorDB(array($que0));
			$mess = $e->getMessage();
		}	
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<div class="pesan pull-4 span-22 prepend-top">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<input type="hidden" id="keyProses0" 	value="2"/>
<input type="hidden" id="tutup" 		value="<?php echo $formId; ?>"/>
<input type="hidden" class="cetak" name="targetUrl" value="cetak_info_pelanggan.php"/>
<input type="hidden" class="cetak" name="targetId" 	value="targetId"/>
<input type="hidden" class="cetak" name="proses" 	value="cetak"/>
<div id="targetId"></div>
<?php
		// line untuk ff continous paper
		$stringCetak  = chr(27).chr(67).chr(3);
		// enable paper out sensor
		$stringCetak .= chr(27).chr(57);
		// set 12 cpi
		$stringCetak .= chr(27).chr(77);
		// draft mode
		$stringCetak .= chr(27).chr(120).chr(48);
		// line spacing x/72
		$stringCetak .= chr(27).chr(65).chr(12);
		$stringCetak .= "DAFTAR TAGIHAN REKENING AIR".chr(10);
		$stringCetak .= printLeft("NO. SAMBUNGAN",15).": ".$pel_no.chr(10);
		$stringCetak .= printLeft("NAMA",13).": ".printLeft(substr($pel_nama,0,40),40).printLeft("GOLONGAN",13).": ".$gol_kode.chr(10);
		$stringCetak .= printLeft("ALAMAT",13).": ".printLeft(substr($pel_alamat,0,40),40).printLeft("RAYON",13).": ".$dkd_kd.chr(10);
		$stringCetak .= printCenter("Tagihan",10).printCenter("Stand Meter",24).printCenter("Rincian Biaya",36).printRight("Total",9).chr(10);
		$stringCetak .= str_repeat(" ",10).printRight("Lalu",6).printRight("Kini",6).printRight("Pakai",6).printRight("Uang Air",12).printRight("Beban",7).printRight("Angsuran",9).printRight("Denda",11).str_repeat(" ",12).chr(10);
?>
<table width="100%" class="prn_table">
	<tr>
		<td colspan="4" class="center"><h3>Info Pelanggan</h3></td>
	</tr>
	<tr>
		<td width="20%">No. Pelanggan</td>
		<td width="40%">: <?php echo $pel_no; ?></td>
		<td width="15%">&nbsp;</td>
		<td width="25%">&nbsp;</td>
	</tr>
	<tr>
		<td>Nama</td>
		<td>: <?php echo $pel_nama; 	?></td>
		<td>Golongan</td>
		<td>: <?php echo $gol_kode; 	?></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td>: <?php echo $pel_alamat; 	?></td>
		<td>Rayon</td>
		<td>: <?php echo $dkd_kd; 		?></td>
	</tr>
	<tr>
		<td>Status</td>
		<td>: <?php echo $kps_ket;		?></td>
		<td>Lembar</td>
		<td>: <?php echo $rek_lembar;	?></td>
	</tr>
</table>
<hr/>
<table width="100%" class="prn_table">
	<tr class="table_cont_btm">
		<td colspan="2" class="prn_cell">Bulan / Tahun </td>
		<td colspan="3" class="center prn_cell">Stand Meter </td>
		<td colspan="4" class="center prn_cell">Rincian Biaya </td>
		<td class="center prn_cell">Total</td>
	</tr>
	<tr class="table_cont_btm center">
		<td colspan="2" class="prn_cell"></td>
		<td class="center prn_cell">Lalu</td>
		<td class="center prn_cell">Kini</td>
		<td class="center prn_cell">Pemakaian</td>
		<td class="center prn_cell">Uang Air</td>
		<td class="center prn_cell">Angsuran</td>
		<td class="center prn_cell">Biaya Pemeliharaan</td>
		<td class="center prn_cell">Denda</td>
		<td class="prn_cell">&nbsp;</td>
	</tr>
<?php
		$hal0	= 1;
		$halA	= 40;
		$halB	= 30;
		$halC	= 47;
		$halT	= 1 + ceil((count($data)-$halA)/$halB);
		$baris	= 6;
		if(!isset($data)){
			$data	= array();
		}
		for($i=0;$i<count($data);$i++){
			$row0 	     	= $data[$i];
			$nomer	     	= ($i+1)+(($pg-1)*$jml_perpage);
			$pemakaian   	= $row0['rek_stankini'] - $row0['rek_stanlalu'];
			$beban_tetap 	= $row0['rek_adm'] + $row0['rek_meter'];
			$total       	= $row0['rek_total'] + $row0['rek_denda'];
			$klas 			= "table_cell1";
			if(($i%2) == 0){
				$klas = "table_cell2";
			}
			$total_beban	+=$beban_tetap;
			$total_denda	+=$row0['rek_denda'];
			$total_materai	+=$row0['rek_materai'];
			$grand_total	+=$total;
			$total_uangair	+=$row0['rek_uangair'];
			$total_angsuran	+=$row0['rek_angsuran'];
			
			$stringCetak	.= printRight($nomer.".",3);
			$stringCetak	.= printRight(substr($row0['rek_nomor'],0,6),7);
			$stringCetak	.= printRight(number_format($row0['rek_stanlalu']),6);
			$stringCetak	.= printRight(number_format($row0['rek_stankini']),6);
			$stringCetak	.= printRight(number_format($pemakaian),6);
			$stringCetak	.= printRight(number_format($row0['rek_uangair']),12);
			$stringCetak	.= printRight(number_format($beban_tetap),7);
			$stringCetak	.= printRight(number_format($row0['rek_angsuran']),9);
			$stringCetak	.= printRight(number_format($row0['rek_denda']),11);
			$stringCetak	.= printRight(number_format($total),12).chr(10);
			$baris++;
			if($baris%$halC==0){
				$stringCetak .= "Halaman ".$hal0." dari ".$halT;
				$stringCetak .= chr(10).chr(10).chr(10).chr(10);
				$baris		  = $baris + 4;
				$hal0++;
			}
?>
	<tr class="<?php echo $klas; ?>">
		<td class="right prn_cell"><?php echo $nomer; ?>.</td>
		<td class="prn_cell"><?php echo $bulan[$row0['rek_bln']]." ".$row0['rek_thn'];  ?></td>
		<td class="right prn_cell"><?php echo number_format($row0['rek_stanlalu']); ?></td>
		<td class="right prn_cell"><?php echo number_format($row0['rek_stankini']); ?></td>
		<td class="right prn_cell"><?php echo number_format($pemakaian); ?></td>
		<td class="right prn_cell"><?php echo number_format($row0['rek_uangair']); ?></td>
		<td class="right prn_cell"><?php echo number_format($row0['rek_angsuran']); ?></td>
		<td class="right prn_cell"><?php echo number_format($beban_tetap); ?></td>
		<td class="right prn_cell"><?php echo number_format($row0['rek_denda']); ?></td>
		<td class="right prn_cell"><?php echo number_format($total); ?></td>
	</tr>
<?php
			}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="table_cont_btm prn_cell">Grand Total :</td>
		<td class="right prn_cell"><?php echo number_format($total_uangair); ?></td>
		<td class="right prn_cell"><?php echo number_format($total_angsuran); ?></td>
		<td class="right prn_cell"><?php echo number_format($total_beban); ?></td>
		<td class="right prn_cell"><?php echo number_format($total_denda); ?></td>
		<td class="right prn_cell"><?php echo number_format($grand_total); ?></td>
	</tr>
</table>
<?php
		$stringCetak .= str_repeat(" ",24).printRight(number_format($total_uangair),14).printRight(number_format($total_beban),9).printRight(number_format($total_angsuran),9).printRight(number_format($total_denda),11).printRight(number_format($grand_total),12);
		if($total_materai>0){
			$stringCetak .= chr(10).chr(10)."Catatan : Total tagihan belum termasuk total biaya materai sebesar : ".number_format($total_materai).chr(12);
		}
		else{
			$stringCetak .= chr(12);
		}
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