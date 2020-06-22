<?php
	if($erno) die();
	$formId = getToken();
	$jmlTA 	= NULL;
	$jmlDA 	= NULL;
	$jmlNA 	= NULL;
	$jmlA 	= NULL;
	$jmlB 	= NULL;
	$jmlC 	= NULL;
	$airB 	= NULL;
	$airC 	= NULL;
	$volB 	= NULL;
	$volC 	= NULL;
	if(!isset($kopel)){
		$kopel		= $_SESSION['Kota_c']."_".$_SESSION['kp_ket'];
	}
	
	if($_SESSION['Group_c']=='000'){
		$filtered = "";
	}
	else if($_SESSION['c_group']=='00'){
		$filtered = "";
	}
	else{
		$filtered = "WHERE kp_kode='".$_SESSION['Kota_c']."'";
	}
	
	/* inquiry kota pelayanan */
	try{
		$que2 = "SELECT CONCAT(kp_kode,'_',kp_ket) AS kopel,CONCAT('[',kp_kode,'] ',kp_ket) AS kp_ket FROM tr_kota_pelayanan $filtered ORDER BY kp_kode ASC";
		if(!$res2 = mysql_query($que2,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row2 = mysql_fetch_array($res2)){
				$data2[] = array("kopel"=>$row2['kopel'],"kp_ket"=>$row2['kp_ket']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que2));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm2 		= array("class"=>"proses setKota","name"=>"kopel","selected"=>$kopel,"onchange"=>"buka('setKota')");

	/* inquiry resume penerbitan */
	$kopel	= explode("_",$kopel);
	try{
		$que4 = "select `b`.`kp_kode` AS `kp_kode`,sum(if(((`a`.`sm_sts` = 1) or (`a`.`sm_sts` = 2)),1,0)) AS `jmlTA`,sum(if((`a`.`sm_sts` = 1),1,0)) AS `jmlDA`,sum(if(((`a`.`sm_kini` - if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))) < 0),1,0)) AS `jmlNA`,sum(if(`getPA`(`a`.`sm_kini`,if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),1,0)) AS `jmlA`,sum(if(`getPA`(`a`.`sm_kini`,if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),`getRekTotal`(`b`.`gol_kode`,`b`.`um_kode`,`a`.`sm_bln`,`a`.`sm_thn`,(`a`.`sm_kini` - if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`)))),0)) AS `airA`,sum(if(`getPB`(`a`.`sm_kini`,if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),1,0)) AS `jmlB`,sum(if(`getPB`(`a`.`sm_kini`,if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),(`a`.`sm_kini` - if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),0)) AS `volB`,sum(if(`getPB`(`a`.`sm_kini`,if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),`getRekTotal`(`b`.`gol_kode`,`b`.`um_kode`,`a`.`sm_bln`,`a`.`sm_thn`,(`a`.`sm_kini` - if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`)))),0)) AS `airB`,sum(if(`getPC`(`a`.`sm_kini`,if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),1,0)) AS `jmlC`,sum(if(`getPC`(`a`.`sm_kini`,if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),(`a`.`sm_kini` - if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),0)) AS `volC`,sum(if(`getPC`(`a`.`sm_kini`,if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`))),`getRekTotal`(`b`.`gol_kode`,`b`.`um_kode`,`a`.`sm_bln`,`a`.`sm_thn`,(`a`.`sm_kini` - if(`isBerjalan`(`b`.`met_tgl`),`b`.`met_stdbaru`,if(isnull(`d`.`pel_no`),`a`.`sm_lalu`,`d`.`cl_stankini_akhir`)))),0)) AS `airC` from ((`tm_stand_meter` `a` join `tm_pelanggan` `b` on(((`b`.`pel_no` = `a`.`pel_no`) and `isActive`(`b`.`kps_kode`)))) left join `tm_klaim` `d` on(((`d`.`pel_no` = `a`.`pel_no`) and (month(`d`.`cl_tgl`) = `a`.`sm_bln`) and (year(`d`.`cl_tgl`) = `a`.`sm_thn`) and (`d`.`cl_sts` = 1)))) where ((`a`.`sm_bln` = month(now())) and (`a`.`sm_thn` = year(now())) and `b`.`kp_kode`='".$kopel[0]."') group by `b`.`kp_kode`";
		if(!$res4 = mysql_query($que4,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row4 = mysql_fetch_array($res4)){
				$data4[] = $row4;
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que4));
		$mess = $e->getMessage();
		$erno = false;
	}
	
	$data1[] 	= array("rek_bln"=>"1","bln_nama"=>"Januari");
	$data1[] 	= array("rek_bln"=>"2","bln_nama"=>"Februari");
	$data1[] 	= array("rek_bln"=>"3","bln_nama"=>"Maret");
	$data1[] 	= array("rek_bln"=>"4","bln_nama"=>"April");
	$data1[] 	= array("rek_bln"=>"5","bln_nama"=>"Mei");
	$data1[] 	= array("rek_bln"=>"6","bln_nama"=>"Juni");
	$data1[] 	= array("rek_bln"=>"7","bln_nama"=>"Juli");
	$data1[] 	= array("rek_bln"=>"8","bln_nama"=>"Agustus");
	$data1[] 	= array("rek_bln"=>"9","bln_nama"=>"September");
	$data1[] 	= array("rek_bln"=>"10","bln_nama"=>"Oktober");
	$data1[] 	= array("rek_bln"=>"11","bln_nama"=>"November");
	$data1[] 	= array("rek_bln"=>"12","bln_nama"=>"Desember");
	$parm1 	 	= array("class"=>"setKota","name"=>"rek_bln","selected"=>date('n'),"disabled"=>"disabled");
	$disabled	= "disabled";
?>
<h2 class="cetak"><?php echo _NAME; ?></h2>
<input type="hidden" id="norefresh" value="1" />
<input type="hidden" class="proses setKota" 	name="appl_kode" value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="proses setKota" 	name="appl_name" value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="proses setKota" 	name="appl_file" value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="proses setKota" 	name="appl_proc" value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="proses setKota" 	name="appl_tokn" value="<?php echo _TOKN; 	?>"/>
<input type="hidden" class="proses" 			name="targetUrl" value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="setKota" 			name="targetUrl" value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="proses" 			name="targetId"  value="targetId"/>
<input type="hidden" class="setKota" 			name="targetId"  value="content"/>
<div class="span-4 cetak">Unit Pelayanan</div>
<div class="span-7 cetak">
	: 
	<?php echo pilihan($data2,$parm2); ?>
</div>
<br/><br/>
<div class="span-4 cetak">Bulan - Tahun</div>
<div class="span-7 cetak">
	: 
	<?php echo pilihan($data1,$parm1); ?>
	<input <?php echo $disabled; ?> type="text" class="cetak" name="rek_thn" size="4" maxlength="4" value="<?php echo date('Y'); ?>"/>
</div>
<br/>
<br/>
<table>
	<tr class="table_cont_btm">
		<td rowspan="2">Pelanggan Aktif</td>
		<td rowspan="2">DSML Disi</td>
		<td rowspan="2">DSML Negatif</td>
		<td rowspan="2">Pemakaian 0M3</td>
		<td colspan="3" class="center">Pemakaian SD 10M3</td>
		<td colspan="3" class="center">Pemakaian Di atas 10M3</td>
		<td colspan="2">Total</td>
	</tr>
	<tr class="table_cont_btm">
		<td>Lembar</td>
		<td>Volume</td>
		<td>Rupiah</td>
		<td>Lembar</td>
		<td>Volume</td>
		<td>Rupiah</td>
		<td>Volume</td>
		<td>Rupiah</td>
	</tr>
<?php
	/** getParam 
		memindahkan semua nilai dalam array POST ke dalam
		variabel yang bersesuaian dengan masih kunci array
	*/
	if(isset($data4)){
	$nilai	= $data4[0];
	$konci	= array_keys($nilai);
	for($i=0;$i<count($konci);$i++){
		$$konci[$i]	= $nilai[$konci[$i]];
	}
	}
	$disabled = "";
	if($jmlTA==$jmlDA AND $jmlNA==0){
		#unset($disabled);
	} 
	/* getParam **/
?>
	<tr class="table_cell2">
		<td><?php echo number_format($jmlTA); ?></td>
		<td><?php echo number_format($jmlDA); ?></td>
		<td><?php echo number_format($jmlNA); ?></td>
		<td><?php echo number_format($jmlA); ?></td>
		<td><?php echo number_format($jmlB); ?></td>
		<td><?php echo number_format($volB); ?></td>
		<td><?php echo number_format($airB); ?></td>
		<td><?php echo number_format($jmlC); ?></td>
		<td><?php echo number_format($volC); ?></td>
		<td><?php echo number_format($airC); ?></td>
		<td><?php echo number_format($volB+$volC); ?></td>
		<td><?php echo number_format($airA+$airB+$airC); ?></td>
	</tr>
	<tr class="table_cont_btm">
		<td colspan="12" id="targetId">
			<input type="Button" value="Proses" onclick="buka('proses')" /><?php echo $disabled; ?>
		</td>
	</tr>
</table>