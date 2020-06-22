<?php
	if($erno) die();
	$formId = getToken();
	$kp_kode	= $_SESSION['Kota_c'];
	$kp_ket		= $_SESSION['kp_ket'];
	$erno		= false;

	$que0 	= "SELECT a.* FROM v_dsr a JOIN tm_kolektif b ON(b.pel_no=a.pel_no) WHERE b.kel_kode='".$kel_kode."' ORDER BY a.pel_no,a.rek_thn ASC,a.rek_bln ASC";
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
		<td colspan="4" class="center"><h3>Cetak Kelompok Kolektif</h3></td>
	</tr>
	<tr>
		<td width="15%">Cabang/Unit</td>
		<td width="35%">: <?php echo $_SESSION['kp_ket']; ?></td>
		<td width="15%">Tanggal</td>
		<td width="35%">: <?php echo date('d/m/Y'); ?></td>
	</tr>
	<tr>
		<td>Kolektor</td>
		<td>: <?php echo $kolektor;	?></td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<hr/>
<table width="100%" class="prn_table">
	<tr class="table_cont_btm">
		<td class="prn_sel">No </td>
		<td class="prn_sel">No SL </td>
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
		<td class="prn_sel prn_right right"><?php echo number_format($row0['beban_tetap']); ?></td>
		<td class="prn_sel prn_right right"><?php echo number_format($row0['rek_total']); ?></td>
	</tr>
<?php
			}
?>
</table>
</div>
</div>