<?php
	include "fungsi.php";
	$formId = getToken();
?>
<div id="<?php echo $formId; ?>" class="peringatan">
	<input id="keyProses1" 	type="hidden" value="B"/>
	<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
	<div class="prepend-top append-5 prepend-5 middle">
		<div class="prepend-top pesan height-2">
			<?php echo $_POST['pesan']; ?>
			<br/><a onclick="tutup('<?php echo $formId; ?>')">[Tutup]</a>
		</div>
	</div>
</div>
