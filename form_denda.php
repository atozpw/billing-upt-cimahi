<?php
	if($erno) die();
	$que0 = "SELECT sys_value FROM system_parameter WHERE sys_param='DENDA'";
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception(mysql_error($link));
		}
		else{
			$row0 = mysql_fetch_array($res0);
			$tgl_denda = $row0['sys_value'];
		}
	}
	catch (Exception $e){
		errorLog::legMess(array($e->getMessage()));
		errorLog::errorDB(array($que0));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
	}
?>
<h2><?php echo _NAME; ?></h2>
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
Tanggal Jatuh Tempo : <input type="text" size="2" maxlength="2" id="tgl_denda" name="tgl_denda" class="proses right" value="<?php echo $tgl_denda; ?>" onmouseover="$(this.id).select()" />
<input type="button" onclick="buka('proses')" value="SET"/>
</div>