<?php
	if($erno) die();
	$formId 	= getToken();
	
	/* inquiry data drd */
	$kopel	= explode("_",$kopel);
	if($gol_kode=='1'){
		$gol_ket	= "BP";
		$que0 		= "SELECT a.*,SUM(a.dibayar) AS total_dibayar FROM v_angsuran a WHERE a.bulan=$rek_bln AND a.tahun=$rek_thn AND a.kp_kode='".$kopel[0]."' AND a.ba_ket='BP' GROUP BY a.pel_no,a.tahun,a.bulan ORDER BY a.tanggal";
	}
	else{
		$gol_ket	= "PKPT";
		$que0 		= "SELECT a.*,SUM(a.dibayar) AS total_dibayar FROM v_angsuran a WHERE a.bulan=$rek_bln AND a.tahun=$rek_thn AND a.kp_kode='".$kopel[0]."' AND a.ba_ket='PKPT' GROUP BY a.pel_no,a.tahun,a.bulan ORDER BY a.tanggal";
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
<h4><?php echo _NAME; ?> - <?php echo $gol_ket; ?></h4>
<table width="100%" class="prn_table">
	<tr>
		<td colspan="2">Tanggal Cetak</td>
		<td colspan="9">: <?=$tanggal?></td>
	</tr>
	<tr>
		<td colspan="2">Bulan - Tahun</td>
		<td colspan="9">: <?=$rek_bln?> - <?=$rek_thn?></td>
	</tr>
	<tr>
		<td colspan="2">Petugas</td>
		<td colspan="9">: <?=_NAMA?></td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_head">No.</td>
		<td class="center prn_head">Tgl.</td>
		<td class="center prn_head">SL</td>
		<td class="center prn_head">Nama</td>
		<td class="center prn_head">Alamat</td>
		<td class="center prn_head">Gol.</td>
		<td class="center prn_head">DKD</td>
		<td class="center prn_head">Status</td>
		<td class="center prn_head">Uang Muka</td>
		<td class="center prn_head">Total Angsuran</td>
		<td class="center prn_head">Total Dibayar</td>
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
		$total_uang_muka[]	= $row0['uang_muka'];
		$total_angsuran[]	= $row0['total_angsuran'];
		$total_dibayar[]	= $row0['total_dibayar'];
?>
  <tr class="<?php echo $klas; ?>">
    <td class="right prn_cell">			<?php echo number_format($nomor); 					?></td>
	<td class="right prn_cell">			<?php echo $row0['tanggal']; 						?></td>
	<td class="right prn_cell">			<?php echo $row0['pel_no']; 						?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['pel_nama']; 						?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['pel_alamat']; 					?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['gol_kode']; 						?></td>
	<td class="center prn_cell">		<?php echo $row0['dkd_kd']; 						?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['kps_ket']; 						?></td>
	<td class="center prn_cell">		<?php echo number_format($row0['uang_muka']); 		?></td>
	<td class="center prn_cell">		<?php echo number_format($row0['total_angsuran']);	?></td>
	<td class="center prn_cell">		<?php echo number_format($row0['total_dibayar']);	?></td>
  </tr>

<?php
   		}
		if($i>0){
?>
    <tr class="table_cont_btm">
		<td colspan="8">&nbsp;</td>
		<td class="center prn_cell">		<?php echo number_format(array_sum($total_uang_muka)); 		?></td>
		<td class="center prn_cell">		<?php echo number_format(array_sum($total_angsuran)); 		?></td>
		<td class="center prn_cell">		<?php echo number_format(array_sum($total_dibayar)); 		?></td>
	</tr>
<?php
		}
?>
</table>
</div>
</div>