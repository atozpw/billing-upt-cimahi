<?php
	if($erno) die();
	$kp_ket	= $_SESSION['kp_ket'];
	if(isset($mess)){
		$mess	= "";
	}
	if(!isset($proses)){
		$proses	= false;
	}
	/** update rute */
	if(isset($simpan)){
		/** koneksi ke database */
		$db		= false;
		try {
			$db 	= new PDO($PSPDO[0],$PSPDO[1],$PSPDO[2]);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $err){
			$mess = $err->getTrace();
			errorLog::errorDB(array($mess[0]['args'][0]));
			$mess = "Mungkin telah terjadi kesalahan pada database server, sehingga koneksi tidak bisa dilakukan";
			$klas = "error";
		}
		
		if($db){
			try {
				for($i=1;$i<=count($pel_no);$i++){
					if(strlen($dkd_no_baru[$i])>0){
						$db->beginTransaction();
						if($dkd_no[$i]==$dkd_no_baru[$i]){
							$que	= "UPDATE tm_pelanggan SET dkd_no=(dkd_no+1) where dkd_kd='".$dkd_kd."' AND dkd_no>=".$dkd_no_baru[$i];
						}
						else if($dkd_no[$i]>$dkd_no_baru[$i]){
							$que	= "UPDATE tm_pelanggan SET dkd_no=(dkd_no+1) where dkd_kd='".$dkd_kd."' AND dkd_no>=".$dkd_no_baru[$i]." AND dkd_no<".$dkd_no[$i]."";
						}
						else{
							$que	= "UPDATE tm_pelanggan SET dkd_no=(dkd_no-1) where dkd_kd='".$dkd_kd."' AND dkd_no<=".$dkd_no_baru[$i]." AND dkd_no>".$dkd_no[$i]."";
						}
						$st 	= $db->exec($que);
						errorLog::logDB(array($que));
						$que	= "UPDATE tm_pelanggan SET dkd_no=".$dkd_no_baru[$i]." WHERE pel_no='".$pel_no[$i]."'";
						$st 	= $db->exec($que);
						errorLog::logDB(array($que));
						$db->commit();
						$mess 	= "Urutan pembacaan SL: ".$pel_no[$i]." telah diset ke urutan ".$dkd_no_baru[$i];
						$klas 	= "success";
						$i		= (count($pel_no)+1);
					}
				}
			}
			catch (PDOException $err){
				$db->rollBack();
				$mess = $err->getTrace();
				errorLog::errorDB(array($mess[0]['args'][0]));
				$mess = "Mungkin telah terjadi kesalahan pada prosedur aplikasi, sehingga proses edit rute SL: ".$pel_no[$i]." tidak bisa dilakukan";
				$klas = "error";
			}
		}
	}
?>
<h3><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h3>
<input type="hidden" id="<?php echo $errorId; ?>" value="<?php echo $mess; ?>"/>
<input type="hidden" id="norefresh" value="1"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan" 	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan"	name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan"	name="errorId"   	value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali refresh next_page pref_page"				name="dkd_kd" 		value="<?php echo $dkd_kd; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan"	name="targetId"  	value="content"/>
<input type="hidden" class="next_page"	name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" 	name="pg" value="<?php echo $pref_page; ?>"/>
<input type="hidden" class="simpan" 	name="pg" value="<?php echo $pg;		?>"/>
<input type="hidden" class="refresh" 	name="pg" value="<?php echo $pg;		?>"/>
<input type="hidden" class="kembali" 	name="pg" value="<?php echo $back; 		?>"/>
<?php
	switch($proses){
		case "rinci":
			$que0 	= "SELECT IFNULL(b.ref_no,'00.00.000.000') AS ref_no,a.* FROM tm_pelanggan a LEFT JOIN ref_pelanggan b ON(b.pel_no=a.pel_no) WHERE a.dkd_kd='$dkd_kd' ORDER BY a.dkd_no,a.pel_no DESC LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk memulai entry perubahan urutan, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk melakukan navigasi, kemudian <b>Enter</b> untuk menyimpan urutan, <b>B</b> untuk kembali ke halaman sebelumnya.</div>";
?>
<input id="keyProses1" type="hidden" value="5"/>
<input type="hidden" class="refresh simpan" name="proses"	value="<?php echo $proses; 	?>"/>
<?php
			break;
		case "cari":
			$kode	= strtoupper($kode);
			if(strlen($kode)==6){
				$que0 	= "SELECT IFNULL(b.ref_no,'00.00.000.000') AS ref_no,a.* FROM tm_pelanggan a LEFT JOIN ref_pelanggan b ON(b.pel_no=a.pel_no) WHERE a.kp_kode='"._KOTA."' AND a.pel_no='".$kode."' ORDER BY a.pel_no DESC LIMIT $limit_awal,$jml_perpage";
			}
			else if(strlen($kode)==10){
				$kode	= substr($kode,0,2).".".substr($kode,2,2).".".substr($kode,4,3).".".substr($kode,7,3);
				$que0 	= "SELECT b.ref_no,a.* FROM tm_pelanggan a JOIN ref_pelanggan b ON(b.pel_no=a.pel_no) WHERE a.kp_kode='"._KOTA."' AND b.ref_no='$kode' ORDER BY a.pel_no DESC LIMIT $limit_awal,$jml_perpage";
			}
			else{
				$que0 	= "SELECT IFNULL(b.ref_no,'00.00.000.000') AS ref_no,a.* FROM tm_pelanggan a LEFT JOIN ref_pelanggan b ON(b.pel_no=a.pel_no) WHERE a.kp_kode='"._KOTA."' AND a.pel_no='".$kode."' ORDER BY a.pel_no DESC LIMIT $limit_awal,$jml_perpage";
			}
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk memulai entry perubahan urutan, kemudian <b>Enter</b> untuk menyimpan urutan, <b>B</b> untuk kembali ke halaman sebelumnya.</div>";
?>
<input id="keyProses1" type="hidden" value="4" />
<input type="hidden" class="refresh simpan" name="proses"	value="<?php echo $proses; 	?>"/>
<input type="hidden" class="refresh simpan" name="kode" 	value="<?php echo $kode;	?>"/>
<?php
			$proses	= "rinci";
			break;
		default :
?>
<input id="keyProses1" type="hidden" value="1" />
<?php
			$que0 	= "SELECT *FROM v_rayon WHERE kp_kode='"._KOTA."' LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
	}
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception(mysql_error($link));
		}
		else{
			$i = 0;
			while($row0 = mysql_fetch_array($res0)){
				$data[] = $row0;
				if(!isset($dkd_kd)){
					$dkd_kd = $row0['dkd_kd'];
				}
				$i++;
			}
			/*	pagination : menentukan keberadaan operasi next page	*/
			if($i==$jml_perpage){
				$next_mess	= "<input type=\"button\" value=\">>\" class=\"form_button\" onClick=\"buka('next_page')\"/>";
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($e->getMessage()));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
	}
	
	switch ($proses){
		case "rinci":
			if(_HINT==1){
				echo $hint;
			}
?>
<input type="hidden" class="next_page pref_page" 		name="proses"	value="<?php echo $proses;	?>"/>
<input type="hidden" class="refresh next_page pref_page simpan" name="dkd_kd" 	value="<?php echo $dkd_kd;	?>"/>
<input type="hidden" class="refresh next_page pref_page simpan" name="back" 	value="<?php echo $back;	?>"/>
<input type="hidden" class="simpan" name="simpan" 	value="1"/>
<input type="hidden" class="simpan" name="dump" 	value="0"/>
<table width="100%">
	<tr class="table_cont_btm">
		<td rowspan="2" width="50">No.</td>
		<td rowspan="2" width="150">No. Pelanggan</td>
		<td rowspan="2" width="150">Nama</td>
		<td rowspan="2" width="150">Alamat</td>
		<td colspan="2" width="50" class="center">No Urut</td>
	</tr>
	<tr class="table_cont_btm">
		<td class="center">Lama</td>
		<td class="center">Baru</td>
	</tr>
<?php
			if(!isset($data)){
				$data	= array();
			}
			for($i=1;$i<=count($data);$i++){
				$nomer		= $i+(($pg-1)*$jml_perpage);
				$class_nya 	= "table_cell1";
				if (($i%2) == 0){
					$class_nya ="table_cell2";
				}
				/** getParam 
					memindahkan semua nilai dalam array ke dalam
					variabel yang bersesuaian dengan masih kunci array
				*/
				$nilai	= $data[($i-1)];
				$konci	= array_keys($nilai);
				for($j=0;$j<count($konci);$j++){
					$$konci[$j]	= $nilai[$konci[$j]];
				}
?>
	<tr class="<?php echo $class_nya; ?>">
		<td><?php echo $nomer; ?></td>
		<td><?php echo $pel_no; ?></td>
		<td><?php echo $pel_nama; ?></td>
		<td><?php echo $pel_alamat; ?></td>
		<td class="center"><?php echo $dkd_no; ?></td>
		<td class="center">
			<input type="hidden" class="simpan" name="dkd_no[<?php echo $i; ?>]" value="<?php echo $dkd_no; ?>" />
			<input type="hidden" class="simpan" name="pel_no[<?php echo $i; ?>]" value="<?php echo $pel_no; ?>" />
			<input id="form-<?php echo $i; ?>" type="text" class="simpan" name="dkd_no_baru[<?php echo $i; ?>]" size="3" maxlength="3" onchange="buka('simpan')" />
		</td>
	</tr>
<?php
			}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="left"></td>
		<td class="right"><?php echo $pref_mess." ".$kembali." ".$next_mess; ?></td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?php echo ($i-1); ?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
			break;
		default:
			if(_HINT==1){
				echo $hint;
			}
?>
<input type="hidden" class="cari" name="proses" value="cari"/>
<input type="hidden" class="cari" name="back" 	value="<?php echo $pg; ?>"/>
<table class="table_info">
	<tr class="table_cont_btm">
		<td colspan="5">
			Pencarian Pelanggan :
			<input id="jumlahFind" type="hidden" value="2"/>
			<input id="aktiveFind" type="hidden" value="0"/>
			<input id="find-1" type="text" class="cari next_page pref_page" name="kode" size="6" maxlength="6" title="masukan nomor sl"/>
			<input id="find-2" type="button" value="Periksa" onclick="buka('cari')"/>
		</td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head"> 
		<td>No</td>
		<td>Kode</td>   
		<td>Tgl Catat</td>        
		<td>Nama Petugas</td>
		<td>Jalan</td>
		<td>Manage</td>
	</tr>
<?php
	for($i=0;$i<count($data);$i++){
		$row0 	= $data[$i];
		$nomer	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$dkd_kd 		= $row0['dkd_kd'];
		$dkd_tcatat 	= $row0['dkd_tcatat'];
		$dkd_pembaca 	= $row0['dkd_pembaca'];
		$dkd_jalan		= $row0['dkd_jalan'];
?>
	<tr class="<?php echo $klas; ?>">
		<td><?php echo $nomer;		?></td>
		<td><?php echo $dkd_kd;		?></td>
		<td><?php echo $dkd_tcatat;	?></td>
		<td><?php echo $dkd_pembaca;?></td>
		<td><?php echo $dkd_jalan;	?></td>
		<td>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="back"	 	value="<?php echo $pg; 			?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="targetId" 	value="content"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="proses"	 	value="rinci"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dump"	 	value="0"/>
			<input id="form-<?php echo ($i+1); ?>" type="button" value="Detail" onclick="buka('rinci_<?php echo $i; ?>')"/>
		</td>
	</tr>

<?php

	}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="left">&nbsp;</td>
		<td class="right"><?php echo $pref_mess." ".$next_mess; ?></td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?php echo $i; ?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
	}
	if(!$erno) mysql_close($link);
?>
