<?php
	if($erno) die();
	$formId 	= getToken();
	if(isset($proses)){
		$kopel				= explode("_",$kopel);
		$_SESSION['Kota_c'] = $kopel[0];
		$_SESSION['kp_ket'] = $kopel[1];
		echo "<div class=\"success\">Perubahan wilayah pelayanan telah diset ke : $kopel[1]</div>";
	}
	else{
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
		
		/* inquiry kota pelayanan */
		try{
			$que2 = "SELECT CONCAT(kp_kode,'_',kp_ket) AS kopel,CONCAT('[',kp_kode,'] ',kp_ket) AS kp_ket FROM tr_kota_pelayanan $filtered ORDER BY kp_kode ASC";
			if(!$res2 = mysql_query($que2,$link)){
				throw new Exception("Terjadi kesalahan pada sistem database<br/>Nomor Tiket : ".substr(_TOKN,-4));
			}
			else{
				while($row2 = mysql_fetch_array($res2)){
					$data2[] = array("kopel"=>$row2['kopel'],"kp_ket"=>$row2['kp_ket']);
				}
				$mess = false;
			}
		}
		catch (Exception $e){
			errorLog::errorDB(array($que2));
			$mess = $e->getMessage();
			$erno = false;
		}
		$parm2 		= array("class"=>"proses","name"=>"kopel","selected"=>$kopel);
?>
<h2 class="proses"><?php echo _NAME; ?></h2>
<input type="hidden" id="norefresh" value="1" />
<input type="hidden" class="proses" 	name="appl_kode" 	value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="proses" 	name="appl_name" 	value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="proses" 	name="appl_file" 	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="proses" 	name="appl_tokn" 	value="<?php echo _TOKN; 	?>"/>
<input type="hidden" class="proses" 	name="appl_proc" 	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="proses" 	name="targetUrl" 	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="proses" 	name="targetId" 	value="targetId"/>
<input type="hidden" class="proses" 	name="proses" 		value="1"/>
<div id="targetId"></div>
<div class="span-4 cetak">Unit Pelayanan</div>
<div class="span-7 cetak">
	: <?php echo pilihan($data2,$parm2); ?>
</div>
<br/><br/>
<div class="span-4 cetak">&nbsp;</div>
<div class="span-7 cetak">&nbsp;
	<input type="button" value="Proses" onclick="buka('proses')"/>
</div>
<?php
	}
?>
