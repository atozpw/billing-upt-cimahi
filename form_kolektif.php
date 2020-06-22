<?php
	if($erno) die();
	unset($mess);
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
			//$cetakin	= "<input type=\"button\" class=\"cetak\" value=\"Cetak Bukti Bayar\" />";
			$selesai	= "<input type=\"button\" class=\"cetak\" value=\"Selesai\" onclick=\"buka('kembali')\" />";
?>
<input id="keyProses1"	type="hidden" value="7" />
<input id="norefresh"	type="hidden" value="1" />
<?php
			include _PROC;
			echo "<div class=\"$klas\">$mess</div>";
			echo $selesai." ".$cetakin;
			break;
		case "rinci":
			try{
				$que0 = "SELECT a.*,CONCAT('[',SUBSTR(c.kel_nama,-2),']',c.kel_kolektor) AS kolektor,c.kel_ket AS keterangan FROM v_dsr a JOIN tm_kolektif b ON(b.pel_no=a.pel_no) JOIN tr_kel_kolektif c ON(c.kel_kode=b.kel_kode) WHERE c.kel_nama='"._KOTA.$pel_no."' ORDER BY a.pel_no,a.rek_thn ASC,a.rek_bln ASC";
				if(!$res0 = mysql_query($que0,$link)){
					throw new Exception(mysql_error($link));
				}
				else{
					while($row0 = mysql_fetch_assoc($res0)){
						$data[$row0['pel_no']][] 	= $row0;
						$grandTotal[]				= $row0['rek_total'] + $row0['rek_denda'];
						$kolektor 					= $row0['kolektor'];
						$keterangan					= $row0['keterangan'];
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($e->getMessage()));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
			if(!$erno) mysql_close($link);
			$i	= count($data);
			if($i>0 and abs($noresi)>0){
?>
<input type="hidden" id="keyProses1" value="6" />
<input type="hidden" id="jumlahForm" value="<?=($i+1)?>" />
<input type="hidden" id="aktiveForm" value="0" />
<input type="hidden" id="bayar" class="noRek kalkulator" name="bayar" value="0"/>
<input type="hidden" class="noRek" 		name="appl_tokn"	value="<?php echo _TOKN; 		?>"/>
<input type="hidden" class="noRek" 		name="noresi"		value="<?php echo $noresi; 		?>"/>
<input type="hidden" class="noRek" 		name="loket" 		value="K"/>
<input type="hidden" class="kalkulator" name="errorUrl" 	value="kalkulator.php"/>
<input type="hidden" class="kalkulator" name="targetId" 	value="content"/>
<table class="table_info">
	<tr class="table_validator">
		<td colspan="2">Kolektor</td>
		<td colspan="10">: <?php echo $kolektor; 	?></td>
	</tr>
	<tr class="table_validator">
		<td colspan="2">Keterangan</td>
		<td colspan="10">: <?php echo $keterangan;	?></td>
	</tr>
	<tr class="table_head"> 
		<td colspan="9" align="left">
			<input type="button" value="Batal" onclick="buka('kembali')"/>
		</td>
		<td class="right">Grand Total :</td>				
		<td class="right" valign="top"><b><?=number_format(array_sum($grandTotal))?></b></td>
		<td align="center">
			<input type="button" value="Hitung" onClick="nonghol('kalkulator')"/> 
		</td>				
	</tr>
	<tr class="table_cont_btm center">
		<td colspan="2" >Bulan / Tahun </td>
		<td colspan="3" class="center">Stand Meter </td>
		<td colspan="5" class="center">Rincian Biaya </td>
		<td class="center">Total</td>
		<td></td>
	</tr>
	<tr class="table_cont_btm center">
		<td colspan="2"></td>
		<td class="center">Lalu</td>
		<td class="center">Kini</td>
		<td class="center">Pakai</td>
		<td class="center">Air</td>
		<td class="center">Angsuran</td>
		<td class="center">Biaya Administrasi</td>
		<td class="center">Denda</td>
		<td class="center">Materai</td>
		<td></td>
		<td></td>
	</tr>
<?php
				if(count($data)>0){
					$level1_val 	= $data;
					$level1_key 	= array_keys($level1_val);
					$nomer			= 0;
					$klas 	  		= "table_cell1";
					/* order by level 1 pelanggan */
					for($i=0;$i<count($level1_val);$i++){
						$pel_no		= $level1_key[$i];
						$rek_lmbr	= count($level1_val[$pel_no]);
						$pel_nama	= $level1_val[$pel_no][0]['pel_nama'];
						$pel_alamat	= $level1_val[$pel_no][0]['pel_alamat'];
						if($klas == "table_cell2"){
							$klas = "table_cell1";
						}
						else if($klas == "table_cell1"){
							$klas = "table_cell2";
						}
						$status = "";
						$merah	= "black";
						if($rek_lmbr>1){
							$status = "disabled";
							$merah	= "red";
						}
?>
	<tr class="<?php echo $klas; ?>">
		<th></th>
		<th colspan="2" style="color: <?=$merah?>"><?php echo $pel_no;		?></th>
		<th colspan="4" style="color: <?=$merah?>"><?php echo $pel_nama;	?></th>
		<th colspan="5" style="color: <?=$merah?>"><?php echo $pel_alamat;	?></th>
	</tr>
<?php
						$level2_val		= $level1_val[$level1_key[$i]];
						$level2_key		= array_keys($level2_val);
						/* order by level 2 rincian tunggakan */
						for($k=0;$k<count($level2_val);$k++){
							$nilai	= $level2_val[$level2_key[$k]];
							if($klas == "table_cell2"){
								$klas = "table_cell1";
							}
							else if($klas == "table_cell1"){
								$klas = "table_cell2";
							}
							$kunci	= array_keys($nilai);
							for($m=0;$m<count($kunci);$m++){
								$$kunci[$m] = $nilai[$kunci[$m]];
							}
?>
	<tr class="<?php echo $klas; ?>">
		<td class="right"><?php echo ($nomer+1); ?>.</td>
		<td class="right"><?php echo $rek_bln." - ".$rek_thn;				?></td>
		<td class="right"><?php echo number_format($rek_stanlalu); 			?></td>
		<td class="right"><?php echo number_format($rek_stankini); 			?></td>
		<td class="right"><?php echo number_format($pemakaian); 			?></td>
		<td class="right"><?php echo number_format($rek_uangair); 			?></td>
		<td class="right"><?php echo number_format($rek_angsuran); 			?></td>
		<td class="right"><?php echo number_format($beban_tetap); 			?></td>
		<td class="right"><?php echo number_format($rek_denda); 			?></td>
		<td class="right"><?php echo number_format($rek_materai); 			?></td>
		<td class="right"><?php echo number_format($grandTotal[$nomer]);	?></td>
		<td>
			<input type="hidden" class="noRek" name="pel_no[<?php echo $nomer;?>]" 			value="<?php echo $pel_no;				?>"/>
			<input type="hidden" class="noRek" name="pel_nama[<?php echo $nomer;?>]" 		value="<?php echo $pel_nama;			?>"/>
			<input type="hidden" class="noRek" name="pel_alamat[<?php echo $nomer;?>]" 		value="<?php echo $pel_alamat;			?>"/>
			<input type="hidden" class="noRek" name="rek_gol[<?php echo $nomer;?>]" 		value="<?php echo $rek_gol;				?>"/>
			<input type="hidden" class="noRek" name="rek_bln[<?php echo $nomer;?>]" 		value="<?php echo $rek_bln;				?>"/>
			<input type="hidden" class="noRek" name="rek_thn[<?php echo $nomer;?>]" 		value="<?php echo $rek_thn;				?>"/>
			<input type="hidden" class="noRek" name="rek_nomor[<?php echo $nomer;?>]" 		value="<?php echo $rek_nomor;			?>"/>
			<input type="hidden" class="noRek" name="dkd_kd[<?php echo $nomer;?>]" 			value="<?php echo $dkd_kd;				?>"/>
			<input type="hidden" class="noRek" name="tgl_baca[<?php echo $nomer;?>]"		value="<?php echo $tgl_baca;			?>"/>
			<input type="hidden" class="noRek" name="rek_stanlalu[<?php echo $nomer;?>]" 	value="<?php echo $rek_stanlalu;		?>"/>
			<input type="hidden" class="noRek" name="rek_stankini[<?php echo $nomer;?>]" 	value="<?php echo $rek_stankini;		?>"/>
			<input type="hidden" class="noRek" name="rek_pakai[<?php $nomer;?>]" 			value="<?php echo $pemakaian;			?>"/>
			<input type="hidden" class="noRek" name="rek_uangair[<?php echo $nomer;?>]" 	value="<?php echo $rek_uangair;			?>"/>
			<input type="hidden" class="noRek" name="rek_bayar[<?php echo $nomer;?>]" 		value="<?php echo $grandTotal[$nomer];	?>"/>
			<input type="hidden" class="noRek" name="rek_beban[<?php echo $nomer;?>]"		value="<?php echo $beban_tetap;			?>"/>
			<input type="hidden" class="noRek" name="rek_denda[<?php echo $nomer;?>]" 		value="<?php echo $rek_denda;			?>"/>
			<input type="hidden" class="noRek" name="rek_materai[<?php echo $nomer;?>]" 	value="<?php echo $rek_materai;			?>"/>
			<input id="total_<?=$nomer?>" 	type="hidden" 	value="<?=$grandTotal[$nomer]?>"/>
			<input id="pilih_<?=$nomer?>" 	type="hidden" 	class="noRek" name="pilih[<?=$nomer?>]" value="0"/>
			<input id="form-<?=($nomer+1)?>" 	type="checkbox" class="pilih" <?=$status?> onclick="bayarKol('<?=$nomer?>')" />
		</td>
		</td>
	</tr>
<?php
						$nomer++;
					}
				}
?>
	<tr class="table_head"> 
		<td colspan="9" align="left">
			<input type="button" value="Batal" onclick="buka('kembali')"/>
		</td>
		<td class="right">Grand Total :</td>				
		<td class="right" valign="top"><b><?=number_format(array_sum($grandTotal))?></b></td>
		<td align="center">
			<input type="button" value="Hitung" onClick="nonghol('kalkulator')"/> 
		</td>				
	</tr>
<?php
	}
?>
</table>
<?php
			}
			else{
?>
<div class="notice span-23 center">Data kolektif tidak ditemukan atau nomer resi belum diset. Tekan tombol <b>B</b> untuk kembali ke halaman sebelumnya.</div>
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
			switch($tr_sts){
				case 3:
					$status = true;
					break;
				case 4:
					$status = false;
					$mess	= "Loket sudah ditutup. Klik <u onclick=\"buka('080303')\">di sini</u> untuk membuka menu Cetak LPP Rinci";
					$klas	= "notice";
					break;
				default:
					$status = false;
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
<input type="hidden" id="jumlahForm" 	value="2" />
<input type="hidden" id="aktiveForm" 	value="0" />
<input type="hidden" id="norefresh" 	value="1" />
<div id="setResi"></div>
<div class="span-14">
	<div class="span-4">&nbsp;</div>
	<div class="span-4">Kode Kelompok</div>
	<div class="span-5">:
		<input type="text" id="form-1" class="buka" name="pel_no" size="2" maxlength="2" style="font-size:15pt; font-family:courier;" onmouseover="$(this.id).focus()" />
	</div>
	<div class="span-4">&nbsp;</div>
	<div class="span-4">Nomer Resi</div>
	<div class="span-5">:
		<input readonly type="text" id="noresi" class="resi buka" name="noresi" size="10" maxlength="20" value="<?php echo $noresi; ?>" style="font-size:15pt; font-family:courier; text-align: right" onmouseover="$(this.id).focus()" />
	</div>
	<div class="span-12">&nbsp;</div>
	<div class="span-10 right">
		<?php if($status){ ?>
		<input disabled type="Button" value="Set Resi" onclick="buka('resi')"/>
		<input type="Button" id="form-2" value="Cek Rekening" onclick="buka('buka')"/>
		<?php } ?>
	</div>
</div>
<?php if(!$status){ ?>
<div class="span-24 <?php echo $klas; ?>"><?php echo $mess; ?></div>
<?php
		}
	}
?>