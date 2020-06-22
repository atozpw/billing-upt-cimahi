<?php
	if($erno) die();
	$formId = getToken();
	if(!isset($proses)){
		$proses	= false;
	}

	/* inquiry rayon */
	try{
		$que2 = "SELECT dkd_kd,CONCAT('[',dkd_kd,']',' ',IFNULL(dkd_jalan,'N/A')) AS dkd_jalan FROM v_rayon WHERE kp_kode='$kp_kode' ORDER BY dkd_kd";
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
	$parm2 = array("class"=>"simpan refresh","id"=>"form-1","name"=>"dkd_kd","selected"=>$dkd_kd);
	
	switch($proses){
		case "pilihRayon":
			echo ": ".pilihan($data2,$parm2);
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
				$que0 = "SELECT kp_kode,kp_ket FROM tr_kota_pelayanan ".$filtered." ORDER BY kp_kode";
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
			$parm0 = array("class"=>"simpan pilih", "name"=>"kp_kode", "selected"=>_KOTA, "disabled"=>"disabled", "onchange"=>"buka('pilih')");
			
			/* inquiry status */
			try{
				$que3 = "SELECT kps_kode,UPPER(kps_ket) AS kps_ket FROM tr_kondisi_ps WHERE kps_kode>=12 AND kps_kode<=14 ORDER BY kps_kode";
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
			$parm3 = array("class"=>"simpan","id"=>"form-2","name"=>"pem_sts","selected"=>$kps_kode);

			/* panduan pintasan aplikasi */
			$panduan	= true;
			if(isset($_SESSION['panduan'])){
				$panduan = true;
			}
			$hint = "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk memulai melakukan perubahan terhadap data pemohon, kemudian tombol <b>Alt+S</b> untuk menyimpan, dan tombol <b>Esc</b> untuk menutup halaman ini.</div>";
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input id="keyProses0" 	type="hidden" value="1"/>
<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
<div class="pesan form-5">
<div class="span-20 right">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<br/>
<h3>Form Data Pemohon</h3>
<hr/>
<input type="hidden" class="pilih" 	name="targetUrl" 	value="<?php echo __FILE__;	?>"/>
<input type="hidden" class="pilih" 	name="proses" 		value="pilihRayon"/>
<input type="hidden" class="pilih"	name="targetId" 	value="targetRayon"/>
<input type="hidden" class="simpan"	name="appl_tokn" 	value="<?php echo _TOKN; 	?>"/>
<input type="hidden" class="simpan"	name="appl_kode" 	value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="simpan"	name="appl_name" 	value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="simpan"	name="appl_file" 	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="simpan"	name="appl_proc" 	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="simpan"	name="targetUrl" 	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="simpan"	name="targetId" 	value="targetUpdate"/>
<input type="hidden" class="simpan"	name="proses" 		value="updatePemohon"/>
<input type="hidden" class="simpan" name="pem_reg" 		value="<?php echo $pem_reg; ?>"/>
<?php
	if(_HINT==1){
		echo $hint;
	}
?>
<div>
	<div class="span-11 left border">
		<div class="append-bottom span-3">No Register</div>
		<div class="append-bottom span-5">: <?php echo $pem_reg;		?></div>
		<div class="append-bottom span-3">Nama</div>
		<div class="append-bottom span-5">: <?php echo $pem_nama;		?></div>
		<div class="append-bottom span-3">Alamat</div>
		<div class="append-bottom span-7">
			: <textarea class="height-2 span-6" name="pem_alamat" disabled><?php echo $pem_alamat; ?></textarea>
		</div>
		<div class="append-bottom span-3">Unit Pelayanan</div>
		<div class="append-bottom span-5">: <?php echo $kp_ket;			?></div>
		<div class="append-bottom span-3">Rayon</div>
		<div class="append-bottom span-5">: <?php echo $dkd_kd;			?></div>
		<div class="append-bottom span-3">Status</div>
		<div class="append-bottom span-5">: <?php echo $kps_ket;		?></div>
		<div class="append-bottom span-3">Tgl Input</div>
		<div class="append-bottom span-5">: <?php echo $pem_tgl_reg;	?></div>
	</div>
	<div class="span-12 left">
		<div id="targetUpdate" class="span-12"></div>
		<div class="append-bottom span-3">No Register</div>
		<div class="append-bottom span-8">
			: <?php echo $pem_reg; ?>
		</div>
		<div class="append-bottom span-3">Nama</div>
		<div class="append-bottom span-8">
			: <input type="text" class="simpan" name="pem_nama" maxlength="45" value="<?php echo $pem_nama; ?>" disabled />
		</div>
		<div class="append-bottom span-3">Alamat</div>
		<div class="append-bottom span-8">
			: <textarea class="simpan height-2 span-6" name="pem_alamat" disabled><?php echo $pem_alamat; ?></textarea>
		</div>
		<div class="append-bottom span-3">Unit Pelayanan</div>
		<div class="append-bottom span-8">
			: <?php echo pilihan($data0,$parm0); ?>
		</div>
		<div class="append-bottom span-3">Rayon</div>
		<div class="append-bottom span-8" id="targetRayon">
			: <?php echo pilihan($data2,$parm2); ?>
		</div>
		<div class="append-bottom span-3">Status</div>
		<div class="append-bottom span-8">
			: <?php echo pilihan($data3,$parm3); ?>
		</div>
		<div class="span-3">&nbsp;</div>
		<div class="span-8">&nbsp;
			<input accesskey="S" type="button" value="Simpan" onclick="buka('simpan')"/>
			<input id="jumlahForm" type="hidden" value="2" />
			<input id="aktiveForm" type="hidden" value="0" />
		</div>
	</div>
</div>
</div>
</div>
<?php
	}
?>
