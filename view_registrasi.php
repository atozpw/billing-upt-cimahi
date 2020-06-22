<?php
	if($erno) die();
	$kp_ket		= $_SESSION['kp_ket'];
	if(!isset($proses)){
		$proses	= false;
	}
	if(!isset($kode)){
		$kode	= false;
	}
?>
<h3><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h3>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="errorId"   	value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="dkd_kd" 		value="<?php echo $dkd_kd; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="targetId"  	value="content"/>
<input type="hidden" class="tambah" name="errorUrl" value="form_registrasi.php" />
<input type="hidden" class="tambah" name="kp_kode" 	value="<?php echo _KOTA; ?>" />
<?php
	switch($proses){
		case "rinci":
			$que0 	= "SELECT CONCAT(a.pem_reg,IF(ISNULL(d.pel_no),'',CONCAT('/',d.pel_no))) AS pem_reg,a.pem_nama,a.pem_alamat,CONCAT('[',c.dkd_kd,']',' ',IFNULL(c.dkd_jalan,'N/A')) AS dkd_kd,c.dkd_kd AS dkd_feed,b.kps_kode,b.kps_ket,DATE_FORMAT(pem_tgl_reg,'%d-%m-%Y') AS pem_tgl_reg,IFNULL(d.biaya_pasang,0) AS biaya_pasang,d.gol_kode,d.um_kode FROM tm_pemohon_bp a JOIN tr_kondisi_ps b ON(b.kps_kode=a.pem_sts) JOIN tr_dkd c ON(c.dkd_kd=a.dkd_kd) LEFT JOIN tm_rekening_bp d ON(d.pem_reg=a.pem_reg) WHERE a.dkd_kd='$dkd_kd' ORDER BY a.pem_reg DESC LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class='notice'><b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih pemohon, kemudian <b>Space</b> untuk melihat rinciannya. Tekan tombol <b>B</b> untuk kembali ke halaman sebelumnya. <b>Biaya pasang</b> hanya dapat diproses pada pemohon dengan status bisa dilayani.</div>";
			if(_KODE == '051101'){
				$errorUrl	= "form_edit_registrasi.php";
			}
			else{
				$errorUrl	= "form_edit_pelanggan.php";
			}
?>
<input id="keyProses1" type="hidden" value="2"/>
<input type="hidden" class="refresh next_page pref_page" name="proses" 	value="<?php echo $proses;	?>"/>
<?php
			break;
		case "cari":
			if(_KOTA=='00'){
				$filter = "";
			}
			else{
				$filter = "a.kp_kode='"._KOTA."' AND";
			}
			$kode	= strtoupper($kode);
			$que0 	= "SELECT CONCAT(a.pem_reg,IF(ISNULL(d.pel_no),'',CONCAT('/',d.pel_no))) AS pem_reg,a.pem_nama,a.pem_alamat,CONCAT('[',c.dkd_kd,']',' ',IFNULL(c.dkd_jalan,'N/A')) AS dkd_kd,c.dkd_kd AS dkd_feed,b.kps_kode,b.kps_ket,DATE_FORMAT(pem_tgl_reg,'%d-%m-%Y') AS pem_tgl_reg,IFNULL(d.biaya_pasang,0) AS biaya_pasang,d.gol_kode,d.um_kode FROM tm_pemohon_bp a JOIN tr_kondisi_ps b ON(b.kps_kode=a.pem_sts) JOIN tr_dkd c ON(c.dkd_kd=a.dkd_kd) LEFT JOIN tm_rekening_bp d ON(d.pem_reg=a.pem_reg) WHERE $filter (a.pem_reg LIKE '%$kode%' OR a.pem_nama LIKE '%$kode%' OR a.pem_alamat LIKE '%$kode%' OR b.kps_ket LIKE '%$kode%') ORDER BY a.pem_nama DESC LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class='notice'>Tekan tombol <b>B</b> untuk kembali ke halaman sebelumnya</div>";
?>
<input id="keyProses1" type="hidden" value="1" />
<input type="hidden" class="refresh next_page pref_page" name="proses" 	value="<?php echo $proses;	?>"/>
<?php
			$proses	= "rinci";
			break;
		default :
?>
<input id="keyProses1" type="hidden" value="1" />
<?php
			if(_KOTA=='00'){
				$filter = "";
			}
			else{
				$filter = "WHERE kp_kode='"._KOTA."'";
			}
			$que0 	= "SELECT *FROM v_rayon $filter LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class='notice'>Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
	}
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception(mysql_error($link));
		}
		else{
			$i = 0;
			while($row0 = mysql_fetch_array($res0)){
				$data[] = $row0;
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
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
		errorLog::errorDB(array($e->getMessage()));
		errorLog::logMess(array($mess));
		errorLog::logDB(array($que0));
	}
	if(!$erno) mysql_close($link);
?>
<input id="<?php echo $errorId; ?>" type="hidden" value="<?php echo $mess; 		?>"/>
<input type="hidden" class="next_page"	name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" 	name="pg" value="<?php echo $pref_page; ?>"/>
<input type="hidden" class="refresh" 	name="pg" value="<?php echo $pg;		?>"/>
<input type="hidden" class="kembali" 	name="pg" value="<?php echo $back; 		?>"/>
<?php
	if(_HINT==1){
		echo $hint;
	}
	switch($proses){
		case "rinci":
			if(!isset($data)){
				$data	= array();
			}
			if(count($data)>0 or $limit_awal>0){
?>
<input type="hidden" class="refresh next_page pref_page" name="kode" 	value="<?php echo $kode; 	?>"/>
<input type="hidden" class="refresh next_page pref_page" name="back" 	value="<?php echo $back; 	?>"/>
<table class="table_info">
	<tr class="table_cont_btm">
		<td colspan="8"></td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head">
		<td>No</td>
		<td>No Register</td>
		<td>Nama</td>
		<td>Alamat</td>
		<td>Rayon</td>
		<td>Tgl Input</td>
		<td>Biaya Pasang</td>
		<td>Status</td>
		<td>Manage</td>
	</tr>
<?php
	for($i=0;$i<count($data);$i++){
		/** getParam 
			memindahkan semua nilai dalam array ke dalam
			variabel yang bersesuaian dengan masih kunci array
		*/
		$nilai	= $data[$i];
		$konci	= array_keys($nilai);
		for($j=0;$j<count($konci);$j++){
			$$konci[$j]	= $nilai[$konci[$j]];
		}
		/* getParam **/

		$nomer	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$param	= "rinci_".$i." biaya_".$i;
?>
	<tr valign="top" class="<?php echo $klas; ?>">
		<td><?php echo $nomer;			?></td>
		<td><?php echo $pem_reg;		?></td>
		<td><?php echo $pem_nama; 		?></td>
		<td><?php echo $pem_alamat;		?></td>
		<td><?php echo $dkd_kd;			?></td>
		<td><?php echo $pem_tgl_reg;	?></td>
		<td class="right"><?php echo number_format($biaya_pasang);	?></td>
		<td><?php echo $kps_ket;		?></td>
		<td>
			<input type="hidden" class="<?php echo $param; ?>" 		name="appl_kode"	value="<?php echo _KODE;			?>"/>
			<input type="hidden" class="<?php echo $param; ?>" 		name="appl_name"	value="<?php echo _NAME; 			?>"/>
			<input type="hidden" class="<?php echo $param; ?>" 		name="appl_file"	value="<?php echo _FILE; 			?>"/>
			<input type="hidden" class="<?php echo $param; ?>" 		name="appl_proc"	value="<?php echo _PROC; 			?>"/>
			<input type="hidden" class="<?php echo $param; ?>" 		name="appl_tokn" 	value="<?php echo _TOKN; 			?>"/>
			<input type="hidden" class="<?php echo $param; ?>" 		name="errorId"   	value="<?php echo getToken();		?>"/>
			<input type="hidden" class="<?php echo $param; ?>" 		name="pem_reg"   	value="<?php echo $pem_reg;			?>"/>
			<input type="hidden" class="<?php echo $param; ?>" 		name="gol_kode"	   	value="<?php echo $gol_kode;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="pem_nama"   	value="<?php echo $pem_nama;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="pem_alamat"  	value="<?php echo $pem_alamat;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="kp_ket"   	value="<?php echo $kp_ket;			?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="kp_kode"   	value="<?php echo _KOTA;			?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>"	name="dkd_kd"   	value="<?php echo $dkd_feed;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="pem_tgl_reg"	value="<?php echo $pem_tgl_reg;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="kps_kode"   	value="<?php echo $kps_kode;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="kps_ket"   	value="<?php echo $kps_ket;			?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="errorUrl" 	value="<?php echo $errorUrl; 		?>"/>
			<input type="hidden" class="biaya_<?php echo $i; ?>" 	name="biaya_pasang"	value="<?php echo $biaya_pasang;	?>"/>
			<input type="hidden" class="biaya_<?php echo $i; ?>" 	name="errorUrl" 	value="form_input_biaya.php" />
			<input id="form-<?php echo ($i+1); ?>" type="button" value="Detail" onclick="nonghol('rinci_<?php echo $i; ?>')" />
<?php
			if($kps_kode==13){
?>
			<input type="button" value="Biaya" onclick="nonghol('biaya_<?php echo $i; ?>')"/>
<?php
			}
?>
		</td>
	</tr>
<?php

	}
?>
	<tr class="table_cont_btm">
		<td colspan="8" class="left"></td>
		<td class="right">
			&nbsp;<?php echo $pref_mess." ".$kembali." ".$next_mess; ?>
		</td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?php echo $i; ?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
			}
			else{
				echo "<center class=\"success\">Data pencarian ".$kode." tidak ditemukan</center>";
				echo $kembali;
			}
			break;
		default:
?>
<input type="hidden" class="cari" name="proses" value="cari"/>
<input type="hidden" class="cari" name="back" 	value="<?php echo $pg; ?>"/>
<table class="table_info">
	<tr class="table_cont_btm">
		<td colspan="5">
			Pencarian Pemohon:&nbsp;
			<input id="jumlahFind" type="hidden" value="2"/>
			<input id="aktiveFind" type="hidden" value="0"/>
			<input id="find-1" type="text" class="cari next_page pref_page" name="kode" size="10" title="masukan status, nomor sl, nama, atau alamat"/>
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
		/** getParam 
			memindahkan semua nilai dalam array ke dalam
			variabel yang bersesuaian dengan masih kunci array
		*/
		$nilai	= $data[$i];
		$konci	= array_keys($nilai);
		for($j=0;$j<count($konci);$j++){
			$$konci[$j]	= $nilai[$konci[$j]];
		}
		/* getParam **/

		$nomer	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
?>
	<tr valign="top" class="<?php echo $klas; ?>">
		<td><?php echo $nomer;			?></td>
		<td><?php echo $dkd_kd;			?></td>
		<td><?php echo $dkd_tcatat; 	?></td>
		<td><?php echo $dkd_pembaca;	?></td>
		<td><?php echo $dkd_jalan;		?></td>
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
			<input id="form-<?=($i+1)?>" type="button" value="Detail" onclick="buka('rinci_<?php echo $i; ?>')"/>
		</td>
	</tr>

<?php

	}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="left">
			<input type="button" value="Tambah" onclick="nonghol('tambah')" />
		</td>
		<td class="right">
			&nbsp;<?php echo $pref_mess." ".$next_mess; ?>
		</td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?=$i?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
	}
?>
