<?php
	if($erno) die();
	$formId 	= getToken();
	
	/* inquiry data drd */
	$kopel	= explode("_",$kopel);
	$que0 	= "SELECT a.dkd_kd,a.pel_no,a.pel_nosl,a.pel_nama,a.pel_alamat,SUM(a.rek_lembar) AS rek_lembar,SUM(a.rek_total) AS rek_total,a.gol_kode,a.kps_ket FROM v_info_pelanggan a WHERE a.rek_lembar>0 AND a.gol_kode='$gol_kode' AND a.kp_kode='".$kopel[0]."' GROUP BY a.pel_no ORDER BY a.kps_ket,SUM(a.rek_lembar) DESC";
	$que1	= "SELECT * FROM v_reff_tarif WHERE gol_kode='$gol_kode'";
	try{
		if(!$res0 = $link->query($que0)){
			throw new Exception($que0);
		}
		else{
			$i = 0;
			while($row0 = $res0->fetch_assoc()){
				$data[] = $row0;
				$i++;	
			}
			$mess = false;
		}

		if(!$res1 = $link->query($que1)){
			throw new Exception($que1);
		}
		else{
			$row1 = $res1->fetch_assoc();			
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
<h4><?=$appl_owner?> - <?=$kopel[1]?></h4>
<hr/>
<h5><?=_NAME?></h5>
<table width="100%" class="prn_table">
	<tr>
		<td colspan="2">Tanggal Cetak</td>
		<td colspan="6">: <?=$tanggal?></td>
	</tr>
	<tr>
		<td colspan="2">Bulan - Tahun</td>
		<td colspan="2">: <?=$rek_bln?> - <?=$rek_thn?></td>
		<td colspan="1">Kelompok</td>
		<td colspan="4">: <?=$row1['kel_ket']?></td>
	<tr>
	<tr>
		<td colspan="2">Petugas</td>
		<td colspan="2">: <?=_NAMA?></td>
		<td colspan="1">Kode Tarif</td>
		<td colspan="4">: <?=$row1['tarif_ket']?></td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_head">No.</td>
		<td class="center prn_head">Kode Rayon</td>
		<td class="center prn_head">Nomor SR</td>
		<td class="center prn_head">Nama</td>
		<td class="center prn_head">Alamat</td>
		<td class="center prn_head">Lembar<br/>(Lembar)</td>
		<td class="center prn_head">Jumlah<br/>(Rupiah)</td>
		<td class="center prn_head">Status</td>
    </tr>
<?php
	if(!isset($data)){
		$data	= array();
	}
	for($i=0;$i<count($data);$i++){
		$nomor		= $i+1;
		$row0 	  	= $data[$i];
		$klas 	  	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$lembar[$row0['kps_ket']][]	= $row0['rek_lembar'];
		$total[$row0['kps_ket']][]	= $row0['rek_total'];
		$grandLembar[]				= $row0['rek_lembar'];
		$grandTotal[]				= $row0['rek_total'];
?>
	<tr class="<?php echo $klas; ?>">
		<td class="right prn_cell"><?php echo number_format($nomor); ?></td>
		<td class="center prn_cell"><?php echo $row0['dkd_kd']; ?></td>
		<td class="right prn_cell"><?php echo $row0['pel_no']; ?></td>
		<td class="left prn_cell prn_left"><?php echo $row0['pel_nama']; ?></td>
		<td class="left prn_cell prn_left"><?php echo $row0['pel_alamat']; ?></td>
		<td class="right prn_cell"><?php echo number_format($row0['rek_lembar']); ?></td>
		<td class="right prn_cell"><?php echo number_format($row0['rek_total']); ?></td>
		<td class="left prn_cell prn_left"><?php echo $row0['kps_ket']; ?></td>
	</tr>

<?php
   		}
		if($i>0){
			$kps_key = array_keys($lembar);
			for($j=0;$j<count($kps_key);$j++){
?>
    <tr class="table_cont_btm">
    	<td colspan="5" class="right prn_total">Total <?php echo $kps_key[$j];?>&nbsp;:</td>
		<td class="right prn_total"><?php echo number_format(array_sum($lembar[$kps_key[$j]])); ?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($total[$kps_key[$j]])); ?></td>
   		<td class="right prn_total">&nbsp;</td>
	</tr>
<?php
			}
?>
    <tr class="table_cont_btm">
    	<td colspan="5" class="right prn_total">Grand Total :</td>
		<td class="right prn_total"><?php echo number_format(array_sum($grandLembar)); ?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($grandTotal)); ?></td>
   		<td class="right prn_total">&nbsp;</td>
	</tr>
<?php
		}
?>
</table>
</div>
</div>