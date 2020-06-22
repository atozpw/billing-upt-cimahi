<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
	switch($proses){
		default :
			$data1[] 	= array("appl_sts"=>1,"ket_sts"=>"Disable");
			$data1[] 	= array("appl_sts"=>0,"ket_sts"=>"Enable");
			$parm1 		= array("class"=>"simpan","name"=>"appl_sts","selected"=>1);
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input id="keyProses0" 	type="hidden" value="1"/>
<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
<div class="pesan span-18">
<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<h3>Form Tambah Menu : <?php echo $e_ga_nama; ?></h3>
<hr/>
<div id="<?php echo $targetId; ?>" class="span-18 left">
<?php
	if(_HINT==1){
?>
	<div class="span-17 notice">Tombol <b>Esc</b> untuk menutup form ini.</div>
<?php
	}
?>
</div>
<input type="hidden" class="simpan" 	name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="simpan" 	name="targetId" 	value="<?php echo $targetId;	?>"/>
<input type="hidden" class="simpan" 	name="ga_kode" 		value="<?php echo $e_ga_kode;	?>"/>
<input type="hidden" class="simpan" 	name="proses" 		value="tambahMenu"/>
<div class="span-11">
	<div>
		<div class="span-2 left prepend-top">Urutan</div>
		<div class="span-8 left prepend-top">: <input type="text" size="30" maxlength="3" class="simpan" name="appl_seq"/></div>
	</div>
	<div>
		<div class="span-2 left prepend-top">Nama</div>
		<div class="span-8 left prepend-top">: <input type="text" size="30" maxlength="50" class="simpan" name="nama_appl"/></div>
	</div>
	<div>
		<div class="span-2 left prepend-top">File Aplikasi</div>
		<div class="span-8 left prepend-top">: <input type="text" size="30" maxlength="50" class="simpan" name="file_appl"/></div>
	</div>
	<div>
		<div class="span-2 left prepend-top">File Proses</div>
		<div class="span-8 left prepend-top">: <input type="text" size="30" maxlength="50" class="simpan" name="proc_appl"/></div>
	</div>
	<div>
		<div class="span-2 left prepend-top">Status</div>
		<div class="span-8 left prepend-top">: <?php echo pilihan($data1,$parm1); ?></div>
	</div>
	<div>
		<div class="span-2 left prepend-top">&nbsp;</div>
		<div class="span-8 left prepend-top">&nbsp;
			<input type="button" value="Simpan" onclick="buka('simpan')"/>
		</div>
	</div>
</div>
</div>
</div>
<?php
	}
?>