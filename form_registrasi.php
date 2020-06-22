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
	$parm2 = array("class"=>"simpan refresh","id"=>"rayn-4","name"=>"dkd_kd","selected"=>$dkd_kd);

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
<input id="aktiveRayn" type="hidden" value="6"/>
<?php
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
			$parm0 = array("class"=>"simpan pilih","id"=>"rayn-3","name"=>"kp_kode","selected"=>$kp_kode,"onchange"=>"buka('pilih')");

			/* inquiry nomer sl */
			try{
				$que5 	= "SELECT getNOREG() AS pem_reg";
				if(!$res5 = mysql_query($que5,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-5));
				}
				else{
					$row5 		= mysql_fetch_array($res5);
					$pem_reg	= $row5['pem_reg'];
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que4));
				$error_mess	= $e->getMessage();
				$show_form 	= false;
			}
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
	<input type="hidden" class="pilih" 			name="targetUrl" 	value="<?php echo __FILE__;	?>"/>
	<input type="hidden" class="pilih" 			name="proses" 		value="pilihRayon"/>
	<input type="hidden" class="pilih"			name="targetId" 	value="targetRayon"/>
	<input type="hidden" class="simpan"			name="appl_tokn" 	value="<?php echo _TOKN; 	?>"/>
	<input type="hidden" class="simpan pilih"	name="appl_kode" 	value="<?php echo _KODE; 	?>"/>
	<input type="hidden" class="simpan pilih"	name="appl_name" 	value="<?php echo _NAME; 	?>"/>
	<input type="hidden" class="simpan pilih"	name="appl_file" 	value="<?php echo _FILE; 	?>"/>
	<input type="hidden" class="simpan pilih"	name="appl_proc" 	value="<?php echo _PROC; 	?>"/>
	<input type="hidden" class="simpan"			name="targetUrl" 	value="<?php echo _PROC; 	?>"/>
	<input type="hidden" class="simpan"			name="targetId" 	value="targetSimpan"/>
	<input type="hidden" class="simpan"			name="proses" 		value="register"/>
	<div>
		<div class="span-24 left">
			<input id="jumlahForm" type="hidden" value="5"/>
			<div class="append-bottom span-3">No Registrasi</div>
			<div class="append-bottom span-18">
				: <input readonly type="text" class="simpan" name="pem_reg" maxlength="6" value="<?php echo $pem_reg; ?>" />
			</div>
			<div class="append-bottom span-3">Nama</div>
			<div class="append-bottom span-18">
				: <input id="rayn-1" type="text" class="simpan" name="pem_nama" maxlength="30"/>
			</div>
			<div class="append-bottom span-3">Alamat</div>
			<div class="append-bottom span-18">
				: <textarea id="rayn-2" class="simpan height-2 span-7" name="pem_alamat"></textarea>
			</div>
			<div class="append-bottom span-3">Unit Pelayanan</div>
			<div class="append-bottom span-18">
				: <?php echo pilihan($data0,$parm0); ?>
			</div>
			<div class="append-bottom span-3">Rayon</div>
			<div class="append-bottom span-18" id="targetRayon">
				: <?php echo pilihan($data2,$parm2); ?>
				<input id="aktiveRayn" type="hidden" value="0"/>
				<input id="jumlahRayn" type="hidden" value="5"/>
			</div>
			<div class="span-3">&nbsp;</div>
			<div class="span-18">&nbsp;
				<input id="rayn-5" type="button" value="Simpan" onclick="buka('simpan')"/>
			</div>
		</div>
	</div>
</div>
</div>
<?php
	}
?>
