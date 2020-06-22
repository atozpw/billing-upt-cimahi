<?php
	if($erno) die();
?>
<h3><?php echo _NAME; ?></h3>
<hr/>
<input id="norefresh" type="hidden" value="1"/>
<input type="hidden" class="proses" name="appl_tokn"	value="<?php echo _TOKN; ?>"/>
<input type="hidden" class="proses" name="appl_kode"	value="<?php echo _KODE; ?>"/>
<input type="hidden" class="proses" name="appl_name"	value="<?php echo _NAME; ?>"/>
<input type="hidden" class="proses" name="appl_file"	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="proses" name="appl_proc"	value="<?php echo _PROC; ?>"/>
<input type="hidden" class="proses" name="targetUrl" 	value="<?php echo _PROC; ?>"/>
<input type="hidden" class="proses" name="targetId"		value="targetId"/>
<div id="targetId">
<div class="success">Klik tombol Reset jika informasi yang terdapat pada Dashboard tidak sesuai dengan transaksi yang terjadi sebenarnya.</div>
<input type="button" value="Reset" onclick="buka('proses')" />
</div>