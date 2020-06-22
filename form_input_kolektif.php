<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input type="hidden" id="keyProses0" 	value="1" />
<input type="hidden" id="tutup" 		value="<?php echo $formId; ?>" />
<input type="hidden" class="simpan" name="targetId" 	value="<?php echo $targetId;	?>"/>
<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="simpan" name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="simpan" name="errorId" 		value="<?php echo getToken();	?>"/>
<input type="hidden" class="simpan" name="kel_kode" 	value="<?php echo $kel_kode;	?>"/>
<input type="hidden" class="simpan" name="kolektor" 	value="<?php echo $kolektor;	?>"/>
<input type="hidden" class="simpan" name="proses"		value="inputKolektif"/>
<div class="pesan span-18">
<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<h3>Form Tambah Pelanggan Kolektif</h3>
<hr/>
<div id="<?php echo $targetId; ?>" class="span-18"></div>
<div class="span-8 left">
	<div class="span-2 prepend-top">Nomor SL</div>
	<div class="span-5 prepend-top">:
		<input id="<?php echo getToken(); ?>" type="text" size="13" maxlength="6" class="simpan" name="pel_no" onmouseover="$(this.id).select()" />
	</div>
	<div class="span-2 prepend-top">&nbsp;</div>
	<div class="span-5 prepend-top">&nbsp;
		<input type="button" class="form_button" value="Simpan" onclick="buka('simpan')"/>
	</div>
</div>
</div>
</div>