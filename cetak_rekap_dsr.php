<?php
	if($erno) die();
	$formId 	= getToken();
	
	/* inquiry data drd */
	$kopel	= explode("_",$kopel);
	if($kopel[0]=='00'){
		$filter = "";
	}
	else{
		$filter = "AND a.kp_kode='".$kopel[0]."'";
	}
	switch($gol_kode){
		case "KW":
			$que0	= "SELECT CONCAT(b.dkd_kd,' [',b.dkd_jalan,']') AS rek_gol,COUNT(a.rek_nomor) AS drd_lembar,SUM(a.drd_pakai) AS drd_pakai,SUM(a.drd_total) AS drd_total,SUM(a.lpp_lembar) AS lpp_lembar,SUM(a.lpp_pakai) AS lpp_pakai,SUM(a.lpp_pakai) AS lpp_pakai,SUM(a.lpp_total) AS lpp_total FROM v_rekap_dsr a JOIN tr_dkd b ON(b.dkd_kd=a.dkd_kd) WHERE a.rek_bln=$rek_bln AND a.rek_thn=$rek_thn $filter GROUP BY a.dkd_kd";
			$kate	= "Wilayah";
			break;
		default :
			$que0	= "SELECT CONCAT(b.gol_kode,' [',b.gol_ket,']') AS rek_gol,COUNT(a.rek_nomor) AS drd_lembar,SUM(a.drd_pakai) AS drd_pakai,SUM(a.drd_total) AS drd_total,SUM(a.lpp_lembar) AS lpp_lembar,SUM(a.lpp_pakai) AS lpp_pakai,SUM(a.lpp_pakai) AS lpp_pakai,SUM(a.lpp_total) AS lpp_total FROM v_rekap_dsr a JOIN tr_gol b ON(b.gol_kode=a.rek_gol) WHERE a.rek_bln=$rek_bln AND a.rek_thn=$rek_thn $filter GROUP BY a.rek_gol";
			$kate	= "Golongan";
	}
	
	try{
		if(!$res0 = $link->query($que0)){
			throw new Exception($que0);
		}
		else{
			$i = 0;
			while($row0 = $res0->fetch_array()){
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
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<div class="pesan form-5">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<h3><?=$appl_owner?> - <?=$kopel[1]?></h3>
<hr/>
<h4><?=_NAME?> (Data Saldo Rekening) Per <?=$kate?></h4>
<table width="100%" class="prn_table">
	<tr>
		<td>Tanggal Cetak</td>
		<td colspan="7">: <?=$tanggal?></td>
	</tr>
	<tr>
		<td>Bulan - Tahun</td>
		<td colspan="7">: <?=$rek_bln?> - <?=$rek_thn?></td>
	</tr>
	<tr>
		<td>Petugas</td>
		<td colspan="7">: <?=_NAMA?></td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_head" rowspan="2"><?php echo $kate; ?></td>
		<td class="center prn_head" colspan="3">DRD</td>
		<td class="center prn_head" colspan="3">TERJUAL</td>
		<td class="center prn_head" colspan="3">SISA</td>
    </tr>
	<tr class="table_cont_btm">
		<td class="center prn_head">Pelanggan</td>
		<td class="center prn_head">M3</td>
		<td class="center prn_head">Rupiah</td>
		<td class="center prn_head">Pelanggan</td>
		<td class="center prn_head">M3</td>
		<td class="center prn_head">Rupiah</td>
		<td class="center prn_head">Pelanggan</td>
		<td class="center prn_head">M3</td>
		<td class="center prn_head">Rupiah</td>
    </tr>
<?php
	if(!isset($data)){
		$data	= array();
	}
	$total_drd_lembar	= array();
	$total_drd_pakai	= array();
	$total_drd_total	= array();
	$total_lpp_lembar	= array();
	$total_lpp_pakai	= array();
	$total_lpp_total	= array();
	$total_dsr_lembar	= array();
	$total_dsr_pakai	= array();
	$total_dsr_total	= array();
	for($i=0;$i<count($data);$i++){
		$row0 	  = $data[$i];
		$klas 	  = "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$total_drd_lembar[$i]	+= $row0['drd_lembar'];
		$total_drd_pakai[$i]	+= $row0['drd_pakai'];
		$total_drd_total[$i]	+= $row0['drd_total'];
		$total_lpp_lembar[$i]	+= $row0['lpp_lembar'];
		$total_lpp_pakai[$i]	+= $row0['lpp_pakai'];
		$total_lpp_total[$i]	+= $row0['lpp_total'];
		$total_dsr_lembar[$i]	+= $row0['drd_lembar'] - $row0['lpp_lembar'];
		$total_dsr_pakai[$i]	+= $row0['drd_pakai'] - $row0['lpp_pakai'];
		$total_dsr_total[$i]	+= $row0['drd_total'] - $row0['lpp_total'];
?>
  <tr class="<?php echo $klas; ?>">
	<td class="prn_cell prn_left"><?php echo $row0['rek_gol']; ?></td>
    <td class="right prn_cell"><?php echo number_format($row0['drd_lembar']);	?></td>
    <td class="right prn_cell"><?php echo number_format($row0['drd_pakai']); 	?></td>
    <td class="right prn_cell"><?php echo number_format($row0['drd_total']); 	?></td>
    <td class="right prn_cell"><?php echo number_format($row0['lpp_lembar']);	?></td>
    <td class="right prn_cell"><?php echo number_format($row0['lpp_pakai']); 	?></td>
    <td class="right prn_cell"><?php echo number_format($row0['lpp_total']); 	?></td>
    <td class="right prn_cell"><?php echo number_format($total_dsr_lembar[$i]);	?></td>
    <td class="right prn_cell"><?php echo number_format($total_dsr_pakai[$i]); 	?></td>
    <td class="right prn_cell"><?php echo number_format($total_dsr_total[$i]); 	?></td>
  </tr>

 <?php
   		}
  ?>
    <tr class="table_cont_btm">
    	<td class="left prn_total">Grand Total :</td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($total_drd_lembar)); ?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($total_drd_pakai)); 	?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($total_drd_total)); 	?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($total_lpp_lembar)); ?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($total_lpp_pakai)); 	?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($total_lpp_total)); 	?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($total_dsr_lembar)); ?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($total_dsr_pakai)); 	?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($total_dsr_total)); 	?></td>
  </tr>
</table>
</div>
</div>
