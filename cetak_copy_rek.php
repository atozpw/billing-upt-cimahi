<?php
	if($erno) die();
	$formId = getToken();
	$que0 	= "SELECT b.pel_nama,b.pel_alamat,b.pel_no,b.rek_gol,b.dkd_kd,rek_bln,rek_thn,rek_stanlalu,rek_stankini,rek_total,rek_denda,rek_materai,rek_uangair,rek_adm,rek_meter,byr_tgl,lok_ip,a.kar_id,dkd_tcatat FROM tm_pembayaran a JOIN tm_rekening b ON a.rek_nomor=b.rek_nomor JOIN tm_pelanggan c ON b.pel_no=c.pel_no JOIN tr_kota_pelayanan d ON c.kp_kode=d.kp_kode JOIN tr_dkd e ON b.dkd_kd=e.dkd_kd WHERE a.byr_no='$byr_no' AND a.rek_nomor='$rek_nomor'";
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception($que0);
		}
		else{
			while($row0 = mysql_fetch_array($res0)){
				$data[] = $row0;
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que0));
		$mess = $e->getMessage();
	}	
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<div class="pesan pull-4 span-22 prepend-top">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<input type="hidden" id="keyProses0" 	value="2"/>
<input type="hidden" id="tutup" 		value="<?php echo $formId; ?>"/>
<input type="hidden" class="cetak" name="targetUrl" value="cetak_info_pelanggan.php"/>
<input type="hidden" class="cetak" name="targetId" 	value="targetId"/>
<input type="hidden" class="cetak" name="proses" 	value="cetak"/>
<div id="targetId"></div>
<table width="100%">
	
</table>
<table width="100%" class="prn_table">
<?php
	if(!isset($data)){
		$data	= array();
	}
	for($i=0;$i<count($data);$i++){
		$row0	= $data[$i];
?>
	<tr>
		<td><img src="images/logorekening.jpg" width="80px"></td>
		<td colspan="5" class="center prn_center"><h1>BUKTI PEMBAYARAN</h1></td>
	</tr>
	<tr>
		<td width="30%" class="prn_bold">Yth. Bapak/Ibu <?php echo $row0['pel_nama']; ?></td>
		<td width="15%" class="prn_head">No.Pelanggan</td>
		<td width="15%" class="prn_head">Golongan</td>
		<td width="15%" class="prn_head">Rayon</td>
		<td width="12%" class="prn_head">Bulan</td>
		<td width="13%" class="right prn_right prn_bold">Copy Rekening</td>
	</tr>
	<tr>
		<td><?php echo $row0['pel_alamat']; ?></td>
		<td><?php echo $row0['pel_no']; ?></td>
		<td><?php echo $row0['rek_gol']; ?></td>
		<td><?php echo $row0['dkd_kd']; ?></td>
		<td><?php echo $bulan[$row0['rek_bln']]." ".$row0['rek_thn']; ?></td>
		<td></td>
	</tr>
	<tr>
		<td><hr></td>
		<td colspan="5" class="center prn_center"><h3>RINCIAN PERHITUNGAN BIAYA AIR</h3></td>
	</tr>
	<tr>
		<td rowspan="10" style="vertical-align: top;">
			Bayarlah rekening secara tepat waktu untuk menghindari denda dan penutupan sambungan instalasi air minum.<br><br>
			
			<br>
			e-Mail : pdamlimaukunci@gmail.com<br>
			FB : Pdam Limau Kunci<br><br>
			
			No. Telp : 0728-21369<br>
			(Kantor Pusat)<br><br>
			
			<br>
			<br><br><hr>
			
			<b>NPWP :<br>01-597-702-8-326-000</b>
		</td>
		<td class="prn_head">Tanggal</td>
		<td class="prn_head">Lalu</td>
		<td class="prn_head">Kini</td>
		<td class="prn_head">Pemakaian</td>
		<td class="prn_head">Total</td>
	</tr>
	<tr>
		<td><?php echo $row0['dkd_tcatat']; ?></td>
		<td><?php echo number_format($row0['rek_stanlalu']); ?></td>
		<td><?php echo number_format($row0['rek_stankini']); ?></td>
		<td><?php echo number_format($row0['rek_stankini']-$row0['rek_stanlalu']); ?></td>
		<td><?php echo number_format($row0['rek_total']+$row0['rek_denda']+$row0['rek_materai']); ?></td>
	</tr>
	<tr>
		<td colspan="5" class="center prn_center"><h3>RINGKASAN BIAYA</h3></td>
	</tr>
	<tr>
		<td colspan="2" class="right prn_right">Pemakaian Air :</td>
		<td colspan="2" class="right prn_right"><?php echo number_format($row0['rek_uangair']); ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2" class="right prn_right">Biaya Administrasi :</td>
		<td colspan="2" class="right prn_right"><?php echo number_format($row0['rek_adm']+$row0['rek_meter']); ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2" class="right prn_right">Angsuran :</td>
		<td colspan="2" class="right prn_right"><?php echo number_format($row0['rek_angsuran']); ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2" class="right prn_right">Denda :</td>
		<td colspan="2" class="right prn_right"><?php echo number_format($row0['rek_denda']); ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2" class="right prn_right">Materai :</td>
		<td colspan="2" class="right prn_right"><?php echo number_format($row0['rek_materai']); ?></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="2" class="right prn_right">Total Bulan Ini :</td>
		<td colspan="2" class="right prn_right"><?php echo number_format($row0['rek_total']+$row0['rek_denda']+$row0['rek_materai']); ?></td>
		<td></td>
	</tr>
	<tr>
		<td class="right prn_right prn_head">Terbilang :</td>
		<td colspan="4" class="center prn_center prn_head"><?php echo strtoupper(n2c($row0['rek_total']+$row0['rek_denda']+$row0['rek_materai'])." rupiah"); ?></td>
	</tr>
	<tr>
		<td colspan="6">&nbsp;</td>
	</tr>
	<tr class="prn_breakhere">
		<td colspan="6" class="right prn_right"><i>[<?php echo date('d/m/Y', strtotime($row0['byr_tgl']))." ".date('H:i', strtotime($row0['byr_tgl']))."|".$row0['lok_ip']."|".$row0['kar_id']; ?>]<br>Rekening ini dibuat oleh komputer, tanda tangan pejabat PDAM tidak diperlukan dan sebagai bukti pembayaran yang sah</i></td>
	</tr>
<?php
	}
?>
</table>
</div>
</div>