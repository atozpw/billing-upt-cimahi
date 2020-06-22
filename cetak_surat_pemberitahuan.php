<?php
	if($erno) die();
	$formId = getToken();

	try{
		$que0 = "SELECT b.pel_no, b.pel_nama, b.pel_alamat, b.rek_gol, substr(b.dkd_kd,1,2) as dkd_kd, count(b.pel_no) as rek_jml, sum(b.rek_total) as rek_total, sum(getDenda(b.rek_gol,b.rek_bln,b.rek_thn)) as den_total FROM tm_surat_peringatan a JOIN tm_rekening b ON a.pel_no=b.pel_no WHERE a.sr_nomor='$sr_nomor' AND b.rek_sts=1 AND b.rek_byr_sts=0 GROUP BY b.pel_no";
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

	try{
		$que1 = "SELECT sys_value FROM system_parameter WHERE sys_param='DENDA'";
		if(!$res1 = mysql_query($que1,$link)){
			throw new Exception($que1);
		}
		else{
			while($row1 = mysql_fetch_array($res1)){
				$denda 	= $row1['sys_value'];
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que1));
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
	<tr>
		<td colspan="6" class="center cetak"><h3>Cetak Surat Pemberitahuan</h3></td>
	</tr>
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
		<td colspan="4">
			<table width="100%">
				<tr>
					<td width="50%"><img src="images/logorekening.jpg" width="80px"></td>
					<td width="15%">BATURAJA, </td>
					<td><?php echo date('d')."-".date('m')."-".date('Y'); ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Kepada YTH.</td>
					<td>: <?php echo $row0['pel_nama']; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>No. Pel/Gol</td>
					<td>: <?php echo $row0['pel_no']."/".$row0['rek_gol']; ?></td>
				</tr>
				<tr>
					<td></td>
					<td>Alamat</td>
					<td>: <?php echo $row0['pel_alamat']; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="5%">Perihal</td>
		<td width="1%" class=""> : </td>
		<td colspan="2" class="prn_bold"><b>Pemberitahuan</b></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td colspan="2">Disampaikan dengan hormat, bahwa berdasarkan catatan kami, sampai dengan saat ini Bapak/Ibu/Saudara sebagai pelanggan <?php echo $appl_owner; ?>, mempunyai tunggakan rekening sebesar Rp. <?php echo number_format($row0['rek_total']); ?> (<?php echo n2c($row0['rek_total']); ?>) <b class="prn_bold">*belum termasuk denda.</b></td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td colspan="2">Untuk konfirmasi dan penyelesaian lebih lanjut Bapak/Ibu/Saudara harap menghubungi kantor pelayanan <?php echo $appl_owner; ?> selambat-lambatnya 3 (tiga) hari setelah diterimanya surat ini pada :</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td width="5%">Hari/Tgl</td>
		<td>: Setiap hari kerja</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td>Waktu</td>
		<td>: 08:00 - 14:00</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td>Alamat</td>
		<td>: 
			<?php
				if($row0['dkd_kd']=='10'){
					echo "Jl. C. Suwarno Gedong Tataan Pesawaran 35371";
				}
			?>
		</td>
	</tr>
		<tr>
		<td></td>
		<td></td>
		<td colspan="2">Jika Bapak/Ibu/Saudara tidak mengindahkan pemberitahuan ini, sampai batas waktu yang telah kami tentukan terpaksa sambungan dirumah Bapak/Ibu/Saudara kami putuskan tanpa pemberitahuan kembali.</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td colspan="2">Demikian hal ini disampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</td>
	</tr>
	<tr>
		<td colspan="4">
			<table width="100%">
				<tr>
					<td width="50%">Diterima Oleh</td>
					<td width="50%" class="center prn_center">Kepala Unit</td>
				</tr>
				<tr>
					<td>Tanggal : <?php echo date('d')."-".date('m')."-".date('Y'); ?></td>
					<td></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>( ____________________________ )</td>
					<td class="center prn_center">( ____________________________ )</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="4"><small>Catatan :</small></td>
	</tr>
	<tr>
		<td colspan="4"><small class="prn_bold">Surat pemberitahuan ini dapat diabaikan apabila pada saat pemberitahuan ini diterima, Bapak/Ibu/Saudara telah menyelesaikan tunggakan sebagaimana tersebut diatas.</small></td>
	</tr>
	<tr class="prn_breakhere">
		<td colspan="6">&nbsp;</td>
	</tr>
<?php
	}
?>
</table>
</div>
</div>