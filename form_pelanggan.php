<?php
	if($erno) die();
	$formId 	= getToken();
	if(!isset($kp_kode)){
		$kp_kode	= $_SESSION['Kota_c'];
	}
	if(!isset($dkd_kd)){
		$dkd_kd	= "";
	}
	if(!isset($gol_kode)){
		$gol_kode	= "";
	}
	if(!isset($kps_kode)){
		$kps_kode	= "";
	}
	if(!isset($proses)){
		$proses	= "";
	}
	if(!isset($hint)){
		$hint	= "";
	}

	/* inquiry rayon */
	$data2[]	= array('dkd_kd'=>'000000','dkd_jalan'=>'-');
	try{
		$que2 		= "SELECT dkd_kd,CONCAT('[',dkd_kd,']',' ',IFNULL(dkd_jalan,'N/A')) AS dkd_jalan FROM v_rayon WHERE kp_kode='$kp_kode' ORDER BY dkd_kd";
		if(!$res2 = mysql_query($que2,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row2 = mysql_fetch_array($res2)){
				$data2[] = array("dkd_kd"=>$row2['dkd_kd'],"dkd_jalan"=>$row2['dkd_jalan']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que2));
		$mess = $e->getMessage();
		$erno = false;
	}
	if(count($data2)==1){
		unset($data2);
		$data2[]	= array('dkd_kd'=>'000000','dkd_jalan'=>'Data rayon tidak tersedia');
	}
	$parm2 = array("class"=>"simpan refresh","id"=>"form-8","name"=>"dkd_kd","selected"=>$dkd_kd,"onchange"=>"buka('pilih2')");
	
	/* inquiry nomer sl */
	try{
		$que5 	= "SELECT CONCAT(REPEAT('0',5-LENGTH(MAX(ABS(RIGHT(pel_no,5)))+1)),(MAX(ABS(RIGHT(pel_no,5)))+1)) AS pel_no FROM tm_pelanggan WHERE kp_kode='$kp_kode'";
		if(!$res5 = mysql_query($que5,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-5));
		}
		else{
			$row5 	= mysql_fetch_array($res5);
			if($row5['pel_no']){
				$pel_no	= substr($kp_kode,0,1)."".$row5['pel_no'];
			}else{
				$pel_no	= substr($kp_kode,0,1)."0001";
			}
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que5));
		$error_mess	= $e->getMessage();
		$show_form 	= false;
	}
	
	/* panduan pintasan aplikasi */
	$panduan	= false;
	if(isset($_SESSION['panduan'])){
		$panduan = true;
	}	
	if($panduan){
		$hint = "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk untuk memulai entry data, dan tombol <b>Esc</b> untuk menutup halaman ini.</div>";
	}
	
	switch($proses){
		case "pilihRayon":
			echo ": ".pilihan($data2,$parm2);
?>
<input id="aktiveForm" type="hidden" value="6"/>
<?php
			break;
		case "pilihKode":
			echo ": <input id='form-1' readonly type='text' class='simpan refresh' name='pel_no' maxlength='6' value='".$pel_no."' />";
			break;
		default:
			/* inquiry kopel */
			if($_SESSION['Group_c']=='000'){
				$filtered = '';
			}
			else if($_SESSION['c_group']=='00'){
				$filtered = '';
			}
			else{
				$filtered = "WHERE kp_kode='".$_SESSION['Kota_c']."'";
			}
			try{
				$que0 = "SELECT kp_kode,kp_ket FROM tr_kota_pelayanan $filtered ORDER BY kp_kode";
				if(!$res0 = mysql_query($que0,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row0 = mysql_fetch_array($res0)){
						$data0[] = array("kp_kode"=>$row0['kp_kode'],"kp_ket"=>$row0['kp_ket']);
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que0));
				$mess = $e->getMessage();
				$erno = false;
			}
			$parm0 = array("class"=>"simpan pilih pilih2","id"=>"form-7","name"=>"kp_kode","selected"=>$kp_kode,"onchange"=>"buka('pilih')");
			
			/* inquiry golongan */
			try{
				$que1 = "SELECT gol_kode,CONCAT('[',gol_kode,']',' ',gol_ket) AS gol_ket FROM tr_gol ORDER BY gol_kode";
				if(!$res1 = mysql_query($que1,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row1 = mysql_fetch_array($res1)){
						$data1[] = array("gol_kode"=>$row1['gol_kode'],"gol_ket"=>$row1['gol_ket']);
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que1));
				$mess = $e->getMessage();
				$erno = false;
			}
			$parm1 = array("class"=>"simpan","id"=>"form-4","name"=>"gol_kode","selected"=>$gol_kode);
			
			/* inquiry status */
			try{
				$que3 = "SELECT kps_kode,UPPER(kps_ket) AS kps_ket FROM tr_kondisi_ps WHERE (kps_kode=0 OR kps_kode=7) ORDER BY kps_kode";
				if(!$res3 = mysql_query($que3,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row3 = mysql_fetch_array($res3)){
						$data3[] = array("kps_kode"=>$row3['kps_kode'],"kps_ket"=>$row3['kps_ket']);
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que3));
				$mess = $e->getMessage();
				$erno = false;
			}
			$parm3 = array("class"=>"simpan","id"=>"form-9","name"=>"kps_kode","selected"=>$kps_kode);
			
			/* pilih ukuran meter */
			try{
				$que4 	= "SELECT um_kode,CONCAT(um_ukuran,' inch') AS um_ukuran FROM tr_ukuranmeter ORDER BY um_kode";
				if(!$res4 = mysql_query($que4,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row4 = mysql_fetch_array($res4)){
						$data4[] = array("um_kode"=>$row4['um_kode'],"um_ukuran"=>$row4['um_ukuran']);
					}
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que4));
				$error_mess	= $e->getMessage();
				$show_form 	= false;
			}
			$parm4 = array("class"=>"simpan","id"=>"form-5","name"=>"um_kode","selected"=>"0"); 
			
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input id="keyProses0" 	type="hidden" value="1"/>
<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
<div class="span-18 pesan">
	<div class="span-18 right">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
	<h3>Form Data Pelanggan</h3>
	<hr/>
	<div id="targetSimpan">
<?php
	echo $hint;
?>
	</div>
	<input type="hidden" class="pilih" 	name="targetUrl" 	value="<?php echo __FILE__;	?>"/>
	<input type="hidden" class="pilih" 	name="proses" 		value="pilihRayon"/>
	<input type="hidden" class="pilih"	name="targetId" 	value="targetRayon"/>
	<input type="hidden" class="pilih"	name="dump" 		value="0"/>
	<input type="hidden" class="pilih2" name="targetUrl" 	value="<?php echo __FILE__;	?>"/>
	<input type="hidden" class="pilih2" name="proses" 		value="pilihKode"/>
	<input type="hidden" class="pilih2"	name="targetId" 	value="targetKode"/>
	<input type="hidden" class="pilih2"	name="dump" 		value="0"/>
	<input type="hidden" class="simpan"	name="appl_tokn" 	value="<?php echo _TOKN; 	?>"/>
	<input type="hidden" class="simpan"	name="appl_kode" 	value="<?php echo _KODE; 	?>"/>
	<input type="hidden" class="simpan"	name="targetUrl" 	value="<?php echo _PROC; 	?>"/>
	<input type="hidden" class="simpan"	name="targetId" 	value="targetSimpan"/>
	<input type="hidden" class="simpan"	name="proses" 		value="tambahSL"/>
	<input type="hidden" class="simpan"	name="dump" 		value="0"/>
	<input type="hidden" class="simpan" name="pel_no" 		value="<?php echo $pel_no; 	?>"/>
	<div>
		<div class="span-24 left">
			<input id="jumlahForm" type="hidden" value="10"/>
			<div class="append-bottom span-3">No Pelanggan</div>
			<div class="append-bottom span-18" id="targetKode">
				: <input id="form-1" readonly type="text" class="simpan" name="pel_no" maxlength="6" value="<?php echo $pel_no; ?>" />
			</div>
			<div class="append-bottom span-3">Nama</div>
			<div class="append-bottom span-18">
				: <input id="form-2" type="text" class="simpan" name="pel_nama" maxlength="30"/>
			</div>
			<div class="append-bottom span-3">Alamat</div>
			<div class="append-bottom span-18">
				: <textarea id="form-3" class="simpan height-2 span-7" name="pel_alamat"></textarea>
			</div>
			<div class="append-bottom span-3">Golongan</div>
			<div class="append-bottom span-18">
				: <?php echo pilihan($data1,$parm1); ?>
			</div>
			<div class="append-bottom span-3">Ukuran Meter</div>
			<div class="append-bottom span-18">
				: <?php echo pilihan($data4,$parm4); ?>
			</div>
			<div class="append-bottom span-3">Stand Pasang</div>
			<div class="append-bottom span-18">
				: <input id="form-6" type="text" class="simpan" name="stan_pasang" maxlength="9"/>
			</div>
			<div class="append-bottom span-3">Unit Pelayanan</div>
			<div class="append-bottom span-18">
				: <?php echo pilihan($data0,$parm0); ?>
			</div>
			<div class="append-bottom span-3">Rayon</div>
			<div class="append-bottom span-18" id="targetRayon">
				: <?php echo pilihan($data2,$parm2); ?>
				<input id="aktiveForm" type="hidden" value="0"/>
			</div>
			<div class="append-bottom span-3">Status</div>
			<div class="append-bottom span-18">
				: <?php echo pilihan($data3,$parm3); ?>
			</div>
			<div class="span-3">&nbsp;</div>
			<div class="span-18">&nbsp;
				<input id="form-10" type="button" value="Simpan" onclick="buka('simpan')"/>
			</div>
		</div>
	</div>
</div>
</div>
<?php
	}
?>