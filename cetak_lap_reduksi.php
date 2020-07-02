<?php
	if($erno) die();
	$formId 	= getToken();
	if(!isset($tgl_awal)){
		$tgl_awal	= 1;
	}
	if(!isset($tgl_akhir)){
		$tgl_akhir	= 1;
	}
	/* koneksi database */
	/* link : link baca */
	$mess 	= "user : ".$DUSER." tidak bisa terhubung ke server : ".$DHOST;
	$link 	= mysqli_connect($DHOST,$DUSER,$DPASS,$DNAME) or die(errorLog::errorDie(array($mess)));
	try{
		if(!$link){
			throw new Exception("user : ".$DUSER." tidak bisa terhubung ke database : ".$DNAME);
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($e->getMessage()));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
		$klas = "error";
		$erno = false;
	}
	
	/* inquiry data drd */
	$kopel		= explode("_",$kopel);
	$que0 		= "SELECT *,DATE_FORMAT(rd_tgl, '%d/%m/%Y') AS tgl_reduksi FROM v_reduksi WHERE YEAR(rd_tgl)=YEAR(NOW()) AND MONTH(rd_tgl)=MONTH(NOW()) AND kp_kode='".$kopel[0]."' ORDER BY rd_tgl,pel_no,rek_thn,rek_bln"; 
	try{
		if(!$res0 = $link->query($que0)){
			throw new Exception($que0);
		}
		else{
			$i = 0;
			$data = array();
			while($row0 = $res0->fetch_array()){
				$data[] = $row0;
				$i++;	
			}
		$mess = false;
		}
	}
	catch (Exception $e){
		$mess = $e->getMessage();
		errorLog::errorDB(array($mess));
		errorLog::logDB(array($que0));
	}
	if(strlen($gol_kode)<1){
		$gol_kode = "Seluruh Golongan";
	}
	$tgl_awal	= $tgl_awal."/".$rek_bln."/".$rek_thn;
	$tgl_akhir	= $tgl_akhir."/".$rek_bln."/".$rek_thn;
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<div class="pesan form-5">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<h3><?php echo $appl_owner; ?> - <?php echo $kopel[1]; ?></h3>
<hr/>
<h4><?php echo _NAME; ?> Rekening</h4>
<table width="100%" class="prn_table">
	<tr>
		<td colspan="2">Tanggal Cetak</td>
		<td colspan="6">: <?php echo $tanggal; ?></td>
	</tr>
	<tr>
		<td colspan="2">Periode</td>
		<td colspan="6">: <?php echo $rek_bln." - ".$rek_thn; ?></td>
	</tr>
	<tr>
		<td colspan="2">Golongan</td>
		<td colspan="6">: <?php echo $gol_kode; ?></td>
	</tr>
	<tr>
		<td colspan="2">Petugas</td>
		<td colspan="6">: <?php echo _NAMA; ?></td>
	</tr>
	<tr class="table_cont_btm">
		<td rowspan="2" class="center prn_head">No.</td>
		<td rowspan="2" class="center prn_head">No. SR</td>
		<td rowspan="2" class="center prn_head">Nama</td>
		<td rowspan="2" class="center prn_head">Bulan-Tahun</td>
		<td rowspan="2" class="center prn_head">Tanggal</td>
		<td colspan="2" class="center prn_head">Sebelumnya(Rupiah)</td>
		<td rowspan="2" class="center prn_head">Reduksi<br/>(M3)</td>
		<td colspan="2" class="center prn_head">Hasil Reduksi(Rupiah)</td>
		<td colspan="2" class="center prn_head">Selisih(Rupiah)</td>
    </tr>
	<tr class="table_cont_btm">
		<td class="center prn_cell">Uang Air</td>
		<td class="center prn_cell">Total</td>
		<td class="center prn_cell">Uang Air</td>
		<td class="center prn_cell">Total</td>
		<td class="center prn_cell">Uang Air</td>
		<td class="center prn_cell">Total</td>
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
		$periode[$i]						= $row0['rek_bln']."-".$row0['rek_thn'];
		$lembar[$periode[$i]][$i]			= 1;
		$uangair_awal[$periode[$i]][$i]		= $row0['rd_uangair_awal'];
		$total_awal[$periode[$i]][$i]		= $row0['rd_uangair_awal']+$row0['rek_beban'];
		$uangair_akhir[$periode[$i]][$i]	= $row0['rd_uangair_akhir'];
		$total_akhir[$periode[$i]][$i]		= $row0['rd_uangair_akhir']+$row0['rek_beban'];
		$uangair_selisih[$periode[$i]][$i]	= $row0['rd_uangair_awal']-$row0['rd_uangair_akhir'];
?>
  <tr class="<?php echo $klas; ?>">
    <td class="right prn_cell"><?php echo number_format($nomor); 							?></td>
	<td class="right prn_cell"><?php echo $row0['pel_no']; 									?></td>
	<td class="left prn_cell prn_left"><?php echo $row0['pel_nama']; 						?></td>
	<td class="right prn_cell"><?php echo $periode[$i]; 									?></td>
    <td class="right prn_cell"><?php echo $row0['tgl_reduksi']; 							?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rd_uangair_awal']); 			?></td>
    <td class="right prn_cell"><?php echo number_format($total_awal[$periode[$i]][$i]); 	?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rd_nilai']); 				?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rd_uangair_akhir']); 		?></td>
    <td class="right prn_cell"><?php echo number_format($total_akhir[$periode[$i]][$i]); 	?></td>
    <td class="right prn_cell"><?php echo number_format($uangair_selisih[$periode[$i]][$i]);?></td>
    <td class="right prn_cell"><?php echo number_format($uangair_selisih[$periode[$i]][$i]);?></td>
  </tr>

<?php
   		}
		if($i>0){
			for($j=0;$j<count($periode);$j++){
?>
	<tr class="table_cont_btm">
    	<td colspan="3" class="right prn_total"> Total Bulan :</td>
		<td class="right prn_total"><?php echo $periode[$j]; ?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($lembar[$periode[$j]])); ?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($uangair_awal[$periode[$j]])); ?></td>
		<td class="right prn_total"><?php echo number_format(array_sum($total_awal[$periode[$j]])); ?></td>
		<td class="right prn_total"></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($uangair_akhir[$periode[$j]])); ?></td>
   		<td class="right prn_total"><?php echo number_format(array_sum($total_akhir[$periode[$j]])); ?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($uangair_selisih[$periode[$j]])); ?></td>
   		<td class="right prn_total"><?php echo number_format(array_sum($uangair_selisih[$periode[$j]])); ?></td>
	</tr>
<?php
			}
		}
?>
</table>
</div>
</div>
