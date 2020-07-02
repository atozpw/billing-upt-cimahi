<?php
	if($erno) die();
	$formId 	= getToken();
	$targetId 	= getToken();
	$errorId 	= getToken();
	
	/* inquiry kota pelayanan */
	$kopel	= $_SESSION['Kota_c']."_".$_SESSION['kp_ket'];
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
		$que3 = "SELECT CONCAT(kp_kode,'_',kp_ket) AS kopel,CONCAT('[',kp_kode,'] ',kp_ket) AS kp_ket FROM tr_kota_pelayanan $filtered ORDER BY kp_kode ASC";
		if(!$res3 = $link->query($que3)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row3 = $res3->fetch_array()){
				$data3[] = array("kopel"=>$row3['kopel'],"kp_ket"=>$row3['kp_ket']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que3));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm3 		= array("class"=>"simpan","id"=>"rayn-1","name"=>"kopel","selected"=>$kopel);
	
	/* inquiry karyawan */
	try{
		$que2 = "SELECT kar_id,kar_nama FROM tm_karyawan WHERE grup_id='002' ORDER BY kar_nama";
		if(!$res2 = $link->query($que2)){
			throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
		}
		else{
			while($row2 = $res2->fetch_array()){
				$data2[] = array("kar_id"=>$row2['kar_id'],"kar_nama"=>$row2['kar_nama']);
			}
			$mess = false;
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($que2));
		$mess = $e->getMessage();
		$erno = false;
	}
	$parm2 = array("class"=>"simpan","id"=>"rayn-4","name"=>"kar_id","selected"=>$kar_id);
?>
<div id="<?php echo $formId; ?>" class="peringatan">
	<input id="keyProses0" 	type="hidden" value="1"/>
	<input id="tutup" 		type="hidden" value="<?php echo $formId; ?>" />
	<div class="pesan span-18">
		<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
		<h3>Form Edit Data Rayon</h3>
		<hr/>
		<div id="<?php echo $targetId; ?>">
<?php
			if(_HINT==1){
?>
			<div class="notice left">Tekan tombol <b>Enter</b> untuk memulai proses entryan, <b>Alt+S</b> untuk menyimpan dan <b>Delete</b> untuk menghapus.</div>
<?php
			}
?>
		</div>
		<div class="span-9 left">
			<div class="span-2 prepend-top">Unit Layanan</div>
			<div class="span-5 prepend-top">: <?php echo pilihan($data3,$parm3); ?></div>
			<div class="span-2 prepend-top">Kode Rute</div>
			<div class="span-5 prepend-top">:
				<input id="rayn-2" type="text" maxlength="6" size="20" class="simpan" name="dkd_kd" value="<?php echo $dkd_kd; ?>" onmouseover="$(this.id).select()" />
			</div>
			<div class="span-2 prepend-top">Jalan/Lokasi</div>
			<div class="span-5 prepend-top">
				<textarea id="rayn-3" class="simpan height-2" name="dkd_jalan" onmouseover="$(this.id).select()"><?php echo $dkd_jalan; ?></textarea>
			</div>
			<div class="span-2 prepend-top">Pembaca</div>
			<div class="span-5 prepend-top">: <?=pilihan($data2,$parm2)?></div>
			<div class="span-2 prepend-top">Tgl. Catat</div>
			<div class="span-5 prepend-top">:
				<input id="rayn-5" type="text" maxlength="2" size="20" class="simpan" name="dkd_tcatat" value="<?php echo $dkd_tcatat; ?>" onmouseover="$(this.id).select()" />
			</div>
			<div class="span-2 prepend-top">&nbsp;</div>
			<div class="span-5 prepend-top">&nbsp;
				<input type="hidden" class="simpan delete" 	name="targetId" 	value="<?php echo $targetId;	?>"/>
				<input type="hidden" class="simpan delete" 	name="errorId" 		value="<?php echo $errorId;		?>"/>
				<input type="hidden" class="simpan delete" 	name="dkd_lama"		value="<?php echo $dkd_kd;		?>"/>
				<input type="hidden" class="simpan delete" 	name="targetUrl"	value="<?php echo _PROC; 		?>"/>
				<input type="hidden" class="simpan" 		name="proses"		value="editRayon" />
				<input type="hidden" class="delete" 		name="proses"		value="deleteRayon" />
				<input type="hidden" class="delete" 		name="dump"			value="0" />
				<input id="rayn-6" type="button" value="Simpan" onclick="buka('simpan')" accesskey="S" />
				<input type="button" value="Delete" onclick="buka('delete')"/>
				<input id="jumlahRayn" type="hidden" value="6"/>
				<input id="aktiveRayn" type="hidden" value="0"/>
			</div>
		</div>
	</div>
</div>