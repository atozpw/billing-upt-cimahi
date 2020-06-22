<?php
	if($erno) die();
	unset($mess);
	if(!isset($proses)){
		$proses = false;
	}
?>
<input type="hidden" class="kembali buka noRek" name="appl_tokn"	value="<?php echo _TOKN; 	?>"/>
<input type="hidden" class="kembali buka noRek" name="appl_kode"	value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="kembali buka noRek" name="appl_name"	value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="kembali buka noRek" name="appl_file"	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="kembali buka noRek" name="appl_proc"	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="kembali buka" 		name="errorId"		value="<?php echo $errorId; ?>"/>
<input type="hidden" class="kembali buka noRek" name="targetUrl" 	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="kembali buka noRek" name="targetId"		value="content"/>
<h2 class="cetak"><?php echo _NAME; ?></h2><hr class="cetak" />
<?php
	switch($proses){
		case "bayar":
			$selesai	= "<input type=\"button\" class=\"cetak\" value=\"Selesai\" onclick=\"buka('kembali')\" />";
?>
<script>$('#peringatan').hide();</script>
<input id="keyProses1"	type="hidden" value="7" />
<input id="norefresh"	type="hidden" value="1" />
<?php
			include _PROC;
			echo "<div class=\"$klas\">$mess</div>";
			echo $selesai;
			break;
		case "rinci":
			try{
				if(strlen($pel_no)==0){
					$pel_no = '043000';
				}
				if($na_kode==1){
					$que0 	= "SELECT 'Biaya Pemasangan Baru' AS na_ket,1 AS na_kode,'Biaya Pemasangan Baru' AS na_rinci,b.biaya_pasang AS tna_jml,a.pem_reg AS pel_no,a.pem_nama AS pel_nama,a.pem_alamat AS pel_alamat,CONCAT('[',d.gol_kode,'] ',d.gol_ket) AS gol_ket,a.dkd_kd,a.pem_sts AS kps_kode FROM tm_pemohon_bp a JOIN tm_rekening_bp b ON(b.pem_reg=a.pem_reg) JOIN tr_gol d ON(d.gol_kode=b.gol_kode) WHERE a.pem_reg='".$pel_no."' AND a.pem_sts=13";
				}
				else{
					$que0 	= "SELECT a.na_ket,a.na_kode,IFNULL(b.tna_ket,a.na_ket) AS na_rinci,b.tna_jml,c.pel_no,c.pel_nama,c.pel_alamat,CONCAT('[',d.gol_kode,'] ',d.gol_ket) AS gol_ket,SUBSTR(c.dkd_kd,-3) AS dkd_kd,c.kps_kode FROM tr_non_air a JOIN tr_tarif_non_air b ON(b.na_kode=a.na_kode) JOIN tm_pelanggan c ON(c.gol_kode=b.gol_kode) JOIN tr_gol d ON(d.gol_kode=c.gol_kode) WHERE a.na_kode=".$na_kode." AND c.pel_no='".$pel_no."'";
				}
				if(!$res0 = mysql_query($que0,$link)){
					throw new Exception(mysql_error($link));
				}
				else{
					$i = 0;
					$grandTotal	= array(0);
					while($row0 = mysql_fetch_array($res0)){
						$data[$i] 		= $row0;
						$grandTotal[$i]	= $row0['tna_jml'];
						$pel_no			= $row0['pel_no'];
						$pel_nama		= $row0['pel_nama'];
						$pel_alamat		= $row0['pel_alamat'];
						$kps_kode		= $row0['kps_kode'];
						$dkd_kd			= $row0['dkd_kd'];
						$golongan		= $row0['gol_ket'];
						$na_kode		= $row0['na_kode'];
						$na_ket			= $row0['na_ket'];
						$i++;
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::logDB(array($que0));
				errorLog::errorDB(array($e->getMessage()));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
			if(!$erno) mysql_close($link);
			if(!isset($data)){
				$data	= array();
			}
			$i	= count($data);
			if($i>0 and abs($noresi)>0){
				$hitung = '';
				if($na_kode==1 AND $kps_kode<>13){
					unset($data);
					?><input type="hidden" id="keyProses1" value="D" /><?php
				}
				elseif($na_kode>=2 AND $na_kode<=4 AND $kps_kode<>7){
					unset($data);
					?><input type="hidden" id="keyProses1" value="D" /><?php
				}
				else{
					?><input type="hidden" id="keyProses1" value="6" /><?php
					$hitung = "<input type=\"button\" value=\"Hitung\" onclick=\"nonghol('kalkulator')\">";
				}
?>
<input type="hidden" id="jumlahForm" value="<?php echo $i; ?>"/>
<input type="hidden" id="aktiveForm" value="0"/>
<input type="hidden" id="bayar" class="noRek kalkulator" name="bayar" value="<?php echo array_sum($grandTotal); ?>"/>
<input type="hidden" class="kalkulator" name="appl_kode" 	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kalkulator" name="appl_name" 	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kalkulator" name="appl_file" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kalkulator" name="appl_proc" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kalkulator" name="errorUrl" 	value="kalkulator.php"/>
<input type="hidden" class="kalkulator" name="targetId" 	value="content"/>
<input type="hidden" class="noRek" 		name="loket" 		value="N"/>
<input type="hidden" class="noRek" 		name="pel_no" 		value="<?php echo $pel_no; 		?>"/>
<input type="hidden" class="noRek" 		name="pel_nama" 	value="<?php echo $pel_nama; 	?>"/>
<input type="hidden" class="noRek" 		name="pel_alamat"	value="<?php echo $pel_alamat; 	?>"/>
<input type="hidden" class="noRek" 		name="dkd_kd"		value="<?php echo $dkd_kd; 		?>"/>
<input type="hidden" class="noRek" 		name="gol_kode"		value="<?php echo $golongan; 	?>"/>
<input type="hidden" class="noRek" 		name="noresi" 		value="<?php echo $noresi; 		?>"/>
<input type="hidden" class="noRek" 		name="na_kode" 		value="<?php echo $na_kode;		?>"/>
<input type="hidden" class="noRek" 		name="na_ket" 		value="<?php echo $na_ket;		?>"/>
<table class="table_info">
	<tr class="table_validator">
		<td width="100px">Nomor SR / Reg</td>
		<td>: <?php echo $pel_no; 			?></td>
		<td>Golongan</td>
		<td>: <?php echo $golongan; 		?></td>
	</tr>
	<tr class="table_validator">
		<td>Nama</td>
		<td>: <?php echo $pel_nama; 		?></td>
		<td>Alamat</td>
		<td>: <?php echo $pel_alamat;		?></td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr class="table_head">
		<td colspan="4"><?php echo $na_ket;	?></td>
	</tr>
	<tr class="table_cont_btm center">
		<td class="left">No</td>
		<td class="left" colspan="2">Rincian</td>
		<td class="right">Biaya</td>
	</tr>
<?php
				if(!isset($data)){
					$data	= array();
				}
				if(count($data)>0){
					for($i=0;$i<count($data);$i++){
						$class_nya 		= "table_cell1";
						if ($i%2==0){
							$class_nya 	= "table_cell2";
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
	<tr class="<?=$class_nya?>" >
		<td class="left"><?php echo ($i+1);	?></td>
		<td class="left" colspan="2"><?php echo $na_rinci; ?></td>
		<td class="right">
			<?php echo number_format($tna_jml);	?>
			<input id="pilih_<?=$i?>" type="hidden" class="noRek" name="pilih[<?=$i?>]" value="1"/>
		</td>
	</tr>
<?php
					}
?>
	<tr class="table_head"> 
		<td align="left" colspan="2">
			<input type="button" value="Kembali" onclick="buka('kembali')"/>&nbsp;<?php echo $hitung; ?>
		</td>
		<td class="right" colspan="2">Grand Total : <b><?php echo number_format(array_sum($grandTotal)); ?></b></td>
	</tr>
</table>
<?php
				}
				else{
?>
	<tr><td class="notice" colspan="4">Tagihan tidak ditemukan</td></tr>					   				   
	<tr class="table_head"> 
		<td align="left" colspan="4">
			<input type="button" value="Kembali" onclick="buka('kembali')"/>
		</td>
	</tr>
</table>
<?php
				}
			}
			else{
?>
<div class="notice span-23 center">Data tagihan tidak ditemukan atau nomer resi belum diset. Tekan tombol <b>B</b> untuk kembali ke halaman sebelumnya.</div>
<hr class="space"/>
<input type="hidden" id="keyProses1" value="D" />
<input type="button" value="Kembali" onclick="buka('kembali')" />
<?php
			}
			break;
		default:
			$que0 	= "SELECT sys_value1 AS noresi FROM system_parameter WHERE sys_param='RESI' AND sys_value='"._USER."'";
			$que1	= "SELECT MAX(tr_sts) AS tr_sts FROM tr_trans_log WHERE DATE(getTanggal(tr_id))=CURDATE() AND kar_id='"._USER."'";
			$res0 	= mysql_query($que0,$link);
			$row0 	= mysql_fetch_array($res0);
			$res1 	= mysql_query($que1,$link);
			$row1 	= mysql_fetch_array($res1);
			$noresi	= $row0['noresi'];
			$tr_sts	= abs($row1['tr_sts']);
			
			/* inquiry tarif non air */
			$data5[]	= array("na_kode"=>0,"na_ket"=>"-");
			try{
				$que5 	= "SELECT na_kode,na_ket FROM tr_non_air ORDER BY na_ket ASC";
				if(!$res5 = mysql_query($que5,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row5 = mysql_fetch_array($res5)){
						$data5[] = array("na_kode"=>$row5['na_kode'],"na_ket"=>$row5['na_ket']);
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que5));
				$mess = $e->getMessage();
				$erno = false;
			}
			$parm5 		= array("id"=>"form-2","class"=>"buka","name"=>"na_kode","selected"=>0,"style"=>"font-size:12pt; font-family:courier; text-align: right","onmouseover"=>"$(this.id).focus()");
			
			switch($tr_sts){
				case 3:
					$status = true;
					break;
				case 4:
					$status = false;
					$parm5 	= array("disabled"=>"disabled","id"=>"form-2","class"=>"buka","name"=>"na_kode","selected"=>0,"style"=>"font-size:12pt; font-family:courier; text-align: right","onmouseover"=>"$(this.id).focus()");
					$mess	= "Loket sudah ditutup. Klik <u onclick=\"buka('081102')\">di sini</u> untuk membuka menu Cetak LPP Rinci";
					$klas	= "notice";
					break;
				default:
					$status = false;
					$parm5 	= array("disabled"=>"disabled","id"=>"form-2","class"=>"buka","name"=>"na_kode","selected"=>0,"style"=>"font-size:12pt; font-family:courier; text-align: right","onmouseover"=>"$(this.id).focus()");
					$mess	= "Loket belum dibuka. Klik <u onclick=\"buka('080101')\">di sini</u> untuk membuka menu Buka Loket";
					$klas	= "notice";
			}
?>
<input type="hidden" class="buka resi" 	name="appl_tokn" 	value="<?php echo getToken(); 	?>"/>
<input type="hidden" class="buka resi" 	name="errorId" 		value="<?php echo getToken(); 	?>"/>
<input type="hidden" class="resi" 		name="targetUrl" 	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="resi" 		name="proses"	 	value="setResi"/>
<input type="hidden" class="resi" 		name="targetId"	 	value="setResi"/>
<input type="hidden" class="buka" 		name="proses"	 	value="rinci"/>
<input type="hidden" id="keyProses1" 	value="C" />
<input type="hidden" id="jumlahForm" 	value="3" />
<input type="hidden" id="aktiveForm" 	value="0" />
<input type="hidden" id="norefresh" 	value="1" />
<div id="setResi"></div>
<div class="span-18">
	<div class="span-4">&nbsp;</div>
	<div class="span-4">Nomor Pelanggan / Reg</div>
	<div class="span08">:
		<input id="form-1" type="text" class="buka sl" name="pel_no" size="13" style="font-size:15pt; font-family:courier;" maxlength="6" onmouseover="$(this.id).focus()" />
	</div>
	<div class="span-4">&nbsp;</div>
	<div class="span-4">Kategori</div>
	<div class="span08">:
		<?php echo pilihan($data5,$parm5); ?>
	</div>
	<div class="span-4">&nbsp;</div>
	<div class="span-4">Nomer Resi</div>
	<div class="span08">:
		<input readonly type="text" id="noresi" class="resi buka" name="noresi" size="10" maxlength="6" value="<?php echo $noresi; ?>" style="font-size:15pt; font-family:courier; text-align: right" onmouseover="$(this.id).focus()" />
	</div>
	<div class="span-12">&nbsp;</div>
	<div class="span-10 right">
		<?php if($status){ ?>
		<input disabled type="Button" value="Set Resi" onclick="buka('resi')"/>
		<input type="Button" id="form-3" value="Cetak Resi" onclick="buka('buka')"/>
		<?php } ?>
	</div>
</div>
<?php if(!$status){ ?>
<div class="span-24 <?php echo $klas; ?>"><?php echo $mess; ?></div>
<?php
		}
	}
?>
