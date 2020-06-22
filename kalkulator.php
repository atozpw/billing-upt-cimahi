<?php
	$formId = getToken();
?>
<div id="<?php echo $formId; ?>" class="load">
<div class="pesan span-18">
<div class="span-18 right large">[<a title="Tutup jendela ini" onclick="tutup('<?php echo $formId; ?>')">Tutup</a>]</div>
<h3>Hitung kembalian</h3>
<hr/>
<div id="targetId" class="span-23 left">
<input type="hidden" id="tutup" value="<?php echo $formId; ?>" />
<input type="hidden" id="keyProses0" value="1" />
<input type="hidden" id="jumlahCalc" value="3" />
<input type="hidden" id="aktiveCalc" value="0" />
<input type="hidden" class="noRek" name="proses" value="bayar"/>
<input id="calc-0" type="hidden" value="<?=$bayar?>"/>
<?php
	if($bayar>0){
?>
<table>
	<tr>
		<td>Total Tagihan</td>
		<td style="font-size:15pt; font-family:courier;">
			: <b><?=number_format($bayar)?></b>
		</td>
	</tr>
	<tr>
		<td>Dibayar</td>
		<td style="font-size:15pt; font-family:courier;">:
			<input id="calc-1" type="text" class="noRek" name="dibayar" style="font-size:15pt; font-family:courier;" value="<?=$bayar?>" onMouseOver="$(this.id).select()" onChange="$('calc-2').value=$('calc-1').value-$('calc-0').value"/>
		</td>
	</tr>
	<tr>
		<td>Kembalian</td>
		<td style="font-size:15pt; font-family:courier;">:
			<input id="calc-2" type="text" class="noRek" name="kembalian" style="font-size:15pt; font-family:courier;" value="0" readonly />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input id="calc-3" type="button" value="Bayar" onfocus="buka('noRek')"/>
		</td>
	</tr>
</table>
<?php
	}
	else{
?>
<div class="span-17 notice">Belum ada rekening dipilih</div>
<?php
	}
?>
</div>
</div>
</div>