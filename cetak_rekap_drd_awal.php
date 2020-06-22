<?php
	if($erno) die();
	$formId 	= getToken();
	
	/* inquiry data drd */
	$kopel	= explode("_",$kopel);
	if($kopel[0]=='00'){
		$filter = "";
	}
	else{
		$filter = "AND c.kp_kode='".$kopel[0]."'";
	}
	switch($gol_kode){
		case "KW":
			$que0 	= "SELECT CONCAT(b.dkd_kd,' [',b.dkd_jalan,']') AS rek_gol,COUNT(a.rek_nomor) AS rek_lembar,SUM(a.rek_stankini-a.rek_stanlalu) AS rek_pakai,SUM(a.rek_uangair) AS rek_uangair,SUM(a.rek_meter+a.rek_adm) AS rek_meter,SUM(a.rek_total) AS rek_total FROM tm_drd_awal a JOIN tr_dkd b ON(b.dkd_kd=a.dkd_kd) JOIN tm_pelanggan c ON(c.pel_no=a.pel_no) WHERE a.rek_bln=$rek_bln AND a.rek_thn=$rek_thn $filter GROUP BY a.dkd_kd"; 
			$kate	= "Wilayah";
			break;
		default :
			$que0 	= "SELECT CONCAT(b.gol_kode,' [',b.gol_ket,']') AS rek_gol,COUNT(a.rek_nomor) AS rek_lembar,SUM(a.rek_stankini-a.rek_stanlalu) AS rek_pakai,SUM(a.rek_uangair) AS rek_uangair,SUM(a.rek_meter+a.rek_adm) AS rek_meter,SUM(a.rek_total) AS rek_total FROM tm_drd_awal a JOIN tr_gol b ON(b.gol_kode=a.rek_gol) JOIN tm_pelanggan c ON(c.pel_no=a.pel_no) WHERE a.rek_bln=$rek_bln AND a.rek_thn=$rek_thn $filter GROUP BY a.rek_gol"; 
			$kate	= "Golongan";
	}
	
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception($que0);
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
<h4><?=_NAME?> (Data Rekening Ditagih) Per <?=$kate?></h4>
<table width="100%" class="prn_table">
	<tr>
		<td>Tanggal Cetak</td>
		<td colspan="5">: <?=$tanggal?></td>
	</tr>
	<tr>
		<td>Bulan - Tahun</td>
		<td colspan="5">: <?=$rek_bln?> - <?=$rek_thn?></td>
	</tr>
	<tr>
		<td>Petugas</td>
		<td colspan="5">: <?=_NAMA?></td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_head"><?php echo $kate; ?></td>
		<td class="center prn_head" align="right">Rekening<br/>(Lembar)</td>
		<td class="center prn_head">Kubikasi<br/>(m3)</td>
		<td class="center prn_head">Uang Air<br/>(Rupiah)</td>
		<td class="center prn_head">Pemeliharaan<br/>(Rupiah)</td>
		<td class="center prn_head">Total<br/>(Rupiah)</td>
    </tr>
<?php
	for($i=0;$i<count($data);$i++){
		$row0 	  = $data[$i];
		$klas 	  = "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$total_lembar	+= $row0['rek_lembar'];
		$total_pakai	+= $row0['rek_pakai'];
		$total_uangair	+= $row0['rek_uangair'];
		$total_meter	+= $row0['rek_meter'];
		$total_total	+= $row0['rek_total'];
?>
  <tr class="<?php echo $klas; ?>">
	<td class="prn_cell prn_left"><?php echo $row0['rek_gol']; ?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rek_lembar']); ?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rek_pakai']); ?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rek_uangair']); ?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rek_meter']); ?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rek_total']); ?></td>
  </tr>

 <?php
   		}
  ?>
    <tr class="table_cont_btm">
    	<td class="left prn_total">Grand Total :</td>
	 	<td class="right prn_total"><?php echo number_format($total_lembar); ?></td>
		<td class="right prn_total"><?php echo number_format($total_pakai); ?></td>
	 	<td class="right prn_total"><?php echo number_format($total_uangair); ?></td>
   		<td class="right prn_total"><?php echo number_format($total_meter); ?></td>
   		<td class="right prn_total"><?php echo number_format($total_total); ?></td>
  </tr>
</table>
</div>
</div>