<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
	$errorId 	= getToken();
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input type="hidden" id="keyProses0" 	value="1" />
<input type="hidden" id="tutup" 		value="<?php echo $formId; ?>" />
<div class="pesan span-18">
<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<h3>Form Hapus Grup Pengguna</h3>
<hr/>
<div id="<?php echo $targetId; ?>" class="span-18 left">
	<div class="span-2 prepend-top">Grup Kode</div>
	<div class="span-5 prepend-top">: <?php echo $grup_id; ?></div>
	<div class="span-2 prepend-top">Grup Nama</div>
	<div class="span-5 prepend-top">: <?php echo $grup_nama; ?></div>
	<div class="span-2 prepend-top">&nbsp;</div>
	<div class="span-5 prepend-top">&nbsp;
		<input type="hidden" class="simpan" name="targetId" 	value="<?php echo $targetId;	?>"/>
		<input type="hidden" class="simpan" name="errorId" 		value="<?php echo $errorId;		?>"/>
		<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
		<input type="hidden" class="simpan" name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
		<input type="hidden" class="simpan" name="appl_name" 	value="<?php echo _NAME;		?>"/>
		<input type="hidden" class="simpan" name="appl_file" 	value="<?php echo _FILE;		?>"/>
		<input type="hidden" class="simpan" name="appl_proc" 	value="<?php echo _PROC;		?>"/>
		<input type="hidden" class="simpan" name="grup_id" 		value="<?php echo $grup_id;		?>"/>
		<input type="hidden" class="simpan" name="grup_nama" 	value="<?php echo $grup_nama;	?>"/>
		<input type="hidden" class="simpan" name="proses"		value="hapusGrup"/>
		<input type="button" class="form_button" value="Hapus" onclick="buka('simpan')"/>
	</div>
</div>
</div>
</div>