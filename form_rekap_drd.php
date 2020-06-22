<?php
	if($erno) die();
	$formId 	= getToken();
	$disabled	= false;
	$kopel		= $_SESSION['Kota_c']."_".$_SESSION['kp_ket'];
	if($_SESSION['Group_c']=='000'){
		$filtered = '';
	}
	else if($_SESSION['c_group']=='00'){
		$filtered = '';
	}
	else{
		$filtered = "WHERE kp_kode='".$_SESSION['Kota_c']."'";
	}
	
	/* inquiry kota pelayanan */
	try{
		$que2 = "SELECT CONCAT(kp_kode,'_',kp_ket) AS kopel,CONCAT('[',kp_kode,'] ',kp_ket) AS kp_ket FROM tr_kota_pelayanan $filtered ORDER BY kp_kode ASC";
		if(!$res2 = mysql_query($que2,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row2 = mysql_fetch_array($res2)){
				$data2[] = array("kopel"=>$row2['kopel'],"kp_ket"=>$row2['kp_ket']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que2));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm2 		= array("class"=>"cetak","name"=>"kopel","selected"=>$kopel);
	
	if(!isset($rek_bln) and !isset($rek_thn)){
		$rek_bln = date('n');
		$rek_thn = date('Y');
		if($rek_bln==1){
			$rek_bln = 12;
			$rek_thn = $rek_thn-1;
		}
		else{
			$rek_bln = $rek_bln-1;
		}
	}

	switch(_KODE){
		case '050901':
			$disabled	= "disabled";
			$data3[]	= array("gol_kode"=>"1","gol_ket"=>"Abnormal Atas");
			$data3[]	= array("gol_kode"=>"2","gol_ket"=>"Abnormal Bawah");
			$data3[]	= array("gol_kode"=>"3","gol_ket"=>"Abnormal Negatif");
			$data3[]	= array("gol_kode"=>"4","gol_ket"=>"Pemakaian Nol");
			$data3[]	= array("gol_kode"=>"5","gol_ket"=>"Belum Diisi");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","disabled"=>"disabled","selected"=>date('n'));
			$parm2 		= array("class"=>"cetak","name"=>"kopel","disabled"=>"disabled","selected"=>$kopel);
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>1);
			$rek_thn	= date('Y');
			break;
		case "060201":
			$disabled	= "disabled";
			$data3[]	= array("gol_kode"=>NULL,"gol_ket"=>"-");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>$rek_bln,"disabled"=>"disabled");
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			try{
				$que3 = "SELECT gol_kode,CONCAT('[',gol_kode,'] ',gol_ket) AS gol_ket FROM tr_gol ORDER BY gol_kode ASC";
				if(!$res3 = mysql_query($que3,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row3 = mysql_fetch_array($res3)){
						$data3[] = array("gol_kode"=>$row3['gol_kode'],"gol_ket"=>$row3['gol_ket']);
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que3));
				$mess = $e->getMessage();
				$erno = false;
			}
			break;
		case "060102":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>$rek_bln);
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			break;
		case "060106":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>$rek_bln);
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			break;
		case "060107":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>$rek_bln);
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			break;
		case "060109":
			$rek_thn	= date('Y');
			$data3[]	= array("gol_kode"=>"1","gol_ket"=>"BP");
			$data3[]	= array("gol_kode"=>"2","gol_ket"=>"PKPT");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>1);
			break;
		case "060110":
			$rek_thn	= date('Y');
			$data3[]	= array("gol_kode"=>"1","gol_ket"=>"BP");
			$data3[]	= array("gol_kode"=>"2","gol_ket"=>"PKPT");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>1);
			break;
		case "060204":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>$rek_bln);
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			break;
		case "060901":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			$rek_thn	= date('Y');
			break;
		case "060902":
			// Rincian Pemasangan
			$data3[]	= array("gol_kode"=>"SL","gol_ket"=>"Sambungan");
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			$rek_thn	= date('Y');
			break;
		case "060905":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			$rek_thn	= date('Y');
			break;
		case "060906":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			$rek_thn	= date('Y');
			break;
		case "060907":
			// Rincian Pembukaan Kembali
			$data3[]	= array("gol_kode"=>"SL","gol_ket"=>"Sambungan");
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			$rek_thn	= date('Y');
			break;
		case "060908":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			$rek_thn	= date('Y');
			break;
		case "060909":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL);
			$rek_thn	= date('Y');
			break;
		case "060108":
			$data3[]	= array("gol_kode"=>"KW","gol_ket"=>"Wilayah");
			$data3[]	= array("gol_kode"=>"KG","gol_ket"=>"Golongan");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>date('n'));
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL,"disabled"=>"disabled");
			break;
		case "060203":
			// Cetak rincian DSR
			$disabled	= "";
			$parm1	 	= array("class"=>_KODE." next_page pref_page","name"=>"rek_bln","selected"=>$rek_bln);
			break;
		default :
			$disabled	= "disabled";
			$data3[]	= array("gol_kode"=>"-","gol_ket"=>"-");
			$parm1	 	= array("class"=>"cetak","name"=>"rek_bln","selected"=>$rek_bln,"disabled"=>"disabled");
			$parm3 		= array("class"=>"cetak","name"=>"gol_kode","selected"=>NULL,"disabled"=>"disabled");
	}
	
	$data1[] = array("rek_bln"=>"1","bln_nama"=>"Januari");
	$data1[] = array("rek_bln"=>"2","bln_nama"=>"Februari");
	$data1[] = array("rek_bln"=>"3","bln_nama"=>"Maret");
	$data1[] = array("rek_bln"=>"4","bln_nama"=>"April");
	$data1[] = array("rek_bln"=>"5","bln_nama"=>"Mei");
	$data1[] = array("rek_bln"=>"6","bln_nama"=>"Juni");
	$data1[] = array("rek_bln"=>"7","bln_nama"=>"Juli");
	$data1[] = array("rek_bln"=>"8","bln_nama"=>"Agustus");
	$data1[] = array("rek_bln"=>"9","bln_nama"=>"September");
	$data1[] = array("rek_bln"=>"10","bln_nama"=>"Oktober");
	$data1[] = array("rek_bln"=>"11","bln_nama"=>"November");
	$data1[] = array("rek_bln"=>"12","bln_nama"=>"Desember");

	switch(_KODE){
		case "060104":
			$kp_ket	= $_SESSION['kp_ket'];
?>
<h3><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h3>
<input type="hidden" class="next_page pref_page" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="next_page pref_page" 	name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="targetId"  	value="content"/>
<input type="hidden" class="next_page"				name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" 				name="pg" value="<?php echo $pref_page; ?>"/>
<?php
			$que0 	= "SELECT *FROM v_rayon WHERE kp_kode='"._KOTA."' LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
			
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
				errorLog::errorDB(array($e->getMessage()));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
?>
<input type="hidden" class="cari" name="proses" value="cari"/>
<input type="hidden" class="cari" name="back" 	value="<?php echo $pg; ?>"/>
<table class="table_info">
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
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="targetId" 	value="cetak_<?php echo $i; ?>"/>
			<span id="cetak_<?php echo $i; ?>">
				<input type="button" value="cetak" onclick="buka('cetak_<?php echo $i; ?>')"/>
			</span>
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
			break;
		case "060108":
			$kp_ket	= $_SESSION['kp_ket'];
?>
<h3><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h3>
<input type="hidden" class="next_page pref_page" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="next_page pref_page" 	name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="targetId"  	value="content"/>
<input type="hidden" class="next_page"				name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" 				name="pg" value="<?php echo $pref_page; ?>"/>
<?php
			$que0 	= "SELECT *FROM v_rayon WHERE kp_kode='"._KOTA."' LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
			
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
				errorLog::errorDB(array($e->getMessage()));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
?>
<input type="hidden" class="cari" name="proses" value="cari"/>
<input type="hidden" class="cari" name="back" 	value="<?php echo $pg; ?>"/>
<table class="table_info">
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
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="targetId" 	value="cetak_<?php echo $i; ?>"/>
			<span id="cetak_<?php echo $i; ?>">
				<input type="button" value="cetak" onclick="buka('cetak_<?php echo $i; ?>')"/>
			</span>
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
			break;
		case "060203":
			// Cetak Rincian DSR
			$kp_ket	= $_SESSION['kp_ket'];
?>
<h3 class="cetak"><?php echo _NAME; ?> - <?php echo $kp_ket; ?></h3>
<input type="hidden" class="next_page pref_page" 	name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="appl_tokn" 	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="next_page pref_page" 	name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="next_page pref_page" 	name="targetId"  	value="content"/>
<input type="hidden" class="next_page"				name="pg" value="<?php echo $next_page; ?>"/>
<input type="hidden" class="pref_page" 				name="pg" value="<?php echo $pref_page; ?>"/>
<?php
			$que0 	= "SELECT *FROM v_rayon WHERE kp_kode='"._KOTA."' LIMIT $limit_awal,$jml_perpage";
			$hint 	= "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk melakukan pencarian data, <b>Up Arrow</b> dan <b>Down Arrow</b> untuk memilih rincian setiap rayon, kemudian <b>Space</b> untuk melihat rinciannya.</div>";
			
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
				errorLog::errorDB(array($e->getMessage()));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
?>
<table class="table_info cetak">
	<tr class="table_cont_btm">
		<td colspan="6" class="right">
			<?php echo pilihan($data1,$parm1); ?>
			<input <?php echo $disabled; ?> type="text" class="<?php echo _KODE; ?> next_page pref_page" name="rek_thn" size="4" maxlength="4" value="<?php echo $rek_thn; ?>" />
			<input type="button" value="Set" onclick="buka('<?php echo _KODE; ?>')" />
		</td>
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
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="rek_bln"		value="<?php echo $rek_bln;		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="rek_thn"		value="<?php echo $rek_thn;		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_kode"	value="<?php echo _KODE;		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_name"	value="<?php echo _NAME; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_file"	value="<?php echo _FILE; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_proc"	value="<?php echo _PROC; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="appl_tokn" 	value="<?php echo _TOKN; 		?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="errorId"   	value="<?php echo getToken();	?>"/>
			<input type="hidden" class="cetak_<?php echo $i; ?>" name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
			<span id="cetak_<?php echo $i; ?>">
				<input type="button" value="cetak" onclick="nonghol('cetak_<?php echo $i; ?>')"/>
			</span>
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
<input id="aktiveForm" type="hidden" value="0"/><?php
			break;
		default : 
?>
<h2 class="cetak"><?php echo _NAME; ?></h2>
<input type="hidden" id="norefresh" value="1" />
<input type="hidden" class="cetak" 	name="appl_kode" value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="cetak" 	name="appl_name" value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="cetak" 	name="appl_file" value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="cetak" 	name="appl_proc" value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="cetak" 	name="targetUrl" value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="cetak" 	name="appl_tokn" value="<?php echo _TOKN; 	?>"/>
<div class="span-4 cetak">Kota Pelayanan</div>
<div class="span-7 cetak">
	: 
	<?php echo pilihan($data2,$parm2); ?>
</div>
<br/><br/>
<div class="span-4 cetak">Bulan - Tahun</div>
<div class="span-7 cetak">
	: 
	<?php echo pilihan($data1,$parm1); ?>
	<input <?php echo $disabled; ?> type="text" class="cetak" name="rek_thn" size="4" maxlength="4" value="<?php echo $rek_thn; ?>"/>
</div>
<br/><br/>
<div class="span-4 cetak">Kategori</div>
<div class="span-7 cetak">
	: 
	<?php echo pilihan($data3,$parm3); ?>
</div>
<br/><br/>
<div class="span-4 cetak">&nbsp;</div>
<div class="span-7 cetak">&nbsp;
	<input type="Button" value="Cetak" onclick="nonghol('cetak')"/>
</div>
<?php
	}
	if(!$erno) mysql_close($link);
?>
