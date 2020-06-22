<?php
	if($erno) die();
	$formId = getToken();
	$kp_kode	= $_SESSION['Kota_c'];

	$que0 	= "SELECT a.pel_no,a.pel_nama,a.rek_gol AS gol_kode,a.dkd_kd,a.rek_stanlalu,a.rek_stankini,(a.rek_stankini-a.rek_stanlalu) AS rek_pakai,a.rek_uangair,(a.rek_meter+a.rek_adm) AS rek_beban,a.rek_angsuran,a.rek_total,CONCAT('JALAN : [',b.dkd_kd,'] ',b.dkd_jalan) AS rayon FROM tm_rekening a JOIN tr_dkd b ON(b.dkd_kd=a.dkd_kd) JOIN tm_pelanggan d ON(d.pel_no=a.pel_no) WHERE a.rek_sts=1 AND a.rek_byr_sts=0 AND a.rek_bln=$rek_bln AND a.rek_thn=$rek_thn AND d.kp_kode='".$kp_kode."' AND a.dkd_kd='".$dkd_kd."' ORDER BY a.dkd_kd,a.pel_no";
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
<table width="100%" class="prn_table">
	<tr>
		<td colspan="4" class="center"><h3>Cetak DSR</h3></td>
	</tr>
	<tr>
		<td width="15%">Cabang/Unit</td>
		<td width="35%">: <?php echo $_SESSION['kp_ket']; ?></td>
		<td width="15%">&nbsp;</td>
		<td width="35%">&nbsp;</td>
	</tr>
	<tr>
		<td>Periode</td>
		<td>: <?php echo $bulan[$rek_bln]." ".$rek_thn;	?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<hr/>
<table width="100%" class="prn_table">
	<tr class="table_cont_btm">
		<td class="prn_sel">No </td>
		<td class="prn_sel">No SR </td>
		<td class="prn_sel">Nama </td>
		<td class="prn_sel">Gol </td>
		<td class="prn_sel">Lalu </td>
		<td class="prn_sel">Kini </td>
		<td class="prn_sel">Harga Air </td>
		<td class="prn_sel">Pemeliharaan </td>
		<td class="prn_sel">Total </td>
	</tr>
<?php
		if(!isset($data)){
			$data	= array();
		}
		for($i=0;$i<count($data);$i++){
			$row0 	  = $data[$i];
			$nomer	  = ($i+1)+(($pg-1)*$jml_perpage);
			$klas 	  = "table_cell1";
			if(($i%2) == 0){
				$klas = "table_cell2";
			}
?>
	<tr class="<?php echo $klas; ?>">
		<td class="right prn_right prn_sel"><?php echo $nomer; ?>.</td>
		<td class="prn_sel"><?php echo $row0['pel_no'];  ?></td>
		<td class="prn_sel"><?php echo $row0['pel_nama'];  ?></td>
		<td class="prn_sel"><?php echo $row0['gol_kode']; ?></td>
		<td class="prn_sel prn_right right"><?php echo number_format($row0['rek_stanlalu']); ?></td>
		<td class="prn_sel prn_right right"><?php echo number_format($row0['rek_stankini']); ?></td>
		<td class="prn_sel prn_right right"><?php echo number_format($row0['rek_uangair']); ?></td>
		<td class="prn_sel prn_right right"><?php echo number_format($row0['rek_beban']); ?></td>
		<td class="prn_sel prn_right right"><?php echo number_format($row0['rek_total']); ?></td>
	</tr>
<?php
			}
?>
</table>
</div>
</div>