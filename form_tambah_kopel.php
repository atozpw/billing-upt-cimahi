<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
	$errorId 	= getToken();
	$cab_kode	= "01";
	
	/* inquiry cabang */
	//$filtered	= "WHERE cab_kode='00'";
	$filtered = "";
	try{
		$que3 = "SELECT cab_kode,CONCAT('[',cab_kode,'] ',cab_ket) AS cab_ket FROM tr_cabang $filtered ORDER BY cab_kode ASC";
		if(!$res3 = mysql_query($que3,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row3 = mysql_fetch_array($res3)){
				$data3[] = array("cab_kode"=>$row3['cab_kode'],"cab_ket"=>$row3['cab_ket']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que3));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm3 		= array("class"=>"simpan","id"=>"rayn-1","name"=>"cab_kode","selected"=>$cab_kode);
?>
<div id="<?php echo $formId; ?>" class="peringatan">
	<input id="keyProses0" 	type="hidden" value="1"/>
	<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
	<div class="pesan span-18">
		<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
		<h3>Form Edit Data Unit Pelayanan</h3>
		<hr/>
		<div id="<?php echo $targetId; ?>">
<?php
			if(_HINT==1){
?>
			<div class="notice left">Tekan tombol <b>Enter</b> untuk memulai proses entryan, <b>Alt+S</b> untuk menyimpan dan <b>Delete</b> untuk menghapus.</div>
<?php
			}
?>
		</div>
		<div class="span-9 left">
			<div class="span-2 prepend-top">Cabang</div>
			<div class="span-6 prepend-top">: <?=pilihan($data3,$parm3)?></div>
			<div class="span-2 prepend-top">Kode</div>
			<div class="span-5 prepend-top">:
				<input id="rayn-2" type="text" maxlength="2" size="20" class="simpan" name="kp_kode" onmouseover="$(this.id).select()" />
			</div>
			<div class="span-2 prepend-top">Inisial</div>
			<div class="span-5 prepend-top">:
				<input id="rayn-3" type="text" maxlength="4" size="20" class="simpan" name="kp_nama" onmouseover="$(this.id).select()" />
			</div>
			<div class="span-2 prepend-top">Keterangan</div>
			<div class="span-5 prepend-top">:
				<input id="rayn-4" type="text" maxlength="50" size="20" class="simpan" name="kp_ket" onmouseover="$(this.id).select()" />
			</div>
			<div class="span-2 prepend-top">&nbsp;</div>
			<div class="span-5 prepend-top">&nbsp;
				<input type="hidden" class="simpan" 	name="targetId" 	value="<?php echo $targetId;?>"/>
				<input type="hidden" class="simpan" 	name="errorId" 		value="<?php echo $errorId;	?>"/>
				<input type="hidden" class="simpan" 	name="targetUrl"	value="<?php echo _PROC; 	?>"/>
				<input type="hidden" class="simpan" 	name="dump"			value="0"/>
				<input type="hidden" class="simpan" 	name="proses"		value="addRayon"/>
				<input type="button" value="simpan" onclick="buka('simpan')" accesskey="S" />
				<input id="jumlahRayn" type="hidden" value="4"/>
				<input id="aktiveRayn" type="hidden" value="0"/>
			</div>
		</div>
	</div>
</div>