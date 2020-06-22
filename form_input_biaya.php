<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
	$errorId 	= getToken();
	$data1		= array();
	$parm1		= array();
	$data4		= array();
	$parm4		= array();

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
	$parm1 = array("class"=>"simpan","id"=>"form-1","name"=>"gol_kode","selected"=>$gol_kode);

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
	$parm4 = array("class"=>"simpan","id"=>"form-2","name"=>"um_kode","selected"=>$um_kode);
?>

<div id="<?php echo $formId; ?>" class="span-12 peringatan">
	<input id="keyProses0" 	type="hidden" value="1"/>
	<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
	<div class="pesan">
	<div class="span-12">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
	<h3>Form Input Biaya</h3>
	<hr/>
	<div id="<?php echo $targetId; ?>" class="span-12">
<?php
	if(_HINT==1){
?>
		<div class="notice">
			Tekan tombol <b>Enter</b> untuk memulai proses input, <b>Alt+S</b> untuk menyimpan dan <b>Esc</b> untuk menutup form ini.
		</div>
<?php
	}
?>
		<div class="append-bottom span-3">Golongan</div>
		<div class="append-bottom span-8">
			: <?php echo pilihan($data1,$parm1); ?>
		</div>
		<div class="append-bottom span-3">Ukuran Meter</div>
		<div class="append-bottom span-8">
			: <?php echo pilihan($data4,$parm4); ?>
		</div>
		<div class="span-3 prepend-top">Biaya Pasang</div>
		<div class="span-8 prepend-top">:
			<input id="form-3" type="text" size="20" class="simpan" name="biaya_pasang" value="<?php echo $biaya_pasang; ?>" onmouseover="this.focus()" />
		</div>
		<div class="span-11 prepend-top">&nbsp;
			<input type="hidden" class="simpan"	name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="simpan"	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
			<input type="hidden" class="simpan"	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="simpan"	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="simpan"	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="simpan" name="targetId" 	value="<?php echo $targetId;	?>"/>
			<input type="hidden" class="simpan" name="errorId" 		value="<?php echo $errorId;		?>"/>
			<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="simpan" name="pem_reg" 		value="<?php echo $pem_reg;		?>"/>
			<input type="hidden" class="simpan" name="proses"		value="inputBiaya" />
			<input id="form-4" type="button" value="Simpan" onclick="buka('simpan')" accesskey="S" />
			<input id="jumlahForm" type="hidden" value="4"/>
			<input id="aktiveForm" type="hidden" value="0"/>
		</div>
	</div>
	</div>
</div>
