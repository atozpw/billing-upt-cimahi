<?php
	if($erno) die();
	$kp_ket	= $_SESSION['kp_ket'];
	if(isset($mess)){
		$mess	= "";
	}
	if(!isset($proses)){
		$proses	= false;
	}

	if(isset($simpan)){
		require _PROC;
	}
?>
<h3><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h3>
<input type="hidden" id="<?php echo $errorId; ?>" value="<?php echo $mess; ?>"/>
<input type="hidden" id="norefresh" value="1"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan"	name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan"	name="errorId"   	value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali refresh next_page pref_page"				name="dkd_kd" 		value="<?php echo $dkd_kd; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan"	name="targetId"  	value="content"/>
<input type="hidden" class="pref_page" 	name="pg" value="<?php echo $pref_page; ?>"/>
<input type="hidden" class="simpan" 	name="pg" value="<?php echo $pg;		?>"/>
<input type="hidden" class="refresh" 	name="pg" value="<?php echo $pg;		?>"/>
<input type="hidden" class="kembali" 	name="pg" value="<?php echo $back; 		?>"/>
<?php
	switch($proses){
		default :
?>
<input id="keyProses1" type="hidden" value="1" />
<?php
			// Connect to MSSQL
			$msql 	= mssql_connect("192.168.0.29","sa","023456");
			mssql_select_db('MeterReader', $msql);

			// $jml_perpage = 10;
			$que0 	= "SELECT a.*,SUM(IF(ISNULL(b.dkd_kd),0,1)) AS dkd_jml FROM (SELECT * FROM v_rayon WHERE kp_kode='"._KOTA."' ORDER BY dkd_kd LIMIT ".$limit_awal.",".$jml_perpage.") a LEFT JOIN tm_stand_meter b ON(b.dkd_kd=a.dkd_kd AND b.sm_bln=MONTH(NOW()) AND b.sm_thn=YEAR(NOW()) AND (b.sm_sts=1 OR b.sm_sts=2)) GROUP BY a.dkd_kd";

			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
	}
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception(mysql_error($link));
		}
		else{
			$i = 0;
			while($row0 = mysql_fetch_array($res0)){
				$dkd_kd = $row0['dkd_kd'];
				$que1	= "SELECT a.reading_route AS dkd_kd,COUNT(*) AS dkd_deliver,SUM(CASE ISNULL(b.CustomerId, 0) WHEN 0 THEN 0 ELSE 1 END) AS dkd_valid FROM dbo.RawDataImportTmp a LEFT JOIN dbo.MeterTransaction b ON(b.CustomerId=a.apar_id AND b.Period=a.read_period) WHERE a.read_period=".date('Ym')." AND a.reading_route='".$dkd_kd."' GROUP BY a.reading_route";
				$res1 	= mssql_query($que1);
				$row1 	= mssql_fetch_array($res1);
				if(isset($row1['dkd_kd'])){
					$data[$row0['dkd_kd']] = array_merge($row0,array("dkd_deliver"=>$row1['dkd_deliver'], "dkd_valid"=>$row1['dkd_valid']));
				}
				else{
					$data[$row0['dkd_kd']] = array_merge($row0,array("dkd_deliver"=>0, "dkd_valid"=>0));
				}
				$i++;
			}
			/*	pagination : menentukan keberadaan operasi next page	*/
			if($i==$jml_perpage){
				$next_mess	= "<input type=\"button\" value=\">>\" class=\"form_button\" onClick=\"buka('next_page')\"/>";
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($e->getMessage()));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
	}
	
	switch ($proses){
		default:
			if(_HINT==1){
				echo $hint;
			}
?>
<input type="hidden" class="cari" name="proses" value="cari"/>
<input type="hidden" class="cari" name="back" 	value="<?php echo $pg; ?>"/>
<table class="table_info">
	<tr class="table_cont_btm">
		<td colspan="6">
			Pencarian Pelanggan :
			<input id="jumlahFind" type="hidden" value="2"/>
			<input id="aktiveFind" type="hidden" value="0"/>
			<input id="find-1" type="text" class="cari next_page pref_page" name="kode" size="6" maxlength="6" title="masukan nomor sl"/>
			<input id="find-2" type="button" value="Periksa" onclick="buka('cari')"/>
		</td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head"> 
		<td>No</td>
		<td>Kode</td>   
		<td>Tgl Catat</td>
		<td>DSML</td>
		<td>Order</td>
		<td>Valid</td>
		<td>Manage</td>
	</tr>
<?php
	$kunci	= array_keys($data);
	for($i=0;$i<count($data);$i++){
		$row0 	= $data[$kunci[$i]];
		$nomer	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		if(isset($row0['dkd_kd'])){
			$dkd_kd = $row0['dkd_kd'];
		}
		else{
			$dkd_kd	= "&nbsp;";
		}
		$dkd_tcatat 	= $row0['dkd_tcatat'];
		$dkd_jml	 	= $row0['dkd_jml'];
		$dkd_deliver 	= $row0['dkd_deliver'];
		$dkd_valid		= $row0['dkd_valid'];
		$style			= "";
		if($dkd_deliver<>$dkd_valid){
			$style	= "style=\"color: red\"";
			
		}
?>
	<tr class="<?php echo $klas; ?>">
		<td><?php echo $nomer;			?></td>
		<td><?php echo $dkd_kd;			?></td>
		<td><?php echo $dkd_tcatat;		?></td>
		<td class="right"><?php echo $dkd_jml;		?></td>
		<td><?php echo $dkd_deliver;	?></td>
		<td <?php echo $style; ?>><?php echo $dkd_valid;		?></td>
		<td>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="back"	 	value="<?php echo $pg; 			?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="targetId" 	value="content"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="simpan"	 	value="1"/>
<?php	if($dkd_deliver==$dkd_valid && $dkd_deliver>0){	?>
			<input type="button" value="Retrieve" />
<?php	} else if($dkd_deliver<>$dkd_jml && $dkd_jml>0){	?>
			<input id="form-<?php echo ($i+1); ?>" type="button" value="Transfer" onclick="buka('rinci_<?php echo $i; ?>')"/>
<?php	}	?>
		</td>
	</tr>

<?php

	}
?>
	<tr class="table_cont_btm">
		<td colspan="6" class="left">&nbsp;</td>
		<td class="right">
			<?php echo $pref_mess; ?>&nbsp;
			<input type="text" size="1" class="next_page" name="pg" value="<?php echo $next_page; ?>" onmouseover="this.select()" />
			&nbsp;<?php echo $next_mess; ?>
		</td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?php echo $i; ?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
	}
	if(!$erno) mysql_close($link);
?>
