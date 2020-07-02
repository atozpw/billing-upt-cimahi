<?php
	if($erno) die();
	$noQue	= false;
	switch($proses){
		case "simpanAppl":
			$note	= true;
			if(!isset($pilihan)){
				$pilihan 	= 0;
			}
			$que0 	= "CALL p_tambah_appl('$grup_id','$grup_nama','$kode','$nama','$pilihan',@mess)";
			$que1 	= "SELECT @mess AS mess";
			break;
		case "hapusGrup":
			$note	= true;
			$que0 	= "CALL p_hapus_grup('$grup_id','$grup_nama',@mess)";
			$que1 	= "SELECT @mess AS mess";
			unset($proses);
			break;
		case "tambahGrup":
			$note	= true;
			$que0 	= "CALL p_tambah_grup('$grup_id','$grup_nama',@mess)";
			$que1 	= "SELECT @mess AS mess";
			break;
		case "editGrup":
			$note	= true;
			$que0 	= "CALL p_edit_grup('$grup_id','$grup_nama',@mess)";
			$que1 	= "SELECT @mess AS mess";
			break;
		default :
			$noQue	= true;
	}
	/* eksekusi prosedure*/
	if(!$noQue){
		/* proc : link tulis */
		$mess 	= "user : ".$PUSER." tidak bisa terhubung ke server : ".$PHOST;
		$proc 	= mysqli_connect($PHOST,$PUSER,$PPASS,$PNAME) or die(errorLog::errorDie(array($mess)));
		try{
			if(!$proc){
				throw new Exception("user : ".$PUSER." tidak bisa terhubung ke database : ".$PNAME);
			}
		}
		catch (Exception $e){
			errorLog::errorDB(array($e->getMessage()));
			$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			$klas = "error";
		}
		try{
			if(!$res0 = $proc->query($que0)){
				throw new Exception($proc->error);
			}
			else{
				errorLog::logDB(array($que0));
			}
			if(!$res1 = $proc->query($que1)){
				throw new Exception($que1);
			}
			else{
				$row1 = $res1->fetch_array();
				if(!$mess = $row1['mess']){
					$mess = false;
				}
				$klas = "notice";
			}
		}
		catch (Exception $e){
			errorLog::errorDB(array($e->getMessage()));
			$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			$klas = "error";
		}
		echo "<input type=\"hidden\" id=\"$errorId\" value=\"$mess\"/>";
		if($note)
			echo "<fieldset class=\"$klas\">$mess</fieldset>";
			$proc->close();
		errorLog::logMess(array($mess));
	}
?>