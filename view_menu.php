<?php
	if($erno) die();
	$errorId 	= getToken();
	if(!isset($proses)){
		$proses	= "";
	}
	if(!isset($kode)){
		$kode	= "";
	}
	if(!isset($param)){
		$param	= "";
	}
	if(!isset($rinci)){
		$rinci	= "";
	}
	if(!isset($hint)){
		$hint	= "";
	}
	
	/* retrive info */
	switch($proses){
		case "rinci":
?>
<input type="hidden" id="keyProses1" value="9" />
<input type="hidden" class="next_page pref_page refresh" name="back" value="<?php echo $back; ?>"/>
<?php
			$que0 		= "SELECT *FROM v_menu_item WHERE ga_kode='".$kode_appl."' OR appl_kode='".$kode_appl."' ORDER BY ga_kode,appl_seq LIMIT $limit_awal,$jml_perpage";
			$param		= "<input type=\"hidden\" class=\"next_page pref_page refresh\"	name=\"proses\" value=\"$proses\"/>";
			$param     .= "<input type=\"hidden\" class=\"next_page pref_page refresh\"	name=\"kode_appl\" value=\"$kode_appl\"/>";
			$param     .= "<input type=\"hidden\" class=\"next_page pref_page refresh\"	name=\"nama_appl\" value=\"$nama_appl\"/>";
			$e_ga_kode	= $kode_appl;
			$e_ga_nama	= $nama_appl;
			$rinci		= true;
			unset($proses);
			break;			
		case "cari":
?>
<input type="hidden" id="keyProses1" value="9" />
<input type="hidden" class="next_page pref_page refresh" name="back" value="<?php echo $back; ?>"/>
<?php
			$hint 		= "<div class=\"notice\">Tombol <b>&larr;</b> dan <b>&rarr;</b> untuk navigasi halaman, kemudian <b>B</b> untuk kembali ke halaman sebelumnya.</div>";
			$kode		= strtoupper($kode);
			$que0 		= "SELECT *FROM v_menu_item WHERE l3!='00' AND UPPER(appl_nama) LIKE '%".$kode."%' LIMIT $limit_awal,$jml_perpage";
			$param		= "<input type=\"hidden\" class=\"next_page pref_page refresh\"	name=\"proses\" value=\"$proses\"/>";
			#$param     .= "<input type=\"hidden\" class=\"next_page pref_page refresh\"	name=\"kode_appl\" value=\"$kode_appl\"/>";
			unset($proses);
			$tambah		= "";
			break;
		default:
?>
<input type="hidden" id="keyProses1" value="8" />
<?php
			$hint 		= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>&larr;</b> dan <b>&rarr;</b> untuk navigasi halaman.</div>";
			$que0 		= "SELECT *FROM v_menu_item WHERE l3='00' ORDER BY ga_kode,appl_seq LIMIT $limit_awal,$jml_perpage";
			$e_ga_kode	= "000000";
			$e_ga_nama	= "Root";
			$kembali	= "";
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
	echo $param;
	unset($param);
?>
<input type="hidden" class="next_page pref_page kembali refresh cari tambah simpan" 	name="appl_tokn"	value="<?php echo _TOKN; 		?>"/>
<input type="hidden" class="next_page pref_page kembali refresh cari tambah simpan" 	name="appl_kode"	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="next_page pref_page kembali refresh cari tambah simpan" 	name="appl_name"	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="next_page pref_page kembali refresh cari tambah simpan" 	name="appl_file"	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page kembali refresh cari tambah simpan" 	name="appl_proc"	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="next_page pref_page kembali refresh cari tambah" 			name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page kembali refresh cari tambah" 			name="targetId"		value="content"/>
<input type="hidden" class="next_page pref_page kembali refresh cari tambah" 			name="jml_perpage"	value="10"/>
<input type="hidden" class="next_page" 													name="pg" 			value="<?php echo $next_page;	?>"/>
<input type="hidden" class="pref_page" 													name="pg" 			value="<?php echo $pref_page;	?>"/>
<input type="hidden" class="kembali" 													name="pg" 			value="<?php echo $back;		?>"/>
<input type="hidden" class="refresh" 													name="pg" 			value="<?php echo $pg;			?>"/>
<h2 class="title"><?php echo _NAME; ?></h2>
<?php
	switch($aksi){
		default:
			if(_HINT==1){
				echo $hint;
			}
?>
<table class="table_info">
<tr class="table_cont_btm">
	<td colspan="5">
		Pencarian Menu :
		<input id="jumlahFind" type="hidden" value="2"/>
		<input id="aktiveFind" type="hidden" value="0"/>
		<input id="find-1" type="text" class="next_page pref_page cari refresh" name="kode" size="10" value="<?php echo $kode; ?>" title="masukan nama pengguna"/>
		<input id="find-2" type="button" value="Periksa" onclick="buka('cari')"/>
		<input type="hidden" class="cari" name="jml_perpage"	value="10"/>
		<input type="hidden" class="cari" name="proses" 		value="cari"/>
		<input type="hidden" class="cari" name="back" 			value="<?php echo $pg; ?>"/>
	</td>
	<td class="right">Halaman : <?php echo $pg; ?></td>
</tr>
<tr class="table_head">
	<td class="left">Kode</td>
	<td class="left">Nama</td>
	<td class="left">Parent</td>
	<td class="left">Status</td>
	<td class="left">Urutan</td>
	<td class="center">Pengaturan</td>
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
					$kode_appl 	= $row0->appl_kode;
					$appl_seq 	= $row0->appl_seq;
					$ga_kode	= $row0->ga_kode;
					$ga_nama	= $row0->ga_nama;
					$nama_appl	= $row0->appl_nama;
					$appl_sts	= $row0->appl_sts;
					$status		= $row0->status;
					if($status=="Disable"){
						$status = "<font color=\"red\">$status</font>";
					}
					$l3			= $row0->l3;
					$comm		= "detail_".$kode_appl;
					if($l3=='00' and !$rinci){
						//$detail	= "<img src=\"images/edit.gif\"  border=\"0\" title=\"Detail\" onClick=\"buka('".$comm."')\"/>";
						$detail	= "<input type=\"button\" value=\"Detail\" onClick=\"buka('".$comm."')\"/>";
						$param	= "<input type=\"hidden\" class=\"$comm\" name=\"targetUrl\" value=\"$appl_file\"/>";
					}
					else{
						//$detail	= "<img src=\"images/edit.gif\"  border=\"0\" title=\"Detail\" onClick=\"nonghol('".$comm."')\"/>";
						$detail	= "<input type=\"button\" value=\"Detail\" onClick=\"nonghol('".$comm."')\"/>";
						$param	= "<input type=\"hidden\" class=\"$comm\" name=\"errorUrl\" value=\"form_edit_menu.php\"/>";
					}
				}
				else{
					$kode_appl	= "";
					$nama_appl	= "";
					$ga_nama	= "";
					$status		= "";
					$appl_seq	= "";
				}
?>
	<tr class="<?php echo $kelas; ?>">
		<td><?php echo $kode_appl; ?></td>
		<td><?php echo $nama_appl; ?></td>
		<td><?php echo $ga_nama; ?></td>
		<td><?php echo $status; ?></td>
		<td><?php echo $appl_seq; ?></td>
		<td>
<?php
				if(isset($data)){
					if($j<count($data)){
						echo $param;
						$nama_appl = htmlentities($nama_appl);
?>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_tokn" 		value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_kode"		value="<?php echo _KODE; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_name"		value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_file"		value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_proc"		value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="kode_appl" 		value="<?php echo $kode_appl;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_seq" 		value="<?php echo $appl_seq;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="nama_appl" 		value="<?php echo $nama_appl;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="appl_sts" 		value="<?php echo $appl_sts;	?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="back"	 		value="<?php echo $pg;			?>"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="jml_perpage"	value="10"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="targetId" 		value="content"/>
			<input type="hidden" class="<?php echo $comm; ?>" name="proses" 		value="rinci"/>
<?php
						echo $detail;
					}
					else{
						//echo "<img src=\"images/blank.png\"/>";
						echo "&nbsp;";
					}
				}
				else{
					echo "&nbsp;";
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
			<input type="hidden" class="tambah" name="errorUrl"		value="form_tambah_menu.php"/>
			<input type="hidden" class="tambah" name="e_ga_kode"	value="<?php echo $e_ga_kode; ?>"/>
			<input type="hidden" class="tambah" name="e_ga_nama"	value="<?php echo $e_ga_nama; ?>"/>
			<?php echo $tambah; ?>
		</td>
		<td colspan="2" class="right"><?php echo $pref_mess." ".$kembali." ".$next_mess?></td>
	</tr>	
</table>
<?php
	}
?>
