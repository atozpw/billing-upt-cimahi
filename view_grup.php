<?php
	if($erno) die();
	
	/* retrive info */
	if(!isset($proses)){
		$proses	= "";
	}
	switch($proses){
		case "cari":
			$kode	= strtoupper($kode);
			$que0 	= "SELECT grup_id,grup_nama FROM tm_group WHERE grup_id!='000' AND UPPER(grup_nama) LIKE '%".$kode."%' ORDER BY grup_id LIMIT $limit_awal,$jml_perpage";
			echo "<input type=\"hidden\" class=\"next_page pref_page refresh\"	name=\"proses\" value=\"$proses\"/>";
			echo "<input type=\"hidden\" class=\"next_page pref_page refresh\"	name=\"jml_perpage\" value=\"15\"/>";
			unset($proses);
			break;
		default:
			$que0 	= "SELECT grup_id,grup_nama FROM tm_group WHERE grup_id!='000' ORDER BY grup_id LIMIT $limit_awal,$jml_perpage";
	}
	try{		
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			$i = 0;
			while($row0 = mysql_fetch_object($res0)){
				$data[] = $row0;
				$i++;
			}
			/*	menentukan keberadaan operasi next page	*/
			if($i==$jml_perpage){
				$next_mess	= "<input type=\"button\" value=\">>\" class=\"form_button\" onclick=\"buka('next_page')\"/>";
			}
			$mess 		= false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que0));
		$mess = $e->getMessage();
		$erno = false;
	}
	if(!$erno) mysql_close($link);
?>
<input type="hidden" id="keyProses1" value="A" />
<input type="hidden" class="next_page pref_page cari refresh tambah kembali" name="targetId" 	value="content"/>
<input type="hidden" class="next_page pref_page cari refresh tambah kembali" name="targetUrl" 	value="<?=_FILE?>"/>
<input type="hidden" class="next_page pref_page cari refresh tambah kembali" name="appl_kode"	value="<?=_KODE?>"/>
<input type="hidden" class="next_page pref_page cari refresh tambah kembali" name="appl_name"	value="<?=_NAME?>"/>
<input type="hidden" class="next_page pref_page cari refresh tambah kembali" name="appl_file"	value="<?=_FILE?>"/>
<input type="hidden" class="next_page pref_page cari refresh tambah kembali" name="appl_proc"	value="<?=_PROC?>"/>
<input type="hidden" class="next_page pref_page cari refresh tambah kembali" name="appl_tokn" 	value="<?=_TOKN?>"/>
<input type="hidden" class="next_page" 	name="pg" value="<?=$next_page?>"/>
<input type="hidden" class="pref_page" 	name="pg" value="<?=$pref_page?>"/>
<input type="hidden" class="refresh" 	name="pg" value="<?=$pg?>"/>
<h2><?=_NAME?></h2>
<?php
	switch($proses){
		default:
			if(!isset($kode)){
				$kode	= "";
			}
?>
<input type="hidden" class="tambah" name="errorUrl"	value="form_tambah_grup.php"/>
<table class="table_info">
	<tr class="table_cont_btm">
		<td colspan="2">
			Pencarian Grup Pengguna :
			<input type="text" class="next_page pref_page cari refresh" name="kode" size="10" value="<?php echo $kode; ?>" title="masukan nama pengguna"/>
			<input type="hidden" class="cari" name="proses" 		value="cari"/>
			<input type="hidden" class="cari" name="jml_perpage" 	value="15"/>
			<input type="hidden" class="cari" name="back" 			value="<?php echo $pg; ?>"/>
			<input type="button" value="Periksa" onclick="buka('cari')"/>
		</td>
		<td class="right">Halaman : <?php echo $pg; ?></td>
	</tr>
	<tr class="table_head">
		<td>Kode</td>
		<td>Nama</td>
		<td>Atur</td>
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
				if(isset($data[$j])){
					$row0 		= $data[$j];
					$grup_id 	= $row0->grup_id;
					$grup_nama	= $row0->grup_nama;
					//$edit		= "<img src=\"images/edit.gif\" title=\"Edit Grup\" onclick=\"nonghol('edit_$i')\"/>";
					//$appl		= "<img src=\"images/edit.gif\" title=\"Detail Hak Akses\" onclick=\"nonghol('appl_$i')\"/>";
					//$hapus	= "<img src=\"images/delete.gif\" title=\"Hapus Grup\" onclick=\"nonghol('hapus_$i')\"/>";
					$edit		= "<input type=\"button\" value=\"Edit Grup\" onclick=\"nonghol('edit_$i')\" />";
					$appl		= "<input type=\"button\" value=\"Edit Akses\" onclick=\"nonghol('appl_$i')\"/>";
					$hapus		= "<input type=\"button\" value=\"Hapus\" onclick=\"nonghol('hapus_$i')\"/>";
					$j++;
				}
				else{
					$edit		= "<img src=\"images/blank.png\"/>";
					$grup_id 	= null;
					$grup_nama	= null;
					$hapus		= null;
					$detail		= null;
					$appl		= null;
				}
				$comm		= "appl_".$i." edit_".$i." hapus_".$i;
				$errorId	= getToken();
?>
	<tr class="<?=$kelas?>">
		<td style="vertical-align:top"><?=$grup_id?></td>
		<td style="vertical-align:top"><?=$grup_nama?></td>
		<td>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_kode" 	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_name" 	value="<?php echo _NAME;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_file" 	value="<?php echo _FILE;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_proc" 	value="<?php echo _PROC;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_tokn" 	value="<?php echo _TOKN;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="grup_id" 	value="<?php echo $grup_id;		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="grup_nama" 	value="<?php echo $grup_nama;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="errorId" 	value="<?php echo $errorId;		?>"/>
			<input type="hidden" class="edit_<?=$i?>" 	name="errorUrl" 	value="form_edit_grup.php"/>
			<input type="hidden" class="appl_<?=$i?>" 	name="errorUrl" 	value="form_edit_appl.php"/>
			<input type="hidden" class="hapus_<?=$i?>" 	name="errorUrl" 	value="form_hapus_grup.php"/>
			<?=$appl." ".$edit." ".$hapus?>
		</td>
	</tr>
<?php
			}
			if($j==$jml_perpage){
				$next_mess	= "<input type=\"button\" name=\"Submit\" value=\">>\" class=\"form_button\" onclick=\"buka('next_page')\"/>";
			}
?>
	<tr class="table_cont_btm">
		<td>
			<input type="button" class="from_button" value="Tambah" onclick="nonghol('tambah')"/>
		</td>
		<td colspan="2" class="right"><?php echo $pref_mess." ".$next_mess; ?></td>
	</tr>	
</table>
<?php
	}
?>
