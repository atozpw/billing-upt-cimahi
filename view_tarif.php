<?php
	if($erno) die();
	if(!isset($appl_tokn)) define("_TOKN",getToken());
	
	/* koneksi database */
	/* link : link baca */
	$link 	= mysql_connect($DHOST,$DUSER,$DPASS) or die(errorLog::errorDie(array(mysql_error())));
	mysql_select_db($DNAME,$link) or die(errorLog::errorDie(array(mysql_error())));

	/** Pagination */
	if(isset($pg) and $pg>1){
		$next_page 	= $pg + 1;
		$pref_page 	= $pg - 1;
		$pref_mess	= "<input type=\"button\" value=\"<<\" class=\"form_button\" onClick=\"buka('pref_page')\"/>";
	}
	else{
		$pg 		= 1;
		$next_page 	= 2;
		$pref_page 	= 1;
	}
	$jml_perpage 	= 15;
	$limit_awal	 	= ($pg - 1) * $jml_perpage;
			
	/* retrive kode tarif */
	try{
		$que0 = "SELECT a.* FROM tm_kode_tarif a JOIN tr_gol b ON(b.gol_kode=a.gol_kode) WHERE b.gol_sts=2 ORDER BY a.tar_bln_akhir DESC,a.gol_kode LIMIT $limit_awal,$jml_perpage";
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			$i = 0;
			while($row0 = mysql_fetch_array($res0)){
				$data[] = $row0;
				$i++;
			}
			/*	menentukan keberadaan operasi next page	*/
			if($i==$jml_perpage){
				$next_mess	= "<input type=\"button\" value=\">>\" class=\"form_button\" onClick=\"buka('next_page')\"/>";
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que0));
		$mess = $e->getMessage();
		$erno = false;
	}
	if(!$erno) mysql_close($link);
?>
<h2><?php echo _NAME?></h2>
<input type="hidden" id="<?php echo $errorId; ?>" value="<?php echo $mess; ?>"/>
<?php
	switch($proses){
		default:
?>
<input type="hidden" class="refresh simpan pilih tambah test import next_page pref_page" name="appl_kode" 	value="<?php echo _KODE; ?>"/>
<input type="hidden" class="refresh simpan pilih tambah test import next_page pref_page" name="appl_name" 	value="<?php echo _NAME; ?>"/>
<input type="hidden" class="refresh simpan pilih tambah test import next_page pref_page" name="appl_file" 	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="refresh simpan pilih tambah test import next_page pref_page" name="appl_proc" 	value="<?php echo _PROC; ?>"/>
<input type="hidden" class="refresh simpan pilih tambah test import next_page pref_page" name="appl_tokn" 	value="<?php echo _TOKN; ?>"/>
<input type="hidden" class="refresh next_page pref_page" 	name="targetUrl"	value="<?php echo _FILE; 	  ?>"/>
<input type="hidden" class="refresh next_page pref_page" 	name="errorId"		value="<?php echo getToken(); ?>"/>
<input type="hidden" class="refresh next_page pref_page" 	name="targetId"  	value="content"/>
<table class="table_info">
	<tr class="table_cont_btm">
		<td rowspan="2">No</td>
		<td rowspan="2">Gol</td>
		<td rowspan="2">Berlaku</td>
		<td colspan="8" class="center">Batas Pemakaian Sampai Dengan (m3/Rupiah)</td>
		<td rowspan="2">Adm</td>
		<td rowspan="2">Denda</td>
		<td rowspan="2">Model</td>
		<td rowspan="2">Manage</td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center">1</td>
		<td class="center">2</td>
		<td class="center">3</td>
		<td class="center">4</td>
		<td class="center">5</td>
		<td class="center">6</td>
		<td class="center">7</td>
		<td class="center">8</td>
	</tr>
<?php
		for($i=0;$i<count($data);$i++){
			$row0	= $data[$i];
			$nomor	= ($i+1)+(($pg-1)*$jml_perpage);
			$klass 	= "table_cell1";
			if(($i%2) == 0){
				$klass = "table_cell2";
			}
?>
	<tr class="<?php echo $klass; ?>">
		<td><?php echo $nomor; ?></td>
		<td><?php echo $row0['gol_kode']; ?></td>
		<td><?php echo $row0['tar_bln_mulai']." - ".$row0['tar_bln_akhir']; ?></td>
		<td class="right"><?php echo number_format($row0['tar_sd1'])."/".number_format($row0['tar_1']); ?></td>
		<td class="right"><?php echo number_format($row0['tar_sd2'])."/".number_format($row0['tar_2']); ?></td>
		<td class="right"><?php echo number_format($row0['tar_sd3'])."/".number_format($row0['tar_3']); ?></td>
		<td class="right"><?php echo number_format($row0['tar_sd4'])."/".number_format($row0['tar_4']); ?></td>
		<td class="right"><?php echo number_format($row0['tar_sd5'])."/".number_format($row0['tar_5']); ?></td>
		<td class="right"><?php echo number_format($row0['tar_sd6'])."/".number_format($row0['tar_6']); ?></td>
		<td class="right"><?php echo number_format($row0['tar_sd7'])."/".number_format($row0['tar_7']); ?></td>
		<td class="right"><?php echo ">".number_format($row0['tar_sd7'])."/".number_format($row0['tar_8']); ?></td>
		<td class="right"><?php echo number_format($row0['tar_adm']); ?></td>
		<td class="right"><?php echo number_format($row0['tar_denda1']); ?></td>
		<td><?php echo $row0['tar_model']; ?></td>
		<td>
			<input type="hidden" class="edit_<?php echo $nomor; ?>" 	name="dump" 	value="1"/>
			<input type="hidden" class="edit_<?php echo $nomor; ?>" 	name="targetId" value="content"/>
			<input type="hidden" class="edit_<?php echo $nomor; ?>" 	name="errorUrl" value="form_edit_gol_kode.php"/>
			<input type="hidden" class="delete_<?php echo $nomor; ?>" 	name="appl_kode" value="<?php echo _KODE; ?>"/>
			<input type="hidden" class="delete_<?php echo $nomor; ?>" 	name="targetId" value="content"/>
			<input type="hidden" class="delete_<?php echo $nomor; ?>" 	name="errorUrl" value="form_edit_gol_kode.php"/>
			<img src="./images/delete.gif" title="Delete golongan tarif" onclick="nonghol('delete_<?php echo $nomor; ?>')"/>
		</td>
	</tr>
<?php
		}
?>
	<tr class="table_cont_btm">
		<td colspan="4">
			<input type="button" class="form_button" value="Tambah Golongan" onclick="nonghol('tambah')"/>
			<input type="button" class="form_button" value="Validasi Tarif" onclick="nonghol('test')"/>
			<input type="hidden" class="tambah" name="errorUrl" value="tambah_golongan.php"/>
			<input type="hidden" class="tambah" name="dump" 	value="0"/>
			<input type="hidden" class="test" name="errorUrl" 	value="form_test_tarif.php"/>
			<input type="hidden" class="test" name="dump" 		value="0"/>
		</td>
		<td colspan="11" class="right">
			<?php echo $pref_mess." ".$next_mess; ?>
			<input type="hidden" class="pref_page" 	name="pg" value="<?php echo $pref_page; ?>"/>
			<input type="hidden" class="next_page" 	name="pg" value="<?php echo $next_page; ?>"/>
			<input type="hidden" class="refresh" 	name="pg" value="<?php echo $pg; ?>"/>
		</td>
	</tr>
</table>
<?php
	}
?>