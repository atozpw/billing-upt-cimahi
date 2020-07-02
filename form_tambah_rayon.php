<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
	$errorId 	= getToken();
	$data2		= array();
	$parm2		= array();
	/* inquiry karyawan */
	try{
		$que2 = "SELECT kar_id,kar_nama FROM tm_karyawan WHERE grup_id='002' ORDER BY kar_nama";
		if(!$res2 = $link->query($que2)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row2 = $res2->fetch_array()){
				$data2[] = array("kar_id"=>$row2['kar_id'],"kar_nama"=>$row2['kar_nama']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que2));
		$mess = $e->getMessage();
		$erno = false;
	}
	if(!isset($kar_id)){
		$kar_id	= "";
	}
	$parm2 = array("class"=>"simpan","id"=>"rayn-3","name"=>"dkd_pembaca","selected"=>$kar_id);
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input id="keyProses0" 	type="hidden" value="3"/>
<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
<div class="pesan">
<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<h3>Form Tambah Data Rayon</h3>
<hr/>
<div id="<?php echo $targetId; ?>" class="span-18 left">
<?php
	if(_HINT==1){
?>
	<div class="notice left">Tekan tombol <b>Enter</b> untuk memulai proses input data, <b>Alt+S</b> untuk menyimpan dan <b>Esc</b> untuk menutup form ini.</div>
<?php
	}
?>
	<div class="span-2 prepend-top">Rute</div>
	<div class="span-15 prepend-top">:&nbsp;
		<input id="rayn-1" type="text" size="20" maxlength="6" class="simpan" name="dkd_kd" onmouseover="$('dkd_rayon').focus()" />
	</div>
	<div class="span-2 prepend-top">Jalan/Lokasi</div>
	<div class="span-15 prepend-top">:&nbsp;
		<textarea id="rayn-2" class="simpan height-2" name="dkd_jalan"></textarea>
	</div>
	<div class="span-2 prepend-top">Pembaca</div>
	<div class="span-15 prepend-top">:&nbsp;<?php echo pilihan($data2,$parm2); ?></div>
	<div class="span-2 prepend-top">Tgl. Catat</div>
	<div class="span-15 prepend-top">:&nbsp;
		<input id="rayn-4" type="text" maxlength="2" size="20" class="simpan" name="dkd_tcatat" onmouseover="$('dkd_tcatat').focus()" />
	</div>
	<div class="span-2 prepend-top">&nbsp;</div>
	<div class="span-15 prepend-top">&nbsp;
		<input type="hidden" class="simpan" name="targetId" 	value="<?php echo $targetId;?>"/>
		<input type="hidden" class="simpan" name="errorId" 		value="<?php echo $errorId;	?>"/>
		<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _PROC; 	?>"/>
		<input type="hidden" class="simpan" name="proses"		value="addRayon"/>
		<input type="button" value="Simpan" onclick="buka('simpan')" accesskey="S" />
		<input id="jumlahRayn" type="hidden" value="4"/>
		<input id="aktiveRayn" type="hidden" value="0"/>
	</div>
</div>
</div>
</div>
