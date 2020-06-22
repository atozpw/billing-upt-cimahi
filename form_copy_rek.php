<?php
	if($erno) die();
	unset($mess);
	if(!isset($proses)){
		$proses = false;
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
	if(!isset($rek_nomor)){
		$rek_nomor	= "";
	}
	if(!isset($gol_kode)){
		$gol_kode	= "";
	}
	$kopel	= $_SESSION['kp_ket'];
	
	switch($proses){
		case "cetak":
			try{
				$wsdl_url 	= "http://"._PRIN."/printClient/printServer.wsdl";
				$client   	= new SoapClient($wsdl_url, array('cache_wsdl' => WSDL_CACHE_NONE) );
				$cetak 		= true;
			}
			catch (Exception $e){
				$mess		= "Perangkat pencetak belum tersedia.";
				errorLog::errorDB(array($mess));
				$cetak 		= false;
			}
			
			if($cetak){
				try {
					$stringFile	  = _TOKN.".txt";
					$openFile 	  = fopen("_data/".$stringFile, 'w');
					fwrite($openFile, base64_decode($stringCetak));
					fclose($openFile);
					$client->cetak($stringCetak,$stringFile);
					$mess	= "Proses cetak copy resi : ".$rek_nomor." telah dilakukan";
				}
				catch (Exception $err) {
					$mess	= "Proses cetak copy resi : ".$rek_nomor." gagal dilakukan";
				}
			}
			errorLog::logMess(array($mess));
			echo "$mess";
			break;
		case "rinci":
			$data		= array();
			$grandTotal	= array();
			try{
				if(_USER=='admin'){
					$que0 	= "SELECT a.*,IF((rek_bln=".$rek_bln." AND rek_thn=".$rek_thn."),1,0) AS pilih FROM v_lpp a WHERE a.pel_no='$pel_no' AND a.byr_sts>0 ORDER BY a.rek_thn ASC,a.rek_bln ASC";
				}
				else{
					$que0 	= "SELECT a.*,IF((rek_bln=".$rek_bln." AND rek_thn=".$rek_thn."),1,0) AS pilih FROM v_lpp a WHERE a.kar_id='"._USER."' AND a.pel_no='$pel_no' AND MONTH(a.byr_tgl)=MONTH(CURDATE()) AND a.byr_sts>0 ORDER BY a.rek_thn ASC,a.rek_bln ASC";
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
						if($row0['pilih']==1){
							$byr_no 		= $row0['byr_no'];
							$rek_nomor 		= $row0['rek_nomor'];
							$pemakaian		= $row0['pemakaian'];
							$gol_kode		= $row0['gol_kode'];
						}
					}
					$mess = false;
				}
				$que0	= "SELECT a.* FROM tm_kode_tarif a WHERE ".substr($rek_nomor,0,6).">=a.tar_bln_mulai AND ".substr($rek_nomor,0,6)."<=a.tar_bln_akhir AND a.gol_kode='".$gol_kode."'";
				/** getParam 
					memindahkan semua nilai dalam array POST ke dalam
					variabel yang bersesuaian dengan masih kunci array
				*/
				$res0 	= mysql_query($que0,$link);
				if($res0){
					$nilai	= mysql_fetch_array($res0);
					$konci	= array_keys($nilai);
					for($j=0;$j<count($konci);$j++){
						$$konci[$j]	= $nilai[$konci[$j]];
					}
					/* getParam **/
					$o_awalA	= " ";
					$o_akhirA	= " ";
					$o_hargaA	= " ";
					$o_uangairA	= " ";
					$o_awalB	= " ";
					$o_akhirB	= " ";
					$o_hargaB	= " ";
					$o_uangairB	= " ";
					$o_awalC	= " ";
					$o_akhirC	= " ";
					$o_hargaC	= " ";
					$o_uangairC	= " ";
					if($pemakaian>$tar_sd2){
						$o_awalC	= $tar_sd2+1;
						$o_akhirC	= $pemakaian;
						$o_hargaC	= $tar_3;
						$o_uangairC	= ($pemakaian-$tar_sd2)*$tar_3;
						$pemakaian 	= $tar_sd2;
					}
					if($pemakaian>$tar_sd1){
						$o_awalB	= $tar_sd1+1;
						$o_akhirB	= $pemakaian;
						$o_hargaB	= $tar_2;
						$o_uangairB = ($pemakaian-$tar_sd1)*$tar_2;
						$pemakaian 	= $tar_sd1;
					}
					if($pemakaian>0){
						$o_awalA	= 0;
						$o_akhirA	= $pemakaian;
						$o_hargaA	= $tar_1;
						$o_uangairA = $pemakaian*$tar_1;
					}
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que0));
				$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
			}
			if(!$erno) mysql_close($link);
?>
<h2 class="cetak"><?php echo _NAME; ?></h2><hr class="cetak" />
<input id="<?php echo $errorId; ?>" type="hidden" value="<?=$mess?>"/>
<input type="hidden" id="norefresh" 	value="1" />
<input type="hidden" id="keyProses1" 	value="F" />
<input type="hidden" id="jumlahForm" 	value="1" />
<input type="hidden" id="aktiveForm" 	value="0" />
<input type="hidden" class="valCopy" 			name="appl_tokn"	value="<?php echo _TOKN;	 	?>"/>
<input type="hidden" class="kembali valCopy" 	name="appl_kode"	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="kembali valCopy" 	name="appl_name"	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="kembali valCopy" 	name="appl_file"	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali valCopy" 	name="appl_proc"	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="kembali" 			name="targetUrl"	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="kembali" 			name="targetId"		value="content"/>
<input type="hidden" class="valCopy" 			name="targetUrl" 	value="<?php echo _PROC;		?>"/>
<input type="hidden" class="valCopy cetak" 		name="byr_no" 		value="<?php echo $byr_no;		?>"/>
<input type="hidden" class="valCopy cetak" 		name="rek_nomor" 	value="<?php echo $rek_nomor;	?>"/>
<input type="hidden" class="valCopy" 			name="errorId"		value="<?php echo getToken(); 	?>"/>
<input type="hidden" class="valCopy" 			name="targetId"		value="targetId"/>
<input type="hidden" class="valCopy" 			name="proses"		value="cetak"/>
<input type="hidden" class="valCopy" 			name="dump"			value="0" />
<table class="table_info cetak">
	<tr class="table_validator">
		<td colspan="2">Nomor SR</td>
		<td colspan="5">:<?php echo $pel_no; 	?></td>
		<td colspan="1">Golongan</td>
		<td colspan="4">:<?php echo $golongan; 	?></td>
	</tr>
	<tr class="table_validator">
		<td colspan="2">Nama</td>
		<td colspan="5">:<?php echo $pel_nama; 	?></td>
		<td colspan="1">Alamat</td>
		<td colspan="4">:<?php echo $pel_alamat;?></td>
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
		<td class="right" valign="center"><b>
<?php
	if(count($data)>0){
		echo number_format(array_sum($grandTotal));
	}
	else{
		echo 0;
	} 
?>
		</b></td>
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
		<td class="center">Biaya Beban Tetap</td>
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
					$form = false;
					if(isset($byr_no) and isset($rek_nomor)){
						$form = true;
					}
					if($form){
						switch($byr_loket){
							case "N":
								$noresi = "O";
								break;
							case "T":
								$noresi = "U";
								break;
							case "D":
								$noresi = "E";
								break;
							default:
								$noresi = "C";
						}
						$rek_bayar = $grandTotal[$i];l
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
						if($pilih){

					// line untuk ff continous paper
					$stringCetak  = chr(27).chr(67).chr(1);
					// enable paper out sensor
					$stringCetak .= chr(27).chr(57);
					// draft mode
					$stringCetak .= chr(27).chr(120).chr(48);
					// mode 10 cpi
					$stringCetak .= chr(27).chr(80);
					// mode condensed
					$stringCetak .= chr(15);
								// line spacing x/n
								$stringCetak .= chr(27).chr(65).chr(28);
								$stringCetak .= chr(10);
								$stringCetak .= chr(27).chr(65).chr(8);
								$stringCetak .= chr(10);

								if($l>0){
									if($l % 2==0){
										// line spacing x/n
										$stringCetak .= chr(27).chr(65).chr(13);
										$stringCetak .= chr(10);
									}
									else{
										// line spacing x/n
										$stringCetak .= chr(27).chr(65).chr(14);
										$stringCetak .= chr(10);
									}
								}

								// line spacing x/n
								$stringCetak .= chr(27).chr(65).chr(10);
								$stringCetak .= str_repeat(' ',20).printLeft($byr_no, 20).str_repeat(" ", 21).printLeft($pel_no,37).str_repeat(' ', 21).printLeft($byr_no, 16).chr(10);
								$stringCetak .= chr(27).chr(65).chr(12);
								$stringCetak .= str_repeat(' ',20).printLeft($bulan[$rek_bln].' '.$rek_thn,20).str_repeat(' ', 21).printLeft($dkd_kd, 37).str_repeat(" ", 21).substr($bulan[$rek_bln],0,3).' '.$rek_thn.chr(10);
								$stringCetak .= str_repeat(' ',20).printLeft($rek_gol,20).str_repeat(" ",21).printLeft($loket, 37).str_repeat(' ', 21).printLeft($rek_gol,16).chr(10);
								$stringCetak .= str_repeat(' ',20).printLeft(substr($pel_no,0,20),20).str_repeat(" ",21).printLeft($pel_nama,37).str_repeat(' ',21).printLeft($tgl_baca,16).chr(10);
								$stringCetak .= str_repeat(' ',20).printLeft($loket,20).str_repeat(" ",21).printLeft(substr($pel_alamat,0,37),37).str_repeat(' ',21).printRight(number_format($rek_stankini),16).chr(10);
								$stringCetak .= str_repeat(' ',61).printLeft(substr($pel_alamat,37,37),37).str_repeat(' ',21).printRight(number_format($rek_stanlalu),16).chr(10);
								$stringCetak .= str_repeat(' ',61).printLeft(substr($pel_alamat,74,37),37).str_repeat(' ',21).printRight(number_format($rek_stankini-$rek_stanlalu),16).chr(10);
								$stringCetak .= chr(10);
								$stringCetak .= '   '.printLeft($pel_nama,37).chr(10);
								$stringCetak .= '   '.printLeft(substr($pel_alamat,0,37),37).'   '.printCenter("101", 7).' '.printLeft("Penjualan Air",33).' '.printRight(number_format($rek_stankini-$rek_stanlalu).' ',9).printCenter('M3',10).printRight("-",12).' '.printRight(number_format($rek_uangair),17).chr(10);
								$stringCetak .= '   '.printLeft(substr($pel_alamat,37,37),37).'   '.printCenter("201",7).' '.printLeft("Biaya Administrasi",33).' '.printRight('1 ',9).printCenter("Period",10).printRight(number_format($rek_adm),12).' '.printRight(number_format($rek_adm),17).chr(10);
								$stringCetak .= '   '.printLeft(substr($pel_alamat,74,37),37).'   '.printCenter("301",7).' '.printLeft("Biaya Pemeliharaan Meter",33).' '.printRight('1 ',9).printCenter("Period",10).printRight(number_format($rek_meter),12).' '.printRight(number_format($rek_meter),17).chr(10);
								if($rek_denda > 0) {
									$stringCetak .= str_repeat(' ', 43).printCenter("901",7).' '.printLeft("Denda Jatuh Tempo",33).' '.printRight('1 ',9).printCenter("Period",10).printRight(number_format($rek_denda[$i]),12).' '.printRight(number_format($rek_denda),17);
								}
								$stringCetak .= chr(10);
								$stringCetak .= chr(10);
								$stringCetak .= chr(27).chr(65).chr(28);
								$stringCetak .= chr(10);
								$stringCetak .= chr(10);
								$stringCetak .= chr(27).chr(65).chr(17);
								$stringCetak .= chr(10);
								$stringCetak .= str_repeat(' ',4)."TOTAL".printRight(number_format($rek_bayar),30).str_repeat(' ',78).printRight(number_format($rek_bayar),17).chr(10);

								// line spacing x/n
								$stringCetak .= chr(27).chr(65).chr(15);
								$terbilang    = ucwords(n2c($rek_bayar,"Rupiah"));
								$stringCetak .= str_repeat(' ',10).printLeft($tgl_sekarang_full,30).str_repeat(' ',73).printCenter($tgl_sekarang_full,22).chr(10);
								$stringCetak .= chr(27).chr(65).chr(12);
								$stringCetak .= str_repeat(' ',44).substr($terbilang,0,60).chr(10);
								$stringCetak .= str_repeat(' ',44).substr($terbilang,60,60).chr(10);
								$stringCetak .= str_repeat(' ',44).substr($terbilang,120,60).chr(10);
								$stringCetak .= str_repeat(' ',3).printCenter($nama_direktur,33).str_repeat(' ',77).printRight($nama_direktur,22).chr(10);
								$stringCetak .= chr(10);
								$stringCetak .= chr(10);
							
							// // line spacing x/n
							// $stringCetak .= chr(27).chr(65).chr(12);							
							// $stringCetak .= str_repeat(' ',10).printLeft($bulan[$rek_bln]." ".$rek_thn,14).		"             ".printLeft($pel_no,25).$bulan[$rek_bln]." ".$rek_thn.chr(10);
							// $stringCetak .= str_repeat(' ',10).printLeft($pel_no,14).							"             ".$pel_nama.chr(10);
							// $stringCetak .= str_repeat(' ',10).printLeft(substr($pel_nama,0,14),14).			"             ".$pel_alamat.chr(10);
							// $stringCetak .= str_repeat(' ',10).printLeft(substr($pel_alamat,0,14),14).			"             ".printLeft($kopel,25).$gol_kode.chr(10);
							// $stringCetak .= str_repeat(' ',10).printLeft($gol_kode."/".$dkd_kd,14).				"             ".printLeft($tgl_baca,25).$dkd_kd.chr(10);
							// $stringCetak .= str_repeat(' ',10).printLeft(substr($kopel,0,14),14).chr(10);
							// $stringCetak .= printRight(number_format($rek_uangair)."  ",24).					"  ".printRight(number_format($rek_stankini),6).printRight(number_format($beban_tetap),48).chr(10);
							// $stringCetak .= str_repeat(' ',24).													"  ".printRight($o_awalA,11)."  ".printRight($o_akhirA,4).printRight(" ".$o_hargaA,6).printRight(number_format($o_uangairA),12).printRight(number_format($rek_angsuran),19).chr(10);
							// $stringCetak .= printRight(number_format($beban_tetap)."  ",24).					"  ".printRight(number_format($rek_stanlalu),6).printRight($o_awalB,5)."  ".printRight($o_akhirB,4).printRight(" ".$o_hargaB,6).printRight(number_format($o_uangairB),12).printRight(number_format($rek_denda),19).chr(10);
							// $stringCetak .= printRight(number_format($rek_angsuran)."  ",24).					"  ".printRight($o_awalC,11)."  ".printRight($o_akhirC,4).printRight(" ".$o_hargaC,6).printRight(number_format($o_uangairC),12).chr(10);
							// $stringCetak .= printRight(number_format($rek_denda)."  ",24).						"  ".printRight(number_format($pemakaian),6).printRight(number_format($rek_uangair),29).printRight(number_format($rek_bayar),19).chr(10);
							// $stringCetak .= printRight(number_format($rek_bayar)."  ",24).chr(10);

							// // line spacing x/n
							// $stringCetak .= chr(27).chr(65).chr(8);
							// $stringCetak .= chr(10);

							// // line spacing x/n
							// $stringCetak .= chr(27).chr(65).chr(12);
							// $stringCetak .= str_repeat(' ',8).printLeft($tgl_sekarang,16).						" ".strtoupper(substr((n2c($rek_bayar,"Rupiah")),0,34)).chr(10);
							// $stringCetak .= str_repeat(' ',24).													" ".strtoupper(substr((n2c($rek_bayar,"Rupiah")),34,34)).chr(10);
							// $stringCetak .= printRight($byr_no."/".$noresi."/"._USER,24).					" ".$byr_no."/".$noresi."/"._USER.chr(10).chr(10).chr(10).chr(12);
						}
					}
				}
			}
			else{
?>
	<tr><td class="notice" colspan="12">Transaksi pembayaran tidak ditemukan.</td></tr>
<?php
			}
?>					   				   
	<tr class="table_head">
		<td colspan="12" id="targetId">
			<input type="hidden" class="valCopy" name="stringCetak" value="<?php echo base64_encode($stringCetak); ?>" />
			<input id="form-1" type="button" value="Cetak" onclick="nonghol('cetak')" /> 
			<input type="hidden" class="cetak" 	name="targetUrl" value="cetak_copy_rek.php"/>
		</td>				
	</tr>
</table>
<?php
			break;
		default:
			$que1	= "SELECT MAX(tr_sts) AS tr_sts FROM tr_trans_log WHERE DATE(getTanggal(tr_id))=CURDATE() AND kar_id='"._USER."'";
			$res1 	= mysql_query($que1,$link);
			$row1 	= mysql_fetch_array($res1);
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
<h2 class="cetak"><?php echo _NAME; ?></h2><hr class="cetak" />
<input type="hidden" id="norefresh" 	value="1" />
<input type="hidden" id="keyProses1" 	value="C" />
<input type="hidden" id="jumlahForm" 	value="4" />
<input type="hidden" id="aktiveForm" 	value="0" />
<input type="hidden" class="buka" 	name="appl_tokn"	value="<?php echo getToken();	?>"/>
<input type="hidden" class="buka" 	name="appl_kode"	value="<?php echo _KODE; 		?>"/>
<input type="hidden" class="buka" 	name="appl_name"	value="<?php echo _NAME; 		?>"/>
<input type="hidden" class="buka" 	name="appl_file"	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="buka" 	name="appl_proc"	value="<?php echo _PROC; 		?>"/>
<input type="hidden" class="buka" 	name="errorId"		value="<?php echo getToken();	?>"/>
<input type="hidden" class="buka" 	name="targetUrl" 	value="<?php echo _FILE; 		?>"/>
<input type="hidden" class="buka" 	name="targetId"		value="content"/>
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
