<input type="hidden" class="hitung cekDSR kembali"	name="appl_kode" 	value="<?php echo _KODE; 	    ?>"/>
<input type="hidden" class="hitung cekDSR kembali" 	name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="hitung cekDSR kembali" 	name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="hitung cekDSR kembali" 	name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="hitung cekDSR kembali" 	name="targetUrl"  	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="cekDSR kembali" 		name="targetId"  	value="content"/>
<input type="hidden" class="cekDSR"			 		name="errorId"   	value="<?php echo getToken();	?>"/>
<?php
	if($erno) die();
	if(!isset($proses)){
		$proses = false;
	}
	$kp_kode 	= _KOTA;
	$mess		= false;
	$kembali	= "<input type=\"button\" value=\"Kembali\" onclick=\"buka('kembali')\"/>";
	
	switch($proses){
		case "hitung":
			$pemakaian 			= $rek_stankini - $rek_stanlalu;
			if($pemakaian>$reduksi){
				$pakai_reduksi 	= $pemakaian - $reduksi;

				/* hitung perubahan rekening air */
				try{
					$que0 = "CALL p_hitung_rekening('$rek_gol','$rek_bln','$rek_thn','$pakai_reduksi','1',@b_air,@b_adm,@b_meter)";
					if(!$res0 = $link->query($que0)){
						throw new Exception($que0);
					}
					else{
						$que2 				= "SELECT @b_air AS reduksi_uangair";
						$res2 				= $link->query($que2);
						$row2 				= $res2->fetch_array();
						$reduksi_uangair 	= $row2['reduksi_uangair'];
						$reduksi_total 		= $reduksi_uangair + $rek_beban;
						$mess 				= false;
					}
				}
				catch (Exception $e){
					errorLog::errorDB(array($que0));
					$mess = $e->getMessage();
				}
			}
			else{
				$pakai_klaim 		= 0;
				$reduksi			= $pemakaian;
				$reduksi_uangair	= 0;
				$reduksi_total		= $rek_uangair + $rek_beban;
			}

			$rek_total 			= $rek_uangair + $rek_beban;
			$rek_reduksiuangair = $reduksi_uangair;
			$rek_selisihuangair = $rek_uangair - $rek_reduksiuangair;
			$rek_reduksitotal 	= $rek_reduksiuangair + $rek_beban; 
			$rek_selisihtotal 	= $rek_total - $rek_reduksitotal;
?>
<table width="100%">
	<tr class="table_cont_btm">
		<td class="center">No</td>
		<td class="center">Bulan / Tahunan</td>
		<td colspan="3" class="center">Sebelumnya</td>
		<td colspan="3" class="center">Sekarang (Reduksi)</td>
		<td colspan="3" class="center">Selisih</td>
	</tr>
	<tr class="table_cell1">
		<td rowspan="5" class="center"><?php echo $rek_nomor; ?></td>
		<td rowspan="5" class="center"><?php echo $bulan[$rek_bln]." ".$rek_thn;  ?></td>
		<td>Stan Lalu (m3)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($rek_stanlalu); ?></td>
		<td rowspan="2">Reduksi (M3)</td>
		<td rowspan="2">
			:&nbsp;<input id="pilih" class="refresh hitung" name="reduksi" maxlength="2" size="2" value="<?php echo $reduksi; ?>" onmouseover="$('pilih').select()"/>
			<input type="hidden" class="refresh hitung" name="appl_kode" 	value="<?php echo _KODE; 			?>"/>
			<input type="hidden" class="refresh hitung" name="appl_name" 	value="<?php echo _NAME; 			?>"/>
			<input type="hidden" class="refresh hitung" name="appl_file" 	value="<?php echo _FILE; 			?>"/>
			<input type="hidden" class="refresh hitung" name="appl_proc" 	value="<?php echo _PROC; 			?>"/>
			<input type="hidden" class="refresh hitung" name="appl_tokn" 	value="<?php echo _TOKN; 			?>"/>
			<input type="hidden" class="refresh hitung" name="targetUrl"  	value="<?php echo _FILE; 			?>"/>
			<input type="hidden" class="refresh hitung" name="targetId"   	value="targetReduksi"/>
			<input type="hidden" class="refresh hitung" name="errorId"  	value="<?php echo getToken(); 		?>"/>
			<input type="hidden" class="refresh hitung" name="pel_no" 		value="<?php echo $pel_no;			?>"/>
			<input type="hidden" class="refresh hitung" name="pel_nama" 	value="<?php echo $pel_nama;		?>"/>
			<input type="hidden" class="refresh hitung" name="rek_nomor"  	value="<?php echo $rek_nomor; 		?>"/>
			<input type="hidden" class="refresh hitung" name="rek_bln"    	value="<?php echo $rek_bln;			?>"/>
			<input type="hidden" class="refresh hitung" name="rek_thn"    	value="<?php echo $rek_thn; 		?>"/>
			<input type="hidden" class="refresh hitung" name="rek_gol"    	value="<?php echo $rek_gol; 		?>"/>
			<input type="hidden" class="refresh hitung" name="rek_stanlalu" value="<?php echo $rek_stanlalu; 	?>"/>
			<input type="hidden" class="refresh hitung" name="rek_stankini" value="<?php echo $rek_stankini; 	?>"/>
			<input type="hidden" class="refresh hitung" name="rek_uangair"  value="<?php echo $rek_uangair; 	?>"/>
			<input type="hidden" class="refresh hitung" name="rek_beban" 	value="<?php echo $rek_beban; 		?>"/>
			<input type="hidden" class="refresh hitung" name="proses" 		value="hitung"/>
		</td>
		<td rowspan="2"><input type="button" value="Hitung" onclick="buka('hitung')"/></td>
		<td rowspan="3">&nbsp;</td>
		<td rowspan="3">&nbsp;</td>
		<td rowspan="3">&nbsp;</td>
	</tr>
	<tr class="table_cell2">
		<td>Stan Kini (m3)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($rek_stankini);		?></td>
	</tr>
	<tr class="table_cell1">
		<td>Pemakaian (m3)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($pemakaian); 		?></td>
		<td>Pemakaian (m3)</td>
		<td>:</td>
		<td class="right"><?php echo " ".number_format($pemakaian); 		?></td>
	</tr>
	<tr class="table_cell2">
		<td>Uang Air (Rp)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($rek_uangair); 		?></td>
		<td>Uang Air (Rp)</td>
		<td>:</td>
		<td class="right"><?php echo " ".number_format($rek_reduksiuangair);?></td>
		<td>Uang Air (Rp)</td>
		<td>:</td>
		<td class="right"><?php echo " ".number_format($rek_selisihuangair);?></td>
	</tr>
	<tr class="table_cell1">
		<td>Nilai Total (Rp)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($rek_total); 		?></td>
		<td>Nilai Total (Rp)</td>
		<td>:</td>
		<td class="right"><?php echo " ".number_format($rek_reduksitotal); 	?></td>
		<td>Nilai Total (Rp)</td>
		<td>:</td>
		<td class="right"><?php echo " ".number_format($rek_selisihtotal); 	?></td>
	</tr>
<?php
		if($reduksi<=50) {
?>
		<td id="prosesID" colspan="11" class="table_cont_btm right">
			<input type="button" value="Proses Reduksi" onclick="buka('proses_reduksi')"/>
			<input type="hidden" class="proses_reduksi" name="appl_kode" 			value="<?php echo _KODE;				?>"/>
			<input type="hidden" class="proses_reduksi" name="appl_kode" 			value="<?php echo _KODE; 				?>"/>
			<input type="hidden" class="proses_reduksi" name="appl_name" 			value="<?php echo _NAME; 				?>"/>
			<input type="hidden" class="proses_reduksi" name="appl_file" 			value="<?php echo _FILE; 				?>"/>
			<input type="hidden" class="proses_reduksi" name="appl_proc" 			value="<?php echo _PROC; 				?>"/>
			<input type="hidden" class="proses_reduksi" name="appl_tokn" 			value="<?php echo _TOKN; 				?>"/>
			<input type="hidden" class="proses_reduksi" name="targetUrl" 			value="<?php echo _PROC; 				?>"/>
			<input type="hidden" class="proses_reduksi" name="rek_reduksiuangair" 	value="<?php echo $rek_reduksiuangair; 	?>"/>
			<input type="hidden" class="proses_reduksi" name="reduksi"	 			value="<?php echo $reduksi; 			?>"/>
			<input type="hidden" class="proses_reduksi" name="pel_no" 				value="<?php echo $pel_no;				?>"/>
			<input type="hidden" class="proses_reduksi" name="rek_uangair"			value="<?php echo $rek_uangair;		 	?>"/>
			<input type="hidden" class="proses_reduksi" name="rek_stanlalu"			value="<?php echo $rek_stanlalu;	 	?>"/>
			<input type="hidden" class="proses_reduksi" name="rek_stankini"			value="<?php echo $rek_stankini;	 	?>"/>
			<input type="hidden" class="proses_reduksi" name="rek_nomor" 			value="<?php echo $rek_nomor;			?>"/>
			<input type="hidden" class="proses_reduksi" name="rek_beban" 			value="<?php echo $rek_beban;			?>"/>
			<input type="hidden" class="proses_reduksi" name="rek_gol"	 			value="<?php echo $rek_gol;				?>"/>
			<input type="hidden" class="proses_reduksi" name="errorId"   			value="<?php echo getToken();			?>"/>
			<input type="hidden" class="proses_reduksi" name="targetId"				value="prosesID"/>
			<input type="hidden" class="proses_reduksi" name="proses"	 			value="proses_reduksi"/>
			<input type="hidden" class="proses_reduksi" name="dump"		 			value="0"/>
			<input type="button" value="Batal" onclick="buka('kembali')" />
		</td>
	</tr>
</table>
<?php
		}
		else{
?>
	<tr>
		<td colspan="11" class="table_cont_btm right">
		    <input name="batal" class="kembali" type="button" value="Kembali" onclick="buka('kembali')" />
			<input type="hidden" id="<?php echo $errorId; ?>" value="Maksimal reduksi yang diperbolehkan ialah 50 M3"/>
		</td>
	</tr>
</table>
<?php
 }
			break;
		case "periksaDSR":
?>
<input type="hidden" id="norefresh" 	value="1" />
<input type="hidden" id="keyProses1"	value="D" />
<input type="hidden" id="<?php echo $errorId; ?>" value="<?php echo $mess; ?>"/>
<input type="hidden" class="kembali" name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali" name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali" name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali" name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali" name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali" name="targetId"  	value="content"/>
<?php
			$formId		= getToken();
			$cekMess	= getToken();
			$form1		= false;
			$form2		= false;
			$form3		= false;
			/* 1. retrieve data pelanggan */
			$que0 = "SELECT *FROM v_data_pelanggan WHERE pel_no='$pel_no' AND kp_kode='$kp_kode'";
			try{
				if(!$res0 = $link->query($que0)){
					throw new Exception($link->error);
				}
				else{
					$form1	= true;
					$i 		= 0;
					while($row0 = $res0->fetch_array()){
						$data0[] = $row0;
						$i++;
					}
					if($i==0) {
						$mess1	= "<center class=\"pesan\">Data Pelanggan dengan SL ".$pel_no." Tidak ditemukan<br/>".$kembali."</center>";
						$form1	= false;
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				$mess = $e->getMessage();
				errorLog::errorDB(array($mess));
			}
			/* 2. retrieve catatan reduksi */
			try {
				$que1 = "SELECT *FROM tm_reduksi WHERE pel_no='$pel_no' AND ABS(SUBSTR(rek_nomor,5,2))=$rek_bln AND SUBSTR(rek_nomor,1,4)=$rek_thn ORDER BY rd_tgl DESC";
				if(!$res1 = $link->query($que1)){
					throw new Exception($link->error);
				}
				else{
					$form2	= true;
					$i 		= 0;
					while($row1 = $res1->fetch_array()){
						$data1[] = $row1;
						$i++;	
					}
					if($i==0) {
						$form2 = false;
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				$mess = $e->getMessage();
				errorLog::errorDB(array($mess));
			}
			/* 3. retrive dsr awal */
			try {
				$que2 = "SELECT *FROM tm_rekening WHERE pel_no='$pel_no' AND rek_bln=$rek_bln AND rek_thn=$rek_thn ORDER BY rek_tgl ASC LIMIT 1";
				if(!$res2 = $link->query($que2)){
					throw new Exception($que2);
				}
				else{
					$form3	= true;
					$i 		= 0;
					while($row2 = $res2->fetch_array()){
						$data2[] 		= $row2;
						$beban_tetap 	= $row2['rek_adm'] + $row2['rek_meter'];
						$angsuran 		= $row2['rek_angsuran'];
						$i++;	
					}
					if($i==0){
						$mess3	= "<br /><center class=\"notice\">Pelanggan tidak memiliki tunggakan</center>";
						$form3 	= false;
					}
				$mess = false;
				}
			}
			catch (Exception $e){
				$mess = $e->getMessage();
				errorLog::errorDB(array($mess));
			}
			$link->close();
			/* form data pelanggan */
			if($form1){
				for($i=0;$i<count($data0);$i++){
					$row0	= $data0[$i];
				}
?>
<h2>REDUKSI REKENING</h2>
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
		<td><b><?php echo ": ".$row0['kps_ket'];?></b></td>
	</tr>
</table>
<hr/>
<?php
			/* from catatan reduksi */
			if($form2){
?>
<h3>Catatan Reduksi Sebelumnya</h3>
<table width="100%">
	<tr class="table_cont_btm"> 
	    <td rowspan="1" class="center">Tanggal</td>				
	    <td colspan="2" class="center">Sebelumnya</td>
	    <td colspan="3" class="center">Hasil Koreksi</td>
	    <td colspan="2" class="center">Selisih</td>
	</tr>
	<tr class="table_cont_btm"> 			
		<td></td>  				    
		<td class="center">Uang Air (Rp)</td>
		<td class="center">Nilai Total (Rp)</td>
		<td class="center">Reduksi (M3)</td>
		<td class="center">Uang Air (Rp)</td>
		<td class="center">Nilai Total (Rp)</td>
		<td class="center">Uang Air (Rp)</td>
		<td class="center">Nilai Total (Rp)</td>
	</tr>
<?php
				for($i=0;$i<count($data1);$i++){
					$row1 	= $data1[$i];
					$klas 	= "table_cell1";
					if(($i%2) == 0){
						$klas = "table_cell2";
					}
					$rd_uangair_selisih = $row1['rd_uangair_awal'] - $row1['rd_uangair_akhir'];
					$rd_total_awal      = $row1['rd_uangair_awal'] + $beban_tetap + $angsuran ;
					$rd_total_akhir     = $row1['rd_uangair_akhir'] + $beban_tetap + $angsuran ;
					$rd_total           = $rd_total_awal - $rd_total_akhir;
?>
	<tr valign="top" class="<?php echo $klas; ?>" >  
		<td class="center"><?php echo $row1['rd_tgl']; ?></td>											 	    					
		<td class="right"><?php echo number_format($row1['rd_uangair_awal']); ?></td>
		<td class="right"><?php echo number_format($rd_total_awal); ?></td>
		<td class="right"><?php echo number_format($row1['rd_nilai']); ?></td>
		<td class="right"><?php echo number_format($row1['rd_uangair_akhir']); ?></td>   		    
		<td class="right"><?php echo number_format($rd_total_akhir); ?></td>
		<td class="right"><?php echo number_format($rd_uangair_selisih); ?></td>
		<td class="right"><?php echo number_format($rd_total); ?></td>
	</tr>
<?php
				}
?>
	<tr class="table_cont_btm">
		<td>&nbsp;</td>
		<td colspan="8"></td>
	</tr>
</table>
<?php
			}
			/* form reduksi */
			if($form3){
				for($i=0;$i<count($data2);$i++){
					$row2 	= $data2[$i];
				}
				$pemakaian = $row2['rek_stankini'] - $row2['rek_stanlalu'];
				$rek_beban = $row2['rek_adm'] + $row2['rek_meter'] + $row2['rek_angsuran'];
?>
<h3>Data Saldo Rekening</h3>
<div id="targetReduksi">
<table width="100%">
	<tr class="table_cont_btm">
		<td class="center">No</td>
		<td class="center">Bulan / Tahunan</td>
		<td colspan="3" class="center">Sebelumnya</td>
		<td colspan="3" class="center">Sekarang (Reduksi)</td>
	</tr>
	<tr class="table_cell1">
		<td rowspan="5" class="center"><?php echo $row2['rek_nomor']; ?></td>
		<td rowspan="5" class="center"><?php echo $bulan[$row2['rek_bln']]." ".$row2['rek_thn'];  ?></td>
		<td>Stan Lalu (m3)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($row2['rek_stanlalu']); ?></td>
		<td rowspan="3">Reduksi (M3)</td>
		<td rowspan="3">
			:&nbsp;<input id="pilih" class="hitung" name="reduksi" maxlength="2" size="5" value="0" onmouseover="$('pilih').select()"/>
			&nbsp;<input type="button" name="Button" value="Hitung" onclick="buka('hitung')"/>
			<input type="hidden" class="hitung" name="appl_kode" 	value="<?php echo _KODE; 					?>"/>
			<input type="hidden" class="hitung" name="appl_name" 	value="<?php echo _NAME; 					?>"/>
			<input type="hidden" class="hitung" name="appl_file" 	value="<?php echo _FILE; 					?>"/>
			<input type="hidden" class="hitung" name="appl_proc" 	value="<?php echo _PROC; 					?>"/>
			<input type="hidden" class="hitung" name="appl_tokn" 	value="<?php echo _TOKN; 					?>"/>
			<input type="hidden" class="hitung" name="targetUrl"  	value="<?php echo _FILE; 					?>"/>
			<input type="hidden" class="hitung" name="targetId"   	value="targetReduksi"/>
			<input type="hidden" class="hitung" name="errorId"  	value="<?php echo getToken(); 				?>"/>
			<input type="hidden" class="hitung" name="pel_no" 		value="<?php echo $row0['pel_no'];			?>"/>
			<input type="hidden" class="hitung" name="pel_nama" 	value="<?php echo $row0['pel_nama'];		?>"/>
			<input type="hidden" class="hitung" name="rek_nomor"  	value="<?php echo $row2['rek_nomor']; 		?>"/>
			<input type="hidden" class="hitung" name="rek_bln"    	value="<?php echo $row2['rek_bln'];	 		?>"/>
			<input type="hidden" class="hitung" name="rek_thn"    	value="<?php echo $row2['rek_thn']; 		?>"/>
			<input type="hidden" class="hitung" name="rek_gol"    	value="<?php echo $row2['rek_gol']; 		?>"/>
			<input type="hidden" class="hitung" name="rek_stanlalu" value="<?php echo $row2['rek_stanlalu']; 	?>"/>
			<input type="hidden" class="hitung" name="rek_stankini" value="<?php echo $row2['rek_stankini']; 	?>"/>
			<input type="hidden" class="hitung" name="rek_uangair"  value="<?php echo $row2['rek_uangair']; 	?>"/>
			<input type="hidden" class="hitung" name="rek_beban" 	value="<?php echo $rek_beban; 				?>"/>
			<input type="hidden" class="hitung" name="proses" 		value="hitung"/>
		</td>
	</tr>
	<tr class="table_cell2">
		<td>Stan Kini (m3)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($row2['rek_stankini']); ?></td>
	</tr>
	<tr class="table_cell1">
		<td>Pemakaian (m3)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($pemakaian); ?></td>
	</tr>
	<tr class="table_cell2">
		<td>Uang Air (Rp)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($row2['rek_uangair']); ?></td>
		<td colspan="3"></td>
	</tr>
	<tr class="table_cell1">
		<td>Nilai Total (Rp)</td>
		<td class="right">:</td>
		<td class="right"><?php echo " ".number_format($row2['rek_total']); ?></td>
		<td colspan="3"></td>
	</tr>
	<tr class="table_cont_btm">
		<td colspan="8" class="right">
			<input name="batal" class="kembali" type="button" value="Kembali" onclick="buka('kembali')" />
		</td>
	</tr>
</table>
</div>
<?php
				}
				else{
					echo $mess3;
					echo $kembali;
				}		
			}
			else{
				echo $mess1;
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
			$parm1	 = array("id"=>"form-2","class"=>"cekDSR","name"=>"rek_bln","selected"=>$rek_bln);
?>
<h2><?php echo _NAME; ?></h2><hr/>
<input type="hidden" class="cekDSR" name="appl_tokn" 	value="<?php echo getToken(); ?>"/>
<input type="hidden" class="cekDSR" name="proses"	 	value="periksaDSR"/>
<input type="hidden" id="keyProses1" value="C" />
<input type="hidden" id="jumlahForm" value="4" />
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
	<input id="form-3" type="text" class="cekDSR" name="rek_thn" size="4" maxlength="4" value="<?php echo $rek_thn; ?>"/>
</div>
<br/><br/>
<div class="span-16 center">
	<input id="form-4" type="Button" value="Cek Rekening" onclick="buka('cekDSR')"/>
</div>
<?php
	}
?>
