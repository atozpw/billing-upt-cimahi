<?php
	if($erno) die();
	$kp_ket	= $_SESSION['kp_ket'];
	if(!isset($proses)){
		$proses	= false;
	}
?>
<h3 class="cetak"><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h3>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="errorId"   	value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="dkd_kd" 		value="<?php echo $dkd_kd; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="targetId"  	value="content"/>
<input type="hidden" class="next_page"	name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" 	name="pg" value="<?php echo $pref_page; ?>"/>
<input type="hidden" class="refresh" 	name="pg" value="<?php echo $pg;		?>"/>
<input type="hidden" class="kembali" 	name="pg" value="<?php echo $back; 		?>"/>
<?php
	switch($proses){
		case "rinci":
			$que0 	= "SELECT *FROM v_dsml WHERE dkd_kd='$dkd_kd' ORDER BY dkd_no,pel_no ASC LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk memulai entry DSML, <b>Tab</b> untuk memilih kode abnormal, kemudian <b>Alt + S</b> untuk menyimpan hasil bacaan, <b>B</b> untuk kembali ke halaman sebelumnya.</div>";
?>
<input id="keyProses1" type="hidden" value="3"/>
<input type="hidden" class="refresh next_page pref_page" name="jml_perpage"  	value="25"/>
<?php
			break;
		case "cari":
			if(_KOTA=='00'){
				$filter = "";
			}
			else{
				$filter = "kp_kode='"._KOTA."' AND";
			}
			$kode	= strtoupper($kode);
			if(strlen($kode)==6){
				$que0 	= "SELECT *FROM v_dsml WHERE $filter pel_no='$kode' ORDER BY pel_no DESC LIMIT $limit_awal,$jml_perpage";
			}
			else if(strlen($kode)==10){
				$kode	= substr($kode,0,2).".".substr($kode,2,2).".".substr($kode,4,3).".".substr($kode,7,3);
				$que0 	= "SELECT *FROM v_dsml WHERE $filter ref_no='$kode' ORDER BY pel_no DESC LIMIT $limit_awal,$jml_perpage";
			}
			else{
				$que0 	= "SELECT *FROM v_dsml WHERE $filter ref_no='$kode' ORDER BY pel_no DESC LIMIT $limit_awal,$jml_perpage";
			}
			$proses	= "rinci";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk memulai entry DSML, <b>Tab</b> untuk memilih kode abnormal, <b>B</b> untuk kembali ke halaman sebelumnya.</div>";
?>
<input id="keyProses1" type="hidden" value="4" />
<?php
			break;
		default :
?>
<input id="keyProses1" type="hidden" value="1" />
<?php
			if(_KOTA=='00'){
				$filter = "";
			}
			else{
				$filter = "WHERE kp_kode='"._KOTA."'";
			}
			$que0 	= "SELECT *FROM v_rayon $filter LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice cetak\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
	}
	try{
		if(!$res0 = $link->query($que0)){
			throw new Exception($link->error);
		}
		else{
			$i = 0;
			while($row0 = $res0->fetch_array()){
				$data[] = $row0;
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
		case "rinci":
			try{
				if(isset($kode)){
					$que0 = "SELECT a.dkd_kd,a.dkd_jalan,a.dkd_tcatat,COUNT(b.dkd_kd) AS jml FROM tr_dkd a JOIN tm_pelanggan b ON(b.dkd_kd=a.dkd_kd) WHERE getAktive(b.kps_kode)=1 AND b.dkd_kd=getRayon('$kode') GROUP BY a.dkd_kd";
				}
				else{
					$que0 = "SELECT a.dkd_jalan,a.dkd_tcatat,COUNT(b.dkd_kd) AS jml FROM tr_dkd a JOIN tm_pelanggan b ON(b.dkd_kd=a.dkd_kd) WHERE getAktive(b.kps_kode)=1 AND b.dkd_kd='$dkd_kd' GROUP BY a.dkd_kd";
				}
				if(!$res0 = $link->query($que0)){
					throw new Exception($link->error);
				}
				else{
					$row0 = $res0->fetch_object();
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que0));
				errorLog::logMess(array($e->getMessage()));
				$erno = true;
			}
?>
<input type="hidden" class="next_page pref_page" 	name="dkd_kd" 		value="<?php echo $dkd_kd;		?>"/>
<input type="hidden" class="next_page pref_page" 	name="back" 		value="<?php echo $back;		?>"/>
<input type="hidden" class="simpan" 				name="targetUrl" 	value="<?php echo _PROC;		?>"/>
<input type="hidden" class="simpan" 				name="targetId" 	value="proses"/>
<input type="hidden" class="simpan" 				name="dump" 		value="0"/>
<?php
			try{
				$que2 = "SELECT kwm_kd,UPPER(kwm_ket) AS kwm_ket FROM tr_kondisi_wm ORDER BY kwm_kd";
				if(!$res2 = $link->query($que2)){
					throw new Exception($link->error);
				}
				else{
					while($row2 = $res2->fetch_array()){
						$data2[] = array("kwm_kd"=>$row2['kwm_kd'], "kwm_ket"=>strtoupper($row2['kwm_ket']));
					}
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que2));
				errorLog::logMess(array($e->getMessage()));
				$erno = true;
			}
			
			try{
			$que3 = "SELECT kl_kd,UPPER(kl_ket) AS kl_ket FROM tr_kondisi_lingkungan ORDER BY kl_kd";
				if(!$res3 = $link->query($que3)){
					throw new Exception($link->error);
				}
				else{
					while($row3 = $res3->fetch_array()){
						$data3[] = array("kl_kd"=>$row3['kl_kd'], "kl_ket"=>strtoupper($row3['kl_ket']));
					}
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que3));
				errorLog::logMess(array($e->getMessage()));
				$erno = true;
			}
			if(_HINT==1){
				echo $hint;
			}
			if(!$row0){
				$row0				= new stdClass();
				$row0->dkd_jalan	= false;
				$row0->dkd_tcatat	= false;
				$row0->jml			= false;
			}
?>
<input id="<?php echo $errorId; ?>" type="hidden" value="<?php echo $mess; 		?>"/>
<input id="norefresh" type="hidden" value="1"/>
<input type="hidden" class="refresh next_page pref_page" name="proses" value="<?php echo $proses; ?>"/>
<table class="table_info" >
	<tr class="table_head">
		<td colspan="6">Rayon DKD :[<?php echo $dkd_kd; ?>]-<?php echo $row0->dkd_jalan; ?></td> 
		<td colspan="2">Jml Pelanggan : <?php echo $row0->jml; ?></td>
		<td>Tgl Catat : <?php echo $row0->dkd_tcatat; ?></td>
		<td>Hal : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_validator">
		<td rowspan="2">No.</td>
		<td width="5%" rowspan="2">No Pel</td>	
		<td width="15%" rowspan="2">Nama</td>
		<td width="20%" rowspan="2">Alamat</td>
		<td colspan="3" width="100" class="center">Stand Meter</td>
		<td colspan="2" width="100" class="center">Kode Abnormal</td>
		<td width="5%" rowspan="2" class="center">Keterangan</td>
	</tr>
	<tr class="table_validator">
		<td width="5%" class="center">Lalu</td>
		<td width="5%" class="center">Kini</td>
		<td width="5%" class="center">Pakai</td>
		<td width="15%" class="center">WM</td>
		<td width="15%" class="center">Lingkungan</td>
	</tr>		
<?php
				if(!isset($data)){
					$data	= array();
				}
				for($i=1;$i<=count($data);$i++){
					$nomer	= $i+(($pg-1)*$jml_perpage);
					$class_nya 	= "table_cell1";
					if (($i%2) == 0){
						$class_nya ="table_cell2";
					}
					/** getParam 
						memindahkan semua nilai dalam array ke dalam
						variabel yang bersesuaian dengan masih kunci array
					*/
					$nilai	= $data[($i-1)];
					$konci	= array_keys($nilai);
					for($j=0;$j<count($konci);$j++){
						if(PHP_VERSION < 7){
							$$konci[$j]	= $nilai[$konci[$j]];
						}else{
							${$konci[$j]} = $nilai[$konci[$j]];
						}
					}

					$parm2 	= array("class"=>"simpan","name"=>"kwm_kd[$i]","selected"=>$kwm_kd,"onchange"=>"setUbah($i)","style"=>"font-size: 9pt");
					$parm3 	= array("class"=>"simpan","name"=>"kl_kd[$i]","selected"=>$kl_kd,"onchange"=>"setUbah($i)","style"=>"font-size: 9pt");
?>
					<tr class="<?php echo $class_nya; ?>" >
						<td align="right"><?php echo $nomer; ?>.</td>
						<td><?php echo $pel_no; ?></td>
						<td><?php echo $pel_nama; ?></td>
						<td><?php echo $pel_alamat; ?></td>
						<td class="right"><?php echo $sm_lalu; ?></td>
						<td class="right">
							<input type="hidden" class="simpan" name="sm_nomer[<?php echo $i; ?>]"	value="<?php echo $sm_nomer; ?>"/>
							<input type="hidden" class="simpan" name="pel_no[<?php echo $i; ?>]" 		value="<?php echo $pel_no; ?>"/>
							<input id="sm_lalu_<?php echo $i; ?>" type="hidden" value="<?php echo $sm_lalu; ?>"/>
							<input id="ubah_<?php echo $i; ?>" 	type="hidden" class="simpan" name="ubah[<?php echo $i; ?>]" value="<?php echo $ubah; ?>"/>
							<input id="form-<?php echo $i; ?>" type="text" class="simpan" name="sm_kini[<?php echo $i; ?>]" size="5" value="<?php echo $sm_kini; ?>" onChange="pilihDsml('<?php echo $i; ?>')"/>
						</td>
						<td id="pakai_kini_<?php echo $i; ?>" class="right"><?php echo $pakai_kini; ?></td>
						<td><?php echo pilihan($data2,$parm2); 	?></td>
						<td><?php echo pilihan($data3,$parm3); 	?></td>
						<td><?php echo $sm_ket;					?></td>
					</tr>										  
<?php
				}
?>	
	<tr class="table_cont_btm">
		<td colspan="8">
			&nbsp;
			<span id="proses">
				<input type="button" accesskey="S" value="Simpan" onclick="buka('simpan')"/>
			</span>
		</td>
		<td colspan="2" class="right"><?php echo $pref_mess." ".$kembali." ".$next_mess; ?></td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?php echo ($i-1); ?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
			break;
		default:
			if(_HINT==1){
				echo $hint;
			}
?>
<input type="hidden" class="cari" name="proses" value="cari"/>
<input type="hidden" class="cari" name="back" 	value="<?php echo $pg; ?>"/>
<table class="table_info cetak">
	<tr class="table_cont_btm">
		<td colspan="5">
			Pencarian Pelanggan :
			<input id="jumlahFind" type="hidden" value="2"/>
			<input id="aktiveFind" type="hidden" value="0"/>
			<input id="find-1" type="text" class="cari next_page pref_page" name="kode" size="13" maxlength="13" title="masukan nomor sl"/>
			<input id="find-2" type="button" value="Periksa" onclick="buka('cari')"/>
		</td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head"> 
		<td>No</td>
		<td>Kode</td>   
		<td>Tgl Catat</td>        
		<td>Nama Petugas</td>
		<td>Jalan</td>
		<td>Manage</td>
	</tr>
<?php
	for($i=0;$i<count($data);$i++){
		$row0 	= $data[$i];
		$nomer	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$dkd_kd 		= $row0['dkd_kd'];
		$dkd_tcatat 	= $row0['dkd_tcatat'];
		$dkd_pembaca 	= $row0['dkd_pembaca'];
		$dkd_jalan		= $row0['dkd_jalan'];
?>
	<tr class="<?php echo $klas; ?>">
		<td><?php echo $nomer;		?></td>
		<td><?php echo $dkd_kd;		?></td>
		<td><?php echo $dkd_tcatat;	?></td>
		<td><?php echo $dkd_pembaca;?></td>
		<td><?php echo $dkd_jalan;	?></td>
		<td>
			<input type="hidden" class="rinci_<?php echo $i; ?> cetak_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?> cetak_<?php echo $i; ?>" name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?> cetak_<?php echo $i; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?> cetak_<?php echo $i; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?> cetak_<?php echo $i; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?> cetak_<?php echo $i; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?> cetak_<?php echo $i; ?>" name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="back"	 	value="<?php echo $pg; 			?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="targetId" 	value="content"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="proses"	 	value="rinci"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="jml_perpage"	value="25"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dump"	 	value="0"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="targetUrl" 	value="cetak_dsml.php"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="dkd_tcatat" 	value="<?php echo $dkd_tcatat; 	?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="dkd_pembaca"	value="<?php echo $dkd_pembaca; ?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="dkd_jalan" 	value="<?php echo $dkd_jalan; 	?>"/>
			<input id="form-<?php echo ($i+1); ?>" type="button" value="Detail" onclick="buka('rinci_<?php echo $i; ?>')"/>
			<input type="button" value="Cetak" onclick="nonghol('cetak_<?php echo $i; ?>')"/>
		</td>
	</tr>

<?php

	}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="left">&nbsp;</td>
		<td class="right"><?php echo $pref_mess." ".$next_mess; ?></td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?php echo $i; ?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
	}
	if(!$erno) $link->close();
?>