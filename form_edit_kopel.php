<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
	$errorId 	= getToken();
	$filtered	= "";
	
	/* inquiry cabang */
	try{
		$que3 = "SELECT CONCAT(cab_kode,'_',cab_ket) AS kopel,CONCAT('[',cab_kode,'] ',cab_ket) AS cab_ket FROM tr_cabang $filtered ORDER BY cab_kode ASC";
		if(!$res3 = mysql_query($que3,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row3 = mysql_fetch_array($res3)){
				$data3[] = array("kopel"=>$row3['kopel'],"cab_ket"=>$row3['cab_ket']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que3));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm3 		= array("class"=>"simpan","id"=>"rayn-1","name"=>"kopel","selected"=>$kopel);
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
			<div class="span-5 prepend-top">: <?=pilihan($data3,$parm3)?></div>
			<div class="span-2 prepend-top">Inisial</div>
			<div class="span-5 prepend-top">:
				<input id="rayn-2" type="text" maxlength="4" size="20" class="simpan" name="kp_nama" value="<?php echo $kp_nama; ?>" onmouseover="$(this.id).select()" />
			</div>
			<div class="span-2 prepend-top">Keterangan</div>
			<div class="span-5 prepend-top">:
				<input id="rayn-3" type="text" maxlength="50" size="20" class="simpan delete" name="kp_ket" value="<?php echo $kp_ket; ?>" onmouseover="$(this.id).select()" />
			</div>
			<div class="span-2 prepend-top">&nbsp;</div>
			<div class="span-5 prepend-top">&nbsp;
				<input type="hidden" class="simpan delete" 	name="targetId" 	value="<?php echo $targetId;?>"/>
				<input type="hidden" class="simpan delete" 	name="errorId" 		value="<?php echo $errorId;	?>"/>
				<input type="hidden" class="simpan delete" 	name="kp_kode" 		value="<?php echo $kp_kode;	?>"/>
				<input type="hidden" class="simpan delete" 	name="targetUrl"	value="<?php echo _PROC; 	?>"/>
				<input type="hidden" class="simpan delete" 	name="dump"			value="0"/>
				<input type="hidden" class="simpan" 		name="proses"		value="editRayon"/>
				<input type="hidden" class="delete" 		name="proses"		value="deleteRayon"/>
				<input type="button" value="Simpan" onclick="buka('simpan')" accesskey="S" />
				<input type="button" value="Delete" onclick="buka('delete')"/>
				<input id="jumlahRayn" type="hidden" value="3"/>
				<input id="aktiveRayn" type="hidden" value="0"/>
			</div>
		</div>
	</div>
</div>