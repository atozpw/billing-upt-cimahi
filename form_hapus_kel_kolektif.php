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
<h3>Form Hapus Kelompok Kolektif</h3>
<hr/>
<div id="<?php echo $targetId; ?>" class="span-18 left">
	<div class="span-2 prepend-top">Kode</div>
	<div class="span-15 prepend-top">: <?php echo $kel_nama; ?></div>
	<div class="span-2 prepend-top">Kolektor</div>
	<div class="span-15 prepend-top">: <?php echo $kolektor; ?></div>
	<div class="span-2 prepend-top">Keterangan</div>
	<div class="span-15 prepend-top">: <?php echo $kel_ket; ?></div>
	<div class="span-2 prepend-top">&nbsp;</div>
	<div class="span-15 prepend-top">&nbsp;
		<input type="hidden" class="simpan" name="targetId" 	value="<?php echo $targetId;?>"/>
		<input type="hidden" class="simpan" name="errorId" 		value="<?php echo $errorId;	?>"/>
		<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _PROC; 	?>"/>
		<input type="hidden" class="simpan" name="appl_kode" 	value="<?php echo _KODE; 	?>"/>
		<input type="hidden" class="simpan" name="kel_kode"		value="<?php echo $kel_kode;?>"/>
		<input type="hidden" class="simpan" name="kolektor" 		value="<?php echo $kolektor ; ?>" />
		<input type="hidden" class="simpan" name="proses"		value="hapusKelKolektif"/>
		<input type="button" class="form_button" value="Hapus" onclick="buka('simpan')"/>
	</div>
</div>
</div>
</div>