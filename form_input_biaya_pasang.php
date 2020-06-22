<?php
	if($erno) die();
	$mess	= false;
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
<input id="<?php echo $errorId; ?>" type="hidden" value="<?php echo $mess; ?>" />
<input id="norefresh" type="hidden" value="1" />
<input id="keyProses1" type="hidden" value="C" />
<input type="hidden" class="simpan" name="appl_tokn"	value="<?php echo _TOKN; ?>"/>
<input type="hidden" class="simpan" name="appl_kode"	value="<?php echo _KODE; ?>"/>
<input type="hidden" class="simpan" name="appl_name"	value="<?php echo _NAME; ?>"/>
<input type="hidden" class="simpan" name="appl_file"	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="simpan" name="appl_proc"	value="<?php echo _PROC; ?>"/>
<input type="hidden" class="simpan" name="targetUrl" 	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="simpan" name="targetId" 	value="content"/>
<h2><?php echo _NAME; ?></h2>
<hr/>
<div class="span-11">
<?php echo $mess; ?>
<div class="span-10 left">
	<div class="span-4 prepend-top">No Register</div>
	<div class="span-5 prepend-top">:
		<input id="form-1" type="text" size="13" maxlength="30" class="simpan" style="font-size:15pt; font-family:courier;" name="pem_reg"/>
	</div>
	<div class="span-4 prepend-top">No Surat</div>
	<div class="span-5 prepend-top">:
		<input id="form-2" type="text" size="13" maxlength="30" class="simpan" style="font-size:15pt; font-family:courier;" name="nomer_surat"/>
	</div>
	<div class="span-4 prepend-top">Biaya Pasang</div>
	<div class="span-5 prepend-top">:
		<input type="text" size="20" maxlength="30" class="simpan noRek" style="font-size:15pt; font-family:courier;" value="1932700" disabled />
	</div>
	<div class="span-4 prepend-top">Biaya Kelebihan</div>
	<div class="span-5 prepend-top">:
		<input id="form-3" type="text" size="20" maxlength="30" class="simpan noRek" style="font-size:15pt; font-family:courier;" name="biaya_kelebihan" onchange="$('form-4').value=1234" />
	</div>
	<div class="span-4 prepend-top">Biaya Sebelum Pajak</div>
	<div class="span-5 prepend-top">:
		<input type="text" size="20" maxlength="30" class="simpan noRek" style="font-size:15pt; font-family:courier;" />
	</div>
	<div class="span-4 prepend-top">PPN</div>
	<div class="span-5 prepend-top">:
		<input type="text" size="20" maxlength="30" class="simpan noRek" style="font-size:15pt; font-family:courier;" disabled />
	</div>
	<div class="span-10">&nbsp;</div>
	<hr />
	<div class="span-4">Biaya Setelah Pajak</div>
	<div class="span-5">:
		<input id="form-4" type="text" size="20" maxlength="30" class="simpan noRek" style="font-size:15pt; font-family:courier;" readonly />
	</div>
	<div class="span-4">&nbsp;</div>
	<div class="span-5 prepend-top">&nbsp;
		<input id="form-5" type="button" value="Simpan" onclick="buka('simpan')"/>
		<input id="aktiveForm" type="hidden" value="0" />
		<input id="jumlahForm" type="hidden" value="5" />
	</div>
</div>
</div>
