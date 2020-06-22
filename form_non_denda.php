<?php
	if($erno) die();
	unset($mess);
	if(!isset($proses)){
		$proses 	= false;
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
				$que0 		= "SELECT *FROM v_dsr WHERE pel_no='$pel_no' ORDER BY rek_thn ASC,rek_bln ASC";
				if(!$res0 	= mysql_query($que0,$link)){
					throw new Exception(mysql_error($link));
				}
				else{
					while($row0 = mysql_fetch_array($res0)){
						$row0['rek_denda']		= 0;
						$data[] 				= $row0;
						$grandTotal[]			= $row0['rek_total']+$row0['rek_denda'];
						$pel_no					= $row0['pel_no'];
						$pel_nama				= $row0['pel_nama'];
						$pel_alamat				= $row0['pel_alamat'];
						$golongan				= $row0['gol_ket'];
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($e->getMessage()));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
			if(!$erno) mysql_close($link);
			if(!isset($data)){
				$data	= array();
			}
			$i	= count($data);
			if($i>0 and abs($noresi)>0){
?>
<input type="hidden" id="keyProses1" value="6" />
<input type="hidden" id="jumlahForm" value="<?=$i?>"/>
<input type="hidden" id="aktiveForm" value="0"/>
<input type="hidden" id="bayar" class="noRek kalkulator" name="bayar" value="0"/>
<input type="hidden" class="kalkulator" name="errorUrl" value="kalkulator.php"/>
<input type="hidden" class="kalkulator" name="targetId" value="content"/>
<input type="hidden" class="noRek" 		name="loket" 	value="D"/>
<input type="hidden" class="noRek" 		name="pel_no" 		value="<?php echo $pel_no; 		?>"/>
<input type="hidden" class="noRek" 		name="pel_nama" 	value="<?php echo $pel_nama; 	?>"/>
<input type="hidden" class="noRek" 		name="pel_alamat"	value="<?php echo $pel_alamat; 	?>"/>
<input type="hidden" class="noRek" 		name="noresi" 		value="<?php echo $noresi; 		?>"/>
<table class="table_info">
	<tr class="table_validator">
		<td colspan="2">Nomor SR</td>
		<td colspan="5">: <?php echo $pel_no; 		?></td>
		<td colspan="1">Golongan</td>
		<td colspan="4">: <?php echo $golongan; 	?></td>
	</tr>
	<tr class="table_validator">
		<td colspan="2">Nama</td>
		<td colspan="5">: <?php echo $pel_nama; 	?></td>
		<td colspan="1">Alamat</td>
		<td colspan="4">: <?php echo $pel_alamat;	?></td>
	</tr>
	<tr>
		<td colspan="8">&nbsp;</td>
	</tr>
	<tr class="table_head"> 
		<td colspan="8" align="left">
			<input type="button" value="Batal" onclick="buka('kembali')"/>
		</td>
		<td align="left">Jumlah Rekening : <?=number_format(count($data))?></td>
		<td class="right">Grand Total :</td>				
		<td class="right" valign="center"><b><?=number_format(array_sum($grandTotal))?></b></td>
		<td align="center">
			<input type="button" value="Hitung" onClick="nonghol('kalkulator')"/>
		</td>				
	</tr>
	<tr class="table_cont_btm center">
		<td rowspan="2">No.</td>
		<td rowspan="2">Bulan / Tahun</td>
		<td colspan="3" class="center">Stand Meter</td>
		<td colspan="5" class="center">Rincian Biaya</td>
		<td rowspan="2" class="center">Total</td>
		<td rowspan="2"></td>
	</tr>
	<tr class="table_cont_btm center">
		<td class="center">Lalu</td>
		<td class="center">Kini</td>
		<td class="center">Pakai</td>
		<td class="center">Air</td>
		<td class="center">Angsuran</td>
		<td class="center">Biaya Administrasi</td>
		<td class="center">Denda</td>
		<td class="center">Materai</td>
	</tr>
<?php
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
		<td class="right"><?=($i+1)?></td>
		<td class="right"><?=$bulan[$rek_bln]?> <?=$rek_thn?></td>
		<td class="right"><?=number_format($rek_stanlalu)?></td>
		<td class="right"><?=number_format($rek_stankini)?></td>
		<td class="right"><?=number_format($pemakaian)?></td>
		<td class="right"><?=number_format($rek_uangair)?></td>
		<td class="right"><?=number_format($rek_angsuran)?></td>
		<td class="right"><?=number_format($beban_tetap)?></td>
		<td class="right"><?=number_format($rek_denda)?></td>
		<td class="right"><?=number_format($rek_materai)?></td>
		<td class="right"><?=number_format($grandTotal[$i])?></td>
		<td>
			<input type="hidden" class="noRek" name="rek_nomor[<?php echo $i;?>]" 		value="<?php echo $rek_nomor;		?>"/>
			<input type="hidden" class="noRek" name="dkd_kd[<?php echo $i;?>]" 			value="<?php echo $dkd_kd;			?>"/>
			<input type="hidden" class="noRek" name="tgl_baca[<?php echo $i;?>]"		value="<?php echo $tgl_baca;		?>"/>
			<input type="hidden" class="noRek" name="rek_bln[<?php echo $i;?>]" 		value="<?php echo $rek_bln;			?>"/>
			<input type="hidden" class="noRek" name="rek_thn[<?php echo $i;?>]" 		value="<?php echo $rek_thn;			?>"/>
			<input type="hidden" class="noRek" name="rek_stanlalu[<?php echo $i;?>]" 	value="<?php echo $rek_stanlalu;	?>"/>
			<input type="hidden" class="noRek" name="rek_stankini[<?php echo $i;?>]" 	value="<?php echo $rek_stankini;	?>"/>
			<input type="hidden" class="noRek" name="rek_pakai[<?php echo $i;?>]" 		value="<?php echo $pemakaian;		?>"/>
			<input type="hidden" class="noRek" name="rek_uangair[<?php echo $i;?>]" 	value="<?php echo $rek_uangair;		?>"/>
			<input type="hidden" class="noRek" name="rek_beban[<?php echo $i;?>]" 		value="<?php echo $beban_tetap;		?>"/>
			<input type="hidden" class="noRek" name="rek_angsuran[<?php echo $i;?>]" 	value="<?php echo $rek_angsuran;	?>"/>
			<input type="hidden" class="noRek" name="rek_bayar[<?php echo $i;?>]" 		value="<?php echo $grandTotal[$i];	?>"/>
			<input type="hidden" class="noRek" name="rek_denda[<?php echo $i;?>]" 		value="<?php echo $rek_denda;		?>"/>
			<input type="hidden" class="noRek" name="rek_materai[<?php echo $i;?>]" 	value="<?php echo $rek_materai;		?>"/>
			<input type="hidden" class="noRek" name="rek_gol[<?php echo $i;?>]" 		value="<?php echo $rek_gol;			?>"/>
			<input id="total_<?=$i?>" type="hidden" value="<?=$grandTotal[$i]?>"/>
			<input id="pilih_<?=$i?>" type="hidden" class="noRek" name="pilih[<?=$i?>]" value="0"/>
			<input id="form-<?=($i+1)?>" type="checkbox" class="pilih" onclick="bayarKol('<?=$i?>')"/>
		</td>
	</tr>
<?php
				}
			}
			else{
?>
	<tr><td class="notice" colspan="12">Tunggakan tidak ditemukan</td></tr>
<?php
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
</table>
<?php
			}
			else{
?>
<div class="notice span-23 center">Data tunggakan tidak ditemukan atau nomer resi belum diset. Tekan tombol <b>B</b> untuk kembali ke halaman sebelumnya.</div>
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
	<div class="span-4">Nomor Pelanggan</div>
	<div class="span-5">:
		<input id="form-1" type="text" class="buka sl" name="pel_no" size="13" style="font-size:15pt; font-family:courier;" maxlength="10" onmouseover="$(this.id).focus()" />
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
