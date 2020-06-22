<?php
	if($erno) die();
	if(isset($proses)){	
		try{
			$wsdl_url 	= "http://"._PRIN."/printClient/printServer.wsdl";
			$client   	= new SoapClient($wsdl_url, array('cache_wsdl' => WSDL_CACHE_NONE) );
			$cetak 		= true;
		}
		catch (Exception $e){
			echo $e->getMessage();
			$cetak 		= false;
		}
		
		if($cetak){
			try{
				// line untuk ff continous paper
				$stringCetak  = chr(27).chr(67).chr(3);
				// enable paper out sensor
				$stringCetak .= chr(27).chr(57);
				// draft mode
				$stringCetak .= chr(27).chr(120).chr(48);
				// line spacing x/72
				$stringCetak .= chr(27).chr(65).chr(12);
				$stringCetak .= chr(10).chr(10).chr(10).chr(10);
				$stringCetak .= "LPP RINCI PER PELAKSANA KAS".chr(10);
				$stringCetak .= "PENERIMA      : ".chr(10);
				$stringCetak .= "PERIODE       : ".chr(10);
				$stringCetak .= "TANGGAL CETAK : ".chr(10);
				$stringCetak .= "STATUS LOKET  : ".chr(10);
				$stringCetak .= str_repeat("_",80).chr(12);
				$stringFile	  = "printTest.txt";
				$client->cetak(base64_encode($stringCetak),$stringFile);
				echo "Dokumen telah dicetak.";
			}
			catch (Exception $e){
				errorLog::logMess(array($e->getMessage()));
				echo "<br/>Dokumen gagal dicetak. ".$e->getMessage();
			}
		}
		else{
			echo "<br/>Service printer tidak ditemukan.";
		}
	}
	else{
?>
<input type="hidden" id="norefresh"  value="1"/>
<input type="hidden" id="keyProses1" value="C"/>
<input type="hidden" id="aktiveForm" value="0"/>
<input type="hidden" id="jumlahForm" value="2"/>
<input type="hidden" class="periksa" name="appl_kode"	value="<?php echo _KODE; 	?>"/>
<input type="hidden" class="periksa" name="appl_name"	value="<?php echo _NAME; 	?>"/>
<input type="hidden" class="periksa" name="appl_file"	value="<?php echo _FILE; 	?>"/>
<input type="hidden" class="periksa" name="appl_proc"	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="periksa" name="targetUrl"	value="<?php echo _PROC; 	?>"/>
<input type="hidden" class="periksa" name="targetId"	value="hasil"/>
<input type="hidden" class="periksa" name="proses"		value="periksa"/>
<h2 class="cetak"><?php echo _NAME; ?> :: <?php echo _PRIN; ?></h2><hr class="cetak" />
<div id="hasil" class="box"></div>
<table class="cetak" width="500" align="center">
	<tr>
		<td width="40%" class="form_title right">Tanggal</td>
		<td width="60%">:
			<input type="text" id="form-1" class="cetak" name="dibayar" size="15" maxlength="10" value="<?=$tanggal?>" onmouseover="$(this.id).focus()" />
		</td>
	</tr>
	<tr> 
		<td></td>
		<td class="left">
			<input type="button" id="form-2" value="Cetak" onClick="buka('periksa')"/>
		</td>
	</tr>
</table>
<?php
	}
?>