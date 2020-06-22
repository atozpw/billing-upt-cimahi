<?php
	$i		= 0;
	$que0	= "SELECT a.sm_nomer,ABS(a.pel_no) AS pel_no,b.pel_nama,b.pel_alamat,DAY(a.sm_tgl_baca) AS tgl_baca,a.dkd_no FROM tm_stand_meter a JOIN tm_pelanggan b ON(b.pel_no=a.pel_no AND a.sm_bln=MONTH(NOW()) AND a.sm_thn=YEAR(NOW())) WHERE b.kps_kode<=5 AND a.dkd_kd='".$dkd_kd."';";
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception(mysql_error($link));
		}
		else{
			// Connect to MSSQL
			$msql 	= mssql_connect("192.168.0.29","sa","023456");
			mssql_select_db('MeterReader', $msql);
			$que2	= "DELETE FROM [dbo].[RawDataImportTmp] WHERE [reading_route]='".$dkd_kd."' AND [read_period]=CONVERT(CHAR(6), GETDATE(), 112)";
			if(!$res2 = mssql_query($que2)){
				throw new Exception(mssql_get_last_message());
			}
			else{
				errorLog::logDB(array($que2));
			}
			while($row0 = mysql_fetch_array($res0)){
				$sm_nomer	= $row0['sm_nomer'];
				$pel_no		= $row0['pel_no'];
				$pel_nama	= $row0['pel_nama'];
				$pel_alamat	= $row0['pel_alamat'];
				$tgl_baca	= $row0['tgl_baca'];
				$dkd_no		= $row0['dkd_no'];
				$que1		= "INSERT INTO [dbo].[RawDataImportTmp]([Id],[RowStatus],[apar_id],[apar_name],[reading_day],[reading_route],[reading_sequence],[address],[number_1],[read_period],[CreatedBy],[CreatedDate]) VALUES(NEWID(),0,'".$pel_no."','".$pel_nama."','".$tgl_baca."','".$dkd_kd."','".$dkd_no."','".$pel_alamat."',0,CONVERT(CHAR(6), GETDATE(), 112),'"._USER."',GETDATE())";
				if(!$res1 = mssql_query($que1)){
					throw new Exception(mssql_get_last_message());
				}
				else{
					errorLog::logDB(array($que1));
				}
				$i++;
			}
			$mess 	= $i." SL rayon ".$dkd_kd." telah ditransfer";
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($e->getMessage()));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
	}
	$klas 	= "success";
?>
