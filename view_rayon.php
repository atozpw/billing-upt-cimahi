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
<input type="hidden" class="tambah" name="errorUrl" value="form_pelanggan.php" />
<input type="hidden" class="tambah" name="kp_kode" 	value="<?php echo _KOTA; ?>" />
<?php
	switch($proses){
		case "rinci":
			$que0 	= "SELECT * FROM v_data_pelanggan WHERE dkd_kd='$dkd_kd' ORDER BY pel_no DESC LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>B</b> untuk kembali ke halaman sebelumnya</div>";
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
				$filter = "kp_kode='"._KOTA."' AND";
			}
			$kode	= strtoupper($kode);
			$que0 	= "SELECT * FROM v_data_pelanggan WHERE $filter (pel_no LIKE '%$kode%' OR pel_nama LIKE '%$kode%' OR pel_alamat LIKE '%$kode%' OR kps_ket LIKE '%$kode%') ORDER BY pel_nama DESC LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>B</b> untuk kembali ke halaman sebelumnya</div>";
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
			$que0 	= "SELECT * FROM v_rayon $filter LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
	}
	try{
		if(!$res0 = $link->query($que0)){
			throw new Exception($link->error);
		}
		else{
			$i = 0;
			while($row0 = $res0->fetch_array()){
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
		errorLog::errorDB(array($e->getMessage()));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
	}
	if(!$erno) $link->close();
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
		<td colspan="7"></td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head">
		<td>No</td>
		<td>No Pel</td>
		<td>Nama</td>   
		<td>Alamat</td>        
		<td>Golongan</td>      
		<td>Rayon</td>
		<td>Status</td>
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
?>
	<tr valign="top" class="<?php echo $klas; ?>">
		<td><?php echo $nomer;				?></td>
		<td><?php echo $row0['ref_no'];		?></td>
		<td><?php echo $row0['pel_nama']; 	?></td>
		<td><?php echo $row0['pel_alamat'];	?></td>
		<td><?php echo $row0['gol_kode'];	?></td>
		<td><?php echo $row0['dkd_kd'];		?></td>
		<td><?php echo $row0['kps_ket'];	?></td>
		<td>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_kode"		value="<?php echo _KODE;				?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_name"		value="<?php echo _NAME; 				?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_file"		value="<?php echo _FILE; 				?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_proc"		value="<?php echo _PROC; 				?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_tokn" 		value="<?php echo _TOKN; 				?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="errorId"   		value="<?php echo getToken();			?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="pel_no"   		value="<?php echo $row0['pel_no'];		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="pel_nowm"   		value="<?php echo $row0['pel_nowm'];	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="pel_nama"   		value="<?php echo $row0['pel_nama'];	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="pel_alamat"   	value="<?php echo $row0['pel_alamat'];	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="kp_ket"   		value="<?php echo $kp_ket;				?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="kp_kode"   		value="<?php echo _KOTA;				?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="kps_kode"   		value="<?php echo $row0['kps_kode'];	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="kps_ket"   		value="<?php echo $row0['kps_ket'];		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="um_kode"   		value="<?php echo $row0['um_kode'];		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="um_ket"   		value="<?php echo $row0['um_ket'];		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="met_tgl"   		value="<?php echo $row0['met_tgl'];		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="gol_kode"   		value="<?php echo $row0['gol_kode'];	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="gol_ket"   		value="<?php echo $row0['gol_ket'];		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_kd"   		value="<?php echo $row0['dkd_kd'];		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="errorUrl" 		value="form_edit_pelanggan.php"/>
			<input id="form-<?=($i+1)?>" type="button" value="Detail" onclick="nonghol('rinci_<?php echo $i; ?>')"/>
		</td>
	</tr>
<?php

	}
?>
	<tr class="table_cont_btm">
		<td colspan="7" class="left"></td>
		<td class="right">
			&nbsp;<?php echo $pref_mess." ".$kembali." ".$next_mess; ?>
		</td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?=$i?>"/>
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
			Pencarian Pelanggan :
			<input id="jumlahFind" type="hidden" value="2"/>
			<input id="aktiveFind" type="hidden" value="0"/>
			<input id="find-1" type="text" class="cari next_page pref_page" name="kode" size="10" title="masukan status, nomor pelanggan, nama, atau alamat"/>
			<input id="find-2" type="button" value="Periksa" onclick="buka('cari')"/>
		</td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head"> 
		<td>No</td>
		<td>Rayon</td>   
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
		$dkd_kd = $row0['dkd_kd'];
?>
	<tr valign="top" class="<?php echo $klas; ?>">
		<td><?php echo $nomer;				?></td>
		<td><?php echo $dkd_kd;				?></td>
		<td><?php echo $row0['dkd_tcatat']; ?></td>
		<td><?php echo $row0['dkd_pembaca'];?></td>
		<td><?php echo $row0['dkd_jalan'];	?></td>
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
			<input type="button" value="Tambah" onclick="nonghol('tambah')">
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
