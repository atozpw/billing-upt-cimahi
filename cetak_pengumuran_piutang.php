<?php
	if($erno) die();
	$formId 	= getToken();
	
	if($gol_kode==2){
		$status 	= "Aktif";
		$kondisi	= " AND kps_kode<6";
	}
	else if($gol_kode==3){
		$status 	= "Non-Aktif";
		$kondisi	= " AND kps_kode>5";
	}
	else{
		$status 	= "-";
		$kondisi	= "";
	}
	
	/* inquiry data drd */
	$kopel	= explode("_",$kopel);
	try{
        $que0	= "CREATE TEMPORARY TABLE tmp_$formId SELECT *FROM v_pengumuran_piutang WHERE kp_kode='".$kopel[0]."' ".$kondisi;
		if(!mysql_query($que0,$link)){
			throw new Exception(mysql_error($link));
		}
        	$que0 	= "SELECT rek_gol,SUM(pel_jumlahA) AS pel_jumlahA,SUM(rek_lembarA) AS rek_lembarA,SUM(rek_pakaiA) AS rek_pakaiA,SUM(rek_totalA) AS rek_totalA,SUM(pel_jumlahB) AS pel_jumlahB,SUM(rek_lembarB) AS rek_lembarB,SUM(rek_pakaiB) AS rek_pakaiB,SUM(rek_totalB) AS rek_totalB,SUM(pel_jumlahC) AS pel_jumlahC,SUM(rek_lembarC) AS rek_lembarC,SUM(rek_pakaiC) AS rek_pakaiC,SUM(rek_totalC) AS rek_totalC,SUM(pel_jumlahD) AS pel_jumlahD,SUM(rek_lembarD) AS rek_lembarD,SUM(rek_pakaiD) AS rek_pakaiD,SUM(rek_totalD) AS rek_totalD,SUM(pel_jumlahE) AS pel_jumlahE,SUM(rek_lembarE) AS rek_lembarE,SUM(rek_pakaiE) AS rek_pakaiE,SUM(rek_totalE) AS rek_totalE FROM tmp_$formId GROUP BY rek_gol";
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception(mysql_error($link));
		}
		else{
			$i = 0;
			while($row0 = mysql_fetch_array($res0)){
				$data[] = $row0;
				$i++;	
			}
		$mess = false;
		}
	}
	catch (Exception $e){
		$mess = $e->getMessage();
		#errorLog::errorDB(array($mess));
		errorLog::errorDB(array($que0));
	}
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<div class="pesan form-5">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<h3><?=$appl_owner?> - <?=$kopel[1]?></h3>
<hr/>
<h4><?=_NAME?></h4>
<table width="100%" class="prn_table">
	<tr>
		<td colspan="3">Tanggal Cetak</td>
		<td colspan="18">: <?=$tanggal?></td>
	</tr>
	<tr>
		<td colspan="3">Petugas</td>
		<td colspan="18">: <?=_NAMA?></td>
	</tr>
	<tr>
		<td colspan="3">Status</td>
		<td colspan="18">: <?=$status?></td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_head" rowspan="3">Segmen</td>
		<td class="center prn_head" colspan="20">Umur</td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_cell prn_center prn_bold" colspan="4">0-3</td>
		<td class="center prn_cell prn_center prn_bold" colspan="4">4-6</td>
		<td class="center prn_cell prn_center prn_bold" colspan="4">7-12</td>
		<td class="center prn_cell prn_center prn_bold" colspan="4">13-24</td>
		<td class="center prn_cell prn_center prn_bold" colspan="4">&#62;24</td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_cell prn_center prn_bold">SR</td>
		<td class="center prn_cell prn_center prn_bold">Lembar</td>
		<td class="center prn_cell prn_center prn_bold">M3</td>
		<td class="center prn_cell prn_center prn_bold">Rupiah</td>
		<td class="center prn_cell prn_center prn_bold">SR</td>
		<td class="center prn_cell prn_center prn_bold">Lembar</td>
		<td class="center prn_cell prn_center prn_bold">M3</td>
		<td class="center prn_cell prn_center prn_bold">Rupiah</td>
		<td class="center prn_cell prn_center prn_bold">SR</td>
		<td class="center prn_cell prn_center prn_bold">Lembar</td>
		<td class="center prn_cell prn_center prn_bold">M3</td>
		<td class="center prn_cell prn_center prn_bold">Rupiah</td>
		<td class="center prn_cell prn_center prn_bold">SR</td>
		<td class="center prn_cell prn_center prn_bold">Lembar</td>
		<td class="center prn_cell prn_center prn_bold">M3</td>
		<td class="center prn_cell prn_center prn_bold">Rupiah</td>
		<td class="center prn_cell prn_center prn_bold">SR</td>
		<td class="center prn_cell prn_center prn_bold">Lembar</td>
		<td class="center prn_cell prn_center prn_bold">M3</td>
		<td class="center prn_cell prn_center prn_bold">Rupiah</td>
    </tr>
<?php
	for($i=0;$i<count($data);$i++){
		$nomor		= $i+1;
		$row0 	  	= $data[$i];
		$klas 	  	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$pelangganA[$i]	= $row0['pel_jumlahA'];
		$pelangganB[$i]	= $row0['pel_jumlahB'];
		$pelangganC[$i]	= $row0['pel_jumlahC'];
		$pelangganD[$i]	= $row0['pel_jumlahD'];
		$pelangganE[$i]	= $row0['pel_jumlahE'];
		$lembarA[$i]	= $row0['rek_lembarA'];
		$lembarB[$i]	= $row0['rek_lembarB'];
		$lembarC[$i]	= $row0['rek_lembarC'];
		$lembarD[$i]	= $row0['rek_lembarD'];
		$lembarE[$i]	= $row0['rek_lembarE'];
		$pakaiA[$i]		= $row0['rek_pakaiA'];
		$pakaiB[$i]		= $row0['rek_pakaiB'];
		$pakaiC[$i]		= $row0['rek_pakaiC'];
		$pakaiD[$i]		= $row0['rek_pakaiD'];
		$pakaiE[$i]		= $row0['rek_pakaiE'];
		$totalA[$i]		= $row0['rek_totalA'];
		$totalB[$i]		= $row0['rek_totalB'];
		$totalC[$i]		= $row0['rek_totalC'];
		$totalD[$i]		= $row0['rek_totalD'];
		$totalE[$i]		= $row0['rek_totalE'];
?>
  <tr class="<?php echo $klas; ?>">
    <td class="right prn_cell"><?php echo $row0['rek_gol'];		?></td>
    <td class="right prn_cell"><?php echo number_format($pelangganA[$i]);	?></td>
    <td class="right prn_cell"><?php echo number_format($lembarA[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pakaiA[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($totalA[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pelangganB[$i]);	?></td>
    <td class="right prn_cell"><?php echo number_format($lembarB[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pakaiB[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($totalB[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pelangganC[$i]);	?></td>
    <td class="right prn_cell"><?php echo number_format($lembarC[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pakaiC[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($totalC[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pelangganD[$i]);	?></td>
    <td class="right prn_cell"><?php echo number_format($lembarD[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pakaiD[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($totalD[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pelangganE[$i]);	?></td>
    <td class="right prn_cell"><?php echo number_format($lembarE[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($pakaiE[$i]);		?></td>
    <td class="right prn_cell"><?php echo number_format($totalE[$i]);		?></td>
  </tr>

<?php
   		}
		if($i>0){
?>
    <tr class="table_cont_btm">
    	<td class="left prn_total">Total :</td>
		<td class="right prn_total"><?php echo number_format(array_sum($pelangganA));	?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($lembarA));		?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($pakaiA)); 		?></td>
   		<td class="right prn_total"><?php echo number_format(array_sum($totalA)); 		?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($pelangganB));	?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($lembarB)); 		?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($pakaiB)); 		?></td>
   		<td class="right prn_total"><?php echo number_format(array_sum($totalB)); 		?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($pelangganC));	?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($lembarC)); 		?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($pakaiC)); 		?></td>
   		<td class="right prn_total"><?php echo number_format(array_sum($totalC)); 		?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($pelangganD));	?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($lembarD)); 		?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($pakaiD)); 		?></td>
   		<td class="right prn_total"><?php echo number_format(array_sum($totalD)); 		?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($pelangganE));	?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($lembarE)); 		?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($pakaiE)); 		?></td>
   		<td class="right prn_total"><?php echo number_format(array_sum($totalE)); 		?></td>
	</tr>
<?php
			$pelanggan = array_sum($pelangganA) + array_sum($pelangganB) + array_sum($pelangganC) + array_sum($pelangganD) + array_sum($pelangganE);
			$lembar = array_sum($lembarA) + array_sum($lembarB) + array_sum($lembarC) + array_sum($lembarD) + array_sum($lembarE);
			$pakai = array_sum($pakaiA) + array_sum($pakaiB) + array_sum($pakaiC) + array_sum($pakaiD) + array_sum($pakaiE);
			$total = array_sum($totalA) + array_sum($totalB) + array_sum($totalC) + array_sum($totalD) + array_sum($totalE);
		}
?>
	<tr><td colspan="21">&nbsp;</td></tr>
	<tr>
		<td colspan="4" class="prn_total prn_left">Total Pelanggan</td>
		<td class="prn_total">:</td>
		<td class="right prn_total"><?php echo number_format($pelanggan); ?></td>
		<td colspan="15"></td>
	</tr>
	<tr>
		<td colspan="4" class="prn_total prn_left">Total Lembar Rekening</td>
		<td class="prn_total">:</td>
		<td class="right prn_total"><?php echo number_format($lembar); ?></td>
		<td colspan="15"></td>
	</tr>
	<tr>
		<td colspan="4" class="prn_total prn_left">Total Pemakaian Air (M3)</td>
		<td class="prn_total">:</td>
		<td class="right prn_total"><?php echo number_format($pakai); ?></td>
		<td colspan="15"></td>
	</tr>
	<tr>
		<td colspan="4" class="prn_total prn_left">Total Nilai Rekening (Rp)</td>
		<td class="prn_total">:</td>
		<td class="right prn_total"><?php echo number_format($total); ?></td>
		<td colspan="15"></td>
	</tr>
</table>
</div>
</div>
