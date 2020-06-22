<input type="hidden" class="hitung proses cekDSR kembali"	name="appl_kode" 	value="<?php echo _KODE; ?>"/>
<input type="hidden" class="hitung proses cekDSR kembali" 	name="appl_name" 	value="<?php echo _NAME; ?>"/>
<input type="hidden" class="hitung proses cekDSR kembali" 	name="appl_file" 	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="hitung proses cekDSR kembali" 	name="appl_proc" 	value="<?php echo _PROC; ?>"/>
<input type="hidden" class="hitung cekDSR kembali" 			name="targetUrl"  	value="<?php echo _FILE; ?>"/>
<input type="hidden" class="proses"				 			name="targetUrl"  	value="<?php echo _PROC; ?>"/>
<input type="hidden" class="proses"				 			name="dump"		  	value="0"/>
<input type="hidden" class="cekDSR kembali" 				name="targetId"  	value="content"/>
<?php
	if($erno) die();
	if(!isset($proses)){
		$proses = false;
	}
	$kp_kode 	= _KOTA;
	
	/* koneksi database */
	/* link : link baca */
	$mess 	= "user : ".$DUSER." tidak bisa terhubung ke server : ".$DHOST;
	$link 	= mysql_connect($DHOST,$DUSER,$DPASS) or die(errorLog::errorDie(array($mess)));
	try{
		if(!mysql_select_db($DNAME,$link)){
			throw new Exception("user : ".$DUSER." tidak bisa terhubung ke database : ".$DNAME);
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($e->getMessage()));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
		$klas = "error";
		$erno = false;
	}
	
	$kembali= "<input type=\"button\" value=\"Kembali\" onclick=\"buka('kembali')\"/>";
	
	switch($proses){
		case "hitung":
			/** 2.  retrieve kode klaim */
			try{
				$que1 = "SELECT *FROM tr_klaim";
				if(!$res1 = mysql_query($que1,$link)){
					throw new Exception($que1);
				}
				else{
					while($row1 = mysql_fetch_array($res1)){
						$data2[] = array("kl_kode"=>$row1['kl_kode'], "kl_ket"=>$row1['kl_ket']);
					}
					$parm2	= array("class"=>"proses hitung","name"=>"kl_kode","selected"=>$kl_kode);
					$mess 	= false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que1));
				$mess = $e->getMessage();
			}		
		
			$pakai_klaim = $rek_stanklaim - $rek_stanlalu;
			if($pakai_klaim<0){
				$pakai_klaim 	= 0;
				$rek_stanklaim	= $rek_stanlalu;
			}
			/* hitung perubahan rekening air */
			try{
				$que0 = "CALL p_hitung_rekening('$rek_gol','$rek_bln','$rek_thn','$pakai_klaim','1',@b_air,@b_adm,@b_meter)";
				if(!$res0 = mysql_query($que0,$link)){
					throw new Exception($que0);
				}
				else{
					$que2 = "SELECT @b_air AS klaim_uangair";
					$res2 = mysql_query($que2,$link);
					$row2 = mysql_fetch_array($res2);
					$klaim_uangair = $row2['klaim_uangair'];
					$klaim_total = $klaim_uangair + $rek_beban;
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que0));
				$mess = $e->getMessage();
			}
?>
<input type="hidden" class="proses" name="rek_stankini" value="<?php echo $rek_stankini; ?>" />
<input type="hidden" class="proses" name="klaim_uangair" value="<?php echo $klaim_uangair; ?>" />
<table>
	<tr class="table_head"> 
		<td colspan="2" width="25%">Sebelumnya</td>
		<td colspan="2" width="30%">Sekarang (Koreksi)</td>
		<td colspan="2" width="25%">Selisih</td>
		<td width="15%">Alasan klaim </td>
	</tr>
	<tr class="table_cell1">
		<td class="height-1" style="padding-top:6px">Stan Lalu</td>
		<td>: <?php echo number_format($rek_stanlalu); ?></td>
		<td class="height-1">Stan Lalu</td>
		<td>: <?php echo number_format($rek_stanlalu); ?></td>
		<td>Stan Lalu</td>
		<td>: 0</td>
		<td rowspan="6"><?php echo pilihan($data2,$parm2); ?></td>
	</tr>
	<tr class="table_cell2">
		<td class="height-1">Stan Kini</td><td>: <?php echo number_format($rek_stankini);	?></td>
		<td class="height-1">Stan Kini</td>
		<td>
			: <input id="formHitung" type="text" class="proses hitung" name="rek_stanklaim" size="8" value="<?php echo $rek_stanklaim; ?>" onmouseover="$(this.id).select()" />
			<input type="button" value="Hitung" onclick="buka('hitung')"/>
		</td>
		<td>Stan Kini</td>
		<td>: <?php echo number_format($rek_stanklaim - $rek_stankini); ?></td>
	</tr>
	<tr class="table_cell1">
		<td class="height-1">Pemakaian</td><td>: <?php echo number_format($pakai_kini); 			?></td>
		<td class="height-1">Pemakaian</td><td>: <?php echo number_format($pakai_klaim); 			?></td>
		<td>Pemakaian</td>
		<td>: <?php echo number_format($pakai_klaim-$pakai_kini); ?></td>
	</tr>
	<tr class="table_cell2">
		<td class="height-1">Uang Air</td><td>: <?php echo number_format($rek_uangair); 	?></td>
		<td class="height-1">Uang Air</td><td>: <?php echo number_format($klaim_uangair); 	?></td>
		<td>Uang Air</td>
		<td>: <?php echo number_format($klaim_uangair - $rek_uangair);	?></td>
	</tr>
	<tr class="table_cell1">
		<td class="height-1">Nilai Total</td><td>: <?php echo number_format($rek_total); 	?></td>
		<td class="height-1">Nilai Total</td><td>: <?php echo number_format($klaim_total); 	?></td>
		<td>Nilai Total</td>
		<td>: <?php echo number_format($klaim_total - $rek_total);		?></td>
	</tr>
	<tr class="table_cont_btm">
		<td colspan="7" class="right">
			<input type="button" value="Batal" onclick="buka('kembali')" />
			<input type="button" value="Proses" onclick="buka('proses')" />
		</td>
	</tr>
</table>
<?php
			break;
		case "periksaDSR":
			if(strlen($pel_no)==6){
				$que0 = "SELECT *FROM v_data_pelanggan WHERE pel_no='$pel_no' AND kp_kode='$kp_kode'";
			}
			else{
				$pel_no	= substr($pel_no,0,2).".".substr($pel_no,2,2).".".substr($pel_no,4,3).".".substr($pel_no,7,3);
				$que0 = "SELECT *FROM v_data_pelanggan WHERE reff='$pel_no' AND kp_kode='$kp_kode'";
			}
			$mess0		= "Memulai proses klaim untuk SL ".$pel_no." tagihan bulan ".$bulan[$rek_bln]." - ".$rek_thn;
			errorLog::logMess(array($mess0));
			$formId		= getToken();
			$cekMess	= getToken();
			$form1		= true;
			$form2		= true;
			$form3		= true;
			$rek_bln 	= date('m');
			$rek_thn 	= date('Y');
			if($rek_bln==1){
				$rek_bln = 12;
				$rek_thn--;
			}
			else{
				$rek_bln--;
			}
			
			/** 1. retrieve data pelanggan */
			try{
				if(!$res0 = mysql_query($que0,$link)){
					throw new Exception($que0);
				}
				else{
					$i = 0;
					while($row0 = mysql_fetch_array($res0)){
						$data0[] 	= $row0;
						$pel_no		= $row0['pel_no'];
						$i++;
					}
					if($i==0) {
						$mess1	= "<br /><br /><br /><br /><br /><br /><br /><center class=\"notice\">Data pelanggan : ".$pel_no." tidak ditemukan</center>";
						$form1	= false;
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que0));
				$mess = $e->getMessage();
			}
			
			/** 2.  retrieve kode klaim */
			try{
				$que1 = "SELECT *FROM tr_klaim";
				if(!$res1 = mysql_query($que1,$link)){
					throw new Exception($que1);
				}
				else{
					while($row1 = mysql_fetch_array($res1)){
						$data2[] = array("kl_kode"=>$row1['kl_kode'], "kl_ket"=>$row1['kl_ket']);
					}
					$parm2	= array("class"=>"proses hitung","name"=>"kl_kode","selected"=>"2");
					$mess 	= false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que1));
				$mess = $e->getMessage();
			}
			
			/** 3. retrieve catatan klaim */
			try{
				$que4 = "SELECT a.*,b.rek_uangair AS cl_uangair_awal FROM tm_klaim a JOIN tm_rekening b ON(b.rek_nomor=a.rek_nomor) WHERE a.pel_no='$pel_no' AND ABS(SUBSTR(a.rek_nomor,5,2))=$rek_bln AND SUBSTR(a.rek_nomor,1,4)=$rek_thn ORDER BY a.cl_tgl";
				if(!$res4 = mysql_query($que4,$link)){
					throw new Exception($que4);
				}
				else{
					while($row4 = mysql_fetch_array($res4)){
						$data4[] = $row4;
					}
					$mess 	= false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que4));
				$mess = $e->getMessage();
			}
			
			/* 4. retrive drd awal */
			try {
				$que3 = "SELECT rek_nomor,rek_bln,rek_thn,dkd_kd,rek_stanlalu,rek_stankini,rek_uangair,rek_total,rek_gol FROM tm_rekening WHERE pel_no='$pel_no' AND rek_bln =$rek_bln AND rek_thn=$rek_thn ORDER BY rek_tgl ASC LIMIT 1";
				if(!$res3 = mysql_query($que3,$link)){
					throw new Exception($que3);
				}
				else{
					$row3 		= mysql_fetch_array($res3);
					if(isset($onProses)){
						$rek_stanlalu = $rek_stanklaim;
					}
					else{
						$rek_stanlalu = $row3['rek_stanlalu'];
					}
					$pakai_kini	= $row3['rek_stankini'] - $row3['rek_stanlalu'];
					$rek_beban	= $row3['rek_total'] - $row3['rek_uangair'];
					$mess 		= false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que3));
				$mess = $e->getMessage();
			}
			/* end of iquiry */

			/* 5. retrive status bayar */
			try {
				$que5 = "SELECT COUNT(rek_nomor) AS sts_bayar FROM tm_rekening WHERE pel_no='$pel_no' AND rek_bln =$rek_bln AND rek_thn=$rek_thn AND rek_sts=1 AND rek_byr_sts=0";
				if(!$res5 = mysql_query($que5,$link)){
					throw new Exception($que5);
				}
				else{
					$row5 		= mysql_fetch_array($res5);
					$sts_bayar	= $row5['sts_bayar'];
					$mess 		= false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que5));
				$mess = $e->getMessage();
			}
			/* end of inquiry */
			
			/* form data pelanggan */
			if($form1){
				for($i=0;$i<count($data0);$i++){
					$row0	= $data0[$i];
				}
?>
<h2>Klaim / Perubahan Rekening Air </h2>
<input type="hidden" id="keyProses1" value="D" />
<table>
	<tr>
		<td>No. Pelanggan</td>
		<td><?php echo ": ".$row0['pel_no']; 	?></td>
		<td>Golongan</td>
		<td><?php echo ": ".$row0['gol_kode']; 	?></td>
	</tr>
	<tr>
		<td>Nama</td>
		<td><?php echo ": ".$row0['pel_nama']; 	?></td>
		<td>Rayon Pembacaan</td>
		<td><?php echo ": ".$row0['dkd_kd']; 	?></td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td><?php echo ": ".$row0['pel_alamat'];?></td>
		<td>Status</td>
		<td><?php echo ": ".$row0['kps_ket']; 	?></td>
	</tr>
</table>
<?php
		/* from catatan klaim */
		if($form2){
?>
<h3>Catatan Perubahan Rekening [<?php echo $bulan[$rek_bln]." - ".$rek_thn; ?>]</h3>
<table>
	<tr class="table_head">
		<td>Tanggal Klaim</td>
		<td>Stan Lalu Awal</td>
		<td>Stan Kini Awal</td>
		<td>Stan Kini Akhir</td>
		<td>Uang Air Awal</td>
		<td>Uang Air Akhir</td>
		<td>Selisih Uang Air</td>
	</tr>
<?php
			if(!isset($data4)){
				$data4	= array();
?>
	<tr valign="top" class="table_cell1"><td colspan="7">&nbsp;</td></tr>
<?php
			}
			for($i=0;$i<count($data4);$i++){
				$row4 	= $data4[$i];
				$klas 	= "table_cell1";
				if(($i%2) == 0){
					$klas = "table_cell2";
				}
				$cl_uangair_selisih = $row4['cl_uangair_akhir'] - $row4['cl_uangair_awal'];
?>
	<tr valign="top" class="<?php echo $klas; ?>" >  
		<td class="right"><?php echo $row4['cl_tgl']; ?></td>											 	    					
		<td class="right"><?php echo number_format($row4['cl_stanlalu_awal']); 	?></td>
		<td class="right"><?php echo number_format($row4['cl_stankini_awal']);	?></td>
		<td class="right"><?php echo number_format($row4['cl_stankini_akhir']);	?></td>
		<td class="right"><?php echo number_format($row4['cl_uangair_awal']);	?></td>   		    
		<td class="right"><?php echo number_format($row4['cl_uangair_akhir']);	?></td>
		<td class="right"><?php echo number_format($cl_uangair_selisih);		?></td>
	</tr>
<?php
			}
?>
	<tr class="table_cont_btm">
		<td colspan="9">&nbsp;</td>
	</tr>
</table>
<?php
		}
		/* form klaim */
		if(count($row3['rek_nomor'])==1){
			if($sts_bayar==0){
				$form3 = false;
				$mess3	= "<br /><br /><br /><center class=\"notice large\">Tunggakan bulan ".$bulan[$rek_bln]." - ".$rek_thn." telah dibayar</center>";
			}
		}
		else{
			if(!isset($onProses)){
				$form3 = false;
				$mess3	= "<br /><br /><br /><center class=\"notice large\">Informasi rekening tidak ditemukan</center>";
			}
			else{
				$form3	= false;
				$mess3	= "<br /><br /><br /><center class=\"notice large\">Proses klaim telah selsai, silahkan cetak berita acara</center>";
			}
		}
		if($form3){
?>
<h3>Koreksi Stand Meter</h3>
<input type="hidden" class="proses hitung" 	name="appl_tokn"  	value="<?php echo _TOKN; 				?>"/>
<input type="hidden" class="proses hitung" 	name="rek_stanlalu" value="<?php echo $rek_stanlalu;		?>"/>
<input type="hidden" class="proses hitung" 	name="rek_gol"  	value="<?php echo $row3['rek_gol']; 	?>"/>
<input type="hidden" class="proses hitung"	name="targetId"   	value="<?php echo $formId; 				?>"/>
<input type="hidden" class="hitung" 		name="rek_bln"   	value="<?php echo $row3['rek_bln']; 	?>"/>
<input type="hidden" class="hitung" 		name="rek_thn"   	value="<?php echo $row3['rek_thn']; 	?>"/>
<input type="hidden" class="hitung" 		name="errorId"  	value="<?php echo getToken(); 			?>"/>
<input type="hidden" class="hitung" 		name="rek_stankini" value="<?php echo $row3['rek_stankini'];?>"/>
<input type="hidden" class="hitung" 		name="rek_stanlalu" value="<?php echo $row3['rek_stanlalu'];?>"/>
<input type="hidden" class="hitung" 		name="pakai_kini"   value="<?php echo $pakai_kini; 			?>"/>
<input type="hidden" class="hitung" 		name="rek_uangair"  value="<?php echo $row3['rek_uangair']; ?>"/>
<input type="hidden" class="hitung" 		name="rek_beban"  	value="<?php echo $rek_beban; 			?>"/>
<input type="hidden" class="hitung" 		name="rek_total" 	value="<?php echo $row3['rek_total']; 	?>"/>
<input type="hidden" class="hitung" 		name="proses" 		value="hitung"/>
<input type="hidden" class="proses" 		name="pel_no"		value="<?php echo $pel_no; ?>"/>
<input type="hidden" class="proses" 		name="rek_nomor"	value="<?php echo $row3['rek_nomor']; ?>"/>
<div id="<?php echo $formId; ?>">
<table>
	<tr class="table_head"> 
		<td colspan="2" width="25%">Sebelumnya</td>
		<td colspan="2" width="30%">Sekarang (Koreksi)</td>
		<td colspan="2" width="25%">Selisih</td>
		<td width="15%">Alasan klaim </td>
	</tr>
	<tr class="table_cell1">
		<td class="height-1" style="padding-top:6px">Stan Lalu</td>
		<td>: <?php echo number_format($row3['rek_stanlalu']); ?></td>
		<td class="height-1">Stan Lalu</td>
		<td>: <?php echo number_format($rek_stanlalu); ?></td>
		<td>Stan Lalu</td>
		<td>: -</td>
		<td rowspan="6"><?php echo pilihan($data2,$parm2); ?></td>
	</tr>
	<tr class="table_cell2">
		<td class="height-1">Stan Kini</td><td>: <?php echo number_format($row3['rek_stankini']);	?></td>
		<td class="height-1">Stan Kini</td>
		<td>
			: <input id="formHitung" type="text" class="proses hitung" name="rek_stanklaim" size="8" value="<?php echo $row3['rek_stankini']; ?>" onmouseover="$(this.id).select()" />
			<input type="button" class="form_button" value="Hitung" onclick="buka('hitung')"/>
		</td>
		<td>Stan Kini</td>
		<td>: -</td>
	</tr>
	<tr class="table_cell1">
		<td class="height-1">Pemakaian</td><td>: <?php echo number_format($pakai_kini); 			?></td>
		<td class="height-1">Pemakaian</td>
		<td>: -</td>
		<td>Pemakaian</td>
		<td>: -</td>
	</tr>
	<tr class="table_cell2">
		<td class="height-1">Uang Air</td><td>: <?php echo number_format($row3['rek_uangair']); 	?></td>
		<td class="height-1">Uang Air</td>
		<td>: -</td>
		<td>Uang Air</td>
		<td>: -</td>
	</tr>
	<tr class="table_cell1">
		<td class="height-1">Nilai Total</td><td>: <?php echo number_format($row3['rek_total']); 	?></td>
		<td class="height-1">Nilai Total</td>
		<td>: -</td>
		<td>Nilai Total</td>
		<td>: -</td>
	</tr>
	<tr class="table_cont_btm">
		<td colspan="7" class="right">
			<input type="button" value="Batal" onclick="buka('kembali')" />
		</td>
	</tr>
</table>
</div>
<?php
				}
				else{
					echo $mess3;
					errorLog::logMess(array($mess3));
					echo $kembali;
				}		
			}
			else{
				echo $mess1;
				errorLog::logMess(array($mess1));
				echo $kembali;
			}
			break;
		default:
			$rek_bln = date('m');
			$rek_thn = date('Y');
			if($rek_bln==1){
				$rek_bln = 12;
				$rek_thn--;
			}
			else{
				$rek_bln--;
			}
			for($i=1;$i<=12;$i++){
				$data1[] = array("rek_bln"=>$i,"bln_nama"=>$bulan[$i]);
			}
			$parm1	 = array("class"=>"cekDSR","name"=>"rek_bln","selected"=>$rek_bln);
?>
<h2><?php echo _NAME; ?></h2><hr/>
<input type="hidden" class="cekDSR" name="appl_tokn" 	value="<?php echo getToken(); ?>"/>
<input type="hidden" class="cekDSR" name="proses"	 	value="periksaDSR"/>
<input type="hidden" id="keyProses1" value="C" />
<input type="hidden" id="jumlahForm" value="2" />
<input type="hidden" id="aktiveForm" value="0" />
<div class="span-4">&nbsp;</div>
<div class="span-4">Nomor Pelanggan</div>
<div class="span-8">:
	<input id="form-1" type="text" class="cekDSR sl" name="pel_no" size="13" style="font-size:15pt; font-family:courier;" onmouseover="$(this.id).focus()" />
</div>
<br/><br/>
<div class="span-4">&nbsp;</div>
<div class="span-4">Bulan - Tahun</div>
<div class="span-8">:
	<?php echo pilihan($data1,$parm1); ?>
	<input type="text" class="cekDSR" name="rek_thn" size="4" maxlength="4" value="<?php echo $rek_thn; ?>"/>
</div>
<br/><br/>
<div class="span-16 center">
	<input id="form-2" type="Button" value="Cek Rekening" onclick="buka('cekDSR')"/>
</div>
<?php
	}
?>
