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
<input type="hidden" class="kembali buka valBatal" 	name="appl_tokn"	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="kembali buka valBatal" 	name="appl_kode"	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali buka valBatal" 	name="appl_name"	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali buka valBatal" 	name="appl_file"	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali buka valBatal" 	name="appl_proc"	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali buka" 			name="errorId"		value="<?php echo getToken();	?>"/>
<input type="hidden" class="kembali buka" 			name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali buka" 			name="targetId"		value="content"/>
<h2 class="cetak"><?php echo _NAME; ?></h2><hr class="cetak" />
<?php
	switch($proses){
		case "rinci":
			$data		= array();
			$grandTotal	= array();
			try{
				if(_USER=='admin'){
					$que0 	= "SELECT a.*,b.ba_no FROM v_lpp a JOIN tm_pembatalan b ON(b.byr_no=a.byr_no AND b.rek_nomor=a.rek_nomor AND b.btl_sts=2) WHERE a.pel_no='$pel_no' ORDER BY a.rek_thn ASC,a.rek_bln ASC";
				}
				elseif(strlen($pel_no)==6){
					$que0 	= "SELECT a.*,b.ba_no FROM v_lpp a JOIN tm_pembatalan b ON(b.byr_no=a.byr_no AND b.rek_nomor=a.rek_nomor AND b.btl_sts=2) WHERE a.pel_no='$pel_no' AND DATE(a.byr_tgl)=CURDATE() ORDER BY a.rek_thn ASC,a.rek_bln ASC";
				}
				else{
					$pel_no	= substr($pel_no,0,2).".".substr($pel_no,2,2).".".substr($pel_no,4,3).".".substr($pel_no,7,3);
					$que0 	= "SELECT a.*,b.ba_no FROM v_lpp a JOIN tm_pembatalan b ON(b.byr_no=a.byr_no AND b.rek_nomor=a.rek_nomor AND b.btl_sts=2) WHERE a.pel_no='$pel_no' AND DATE(a.byr_tgl)=CURDATE() ORDER BY a.rek_thn ASC,a.rek_bln ASC";
				}
				if(!$res0 = mysql_query($que0,$link)){
					throw new Exception(mysql_error($link));
				}
				else{
					while($row0 = mysql_fetch_array($res0)){
						$data[] 		= $row0;
						$grandTotal[]	= $row0['rek_total'] + $row0['rek_denda'] + $row0['rek_materai'];
						$pel_nama		= $row0['pel_nama'];
						$pel_alamat		= $row0['pel_alamat'];
						$golongan		= $row0['gol_ket'];
						if($row0['rek_bln']==$rek_bln and $row0['rek_thn']==$rek_thn){
							$ba_batal = $row0['ba_no'];
						}
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que0));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
			if(!$erno) mysql_close($link);
?>
<input type="hidden" id="keyProses1" value="F" />
<input type="hidden" id="jumlahForm" value="1" />
<input type="hidden" id="aktiveForm" value="0" />
<input type="hidden" class="valBatal" name="targetUrl" 	value="<?php echo _PROC;		?>"/>
<input type="hidden" class="valBatal" name="ba_batal" 	value="<?php echo $ba_batal;	?>"/>
<input type="hidden" class="valBatal" name="errorId"	value="<?php echo getToken(); 	?>"/>
<input type="hidden" class="valBatal" name="targetId"	value="targetId"/>
<input type="hidden" class="valBatal" name="proses"		value="valBatal"/>
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
			<input type="button" value="Kembali" onclick="buka('kembali')"/>
		</td>
		<td align="left">Jumlah Rekening :<?=number_format(count($data))?></td>
		<td class="right">Grand Total :</td>				
		<td class="right" valign="center"><b><?=number_format(array_sum($grandTotal))?></b></td>
		<td align="center"></td>				
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
					$form = false;
					if($ba_no==$ba_batal){
						$form = true;
					}
					if($form){
?>
	<tr class="<?=$class_nya?>">
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
		<td></td>
	</tr>
<?php
					}
				}
				$validasi = "<input id=\"form-1\" type=\"button\" value=\"Validasi\" onClick=\"buka('valBatal')\"/>";
			}
			else{
?>
	<tr><td class="notice" colspan="12">Belum cetak berita acara pembatalan.</td></tr>
<?php
				$validasi = "";
			}
?>					   				   
	<tr class="table_head"> 
		<td colspan="9" align="left">
			<input type="button" value="Kembali" onclick="buka('kembali')"/>
		</td>
		<td class="right">Grand Total :</td>				
		<td class="right" valign="top"><b><?=number_format(array_sum($grandTotal))?></b></td>
		<td id="targetId" align="center"><?php echo $validasi; ?></td>				
	</tr>
</table>
<?php
			break;
		default:
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
		<input type="Button" id="form-4" value="Cek Rekening" onclick="buka('buka')"/>
	</div>
</div>
<?php
	}
?>