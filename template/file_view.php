<?php
	if($erno) die();
	$mess = "Kode aplikasi "._KODE;
?>
<input id="<?php echo $errorId; ?>" type="hidden" value="<?php echo $mess; ?>"/>
<input id="norefresh" type="hidden" value="1"/>
<input type="hidden" class="next_page pref_page kembali cari tambah" name="appl_tokn"	value="<?php echo _TOKN; ?>"/>
<input type="hidden" class="next_page pref_page kembali cari tambah" name="appl_kode"	value="<?php echo _KODE; ?>"/>
<input type="hidden" class="next_page pref_page kembali cari tambah" name="appl_name"	value="<?php echo _NAME; ?>"/>
<input type="hidden" class="next_page pref_page kembali cari tambah" name="appl_file"	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="next_page pref_page kembali cari tambah" name="appl_proc"	value="<?php echo _PROC; ?>"/>
<input type="hidden" class="next_page pref_page kembali cari tambah" name="targetUrl" 	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="next_page pref_page kembali cari tambah" name="targetId"	value="cont"/>