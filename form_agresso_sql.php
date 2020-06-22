<h2 class="cetak"><?php echo _NAME; ?> - <?php echo $_SESSION['kp_ket']; ?></h2>

<textarea class="exec" name="query" style="width: 95%; height: 120px;	border: 1px solid #cccccc; padding: 5px; font-family: Tahoma, sans-serif;"></textarea>
<br/>
<input type="hidden" class="exec" name="targetUrl"	value="<?php echo _PROC; ?>" />
<input type="hidden" class="exec" name="targetId" 	value="targetId" />
<input type="hidden" class="exec" name="proses" 	value="1" />
<input type="button" value="Run" onclick="buka('exec')" />

<div id="targetId"></div>
