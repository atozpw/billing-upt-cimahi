<?php
	if($erno) die();
	unset($mess);
	if(!isset($proses)){
		$proses 	= false;
	}
	if(!isset($pel_nama)){
		$pel_nama	= "";
	}
	if(!isset($pel_alamat)){
		$pel_alamat	= "";
	}
	if(!isset($golongan)){
		$golongan	= "";
	}
?>
<input type="hidden" class="kembali buka cetakBA" 	name="appl_tokn"	value="<?php echo _TOKN; 	?>"/>
<input type="hidden" class="kembali buka cetakBA" 	name="appl_kode"	value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="kembali buka cetakBA" 	name="appl_name"	value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="kembali buka cetakBA" 	name="appl_file"	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="kembali buka cetakBA" 	name="appl_proc"	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="kembali buka cetakBA" 	name="errorId"		value="<?php echo $errorId; ?>"/>
<input type="hidden" class="kembali buka cetakBA" 	name="targetUrl" 	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="kembali buka cetakBA" 	name="targetId"		value="content"/>
<input type="hidden" class="cetakBA" 				name="proses"		value="cetakBA"/>
<h2 class="cetak"><?php echo _NAME; ?></h2><hr class="cetak" />
<?php
	switch($proses){
		case "cetakBA":
			include _PROC;
?>
<input id="keyProses1"	type="hidden" value="7" />
<input id="norefresh"	type="hidden" value="1" />
<input type="button" class="cetak" value="Selesai" onclick="buka('kembali')" />
<?php
			$erno = false;
			if(!$erno){
?>
<input type="button" class="cetak" value="Cetak Berita Acara" onclick="window.print()"  />
<?php
				$data_key = array_keys($byr_no);
				for($i=0;$i<count($data_key);$i++){
?>
<table class="span-10 prn_table hide">
	<tr>
		<td><img src="images/logorekening.jpg" width="100px"></td>
		<td><h4>Berita Acara Pembatalan Rekening</h4></td>
	</tr>
	<tr>
		<td align="left" colspan="2">Pada Hari ini Tanggal, <?php echo date('d/m/Y', strtotime($tanggal)) ?>. Telah dibatalkan pembayaran rekening Atas Nama :</td>
	</tr>
	<tr>
		<td colspan="2">
			<!-- Rek Kiri -->
			<table align="center" border="0">
				<tr>
					<td width="100">No. Pelanggan</td>
					<td>:</td>
					<td><?php echo $pel_no ?></td>
				</tr>
				<tr>
					<td>Nama</td>
					<td>:</td>
					<td><?php echo $pel_nama ?></td>
				</tr>
				<tr>
					<td>Golongan</td>
					<td>:</td>
					<td><?php echo $golongan ?></td>
				</tr>
				<tr>
					<td>Rekening Bulan</td>
					<td>:</td>
					<td><?php echo $bulan[$rek_bln[$data_key[$i]]] ?> <?php echo $rek_thn[$data_key[$i]] ?></td>
				</tr>
				<tr><td colspan="3">&nbsp;</td></tr>
				<tr>
					<td>Pemakaian Air</td>
					<td>:</td>
					<td align="right"><?php echo number_format($rek_uangair[$data_key[$i]]) ?></td>
				</tr>
				<tr>
					<td>Beban Tetap</td>
					<td>:</td>
					<td align="right"><?php echo number_format($beban_tetap[$data_key[$i]]) ?></td>
				</tr>
				<tr>
					<td>Angsuran</td>
					<td>:</td>
					<td align="right"><?php echo number_format($rek_angsuran[$data_key[$i]]) ?></td>
				</tr>
				<tr>
					<td>Denda</td>
					<td>:</td>
					<td align="right"><?php echo number_format($rek_denda[$data_key[$i]]) ?></td>
				</tr>
				<tr>
					<td>Materai</td>
					<td>:</td>
					<td align="right"><?php echo number_format($rek_materai[$data_key[$i]]) ?></td>
				</tr>
				<tr align="spacer_b"><td colspan="3">&nbsp;</td></tr>
				<tr>
					<td>Total</td>
					<td>:</td>
					<td align="right"><b><?php echo number_format($rek_bayar[$data_key[$i]]) ?></b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td align="left" colspan="2">Demikian berita acara ini di buat, untuk dipergunakan sebagaimana mestinya.</td></tr>
	<tr><td align="left" colspan="2">Pesawaran, <?php echo date('d/m/Y', strtotime($tanggal)) ?></td></tr>

	<tr>
	<td align="left" colspan="2">
		<table width="80%" align="center">
			<tr >
				<td align="center"><b>KAS</b></td>
				<td align="center"><b>Pelanggan</b></td>
			</tr>
			<tr>
				<td align="center"><br/><br/><br/><br/><b>(<?php echo _NAMA ?>)</b></td>
				<td align="center"><br/><br/><br/><br/><b>(<?php echo $pel_nama ?>)</b></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<?php
					if(($i+1)<count($byr_no)){
						echo "<p class=\"prn_breakhere\"></p>";
					}
				}
			}
			break;
		case "rinci":
			$data		= array();
			$grandTotal	= array();
			try{
				if(_USER=='admin'){
					$que0 	= "SELECT *FROM v_lpp WHERE pel_no='$pel_no' ORDER BY rek_thn ASC,rek_bln ASC";
				}
				elseif(strlen($pel_no)==6){
					$que0 	= "SELECT *FROM v_lpp WHERE pel_no='$pel_no' AND DATE(byr_tgl)=CURDATE() AND kar_id='"._USER."' ORDER BY rek_thn ASC,rek_bln ASC";
				}
				else{
					$pel_no	= substr($pel_no,0,2).".".substr($pel_no,2,2).".".substr($pel_no,4,3).".".substr($pel_no,7,3);
					$que0 	= "SELECT *FROM v_lpp WHERE pel_no='$pel_no' AND DATE(byr_tgl)=CURDATE() AND kar_id='"._USER."' ORDER BY rek_thn ASC,rek_bln ASC";
				}
				
				if(!$res0 = $link->query($que0)){
					throw new Exception($link->error);
				}
				else{
					while($row0 = $res0->fetch_array()){
						$data[] 		= $row0;
						$grandTotal[]	= $row0['rek_total'] + $row0['rek_denda'] + $row0['rek_materai'];
						$pel_nama		= $row0['pel_nama'];
						$pel_alamat		= $row0['pel_alamat'];
						$golongan		= $row0['gol_ket'];
						if($row0['rek_bln']==$rek_bln and $row0['rek_thn']==$rek_thn){
							$byr_batal = $row0['byr_no'];
							if($row0['byr_loket']=="N"){
								$byr_normal = 1;
							}
							else{
								$byr_normal = 0;
								$rek_batal 	= $row0['rek_nomor'];
							}
						}
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($e->getMessage()));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
			if(!$erno) $link->close();
?>
<input type="hidden" id="keyProses1" value="F" />
<input type="hidden" id="jumlahForm" value="1" />
<input type="hidden" id="aktiveForm" value="0" />
<input type="hidden" class="cetakBA" name="pel_no"		value="<?php echo $pel_no; 	?>"/>
<input type="hidden" class="cetakBA" name="pel_nama"	value="<?php echo $pel_nama;?>"/>
<input type="hidden" class="cetakBA" name="golongan"	value="<?php echo $golongan;?>"/>
<table class="table_info">
	<tr class="table_validator">
		<td colspan="2">Nomor SR</td>
		<td colspan="5">:<?php echo $pel_no; 		?></td>
		<td colspan="1">Golongan</td>
		<td colspan="4">:<?php echo $golongan; 	?></td>
	</tr>
	<tr class="table_validator">
		<td colspan="2">Nama</td>
		<td colspan="5">:<?php echo $pel_nama; 	?></td>
		<td colspan="1">Alamat</td>
		<td colspan="4">:<?php echo $pel_alamat;	?></td>
	</tr>
	<tr>
		<td colspan="8">&nbsp;</td>
	</tr>
	<tr class="table_head"> 
		<td colspan="8" align="left">
			<input type="button" value="Kembali" onclick="buka('kembali')"/>
		</td>
		<td align="left">Jumlah Rekening :<?=number_format(count($data))?></td>
		<td class="right">Grand Total :</td>				
		<td class="right" valign="center"><b><?=number_format(array_sum($grandTotal))?></b></td>
		<td align="center">
			<input type="button" value="Proses" onClick="buka('cetakBA')"/>
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
			if(!isset($data)){
				$data	= array();
			}
			if(count($data)>0){
				$k = 0;
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
						if(PHP_VERSION < 7){
							$$konci[$k]	= $nilai[$konci[$k]];
						}else{
							${$konci[$k]} = $nilai[$konci[$k]];
						}
					}
					/* getParam **/
					$form = false;
					if($byr_no==$byr_batal){
						if($byr_normal==1){
							$form = true;
							$k++;
						}
						else{
							if($rek_nomor==$rek_batal){
								$form = true;
								$k++;
							}
						}
					}
					if($form){
?>
	<tr class="<?=$class_nya?>">
		<td class="right"><?=$k?></td>
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
			<input type="hidden" class="cetakBA" name="byr_no[<?php echo $rek_nomor;?>]" 		value="<?php echo $byr_no;			?>"/>
			<input type="hidden" class="cetakBA" name="rek_bln[<?php echo $rek_nomor;?>]" 		value="<?php echo $rek_bln;			?>"/>
			<input type="hidden" class="cetakBA" name="rek_thn[<?php echo $rek_nomor;?>]" 		value="<?php echo $rek_thn;			?>"/>
			<input type="hidden" class="cetakBA" name="rek_stanlalu[<?php echo $rek_nomor;?>]" 	value="<?php echo $rek_stanlalu;	?>"/>
			<input type="hidden" class="cetakBA" name="rek_stankini[<?php echo $rek_nomor;?>]" 	value="<?php echo $rek_stankini;	?>"/>
			<input type="hidden" class="cetakBA" name="rek_uangair[<?php echo $rek_nomor;?>]" 	value="<?php echo $rek_uangair;		?>"/>
			<input type="hidden" class="cetakBA" name="beban_tetap[<?php echo $rek_nomor;?>]" 	value="<?php echo $beban_tetap;		?>"/>
			<input type="hidden" class="cetakBA" name="rek_bayar[<?php echo $rek_nomor;?>]" 	value="<?php echo $grandTotal[$i];	?>"/>
			<input type="hidden" class="cetakBA" name="rek_denda[<?php echo $rek_nomor;?>]" 	value="<?php echo $rek_denda;		?>"/>
			<input type="hidden" class="cetakBA" name="rek_materai[<?php echo $rek_nomor;?>]" 	value="<?php echo $rek_materai;		?>"/>
			<input type="hidden" class="cetakBA" name="rek_gol[<?php echo $rek_nomor;?>]" 		value="<?php echo $rek_gol;			?>"/>
		</td>
	</tr>
<?php
					}
				}
			}
			else{
?>
	<tr><td class="notice" colspan="12">Penerimaan tidak ditemukan atau tidak bisa dibatalkan.</td></tr>
<?php
			}
?>					   				   
	<tr class="table_head"> 
		<td colspan="9" align="left">
			<input type="button" value="Kembali" onclick="buka('kembali')"/>
		</td>
		<td class="right">Grand Total :</td>				
		<td class="right" valign="top"><b><?=number_format(array_sum($grandTotal))?></b></td>
		<td align="center">
			<input id="form-1" type="button" value="Proses" onClick="buka('cetakBA')"/> 
		</td>				
	</tr>
</table>
<?php
			break;
		default:
			$que0 	= "SELECT sys_value1 AS noresi FROM system_parameter WHERE sys_param='RESI' AND sys_value='"._USER."'";
			$que1	= "SELECT MAX(tr_sts) AS tr_sts FROM tr_trans_log WHERE DATE(getTanggal(tr_id))=CURDATE() AND kar_id='"._USER."'";
			$res0 	= $link->query($que0);
			$row0 	= $res0->fetch_array();
			$res1 	= $link->query($que1);
			$row1 	= $res1->fetch_array();
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
			for($i=1;$i<=12;$i++){
				$data1[] = array("rek_bln"=>$i,"bln_nama"=>$bulan[$i]);
			}
			$rek_bln= date('n');
			$rek_thn = date('Y');
			if($rek_bln>1){
				$rek_bln--;
				
			}
			else{
				$rek_bln = 12;
				$rek_thn--;
			}
			$parm1	= array("class"=>"buka","id"=>"form-2","name"=>"rek_bln","selected"=>$rek_bln);
?>
<input type="hidden" id="norefresh" 	value="1" />
<input type="hidden" id="keyProses1" 	value="C" />
<input type="hidden" id="jumlahForm" 	value="4" />
<input type="hidden" id="aktiveForm" 	value="0" />
<input type="hidden" class="buka" 	name="appl_tokn" 	value="<?php echo getToken(); 	?>"/>
<input type="hidden" class="buka" 	name="errorId" 		value="<?php echo getToken(); 	?>"/>
<input type="hidden" class="buka" 	name="proses"	 	value="rinci"/>
<div class="span-14">
	<div class="span-4">&nbsp;</div>
	<div class="span-4">Nomor Pelanggan</div>
	<div class="span-5">:
		<input id="form-1" type="text" class="buka sl" name="pel_no" size="13" style="font-size:15pt; font-family:courier;" maxlength="10" onmouseover="$(this.id).focus()" />
	</div>
	<div class="span-4">&nbsp;</div>
	<div class="span-4">Bulan - Tahun</div>
	<div class="span-5">:
		<?php echo pilihan($data1,$parm1); ?>
		<input type="text" id="form-3" class="buka" name="rek_thn" size="4" maxlength="4" value="<?php echo $rek_thn; ?>"/>
	</div>
	<div class="span-12">&nbsp;</div>
	<div class="span-12 right">
		<?php if($status){ ?>
		<input type="Button" id="form-4" value="Cek Rekening" onclick="buka('buka')"/>
		<?php } ?>
	</div>
</div>
<?php if(!$status){ ?>
<div class="span-24 <?php echo $klas; ?>"><?php echo $mess; ?></div>
<?php
		}
	}
?>
