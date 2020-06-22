<?php
	if($erno) die();
	$kp_kode 	= $_SESSION['Kota_c'];
?>
<h2 class="cetak"><?php echo _NAME; ?> - <?php echo $_SESSION['kp_ket']; ?></h2>
<?php
	switch($proses){
		case "rinci":
?>
<input type="hidden" id="keyProses1" value="9" />
<?php
			$que0 	= "SELECT a.pel_no,a.pel_nama,a.pel_alamat,c.kps_kode,c.kps_ket FROM tm_kolektif b JOIN tm_pelanggan a ON(a.pel_no=b.pel_no) JOIN tr_kondisi_ps c ON(a.kps_kode=c.kps_kode) WHERE b.kel_kode='$kel_kode' ORDER BY b.pel_no LIMIT $limit_awal,$jml_perpage";
			break;
		case "cari":
?>
<input type="hidden" id="keyProses1" value="9" />
<input type="hidden" class="kembali" 						name="pg" 		value="<?php echo $back; 	?>"/>
<input type="hidden" class="refresh next_page pref_page" 	name="back" 	value="<?php echo $back; 	?>"/>
<input type="hidden" class="refresh next_page pref_page" 	name="proses" 	value="<?php echo $proses; 	?>"/>
<?php
			$kode	= strtoupper($kode);
			$que0 	= "SELECT a.kel_kode,SUBSTR(a.kel_nama,-2) AS kel_nama,a.kel_ket,a.kel_kolektor AS kolektor,SUM(IFNULL(b.kel_sts,0)) AS kel_jumlah FROM tr_kel_kolektif a LEFT JOIN tm_kolektif b ON(b.kel_kode=a.kel_kode) WHERE SUBSTR(a.kel_nama,1,2)='$kp_kode' AND (SUBSTR(a.kel_nama,3,2)='$kode' OR UPPER(a.kel_kolektor) LIKE '%$kode%') GROUP BY a.kel_kode ORDER BY a.kel_nama LIMIT $limit_awal,$jml_perpage";
			unset($proses);
			break;
		default :
?>
<input id="keyProses1" type="hidden" value="8" />
<?php
			$que0 	= "SELECT a.kel_kode,SUBSTR(a.kel_nama,-2) AS kel_nama,a.kel_ket,a.kel_kolektor AS kolektor,SUM(IFNULL(b.kel_sts,0)) AS kel_jumlah FROM tr_kel_kolektif a LEFT JOIN tm_kolektif b ON(b.kel_kode=a.kel_kode) WHERE SUBSTR(a.kel_nama,1,2)='$kp_kode' GROUP BY a.kel_kode ORDER BY a.kel_nama LIMIT $limit_awal,$jml_perpage";
	}
	
	/** inquiry data */
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception($que0);
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
		errorLog::errorDB(array($que0));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
	}
	
	if(!$erno) mysql_close($link);
?>
<input type="hidden" id="<?php echo $errorId; ?>" value="<?php echo $mess; ?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page tambah" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="errorId"   	value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 			name="targetId"  	value="content"/>
<input type="hidden" class="next_page" 	name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" 	name="pg" value="<?php echo $pref_page; ?>"/>
<input type="hidden" class="refresh" 	name="pg" value="<?php echo $pg;		?>"/>
<?php
	switch($proses){
		case "rinci":
?>
<input type="hidden" class="kembali" 									name="pg" 		value="<?php echo $back; 	?>"/>
<input type="hidden" class="refresh next_page pref_page" 				name="back" 	value="<?php echo $back; 	?>"/>
<input type="hidden" class="refresh next_page pref_page" 				name="proses" 	value="<?php echo $proses; 	?>"/>
<input type="hidden" class="refresh next_page pref_page tambah cetak" 	name="kel_kode"	value="<?php echo $kel_kode;?>"/>
<input type="hidden" class="refresh next_page pref_page tambah cetak" 	name="kel_nama"	value="<?php echo $kel_nama;?>"/>
<input type="hidden" class="refresh next_page pref_page tambah cetak" 	name="kolektor"	value="<?php echo $kolektor;?>"/>
<input type="hidden" class="refresh next_page pref_page tambah cetak" 	name="kel_ket"	value="<?php echo $kel_ket;	?>"/>
<input type="hidden" class="tambah" name="errorUrl"	value="form_input_kolektif.php" />
<input type="hidden" class="cetak" 	name="errorUrl" value="cetak_kolektif.php" />
<input type="hidden" class="cetak" 	name="targetId" value="cetakId" />
<input type="hidden" class="hapus_<?=$i?>" 	name="errorUrl" value="form_hapus_kolektif.php" />
<div id="cetakId"></div>
<table class="table_info cetak">
	<tr class="table_cont_btm">
		<td colspan="2" class="left">Kelompok : <?php echo $kel_nama; ?></td>
		<td colspan="3" class="left">Kolektor : <?php echo $kolektor; ?></td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head">
		<td>No</td>
		<td>Nomor SR</td>
		<td>Nama</td>
		<td>Alamat</td>
		<td>Status</td>
		<td>Atur</td>
	</tr>
<?php
	for($i=0;$i<count($data);$i++){
		$nomor	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		
		/** getParam 
			memindahkan semua nilai dalam array POST ke dalam
			variabel yang bersesuaian dengan masih kunci array
		*/
		$nilai	= $data[$i];
		$konci	= array_keys($nilai);
		for($j=0;$j<count($konci);$j++){
			$$konci[$j]	= $nilai[$konci[$j]];
		}
		/* getParam **/
		$comm	= "cetak_$i hapus_$i";
?>
	<tr class="<?php echo $klas; ?>">
		<td><?php echo $nomor;		?></td>
		<td><?php echo $pel_no;		?></td>
		<td><?php echo $pel_nama; 	?></td>
		<td><?php echo $pel_alamat;	?></td>
		<td><?php echo $kps_ket;	?></td>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="kel_kode"		value="<?php echo $kel_kode;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="pel_no"		value="<?php echo $pel_no;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="pel_nama"		value="<?php echo $pel_nama;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="pel_alamat"	value="<?php echo $pel_alamat;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>"		name="kps_ket"		value="<?php echo $kps_ket; ?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="hapus_<?php echo $i; ?>" 	name="errorUrl" value="form_hapus_kolektif.php"/>
			<td><img src="images/delete.gif"  border="0" title="Hapus Kolektif" onClick="nonghol('hapus_<?=$i;?>')"/></td>
	</tr>
<?php
	}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="left">
			<?php echo $tambah; ?>
			<input type="button" value="Cetak" onclick="nonghol('cetak')" />
		</td>
		<td class="right">
			&nbsp;<?php echo $pref_mess." ".$kembali." ".$next_mess; ?>
		</td>
	</tr>
</table>
<?php
			break;
		default:
?>
<input type="hidden" id="aktiveFind" value="0" />
<input type="hidden" id="jumlahFind" value="2" />
<input type="hidden" class="tambah" name="errorUrl"	value="form_tambah_kolektif.php" />
<div id="cetakId"></div>
<table class="table_info cetak">
	<tr class="table_cont_btm">
		<td colspan="5">
			Pencarian Kelompok :
			<input type="text" id="find-1" class="cari" name="kode" size="20" maxlength="20" title="masukan nomor atau nama kolektor" onmouseover="$(this.id).select()" value="<?=$kode?>" />
			<input type="hidden" class="cari" name="proses" value="cari"/>
			<input type="hidden" class="cari" name="back" 	value="<?php echo $pg; ?>"/>
			<input type="button" id="find-2" value="Periksa" onclick="buka('cari')"/>
		</td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head"> 
		<td>No</td>
		<td>Kode</td>
		<td>Keterangan</td>     
		<td>Kolektor</td>
		<td>Jumlah SR</td>
		<td>Detail</td>
	</tr>
<?php
	for($i=0;$i<count($data);$i++){
		$nomor	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		
		/** getParam 
			memindahkan semua nilai dalam array POST ke dalam
			variabel yang bersesuaian dengan masih kunci array
		*/
		$nilai	= $data[$i];
		$konci	= array_keys($nilai);
		for($j=0;$j<count($konci);$j++){
			$$konci[$j]	= $nilai[$konci[$j]];
		}
		/* getParam **/
		
		$comm	= "rinci_$i cetak_$i hapus_$i";
?>
	<tr valign="top" class="<?php echo $klas; ?>">
		<td><?php echo $nomor;		?></td>
		<td><?php echo $kel_nama;	?></td>
		<td><?php echo $kel_ket; 	?></td>
		<td><?php echo $kolektor;	?></td>
		<td><?php echo $kel_jumlah;	?></td>
		<td class="center">
			<input type="hidden" class="<?php echo $comm; ?>" 		name="kel_kode"		value="<?php echo $kel_kode;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="kel_nama"		value="<?php echo $kel_nama;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="kel_ket"		value="<?php echo $kel_ket;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="kolektor"		value="<?php echo $kolektor;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="targetId" 	value="content"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="proses" 		value="rinci"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" 	name="errorUrl" value="cetak_kolektif.php"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" 	name="targetId" value="cetakId"/>
			<input type="hidden" class="hapus_<?php echo $i; ?>" 	name="errorUrl" value="form_hapus_kel_kolektif.php"/>
			<img src="./images/edit.gif"	title="Lihat Rincian" onclick="buka('rinci_<?php echo $i; ?>')"/>
			<img src="./images/print.gif" 	title="Cetak Tagihan" onclick="nonghol('cetak_<?php echo $i; ?>')"/>
			<img src="./images/delete.gif" 	title="Hapus Kolektif" onclick="nonghol('hapus_<?php echo $i; ?>')"/>
		</td>
	</tr>
<?php
	}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="left"><?php echo $tambah; ?></td>
		<td class="right">
			&nbsp;<?php echo $pref_mess." ".$next_mess; ?>
		</td>
	</tr>
</table>
<?php
	}
?>
