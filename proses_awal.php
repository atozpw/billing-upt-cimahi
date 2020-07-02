<?php
	if($erno) die();
	if(isset($proses)){
		/** koneksi ke database */
		try {
			$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $err){
			$mess = $err->getTrace();
			#errorLog::errorDB(array($mess[0]['args'][0]));
			$mess = "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan.";
			$klas = "error";
		}
		
		try {
			$db->beginTransaction();
			$que	= "INSERT tr_trans_log(tr_id,tr_sts,tr_ip,kp_kode,kar_id) VALUES("._TOKN.",3,INET_ATON('"._HOST."'),'"._KOTA."','"._USER."')";
			$st 	= $db->exec($que);
			if($st>0){
				$db->commit();
				#errorLog::logDB(array($que));
				$mess = "Loket pembayaran telah dibuka. Klik <u onclick=\"buka('080301')\">di sini</u> untuk membuka menu Pembayaran Berjalan";
				$klas = "success";
			}
		}
		catch (PDOException $err){
			$db->rollBack();
			$mess = $err->getTrace();
			#errorLog::errorDB(array($que));
			#errorLog::logMess(array($mess[0]['args'][0]));
			$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses buka loket tidak bisa dilakukan.";
			$klas = "error";
		}
		unset($db);
		?><div class="<?php echo $klas; ?>"><?php echo $mess; ?></div><?php
	}
	else{
		$que0	= "SELECT IFNULL(MAX(tr_sts),0) AS tr_sts FROM tr_trans_log WHERE kar_id='"._USER."' AND DATE(getTanggal(tr_id))=CURDATE()";
		try{
			if(!$res0 = $link->query($que0)){
				throw new Exception($link->error);
			}
			else{
				$row0 	= $res0->fetch_array();
				$tr_sts	= $row0['tr_sts'];
				unset($mess);
			}
		}
		catch (Exception $e){
			#errorLog::errorDB(array($que0));
			#errorLog::logMess(array($e->getMessage()));
			$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
		}
		if(!$erno) $link->close();
		
		$stsLoket = true;
		switch($tr_sts){
			case "3":
				$stsLoket 	= false;
				$mess 		= "Loket sudah dibuka. Klik <u onclick=\"buka('080301')\">di sini</u> untuk membuka menu Pembayaran Berjalan";
				break;
			case "4":
				$stsLoket	= false;
				$mess		= "Loket sudah ditutup. Klik <u onclick=\"buka('080303')\">di sini</u> untuk membuka menu Cetak LPP Rinci";
				break;
			default:
		}
?>
<h2><?php echo _NAME; ?> - <?php echo $_SESSION['kp_ket']; ?></h2>
<input type="hidden" class="buka" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="buka" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="buka" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="buka" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="buka"	name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="buka" 	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="buka" 	name="targetId"  	value="bukaLoket"/>
<input type="hidden" class="buka" 	name="proses"  		value="buka"/>
<div id="bukaLoket">
	<table>
		<tr valign="top"> 
			<td colspan="2">
				<p style="padding:6px; font-size:14px;">
					Anda login sebagai <b><?php echo $_SESSION['Name_c']; ?></b><br/>
					Akses dari IP <?php echo _HOST; ?>
				</p>
				<hr/>
			</td>
		</tr>
		<tr valign="top"> 
			<td width="30%" class="form_title">Tanggal Hari Ini</td>
			<td width="70%">:
				<input readonly type="text" name="tanggal" size="15" value="<?php echo date('d-m-Y'); ?>" />
			</td>
		</tr>
		<?php if($stsLoket){ ?>
		<tr>
			<td></td>
			<td align="center">
				<input type="button" value="Proses" onclick="buka('buka')" />
			</td>
		</tr>
		<?php } else{ ?>
		<tr>
			<td colspan="2" class="notice"><?php echo $mess; ?></td>
		</tr>
		<?php } ?>
	</table>
</div>
<?php
	}
?>

