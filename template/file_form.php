<?php
	if($erno) die();
	$mess = false;
?>
<input id="<?php echo $errorId; ?>" type="hidden" value="<?php echo $mess; ?>"/>
<input id="norefresh" type="hidden" value="1"/>
<input type="hidden" class="simpan" name="appl_tokn"	value="<?php echo _TOKN; ?>"/>
<input type="hidden" class="simpan" name="appl_kode"	value="<?php echo _KODE; ?>"/>
<input type="hidden" class="simpan" name="appl_name"	value="<?php echo _NAME; ?>"/>
<input type="hidden" class="simpan" name="appl_file"	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="simpan" name="appl_proc"	value="<?php echo _PROC; ?>"/>
<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="simpan" name="targetId" 	value="content"/>
<h2><?=_NAME?></h2>
<hr/>
<div class="span-11">
<?php echo $mess; ?>
<div class="span-10 left">
	<div class="span-4 prepend-top">Password Lama</div>
	<div class="span-5 prepend-top">:
		<input type="password" size="20" maxlength="30" class="simpan" name="password_lama"/>
	</div>
	<div class="span-4 prepend-top">Password Baru</div>
	<div class="span-5 prepend-top">:
		<input type="password" size="20" maxlength="30" class="simpan" name="password_baru"/>
	</div>
	<div class="span-4 prepend-top">Konfirmasi</div>
	<div class="span-5 prepend-top">:
		<input type="password" size="20" maxlength="30" class="simpan" name="password_test"/>
	</div>
	<div class="span-4 prepend-top">&nbsp;</div>
	<div class="span-5 prepend-top">&nbsp;
		<input type="button" value="Simpan" onclick="buka('simpan')"/>
	</div>
</div>
</div>
