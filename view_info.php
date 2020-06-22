<?php
	if($erno) die();
	$kp_ket	= $_SESSION['kp_ket'];
?>
<h2 class="cetak"><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h2>
<?php
	switch($proses){
		case "rinci":
?>
<input id="keyProses1" type="hidden" value="4"/>
<input type="hidden" class="refresh next_page pref_page" name="proses" 	value="<?php echo $proses;	?>"/>
<?php
			$que0 	= "SELECT a.pel_no,CONCAT(a.pel_no,' [',IFNULL(e.ref_no,'00.00.000.000'),']') AS ref_no,a.pel_nama,a.pel_alamat,a.dkd_kd,a.gol_kode,SUM(IFNULL(d.rek_sts,0)) AS rek_lembar,SUM(IFNULL(getTotal(d.rek_total,d.rek_angsuran,d.rek_bln,d.rek_thn),0)) AS rek_total,b.kps_kode,b.kps_ket,c.kp_kode,c.kp_ket FROM tm_pelanggan a JOIN tr_kondisi_ps b ON(b.kps_kode=a.kps_kode AND a.dkd_kd='$dkd_kd') JOIN tr_kota_pelayanan c ON(c.kp_kode=a.kp_kode) LEFT JOIN ref_pelanggan e ON(e.pel_no=a.pel_no) LEFT JOIN tm_rekening d ON(d.pel_no=a.pel_no AND d.rek_sts=1 AND d.rek_byr_sts=0) GROUP BY a.pel_no ORDER BY a.pel_no LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice cetak\">Tekan tombol <b>&larr;</b> dan <b>&rarr;</b> untuk navigasi halaman, <b>&uarr;</b> dan <b>&darr;</b> untuk memilih rincian setiap rayon, <b>Enter</b> untuk melihat rinciannya, kemudian <b>B</b> untuk kembali ke halaman sebelumnya.</div>";
			$messh	= "Rincian Jalan : <b>$dkd_kd</b>";
			break;
		case "cari":
?>
<input id="keyProses1" type="hidden" value="4" />
<input type="hidden" class="refresh next_page pref_page" name="proses" 	value="<?php echo $proses; ?>"/>
<?php
			$kode	= strtoupper($kode);
			if(strlen($kode)==10){
				$kode	= substr($kode,0,2).".".substr($kode,2,2).".".substr($kode,4,3).".".substr($kode,7,3);
			}
			if(_KOTA=='00'){
				$filter = "";
			}
			else{
				$filter = "AND a.kp_kode='"._KOTA."'";
			}
			$que0 	= "SELECT a.pel_no,CONCAT(a.pel_no,' [',IFNULL(e.ref_no,'00.00.000.000'),']') AS ref_no,a.pel_nama,a.pel_alamat,a.dkd_kd,a.gol_kode,SUM(IFNULL(d.rek_sts,0)) AS rek_lembar,SUM(IFNULL(getTotal(d.rek_total,d.rek_angsuran,d.rek_bln,d.rek_thn),0)) AS rek_total,b.kps_kode,b.kps_ket,c.kp_kode,c.kp_ket FROM tm_pelanggan a JOIN tr_kondisi_ps b ON(b.kps_kode=a.kps_kode $filter) JOIN tr_kota_pelayanan c ON(c.kp_kode=a.kp_kode) LEFT JOIN ref_pelanggan e ON(e.pel_no=a.pel_no) LEFT JOIN tm_rekening d ON(d.pel_no=a.pel_no AND d.rek_sts=1 AND d.rek_byr_sts=0) WHERE a.pel_no='$kode' OR UPPER(a.pel_nama) LIKE '%".strtoupper($kode)."%' OR e.ref_no='$kode' GROUP BY a.pel_no ORDER BY a.pel_nama LIMIT $limit_awal,$jml_perpage";
			$proses	= "rinci";
			$hint 	= "<div class=\"notice cetak\">Tekan tombol <b>&larr;</b> dan <b>&rarr;</b> untuk navigasi halaman, <b>&uarr;</b> dan <b>&darr;</b> untuk memilih rincian setiap rayon, <b>Enter</b> untuk melihat rinciannya, kemudian <b>B</b> untuk kembali ke halaman sebelumnya.</div>";
			$messh	= "Hasil pencarian : <b>$kode</b>";
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
			$que0 = "SELECT *FROM v_rayon $filter LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice cetak\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>&larr;</b> dan <b>&rarr;</b> untuk navigasi halaman, <b>&uarr;</b> dan <b>&darr;</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
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
<input type="hidden" id="norefresh" value="1"/>
<input type="hidden" class="kembali refresh cari next_page pref_page cetak" name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page cetak" name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page cetak" name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page cetak" name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page cetak" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="errorId"   	value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="dkd_kd" 	value="<?php echo $dkd_kd; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page" name="targetId"  	value="content"/>
<input type="hidden" class="next_page" name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" name="pg" value="<?php echo $pref_page; ?>"/>
<input type="hidden" class="refresh" name="pg" value="<?php echo $pg;	?>"/>
<input type="hidden" class="kembali" name="pg" value="<?php echo $back; ?>"/>
<?php
	if(_HINT==1){
		echo $hint;
	}
	switch($proses){
		case "rinci":
			if(count($data)>0){
?>
<input type="hidden" class="refresh next_page pref_page" name="kode" value="<?php echo $kode; ?>"/>
<input type="hidden" class="refresh next_page pref_page" name="back" value="<?php echo $back; ?>"/>
<table class="table_info cetak">
  <tr class="table_cont_btm">
		<td colspan="7"><?php echo $messh; ?></td>
		<td colspan="2" class="right">Halaman : <?php echo $pg; ?></td>
  </tr>
  <tr class="table_head">
    <td>No</td>
    <td>No Pel </td>
    <td>Nama</td>
    <td>Gol</td>
    <td>Alamat</td>
    <td>Jml Rek </td>
    <td>Total</td>
    <td>Status</td>
    <td>Manage</td>
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
?>
	<tr class="<?php echo $klas; ?>" >
		<td><?php echo $nomor; 		?></td>
		<td><?php echo $ref_no; 	?></td>
		<td><?php echo $pel_nama; 	?></td>
		<td><?php echo $gol_kode; 	?></td>
		<td><?php echo $pel_alamat; ?></td>
		<td class="right"><?php echo number_format($rek_lembar); ?></td>
		<td class="right"><?php echo number_format($rek_total); ?></td>
		<td><?php echo $kps_ket ?></td>
		<td> 
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_kode"	value="<?php echo _KODE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="errorId"   	value="<?php echo getToken(); 	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="pel_no"   	value="<?php echo $pel_no;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="pel_nama"   	value="<?php echo $pel_nama;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="gol_kode"   	value="<?php echo $gol_kode;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="pel_alamat"  value="<?php echo $pel_alamat;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="rek_lembar"  value="<?php echo $rek_lembar;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="rek_total"   value="<?php echo $jml_total;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="kp_ket"   	value="<?php echo $kp_ket;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="kps_ket"   	value="<?php echo $kps_ket;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="errorUrl" value="cetak_info_pelanggan.php"/>
			<input id="form-<?php echo ($i+1); ?>" type="button" value="Detail" onclick="nonghol('rinci_<?php echo $i; ?>')"/>
		</td>
	</tr>
<?php
				}
?>
	<tr class="table_cont_btm">
		<td colspan="9" class="right">&nbsp;<?php echo $pref_mess." ".$kembali." ".$next_mess; ?></td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?php echo $i; ?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
				}
				else{
					echo "<center class=\"notice\">Informasi pelanggan tidak ditemukan</center>";
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
			Pencarian Pelanggan :
			<input id="jumlahFind" type="hidden" value="2"/>
			<input id="aktiveFind" type="hidden" value="0"/>
			<input id="find-1" type="text" class="cari next_page pref_page" name="kode" size="13" maxlength="13" title="nomor sl atau nama pelanggan" />
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
?>
	<tr valign="top" class="<?php echo $klas; ?>">
		<td><?php echo $nomer;		?></td>
		<td><?php echo $dkd_kd;		?></td>
		<td><?php echo $dkd_tcatat; ?></td>
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
			<input id="form-<?php echo ($i+1); ?>" type="button" value="Detail" onclick="buka('rinci_<?php echo $i; ?>')"/>
		</td>
	</tr>

<?php
				}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="left"></td>
		<td class="right">
			&nbsp;<?php echo $pref_mess." ".$next_mess; ?>
		</td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?php echo $i; ?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
	}
?>