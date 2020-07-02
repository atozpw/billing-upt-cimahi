<?php
	if($erno) die();
	$formId 	= getToken();
	
	/* inquiry data drd */
	$kopel	= explode("_",$kopel);
	if($gol_kode=='SL'){
		$que0 	= "SELECT a.* FROM v_pemasangan a WHERE a.bulan=$rek_bln AND a.tahun=$rek_thn AND a.kp_kode='".$kopel[0]."' ORDER BY a.pel_no";
	}
	else if($gol_kode=='KW'){
		$que0 	= "SELECT a.* FROM v_pemasangan a WHERE a.bulan=$rek_bln AND a.tahun=$rek_thn AND a.kp_kode='".$kopel[0]."' ORDER BY a.dkd_kd";
	}
	else{
		$que0 	= "SELECT a.* FROM v_pemasangan a WHERE a.bulan=$rek_bln AND a.tahun=$rek_thn AND a.kp_kode='".$kopel[0]."' ORDER BY a.gol_kode";
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
<h3><?php echo $appl_owner; ?> - <?php echo $kopel[1]; ?></h3>
<hr/>
<h4><?php echo _NAME; ?></h4>
<table width="100%" class="prn_table">
	<tr>
		<td colspan="2">Tanggal Cetak</td>
		<td colspan="8">: <?php echo $tanggal; ?></td>
	</tr>
	<tr>
		<td colspan="2">Bulan - Tahun</td>
		<td colspan="8">: <?php echo $rek_bln; ?> - <?php echo $rek_thn; ?></td>
	</tr>
	<tr>
		<td colspan="2">Petugas</td>
		<td colspan="8">: <?php echo _NAMA; ?></td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_head">No.</td>
		<td class="center prn_head">Tgl.</td>
		<td class="center prn_head">SR</td>
		<td class="center prn_head">Nama</td>
		<td class="center prn_head">Alamat</td>
		<td class="center prn_head">Gol.</td>
		<td class="center prn_head">DKD</td>
		<td class="center prn_head">Status</td>
		<td class="center prn_head">Remark</td>
		<td class="center prn_head">Pelaksana</td>
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
?>
  <tr class="<?php echo $klas; ?>">
    <td class="right prn_cell">			<?php echo number_format($nomor); 	?></td>
	<td class="right prn_cell">			<?php echo $row0['tanggal']; 		?></td>
	<td class="right prn_cell">			<?php echo $row0['pel_no']; 		?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['pel_nama']; 		?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['pel_alamat']; 	?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['gol_kode']; 		?></td>
	<td class="center prn_cell">		<?php echo $row0['dkd_kd']; 		?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['kps_ket']; 		?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['remark']; 		?></td>
	<td class="prn_cell prn_left">		<?php echo $row0['kar_nama']; 		?></td>
  </tr>

<?php
   		}
		if($i>0){
?>
    <tr class="table_cont_btm"><td colspan="10">&nbsp;</td></tr>
<?php
		}
?>
</table>
</div>
</div>
