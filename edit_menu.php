<?php
	if($erno) die();
	$noQue	= false;
	if(!isset($errorId)){
		$errorId = "";
	}
	switch($proses){
		case "tambahMenu":
			$note	= true;
			$que0 	= "CALL p_tambah_menu('$appl_seq','$ga_kode','$file_appl','$proc_appl','$nama_appl','$appl_sts',@mess)";
			$que1 	= "SELECT @mess AS mess";
			$file 	= "template/file_view.php";
			if(!file_exists($file_appl) and strlen($file_appl)>4){
				copy($file, $file_appl);
			}
			break;
		case "editMenu":
			$note	= true;
			$que0 	= "CALL p_edit_menu('$kode_appl','$appl_seq','$nama_appl','$appl_sts',@mess)";
			$que1 	= "SELECT @mess AS mess";
			break;
		default :
			$noQue	= true;
	}
	/* eksekusi prosedure*/
	if(!$noQue){
		/* proc : link tulis */
		$mess 	= "user : ".$PUSER." tidak bisa terhubung ke server : ".$PHOST;
		$proc 	= mysql_connect($PHOST,$PUSER,$PPASS) or die(errorLog::errorDie(array($mess)));
		try{
			if(!mysql_select_db($PNAME,$proc)){
				throw new Exception("user : ".$PUSER." tidak bisa terhubung ke database : ".$PNAME);
			}
		}
		catch (Exception $e){
			errorLog::errorDB(array($e->getMessage()));
			$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			$klas = "error";
		}
		try{
			if(!$res0 = mysql_query($que0,$proc)){
				throw new Exception(mysql_error($proc));
			}
			else{
				errorLog::logDB(array($que0));
			}
			if(!$res1 = mysql_query($que1,$proc)){
				throw new Exception($que1);
			}
			else{
				$row1 = mysql_fetch_array($res1);
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
			echo "<div class=\"$klas left\">$mess</div>";
		mysql_close($proc);
		errorLog::logMess(array($mess));
	}
?>