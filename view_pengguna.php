<?php
	if($erno) die();
	$errorId = getToken();
	if(!isset($proses)){
		$proses	= "";
	}
	if(!isset($kode)){
		$kode	= "";
	}
	/* retrive info */
	switch($proses){
		case "cari":
			$kode	= strtoupper($kode);
			$que0 	= "SELECT *FROM v_pengguna WHERE usr_id!='admin' AND UPPER(usr_nama) LIKE '%".$kode."%' LIMIT $limit_awal,$jml_perpage";
			echo "<input type=\"hidden\" class=\"next_page pref_page refresh\"	name=\"proses\" value=\"$proses\"/>";
			unset($proses);
			break;
		default:
			$que0 	= "SELECT *FROM v_pengguna WHERE usr_id!='admin' LIMIT $limit_awal,$jml_perpage";
	}
	try{		
		if(!$res0 = $link->query($que0)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{

			$i = 0;
			while($row0 = $res0->fetch_object()){
				$data[] = $row0;
				$i++;
			}
			/*	menentukan keberadaan operasi next page	*/
			if($i==$jml_perpage){
				$next_mess	= "<input type=\"button\" value=\">>\" class=\"form_button\" onClick=\"buka('next_page')\"/>";
			}
			$mess 		= false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que0));
		$mess = $e->getMessage();
		$erno = false;
	}
	if(!$erno) $link->close();
?>
<input type="hidden" class="next_page pref_page refresh cari tambah" 	name="appl_tokn"	value="<?php echo _TOKN; 		?>"/>
<input type="hidden" class="next_page pref_page refresh cari tambah" 	name="appl_kode"	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="next_page pref_page refresh cari tambah" 	name="appl_name"	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="next_page pref_page refresh cari tambah" 	name="appl_file"	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page refresh cari tambah" 	name="appl_proc"	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="next_page pref_page refresh cari tambah" 	name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page refresh cari tambah" 	name="targetId"		value="content"/>
<input type="hidden" class="next_page" 									name="pg" 			value="<?php echo $next_page;	?>"/>
<input type="hidden" class="pref_page" 									name="pg" 			value="<?php echo $pref_page;	?>"/>
<input type="hidden" class="refresh" 									name="pg" 			value="<?php echo $pg;			?>"/>
<h2><?php echo _NAME?></h2>
<?php
	switch($aksi){
		default:
?>
<input type="hidden" id="keyProses1" value="A" />
<table class="table_info">
<tr class="table_cont_btm">
	<td colspan="4">
		Pencarian Pengguna : 
		<input type="text" class="next_page pref_page cari refresh" name="kode" size="10" value="<?php echo $kode; ?>" title="masukan nama pengguna"/>
		<input type="hidden" class="cari" name="proses" value="cari"/>
		<input type="button" value="Periksa" onclick="buka('cari')"/>
	</td>
	<td class="right">Halaman : <?php echo $pg; ?></td>
</tr>
<tr class="table_head">
	<td class="left">ID</td>
	<td class="left">Nama</td>
	<td class="left">Unit Pelayanan</td>
	<td class="left">Grup</td>
	<td class="center">Atur</td>
</tr>
<?php
			$j = 0;
			for($i=1;$i<=$jml_perpage;$i++){

				if ($i%2==0){
					$kelas	= "table_cell2";
				}
				else{
					$kelas 	= "table_cell1";
				}

				if (isset($data[$j])) {
					$row0 	= $data[$j];
					$usr_id 	= $row0->usr_id;
					$usr_nama	= $row0->usr_nama;
					$kp_ket		= $row0->pdam_nama;
					$kp_kode	= $row0->pdam_kode;
					$grup_id	= $row0->grup_id;
					$grup_nama	= $row0->grup_nama;
				}else{
					$usr_id 	= "";
					$usr_nama	= "";
					$kp_ket		= "";
					$kp_kode	= "";
					$grup_id	= "";
					$grup_nama	= "";
				}

				$comm 		= "edit_".$usr_id." delt_".$usr_id." rest_".$usr_id;
				$edit		= "<img src=\"images/edit.gif\"  border=\"0\" title=\"Detail\" onClick=\"nonghol('edit_".$usr_id."')\"/>";
				$hapus		= "<img src=\"images/delete.gif\"  border=\"0\" title=\"Hapus User\" onClick=\"nonghol('delt_".$usr_id."')\"/>";
				$reset		= "<img src=\"images/icon-refresh.png\"  border=\"0\" title=\"Reset Password\" onClick=\"nonghol('rest_".$usr_id."')\"/>";
?>
	<tr class="<?php echo $kelas?>">
		<td class="left"><?php echo $usr_id?></td>
		<td><?php echo $usr_nama?></td>
		<td><?php echo $kp_ket?></td>
		<td><?php echo $grup_nama?></td>
		<td>
<?php
				if(isset($data)){
					if($j<count($data)){
?>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_kode"	value="<?php echo _KODE; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="errorId" 	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="usr_id" 	value="<?php echo $usr_id;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="usr_nama" 	value="<?php echo $usr_nama;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="kp_ket" 	value="<?php echo $kp_ket;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="kp_kode" 	value="<?php echo $kp_kode;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="grup_id" 	value="<?php echo $grup_id;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="grup_nama" 	value="<?php echo $grup_nama;	?>"/>
			<input type="hidden" class="edit_<?php echo $usr_id?>" 	name="errorUrl" 	value="form_edit_pengguna.php"/>
			<input type="hidden" class="delt_<?php echo $usr_id?>" 	name="errorUrl" 	value="form_hapus_pengguna.php"/>
			<input type="hidden" class="rest_<?php echo $usr_id?>" 	name="errorUrl" 	value="form_reset_pengguna.php"/>
<?php
						echo $edit." ".$reset." ".$hapus;
					}
					else{
						echo "<img src=\"images/blank.png\"/>";
					}
				}
				else{
					echo "<img src=\"images/blank.png\"/>";
				}
?>
		</td>
	</tr>
<?php
				$j++;
			}
?>
	<tr class="table_cont_btm">
		<td colspan="4">
			<input type="hidden" class="tambah" name="errorUrl"	value="form_tambah_pengguna.php"/>
			<input type="button" class="from_button" value="Tambah" onclick="nonghol('tambah')"/>
		</td>
		<td colspan="2" class="right"><?php echo $pref_mess." ".$next_mess?></td>
	</tr>	
</table>
<?php
	}
?>
