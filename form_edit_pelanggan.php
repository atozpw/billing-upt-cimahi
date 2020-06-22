<?php
	if($erno) die();
	$formId = getToken();

	/* inquiry rayon */
	try{
		$que2 = "SELECT dkd_kd,CONCAT('[',dkd_kd,']',' ',IFNULL(dkd_jalan,'N/A')) AS dkd_jalan FROM v_rayon WHERE kp_kode='$kp_kode' ORDER BY dkd_kd";
		if(!$res2 = mysql_query($que2,$link)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row2 = mysql_fetch_array($res2)){
				$data2[] = array("dkd_kd"=>$row2['dkd_kd'],"dkd_jalan"=>$row2['dkd_jalan']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que2));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm2 = array("class"=>"simpan refresh","id"=>"form-8","name"=>"dkd_kd","selected"=>substr($dkd_kd,-3));
	
	switch($proses){
		case "pilihRayon":
			echo ": ".pilihan($data2,$parm2);
			break;
		default:
			/* inquiry kopel */
			if($_SESSION['Group_c']=='000'){
				$filtered = '';
			}
			else if($_SESSION['c_group']=='00'){
				$filtered = '';
			}
			else{
				$filtered = "WHERE kp_kode='".$_SESSION['Kota_c']."'";
			}
			try{
				$que0 = "SELECT kp_kode,kp_ket FROM tr_kota_pelayanan $filtered ORDER BY kp_kode";
				if(!$res0 = mysql_query($que0,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row0 = mysql_fetch_array($res0)){
						$data0[] = array("kp_kode"=>$row0['kp_kode'],"kp_ket"=>$row0['kp_ket']);
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que0));
				$mess = $e->getMessage();
				$erno = false;
			}
			$parm0 = array("class"=>"simpan pilih","id"=>"form-7","name"=>"kp_kode","selected"=>_KOTA,"onchange"=>"buka('pilih')");
			
			/* inquiry golongan */
			$filtered	= "WHERE gol_kode='".$gol_kode."'";
			if(_USER=="admin"){
				$filtered = "";
			}
			try{
				$que1 = "SELECT gol_kode,CONCAT('[',gol_kode,']',' ',gol_ket) AS gol_ket FROM tr_gol ".$filtered." ORDER BY gol_kode";
				if(!$res1 = mysql_query($que1,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row1 = mysql_fetch_array($res1)){
						$data1[] = array("gol_kode"=>$row1['gol_kode'],"gol_ket"=>$row1['gol_ket']);
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que1));
				$mess = $e->getMessage();
				$erno = false;
			}
			$parm1 = array("class"=>"simpan","id"=>"form-3","name"=>"gol_kode","selected"=>$gol_kode);
			
			/* inquiry status */
			if($kps_kode==7){
				$filtered	= "WHERE kps_kode=0 OR kps_kode=7";
			}
			elseif($kps_kode==8){
				$filtered	= "WHERE kps_kode=8";
			}
			elseif($kps_kode==9){
				$filtered	= "WHERE kps_kode=0 OR kps_kode=9";
			}
			elseif($kps_kode==10){
				$filtered	= "WHERE kps_kode=10";
			}
			elseif($kps_kode==11){
				$filtered	= "WHERE kps_kode=0 OR kps_kode=11";
			}
			else{
				$filtered	= "WHERE kps_kode<8";
			}
			try{
				$que3 = "SELECT kps_kode,UPPER(kps_ket) AS kps_ket FROM tr_kondisi_ps ".$filtered." ORDER BY kps_kode";
				if(!$res3 = mysql_query($que3,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row3 = mysql_fetch_array($res3)){
						$data3[] = array("kps_kode"=>$row3['kps_kode'],"kps_ket"=>$row3['kps_ket']);
					}
					$mess = false;
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que3));
				$mess = $e->getMessage();
				$erno = false;
			}
			$parm3 = array("class"=>"simpan","id"=>"form-9","name"=>"kps_kode","selected"=>$kps_kode);
			
			/* pilih ukuran meter */
			try{
				$que4 	= "SELECT um_kode,CONCAT(um_ukuran,' inch') AS um_ukuran FROM tr_ukuranmeter ORDER BY um_kode";
				if(!$res4 = mysql_query($que4,$link)){
					throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
				}
				else{
					while($row4 = mysql_fetch_array($res4)){
						$data4[] = array("um_kode"=>$row4['um_kode'],"um_ukuran"=>$row4['um_ukuran']);
					}
				}
			}
			catch (Exception $e){
				errorLog::errorDB(array($que4));
				$error_mess	= $e->getMessage();
				$show_form 	= false;
			}
			$parm4 = array("class"=>"simpan","id"=>"form-4","name"=>"um_kode","selected"=>$um_kode);

			/* panduan pintasan aplikasi */
			$panduan	= true;
			if(isset($_SESSION['panduan'])){
				$panduan = true;
			}	
			$hint = "<div class=\"notice\">Tekan tombol <b>Enter</b> untuk untuk memulai entry data, <b>Alt+M</b> untuk meterisasi , kemudian <b>Tab</b> untuk mengisi stand pasang, kemudian <b>Alt+S</b> untuk menyimpan, dan tombol <b>Esc</b> untuk menutup halaman ini.</div>";
?>
<div id="<?php echo $formId; ?>" class="peringatan">
<input id="keyProses0" 	type="hidden" value="1"/>
<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
<div class="pesan form-5">
<div class="span-20 right">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<br/><h3>Form Data Pelanggan</h3>
<hr/>
<input type="hidden" class="pilih" 	name="targetUrl" 	value="<?php echo __FILE__;	?>"/>
<input type="hidden" class="pilih" 	name="proses" 		value="pilihRayon"/>
<input type="hidden" class="pilih"	name="targetId" 	value="targetRayon"/>
<input type="hidden" class="pilih"	name="dump" 		value="0"/>
<input type="hidden" class="simpan"	name="appl_tokn" 	value="<?php echo _TOKN; 	?>"/>
<input type="hidden" class="simpan"	name="appl_kode" 	value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="simpan"	name="appl_name" 	value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="simpan"	name="appl_file" 	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="simpan"	name="appl_proc" 	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="simpan"	name="targetUrl" 	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="simpan"	name="targetId" 	value="targetUpdate"/>
<input type="hidden" class="simpan"	name="proses" 		value="updateSL"/>
<input type="hidden" class="simpan"	name="dump" 		value="0"/>
<input type="hidden" class="simpan" name="pel_no" 		value="<?php echo $pel_no; 	?>"/>
<?php
//	if(_HINT==1){
//		echo $hint;
//	}
?>
<div>
	<div class="span-11 left border">
		<div class="append-bottom span-3">No Pelanggan</div>
		<div class="append-bottom span-5">: <?php echo $pel_no;			?></div>
		<div class="append-bottom span-3">Unit Pelayanan</div>
		<div class="append-bottom span-5">: <?php echo $kp_ket;			?></div>
		<div class="append-bottom span-3">Nama</div>
		<div class="append-bottom span-5">: <?php echo $pel_nama;		?></div>
		<div class="append-bottom span-3">Alamat</div>
		<div class="append-bottom span-7">: <?php echo $pel_alamat;		?></div>
		<div class="append-bottom span-3">Golongan</div>
		<div class="append-bottom span-5">: <?php echo $gol_kode;		?></div>
		<div class="append-bottom span-3">Rayon</div>
		<div class="append-bottom span-5">: <?php echo $dkd_kd;			?></div>
		<div class="append-bottom span-3">Ukuran Meter</div>
		<div class="append-bottom span-5">: <?php echo $um_ket;			?></div>
		<div class="append-bottom span-3">Meterisasi</div>
		<div class="append-bottom span-5">: <?php echo $met_tgl;		?></div>
		<div class="append-bottom span-3">Status</div>
		<div class="append-bottom span-5">: <?php echo $kps_ket;		?></div>
	</div>
	<div class="span-11 left">
		<div id="targetUpdate" class="span-12"></div>
		<div class="append-bottom span-3">No Pelanggan</div>
		<div class="append-bottom span-7">
			: <?php echo $pel_no; ?>
		</div>
		<div class="append-bottom span-3">Nama</div>
		<div class="append-bottom span-7">
			: <input id="form-1" type="text" class="simpan" name="pel_nama" size="30" maxlength="45" value="<?php echo $pel_nama; ?>" />
		</div>
		<div class="append-bottom span-3">Alamat</div>
		<div class="append-bottom span-7">
			: <textarea id="form-2" class="simpan height-2 span-6" name="pel_alamat"><?php echo $pel_alamat; ?></textarea>
		</div>
		<div class="append-bottom span-3">Golongan</div>
		<div class="append-bottom span-7">
			: <?php echo pilihan($data1,$parm1); ?>
		</div>
		<div class="append-bottom span-3">Ukuran Meter</div>
		<div class="append-bottom span-7">
			: <?php echo pilihan($data4,$parm4); ?>
		</div>
		<div class="append-bottom span-3">Meterisasi</div>
		<div class="append-bottom span-7">
			: <input id="form-5" type="checkbox" accesskey="M" class="simpan" name="meterisasi" value="0" onchange="pilihin(this.id)" />
			<input id="form-6" type="text" class="simpan" name="stan_pasang" maxlength="9"/>
		</div>
		<div class="append-bottom span-3">Unit Pelayanan</div>
		<div class="append-bottom span-7">
			: <?php echo pilihan($data0,$parm0); ?>
		</div>
		<div class="append-bottom span-3">Rayon</div>
		<div class="append-bottom span-7" id="targetRayon">
			: <?php echo pilihan($data2,$parm2); ?>
		</div>
		<div class="append-bottom span-3">Status</div>
		<div class="append-bottom span-7">
			: <?php echo pilihan($data3,$parm3); ?>
		</div>
		<div class="span-3">&nbsp;</div>
		<div class="span-7">&nbsp;
			<input id="form-10" accesskey="S" type="button" value="Simpan" onclick="buka('simpan')"/>
			<input id="jumlahForm" type="hidden" value="10" />
			<input id="aktiveForm" type="hidden" value="0" />
		</div>
	</div>
</div>
</div>
</div>
<?php
	}
?>