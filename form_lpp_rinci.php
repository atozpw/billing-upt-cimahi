<?php
	if($erno) die();
	unset($mess);
	$que1	= "SELECT MAX(tr_sts) AS tr_sts FROM tr_trans_log WHERE DATE(getTanggal(tr_id))=CURDATE() AND kar_id='"._USER."'";
	$res1 	= $link->query($que1);
	$row1 	= $res1->fetch_array();
	$tr_sts	= abs($row1['tr_sts']);
	switch(_KODE){
		case '081301':
			$parm1	 	= array("class"=>"cetak","id"=>"form-2","name"=>"pilihan","selected"=>1);
			$data1[]	= array("pilihan"=>"1","ket"=>"Per Kasir");
			$data1[]	= array("pilihan"=>"2","ket"=>"Per Golongan");
			break;
		default:
			$parm1	 	= array("class"=>"cetak","id"=>"form-2","name"=>"pilihan","disabled"=>"disabled","selected"=>0);
			$data1		= array();
	}
?>
<input type="hidden" id="norefresh"  value="1"/>
<input type="hidden" id="keyProses1" value="C"/>
<input type="hidden" id="aktiveForm" value="0"/>
<input type="hidden" id="jumlahForm" value="3"/>
<input type="hidden" class="cetak" name="appl_kode"	value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="cetak" name="appl_name"	value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="cetak" name="appl_file"	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="cetak" name="appl_proc"	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="cetak" name="errorUrl"	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="cetak" name="tr_sts"	value="<?php echo $tr_sts; 	?>"/>
<h2 class="cetak"><?php echo _NAME; ?></h2><hr class="cetak" />
<table class="cetak" width="500" align="center">
	<tr>
		<td width="40%" class="form_title right">Tanggal</td>
		<td width="60%">:
			<input type="date" id="form-1" class="cetak" name="dibayar" size="15" maxlength="10" value="<?=$tanggal?>" onmouseover="$(this.id).focus()" />
		</td>
	</tr>
	<tr>
		<td width="40%" class="form_title right">Kategori</td>
		<td width="60%">:
			<?php echo pilihan($data1,$parm1); ?>
		</td>
	</tr>
	<tr> 
		<td></td>
		<td class="left">
			<input type="button" id="form-3" value="Cetak" onClick="nonghol('cetak')"/>
		</td>
	</tr>
</table>
