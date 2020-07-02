<?php
	if($erno) die();
	$kp_ket	= $_SESSION['kp_ket'];
	if(isset($mess)){
		$mess = "";
	}
	if(!isset($proses)){
		$proses	= false;
	}
?>
<h3><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h3>
<input type="hidden" id="<?php echo $errorId; ?>" value="<?php echo $mess; ?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan delete tambah"	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan delete tambah"	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan delete tambah"	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan delete tambah"	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page simpan delete tambah"	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page"						name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page"						name="errorId"   	value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali refresh cari next_page pref_page"						name="targetId"  	value="content"/>
<input type="hidden" class="next_page"	name="pg" 		value="<?php echo $next_page; 	?>"/>
<input type="hidden" class="pref_page" 	name="pg" 		value="<?php echo $pref_page; 	?>"/>
<input type="hidden" class="refresh" 	name="pg" 		value="<?php echo $pg;			?>"/>
<input type="hidden" class="tambah" 	name="errorUrl"	value="form_tambah_rayon.php"	   />
<?php
	$kembali 	= "<input type=\"button\" value=\"Kembali\" onclick=\"buka('kembali')\" />";
	switch($proses){
		case "cari":
			$kode	= strtoupper($kode);
			$que0 	= "SELECT *FROM v_rayon WHERE kp_kode='"._KOTA."' AND (dkd_kd='$kode' OR dkd_jalan LIKE '%$kode%' OR dkd_pembaca LIKE '%$kode%') ORDER BY dkd_kd DESC LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tombol <b>N</b> untuk menambah rayon, <b>&larr;</b> dan <b>&rarr;</b> untuk navigasi halaman, <b>&uarr;</b> dan <b>&darr;</b> untuk memilih rincian setiap rayon, kemudian <b>Enter</b> untuk melihat rinciannya, atau <b>B</b> untuk kembali ke halaman sebelumnya.</div>";
?>
<input id="keyProses1" type="hidden" value="2" />
<input type="hidden" class="refresh next_page pref_page" 	name="proses"	value="<?php echo $proses; 	?>"/>
<input type="hidden" class="refresh next_page pref_page" 	name="kode" 	value="<?php echo $kode;	?>"/>
<input type="hidden" class="refresh next_page pref_page" 	name="back" 	value="<?php echo $back;	?>"/>
<input type="hidden" class="kembali" 						name="pg" 		value="<?php echo $back; 	?>"/>
<?php
			$tambah = "<input type=\"button\" value=\"Tambah\" onclick=\"nonghol('tambah')\" />";
			unset($proses);
			break;
		default :
?>
<input id="keyProses1" type="hidden" value="1" />
<?php
			$tambah		= "<input type=\"button\" value=\"Tambah\" onclick=\"nonghol('tambah')\" />";
			$kembali	= "";
			$que0 		= "SELECT *FROM v_rayon WHERE kp_kode='"._KOTA."' ORDER BY dkd_kd LIMIT $limit_awal,$jml_perpage";
			$hint 		= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>&larr;</b> dan <b>&rarr;</b> untuk navigasi halaman, <b>&uarr;</b> dan <b>&darr;</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
	}
	try{
		if(!$res0 = $link->query($que0)){
			throw new Exception($link->error);
		}
		else{
			$i = 0;
			while($row0 = $res0->fetch_array()){
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
<?php
			if(!isset($kode)){
?>
			Pencarian Rayon :
			<input id="jumlahFind" type="hidden" value="2"/>
			<input id="aktiveFind" type="hidden" value="0"/>
			<input id="find-1" type="text" class="cari" name="kode" size="20" maxlength="20" title="masukan kode rayon, alamat atau pembaca"/>
			<input id="find-2" type="button" value="Periksa" onclick="buka('cari')"/>
<?php
			}
?>
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
	if(!isset($data)){
		$data	= array();
	}
	for($i=0;$i<count($data);$i++){
		$nomer	= ($i+1)+(($pg-1)*$jml_perpage);
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		/** getParam 
			memindahkan semua nilai dalam array ke dalam
			variabel yang bersesuaian dengan masih kunci array
		*/
		$nilai	= $data[$i];
		$konci	= array_keys($nilai);
		for($j=0;$j<count($konci);$j++){
			if(PHP_VERSION < 7){
				$$konci[$j]	= $nilai[$konci[$j]];
			}else{
				${$konci[$j]} = $nilai[$konci[$j]];
			}
		}
?>
	<tr class="<?php echo $klas; ?>">
		<td><?php echo $nomer;		?></td>
		<td><?php echo $dkd_kd;		?></td>
		<td><?php echo $dkd_tcatat;	?></td>
		<td><?php echo $dkd_pembaca;?></td>
		<td><?php echo $dkd_jalan;	?></td>
		<td>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_rayon"	value="<?php echo $dkd_rayon;	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_no"		value="<?php echo $dkd_no; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_tcatat"	value="<?php echo $dkd_tcatat; 	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="kar_id"		value="<?php echo $kar_id; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="dkd_jalan"	value="<?php echo $dkd_jalan; 	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="errorUrl" 	value="form_edit_rayon.php"/>
			<input type="hidden" class="rinci_<?php echo $i; ?>" name="targetId" 	value="content"/>
			<input id="form-<?=($i+1)?>" type="button" value="Detail" onclick="nonghol('rinci_<?php echo $i; ?>')"/>
		</td>
	</tr>

<?php

	}
?>
	<tr class="table_cont_btm">
		<td colspan="5" class="left"><?=$tambah?></td>
		<td class="right"><?=$pref_mess?> <?=$kembali?> <?=$next_mess?></td>
	</tr>
</table>
<input id="jumlahForm" type="hidden" value="<?=$i?>"/>
<input id="aktiveForm" type="hidden" value="0"/>
<?php
	}
	if(!$erno) $link->close();
?>
