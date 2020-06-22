<?php
	if($erno) die();
	$formId = getToken();
	/* link : link baca */
	$link 	= mysql_connect($DHOST,$DUSER,$DPASS) or die(errorLog::errorDie(array(mysql_error())));
	mysql_select_db($DNAME,$link) or die(errorLog::errorDie(array(mysql_error())));
	
	/* inquiry data pelanggan */
	$que0 	= "SELECT *FROM tm_reduksi WHERE pel_no='$pel_no' AND SUBSTR(rek_nomor,1,6)=SUBSTR($rek_nomor,1,6) AND sts=1";
	try{
		if(!$res0 = mysql_query($que0,$link)){
			throw new Exception($que0);
		}
		else{
			$i = 0;
			while($row0 = mysql_fetch_array($res0)){
				$data[] = $row0;
				$i++;
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($e->getMessage()));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
	}
	mysql_close($link);	
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<div class="pesan form-5">
<div class="span-14 right large cetak">
	[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]
	[<a onclick="window.print()">Cetak</a>]
</div>
<h3>PDAM Tirta Raharja - <?=$kp_ket?></h3>
<hr/>
<h4>Cetak Berita Acara Reduksi Rekening</h4>
<table width="100%" class="prn_table">
	<tr>
		<td>Tanggal Cetak</td>
		<td colspan="6">: <?=$tanggal?></td>
	</tr>
	<tr>
		<td>Nomor SL</td>
		<td colspan="6">: <?=$pel_no?></td>
	</tr>
	<tr>
		<td>Nama</td>
		<td colspan="6">: <?=$pel_nama?></td>
	</tr>
	<tr>
		<td>Bulan - Tahun</td>
		<td colspan="6">: <?=$bulan[$rek_bln]?> - <?=$rek_thn?></td>
	</tr>
	<tr class="table_cont_btm"> 			
	    <td colspan="2" class="center prn_head">Sebelumnya</td>
	    <td colspan="3" class="center prn_head">Hasil Koreksi</td>
	    <td colspan="2" class="center prn_head">Selisih</td>
	</tr>
	<tr class="table_cont_btm">    
		<td class="center prn_cell">Uang Air (Rp)</td>
		<td class="center prn_cell">Nilai Total (Rp)</td>
		<td class="center prn_cell">Reduksi (%)</td>
		<td class="center prn_cell">Uang Air (Rp)</td>
		<td class="center prn_cell">Nilai Total (Rp)</td>
		<td class="center prn_cell">Uang Air (Rp)</td>
		<td class="center prn_cell">Nilai Total (Rp)</td>
	</tr>
<?php
	for($i=0;$i<count($data);$i++){
		$row1 	= $data[$i];
		$klas 	= "table_cell1";
		if(($i%2) == 0){
			$klas = "table_cell2";
		}
		$rd_uangair_selisih = $row1['rd_uangair_awal'] - $row1['rd_uangair_akhir'];
		$rd_total_awal      = $row1['rd_uangair_awal'] + $rek_beban + $angsuran ;
		$rd_total_akhir     = $row1['rd_uangair_akhir'] + $rek_beban + $angsuran ;
		$rd_total           = $rd_total_awal - $rd_total_akhir;
?>
	<tr valign="top" class="<?php echo $klas; ?>" >										 	    					
		<td class="right prn_cell"><?php echo number_format($row1['rd_uangair_awal']); ?></td>
		<td class="right prn_cell"><?php echo number_format($rd_total_awal); ?></td>
		<td class="right prn_cell"><?php echo number_format($row1['rd_nilai']); ?></td>
		<td class="right prn_cell"><?php echo number_format($row1['rd_uangair_akhir']); ?></td>   		    
		<td class="right prn_cell"><?php echo number_format($rd_total_akhir); ?></td>
		<td class="right prn_cell"><?php echo number_format($rd_uangair_selisih); ?></td>
		<td class="right prn_cell"><?php echo number_format($rd_total); ?></td>
	</tr>
<?php
	}
?>
	<tr class="table_cont_btm">
		<td>&nbsp;</td>
		<td colspan="8"></td>
	</tr>
</table>
<table>
 
	 
				<tr class="form_cell"> 
					<td align="left">Bandung,<?php echo $tanggal; ?></td> 
				</tr> 
				
				<tr class="form_cell"> 
				<td align="left"><table width="80%" align="center"> 
					<tr  class="form_cell"> 
					<td align="left">Diterima Konsumen <br/> Tanggal : </td> 
					<td align="Left"><b>Kepala Cabang </b></td> 
	 				</tr> 
					<tr> 
					<td align="center"><br/><br/><br/><b>(_______________________)</b></td> 
					<td align="center"><br/><br/><br/><b>(_______________________)</b></td> 
	 				</tr> 
				  </table>				</td> 
				</tr> 
</table>
</div>
</div>