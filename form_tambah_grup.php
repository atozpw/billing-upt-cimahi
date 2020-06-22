<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input type="hidden" id="keyProses0" 	value="1" />
<input type="hidden" id="tutup" 		value="<?php echo $formId; ?>" />
<div class="pesan span-18">
<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<h3>Form Tambah Grup Pengguna</h3>
<hr/>
<div id="<?php echo $targetId; ?>" class="span-18"></div>
<div class="span-8 left">
	<div class="span-2 prepend-top">Grup Kode</div>
	<div class="span-5 prepend-top">:
		<input type="text" size="20" maxlength="7" class="simpan" name="grup_id"/>
	</div>
	<div class="span-2 prepend-top">Grup Nama</div>
	<div class="span-5 prepend-top">:
		<input type="text" size="20" maxlength="30" class="simpan" name="grup_nama"/>
	</div>
	<div class="span-2 prepend-top">&nbsp;</div>
	<div class="span-5 prepend-top">&nbsp;
		<input type="hidden" class="simpan" name="targetId" 	value="<?php echo $targetId;?>"/>
		<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _PROC; 	?>"/>
		<input type="hidden" class="simpan" name="appl_kode" 	value="<?php echo _KODE; 	?>"/>
		<input type="hidden" class="simpan" name="proses"		value="tambahGrup"/>
		<input type="button" class="form_button" value="Simpan" onclick="buka('simpan')"/>
	</div>
</div>
</div>
</div>