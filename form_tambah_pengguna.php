<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
	if(!isset($usr_id)){
		$usr_id	= "";
	}
	if(!isset($usr_nama)){
		$usr_nama	= "";
	}
	if(!isset($grup_id)){
		$grup_id	= "";
	}
	
	/* retrieve data pdam */
	try{
		$que1 = "SELECT kp_kode,kp_ket AS kp_nama FROM tr_kota_pelayanan ORDER BY kp_kode";
		if(!$res1 = mysql_query($que1,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			$data1[] = array("kp_kode"=>"-","kp_nama"=>"-");
			while($row1 = mysql_fetch_array($res1)){
				$data1[] = array("kp_kode"=>$row1['kp_kode'],"kp_nama"=>$row1['kp_nama']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que1));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm1 = array("class"=>"simpan","name"=>"kp_kode");

	/* retrieve data grup */
	try{
		$que2 = "SELECT *FROM tm_group";
		if(!$res2 = mysql_query($que2,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			$data2[] = array("grup_id"=>"-","grup_nama"=>"-");
			while($row2 = mysql_fetch_array($res2)){
				$data2[] = array("grup_id"=>$row2['grup_id'],"grup_nama"=>$row2['grup_nama']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que2));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm2 = array("class"=>"simpan","name"=>"grup_id","selected"=>$grup_id);
	
	if(!$erno) mysql_close($link);
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input type="hidden" id="keyProses0" 	value="1" />
<input type="hidden" id="tutup" 		value="<?php echo $formId; ?>" />
<input type="hidden" class="simpan"	name="proses" 		value="tambah"/>
<input type="hidden" class="simpan"	name="targetUrl"	value="<?php echo _PROC;		?>"/>
<input type="hidden" class="simpan"	name="appl_kode"	value="<?php echo _KODE;		?>"/>
<input type="hidden" class="simpan"	name="appl_name"	value="<?php echo _NAME;		?>"/>
<input type="hidden" class="simpan"	name="appl_file"	value="<?php echo _FILE;		?>"/>
<input type="hidden" class="simpan"	name="appl_proc"	value="<?php echo _PROC;		?>"/>
<input type="hidden" class="simpan"	name="targetId" 	value="<?php echo $targetId;	?>"/>
<div class="pesan span-18">
<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<h3>Form Tambah Pengguna</h3>
<hr/>
<div id="<?php echo $targetId; ?>" class="span-18"></div>
<div class="span-11">
	<div>
		<div class="span-2 left">ID</div>
		<div class="span-8 left">: <input type="text" size="10" maxlength="8" class="simpan" name="usr_id" value="<?php echo $usr_id; ?>"/></div>
	</div>
	<div>
		<div class="span-2 left prepend-top">Nama</div>
		<div class="span-8 left prepend-top">: <input type="text" size="20" maxlength="20" class="simpan" name="usr_nama" value="<?php echo $usr_nama; ?>"/></div>
	</div>
	<div>
		<div class="span-2 left prepend-top">Grup</div>
		<div class="span-8 left prepend-top">: <?php echo pilihan($data2,$parm2); ?></div>
	</div>
	<div>
		<div class="span-2 left prepend-top">Unit</div>
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