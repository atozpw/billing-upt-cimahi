<?php
	if($erno) die();
	$formId 	= getToken();
	
	/* inquiry data drd */
	$kopel	= _KOTA."_".$_SESSION['kp_ket'];
	$kopel	= explode("_",$kopel);
	if($kopel[0]=='00'){
		$filter = "";
	}
	else{
		$filter = "AND a.kp_kode='".$kopel[0]."'";
	}
	switch($gol_kode){
		case 1:
			$que0 	= "SELECT a.*,CONCAT(a.pel_no,' [',a.ref_no,']') AS nopel,b.kar_nama FROM v_dsml a JOIN tm_karyawan b ON(b.kar_id=a.kar_id) WHERE a.pakai_kini>50 AND a.sm_sts=1 $filter ORDER BY ABS(a.pakai_kini) DESC";
			$title 	= "Cetak Abnormal Atas";
			break;
		case 2:
			$que0 	= "SELECT a.*,CONCAT(a.pel_no,' [',a.ref_no,']') AS nopel,b.kar_nama FROM v_dsml a JOIN tm_karyawan b ON(b.kar_id=a.kar_id) WHERE a.pakai_kini<11 AND a.pakai_kini>0 AND a.sm_sts=1 $filter ORDER BY ABS(a.pakai_kini) DESC";
			$title 	= "Cetak Abnormal Bawah";
			break;
		case 3:
			$que0 	= "SELECT a.*,CONCAT(a.pel_no,' [',a.ref_no,']') AS nopel,b.kar_nama FROM v_dsml a JOIN tm_karyawan b ON(b.kar_id=a.kar_id) WHERE a.pakai_kini<0 AND a.sm_sts=1 $filter ORDER BY ABS(a.pakai_kini) DESC";
			$title 	= "Cetak Abnormal Negatif";
			break;
		case 4:
			$que0 	= "SELECT a.*,CONCAT(a.pel_no,' [',a.ref_no,']') AS nopel,b.kar_nama FROM v_dsml a JOIN tm_karyawan b ON(b.kar_id=a.kar_id) WHERE a.pakai_kini=0 AND a.sm_sts=1 $filter ORDER BY ABS(a.pakai_kini) DESC";
			$title 	= "Cetak Pemakaian Nol";
			break;
		case 5:
			$que0 	= "SELECT a.*,CONCAT(a.pel_no,' [',a.ref_no,']') AS nopel,b.kar_nama FROM v_dsml a JOIN tm_karyawan b ON(b.kar_id=a.kar_id) WHERE a.sm_sts=2 $filter ORDER BY a.dkd_kd";
			$title 	= "Cetak Belum Diisi";
			break;
	}
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
<div class="pesan form-5">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<h3><?php echo $appl_owner; ?> - <?php echo $kopel[1]; ?></h3>
<hr/>
<h4><?php echo $title; ?></h4>
<table width="100%" class="prn_table">
	<tr>
		<td colspan="2">Tanggal Cetak</td>
		<td colspan="7">: <?php echo $tanggal; ?></td>
	</tr>
	<tr>
		<td colspan="2">Bulan - Tahun</td>
		<td colspan="7">: <?php echo $rek_bln; ?> - <?php echo $rek_thn; ?></td>
	</tr>
	<tr>
		<td colspan="2">Petugas</td>
		<td colspan="7">: <?php echo _NAMA; ?></td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center prn_head">No.</td>
		<td class="center prn_head">Tgl.</td>
		<td class="center prn_head">No Pel</td>
		<td class="center prn_head">Nama</td>
		<td class="center prn_head">Alamat</td>
		<td class="center prn_head">Gol.</td>
		<td class="center prn_head">Rayon</td>
		<td class="center prn_head">Pakai</td>
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
	<td class="right prn_cell">			<?php echo $row0['nopel'];	 		?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['pel_nama']; 		?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['pel_alamat']; 	?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['gol_kode']; 		?></td>
	<td class="center prn_cell">		<?php echo $row0['dkd_kd']; 		?></td>
	<td class="left prn_cell prn_left">	<?php echo $row0['pakai_kini']; 	?></td>
	<td class="prn_cell prn_left">		<?php echo $row0['kar_nama']; 		?></td>
  </tr>

<?php
	}
	if($i>0){
?>
    <tr class="table_cont_btm"><td colspan="9">&nbsp;</td></tr>
<?php
	}
?>
</table>
</div>
</div>