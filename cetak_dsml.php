<?php
	if($erno) die();
	$formId = getToken();

	$que0 	= "SELECT a.* FROM v_dsml a WHERE a.dkd_kd='$dkd_kd' ORDER BY a.dkd_no,a.pel_no";
	try{
		if(!$res0 = $link->query($que0)){
			throw new Exception($que0);
		}
		else{
			while($row0 = $res0->fetch_array()){
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
		<td colspan="4" class="center"><h3>Cetak DSML</h3></td>
	</tr>
	<tr>
		<td width="15%">Cabang/Unit</td>
		<td width="35%">: <?php echo $_SESSION['kp_ket']; ?></td>
		<td width="15%">&nbsp;</td>
		<td width="35%">&nbsp;</td>
	</tr>
	<tr>
		<td>Pencacat</td>
		<td>: <?php echo $dkd_pembaca;	?></td>
		<td>Tanggal Catat</td>
		<td>: <?php echo $dkd_tcatat; 	?></td>
	</tr>
	<tr>
		<td>Rayon</td>
		<td>: <?php echo $dkd_kd;	 	?></td>
		<td>Jalan</td>
		<td>: <?php echo $dkd_jalan;	?></td>
	</tr>
</table>
<hr/>
<table width="100%" class="prn_table">
	<tr class="table_cont_btm">
		<td class="prn_sel">No </td>
		<td class="prn_sel">Nama </td>
		<td class="prn_sel">Alamat </td>
		<td class="prn_sel">Gol.</td>
		<td class="prn_sel">No SR</td>
		<td class="prn_sel">Kini</td>
		<td class="prn_sel">Keterangan</td>
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
		<td class="prn_sel"><?php echo $row0['pel_nama'];  ?></td>
		<td class="prn_sel"><?php echo $row0['pel_alamat']; ?></td>
		<td class="prn_sel"><?php echo $row0['gol_kode']; ?></td>
		<td class="prn_sel"><?php echo $row0['pel_no']; ?></td>
		<td class="prn_sel"></td>
		<td class="prn_sel"></td>
	</tr>
<?php
			}
?>
</table>
</div>
</div>