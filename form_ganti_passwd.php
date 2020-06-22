<?php
	if($erno) die();
	$mess	= "";
	if(isset($password_baru)){
		if($password_baru==$password_test){
			/** koneksi ke database */
			try {
				$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch (PDOException $err){
				$mess = $err->getTrace();
				errorLog::errorDB(array($mess[0]['args'][0]));
				$mess = "<div class=\"notice span-10\">Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan.</div>";
				$klas = "error";
			}
			
			try {
				$db->beginTransaction();
				$que	= "UPDATE tm_karyawan SET kar_pass=MD5('$password_baru') WHERE kar_id='"._USER."' AND kar_pass=MD5('$password_lama')";
				$st 	= $db->exec($que);
				if($st>0){
					$db->commit();
					errorLog::logDB(array($que));
					$mess = "<div class=\"notice span-10\">Password telah berhasil diperbaharui.</div>";
					$klas = "success";
				}
				else{
					$mess = "<div class=\"notice span-10\">Password lama anda salah.</div>";
				}
			}
			catch (PDOException $err){
				$db->rollBack();
				$mess = $err->getTrace();
				errorLog::errorDB(array($que));
				errorLog::logMess(array($mess[0]['args'][0]));
				$mess = "<div class=\"notice span-10\">Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses buka loket tidak bisa dilakukan.</div>";
				$klas = "notice";
			}
			unset($db);
		}
		else{
			$mess = "<div class=\"notice span-10\">Konfirmasi password tidak sesuai dengan password baru anda.</div>";
		}
	}
?>
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
		<input type="hidden" class="simpan" name="targetId" 	value="content"/>
		<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _PROC; 	?>"/>
		<input type="hidden" class="simpan" name="appl_proc" 	value="<?php echo _PROC; 	?>"/>
		<input type="hidden" class="simpan" name="appl_file" 	value="<?php echo _FILE; 	?>"/>
		<input type="hidden" class="simpan" name="appl_kode" 	value="<?php echo _KODE; 	?>"/>
		<input type="hidden" class="simpan" name="appl_name" 	value="<?php echo _NAME; 	?>"/>
		<input type="button" value="Simpan" onclick="buka('simpan')"/>
	</div>
</div>
</div>