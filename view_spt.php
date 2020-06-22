<?php
	if($erno) die();
	$kp_kode 	= _KOTA;
?>
<h2 class="cetak"><?php echo _NAME; ?> - <?php echo $_SESSION['kp_ket']; ?></h2>
<?php
	switch($proses){
		case "rinci":
?>
<input type="hidden" id="keyProses1" value="E"/>
<input type="hidden" class="refresh next_page pref_page" 	name="proses" 	value="<?php echo $proses; ?>"/>
<?php
			$que0 	= "SELECT a.*,IFNULL(MAX(DATE(e.surat_tgl)),'-') AS surat_tgl,IFNULL(e.surat_ket,'-') AS surat_ket FROM (SELECT a.pel_no,a.dkd_kd,a.dkd_no,a.pel_nama,a.pel_alamat,a.gol_kode,COUNT(e.rek_nomor) AS jml_rek,c.kps_kode,UPPER(c.kps_ket) AS kps_ket FROM tm_pelanggan a JOIN tr_kondisi_ps c ON(c.kps_kode=a.kps_kode AND a.kps_kode<=5) JOIN tm_rekening e ON(e.pel_no=a.pel_no AND e.rek_sts=1 AND e.rek_byr_sts=0) WHERE a.dkd_kd='$dkd_kd' GROUP BY a.pel_no ORDER BY COUNT(e.rek_nomor) DESC LIMIT $limit_awal, $jml_perpage) a LEFT JOIN tm_surat_peringatan e ON(e.pel_no=a.pel_no) GROUP BY a.pel_no";
			break;
		case "cari":
?>
<input type="hidden" id="keyProses1" value="E"/>
<input type="hidden" class="refresh next_page pref_page" 	name="proses" 	value="<?php echo $proses; ?>"/>
<?php
			$kode	= strtoupper($kode);
			$que0 	= "SELECT a.*,IFNULL(MAX(DATE(e.surat_tgl)),'-') AS surat_tgl,IFNULL(e.surat_ket,'-') AS surat_ket FROM (SELECT a.pel_no,a.dkd_kd,a.dkd_no,a.pel_nama,a.pel_alamat,a.gol_kode,COUNT(e.rek_nomor) AS jml_rek,c.kps_kode,UPPER(c.kps_ket) AS kps_ket FROM tm_pelanggan a JOIN tr_kondisi_ps c ON(c.kps_kode=a.kps_kode AND a.kps_kode<=5) JOIN tm_rekening e ON(e.pel_no=a.pel_no AND e.rek_sts=1 AND e.rek_byr_sts=0) WHERE a.kp_kode='$kp_kode' AND a.pel_no='$kode' GROUP BY a.pel_no) a LEFT JOIN tm_surat_peringatan e ON(e.pel_no=a.pel_no) GROUP BY a.pel_no";
			$proses	= "rinci";
			break;
		default :
?>
<input id="keyProses1" type="hidden" value="A" />
<?php
			$que0 = "SELECT a.dkd_kd AS dkd_kd,a.dkd_jalan AS dkd_jalan,a.dkd_tcatat AS dkd_tcatat,COUNT(b.pel_no) AS dkd_jml,SUM(IF((IFNULL(b.kps_kode,0)<=5),1,0)) AS dkd_tpsa FROM tr_dkd a JOIN tm_pelanggan b ON(b.dkd_kd=a.dkd_kd AND b.kp_kode ='$kp_kode') GROUP BY a.dkd_kd ORDER BY a.dkd_kd LIMIT $limit_awal,$jml_perpage";
			$hint = "<div class=\"notice\">Tekan tombol <b>&larr;</b> dan <b>&rarr;</b> untuk navigasi halaman.</div>";
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
<input id="norefresh" type="hidden" value="1"/>
<input type="hidden" id="<?php echo $errorId; ?>" value="<?php echo $mess; ?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="errorId"   	value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="targetId"  	value="content"/>
<input type="hidden" class="next_page" 	name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" 	name="pg" value="<?php echo $pref_page; ?>"/>
<input type="hidden" class="refresh" 	name="pg" value="<?php echo $pg;		?>"/>
<?php
	if(_HINT==1){
		echo $hint;
	}
	switch($proses){
		case "rinci":
			if(isset($dkd_kd)){
				$rayon = " : [".$dkd_kd."] - ".$dkd_jalan;
			}
			else{
				$rayon = " : -";
			}
?>
<input type="hidden" class="cetak" name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="cetak" name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="cetak" name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="cetak" name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="cetak" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
<input type="hidden" class="cetak" name="targetUrl"	 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="cetak" name="targetId"   	value="targetId"/>
<input type="hidden" class="cetak" name="proses"   		value="cetakSPT"/>
<input type="hidden" class="cetak" name="dump" 			value="0"/>
<input type="hidden" class="kembali" 									name="pg" 			value="<?php echo $back; 		?>"/>
<input type="hidden" class="next_page pref_page refresh" 				name="back" 		value="<?php echo $back; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 	name="dkd_kd" 		value="<?php echo $dkd_kd; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" 	name="dkd_jalan" 	value="<?php echo $dkd_jalan;	?>"/>
<table class="table_info cetak">
	<tr class="table_cont_btm">
		<td colspan="2">Rayon DKD</td>
		<td colspan="7"><?php echo $rayon; ?></td>
		<td colspan="2" class="left">Hal : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head"> 
		<td width="05%" class="right">No</td>	
		<td width="10%">NoSR</td>
		<td width="15%">Nama</td>
		<td width="20%">Alamat</td>
		<td width="05%">Gol</td>  
		<td width="05%" class="right">Rek</td>
		<td width="10%" class="center">Status</td>
		<td width="10%" class="center">Tgl SPT</td>
		<td width="10%" class="center">Keterangan</td>
		<td width="05%"></td>
		<td width="05%"></td>
	</tr>
<?php
	if(isset($_SESSION['nopel'])){
		$nopel		= $_SESSION['nopel'];
	}
	else{
		$nopel		= array(0);
	}
	for($i=0;$i<count($data);$i++){
		$row0 	= $data[$i];
		$nomor	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		/** getParam 
			memindahkan semua nilai dalam array ke dalam
			variabel yang bersesuaian dengan masih kunci array
		*/
		$nilai	= $row0;
		$konci	= array_keys($nilai);
		for($j=0;$j<count($konci);$j++){
			$$konci[$j]	= $nilai[$konci[$j]];
		}
		/* getParam **/
		
		if($nopel[$pel_no]==1){
			$checked = "checked";
		}
		else{
			$checked = "";
		}
		if(isset($_SESSION['sptket'][$pel_no])){
			$surat_ket = $_SESSION['sptket'][$pel_no];
		}
		$comm = "rinci_$i pilih_$i";
?>
	<tr valign="top" class="<?php echo $klas; ?>">
		<td class="right"><?php echo $nomor; ?></td>
		<td><?php echo $pel_no; ?></td>
		<td><?php echo $pel_nama; ?></td>
		<td><?php echo $pel_alamat; ?></td>
		<td><?php echo $gol_kode; ?></td>
		<td class="right"><?php echo $jml_rek; ?></td>
		<td><?php echo $kps_ket; ?></td>
		<td class="center"><?php echo $surat_tgl; ?></td>
		<td class="center">
			<input id="<?php echo getToken(); ?>" type="text" maxlength="20" class="pilih_<?php echo $i; ?>" name="spt_ket" value="<?php echo $surat_ket; ?>" onmouseover="$(this.id).select()" />
		</td>
		<td class="center">
			<input <?php echo $checked; ?> id="pilih_<?php echo $i; ?>" type="checkbox" onchange="pilihan(this.id)"/>
		</td>
		<td class="center">
			<span id="targetError"></span>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_kode"	value="<?php echo _KODE; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" 		name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="pilih_<?php echo $i; ?>" 	name="pel_no" 		value="<?php echo $pel_no; 		?>"/>
			<input type="hidden" class="pilih_<?php echo $i; ?>" 	name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="pilih_<?php echo $i; ?>" 	name="targetId" 	value="targetError"/>
			<input type="hidden" class="pilih_<?php echo $i; ?>" 	name="proses" 		value="pilihSL"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="dkd_jalan"	value="<?php echo $dkd_jalan;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="pel_no"   	value="<?php echo $pel_no;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="pel_nama"   	value="<?php echo $pel_nama;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="gol_kode"   	value="<?php echo $gol_kode;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="pel_alamat"  	value="<?php echo $pel_alamat;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="rek_lembar"  	value="<?php echo $jml_rek;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="kps_ket"   	value="<?php echo $kps_ket;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" 	name="errorUrl" 	value="cetak_info_pelanggan.php"/>
			<img src="./images/edit.gif" class="prepend-top" title="Lihat rincian tunggakan" onclick="nonghol('rinci_<?php echo $i; ?>')"/>
		</td>
	</tr>
<?php
	}
?>
	<tr class="table_cont_btm">
		<td id="targetId" colspan="8" class="left">
			<input type="button" value="Kembali" onclick="buka('kembali')"/>
			&nbsp;<input type="submit" value="Buat" onclick="buka('cetak')"/>
		</td>
		<td colspan="3" class="right">
			&nbsp;<?php echo $pref_mess." ".$next_mess; ?>
		</td>
	</tr>
</table>
<?php			
			break;
		default:
			if(isset($_SESSION['nopel'])){
				unset($_SESSION['nopel']);
			}
			if(count($data)>0){
?>
<table class="table_info">
	<tr class="table_cont_btm">
		<td colspan="6">
			Pencarian Pelanggan :
			<input id="<?php echo getToken(); ?>" type="text" class="cari next_page pref_page" name="kode" size="13" maxlength="13" title="masukan nomor sr" onmouseover="$(this.id).focus()" />
			<input type="hidden" class="cari" name="proses" value="cari"/>
			<input type="hidden" class="cari" name="back" 	value="<?php echo $pg; ?>"/>
			<input type="button" value="Periksa" onclick="buka('cari')"/>
		</td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head"> 
		<td>No</td>
		<td>Kode</td>   
		<td>Tgl Catat</td>        
		<td>Jalan/Daerah</td>
		<td class="right">Jumlah Sambungan</td>
		<td class="right">Jumlah Aktif</td>
		<td class="center">Manage</td>
	</tr>
<?php
	for($i=0;$i<count($data);$i++){
		$row0 	= $data[$i];
		$nomor	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$dkd_kd 	= $row0['dkd_kd'];
		$dkd_jalan 	= $row0['dkd_jalan'];
?>
	<tr valign="top" class="<?php echo $klas; ?>">
		<td><?php echo $nomor;							?></td>
		<td><?php echo $row0['dkd_kd'];					?></td>
		<td><?php echo $row0['dkd_tcatat']; 			?></td>
		<td><?php echo $row0['dkd_jalan'];				?></td>
		<td class="right"><?php echo $row0['dkd_jml'];	?></td>
		<td class="right"><?php echo $row0['dkd_tpsa'];	?></td>
		<td class="center">
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_jalan"	value="<?php echo $dkd_jalan;	?>"/>
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
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dump"	   	value="0"/>
			<img src="./images/edit.gif" title="Lihat Rincian" onclick="buka('rinci_<?php echo $i; ?>')"/>
		</td>
	</tr>
<?php
	}
?>
	<tr class="table_cont_btm">
		<td colspan="6" class="left"></td>
		<td class="right">
			&nbsp;<?php echo $pref_mess." ".$next_mess; ?>
		</td>
	</tr>
</table>
<?php
			}
			else{
?>
<div class="notice">Data pelanggan SPT tidak ditemukan</div>
<?php
			}
	}
?>
