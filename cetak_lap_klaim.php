<?php
	if($erno) die();
	$formId 	= getToken();
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
	if($gol_kode=="KW"){
		$que0 	= "SELECT a.*,a.dkd_kd AS periode FROM v_klaim a WHERE a.cl_sts=1 AND a.rek_thn=$rek_thn AND a.rek_bln=$rek_bln AND a.kp_kode='".$kopel[0]."' ORDER BY a.dkd_kd,a.cl_tgl"; 
		$title	= "Jalan";
	}
	else{
		$que0 	= "SELECT a.*,a.gol_kode AS periode FROM v_klaim a WHERE a.cl_sts=1 AND a.rek_thn=$rek_thn AND a.rek_bln=$rek_bln AND a.kp_kode='".$kopel[0]."' ORDER BY a.gol_kode,a.cl_tgl"; 
		$title 	= "Golongan";
	}
	try{
		if(!$res0 = $link->query($que0)){
			throw new Exception($que0);
		}
		else{
			$data = array();
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
<div class="pesan form-5">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<h3><?php echo $appl_owner; ?> - <?php echo $kopel[1]; ?></h3>
<hr/>
<h4><?=_NAME?> Rekening</h4>
<table width="100%" class="prn_table">
	<tr>
		<td colspan="2">Tanggal Cetak</td>
		<td colspan="15">: <?=$tanggal?></td>
	</tr>
	<tr>
		<td colspan="2">Periode</td>
		<td colspan="15">: <?php echo $rek_bln." - ".$rek_thn; ?></td>
	</tr>
	<tr>
		<td colspan="2">Petugas</td>
		<td colspan="15">: <?=_NAMA?></td>
	</tr>
	<tr class="table_cont_btm">
		<td rowspan="2" class="center prn_head">No.</td>
		<td rowspan="2" class="center prn_head">SR</td>
		<td rowspan="2" class="center prn_head">Nama</td>
		<td rowspan="2" class="center prn_head"><?php echo $title; ?></td>
		<td rowspan="2" class="center prn_head">Tgl</td>
		<td colspan="4" class="center prn_head">Sebelumnya</td>
		<td colspan="3" class="center prn_head">Hasil Koreksi</td>
		<td colspan="2" class="center prn_head">Selisih</td>
		<td rowspan="2" class="center prn_head">Pelaksana</td>
    </tr>
	<tr class="table_cont_btm">
		<td class="center prn_cell">Stan Lalu</td>
		<td class="center prn_cell">Stan Kini</td>
		<td class="center prn_cell">Pakai</td>
		<td class="center prn_cell">Uang Air</td>
		<td class="center prn_cell">Stan Kini</td>
		<td class="center prn_cell">Pakai</td>
		<td class="center prn_cell">Uang Air</td>
		<td class="center prn_cell">Pakai</td>
		<td class="center prn_cell">Uang Air</td>
	</tr>
<?php
	for($i=0;$i<count($data);$i++){
		$nomor		= $i+1;
		$row0 	  	= $data[$i];
		$klas 	  	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$lembar[$row0['periode']][]				= 1;
		$selisih_stanlalu[$row0['periode']][]	= $row0['selisih_stanlalu'];
		$selisih_stankini[$row0['periode']][]	= $row0['selisih_stankini'];
		$selisih_pakai[$row0['periode']][]		= $row0['selisih_pakai'];
		$selisih_uangair[$row0['periode']][]	= $row0['selisih_uangair'];
		
		$grand_lembar[]				= 1;
		$grand_selisih_stanlalu[]	= $row0['selisih_stanlalu'];
		$grand_selisih_stankini[]	= $row0['selisih_stankini'];
		$grand_selisih_pakai[]		= $row0['selisih_pakai'];
		$grand_selisih_uangair[]	= $row0['selisih_uangair'];
?>
  <tr class="<?php echo $klas; ?>">
    <td class="right prn_cell"><?php echo number_format($nomor); 							?></td>
	<td class="right prn_cell"><?php echo $row0['pel_no']; 									?></td>
	<td class="left prn_cell prn_left"><?php echo $row0['pel_nama']; 						?></td>
	<td class="right prn_cell"><?php echo $row0['periode'];									?></td>
    <td class="right prn_cell"><?php echo $row0['tanggal'];		 							?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rek_stanlalu']); 			?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rek_stankini']); 			?></td>
    <td class="right prn_cell"><?php echo number_format($row0['pakai_awal']); 				?></td>
    <td class="right prn_cell"><?php echo number_format($row0['rek_uangair']); 				?></td>
    <td class="right prn_cell"><?php echo number_format($row0['cl_stankini_akhir']); 		?></td>
    <td class="right prn_cell"><?php echo number_format($row0['pakai_akhir']);		 		?></td>
    <td class="right prn_cell"><?php echo number_format($row0['cl_uangair_akhir']);			?></td>
    <td class="right prn_cell"><?php echo number_format($row0['selisih_pakai']);	 		?></td>
    <td class="right prn_cell"><?php echo number_format($row0['selisih_uangair']);			?></td>
    <td class="right prn_cell"><?php echo $row0['kar_nama'];	 							?></td>
  </tr>

<?php
   		}
		if($i>0){
			$periode = array_keys($lembar);
			for($j=0;$j<count($periode);$j++){
?>
	<tr class="table_cont_btm">
    	<td colspan="3" class="right prn_total">Total <?php echo $title; ?> [<?php echo $periode[$j]; ?>]</td>
		<td class="right prn_total">:</td>
		<td class="right prn_total"><?php echo number_format(array_sum($lembar[$periode[$j]])); 			?></td>
		<td colspan="7" class="right prn_total">&nbsp;</td>
		<td class="right prn_total"><?php echo number_format(array_sum($selisih_pakai[$periode[$j]])); 		?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($selisih_uangair[$periode[$j]])); 	?></td>
		<td class="right prn_total"></td>
	</tr>
<?php
			}
?>
	<tr class="table_cont_btm">
    	<td colspan="4" class="right prn_total">Total Seluruh :</td>
		<td class="right prn_total"><?php echo number_format(array_sum($grand_lembar)); 			?></td>
		<td colspan="7" class="right prn_total">&nbsp;</td>
		<td class="right prn_total"><?php echo number_format(array_sum($grand_selisih_pakai)); 		?></td>
	 	<td class="right prn_total"><?php echo number_format(array_sum($grand_selisih_uangair)); 	?></td>
		<td class="right prn_total"></td>
	</tr>
<?php
		}
?>
</table>
</div>
</div>