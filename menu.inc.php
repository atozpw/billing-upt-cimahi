<?php
	require "model/setDB.php";
	require "model/logging.php";
	/** kode1 yang akan memindahkan semua nilai dalam array POST ke dalam */
	/*	variabel yang bersesuaian dengan masih kunci array */
	$nilai	= $_POST;
	$kunci	= array_keys($nilai);
	for($i=0;$i<count($kunci);$i++){
		$$kunci[$i]	= $nilai[$kunci[$i]];
	}
	/* kode1 **/
	define("_KODE","000000");
	define("_TOKN",getToken());
	$erno = false;
	
	$link 	= mysqli_connect($DHOST,$DUSER,$DPASS,$DNAME) or die(errorLog::errorDie(array(mysql_error())));

	try{
		$que0 = "SELECT *FROM v_menu_item WHERE appl_sts=0 AND getMenu('"._GRUP."',appl_kode)>0";
		if(!$res = $link->query($que0)){
			throw new Exception($link->error);
		}
		while($row	= $res->fetch_assoc()){
			if($row['l2'] == '00' and $row['l3'] == '00'){
				$l1[$row['appl_kode']]['appl_kode'] = $row['appl_kode'];
				$l1[$row['appl_kode']]['appl_name'] = $row['appl_name'];
			}
			else if($row['l3'] == '00'){
				$l2[$row['parent_id']][$row['appl_kode']]['appl_kode'] = $row['appl_kode'];
				$l2[$row['parent_id']][$row['appl_kode']]['appl_name'] = $row['appl_name'];
			}
			else{
				$l3[$row['parent_id']][$row['appl_kode']]['appl_kode'] = $row['appl_kode'];
				$l3[$row['parent_id']][$row['appl_kode']]['appl_name'] = $row['appl_name'];
				$l3[$row['parent_id']][$row['appl_kode']]['appl_file'] = $row['appl_file'];
				$l3[$row['parent_id']][$row['appl_kode']]['appl_proc'] = $row['appl_proc'];
			}
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($e->getMessage()));
		$erno = true;
	}
	$link->close();
	if($erno){
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4)."<br/>Tekan tombol F5 untuk melakukan loading ulang";
		echo "<div class=\"pesan\">$mess</div>";
	}
	
/* menu level 1 */
if(isset($l1)){
	if(count($l1)){
		$k1 = array_keys($l1);
	}
	for($i=0;$i<count($l1);$i++){
		$appl_name = $l1[$k1[$i]]["appl_name"];
		$appl_kode = $l1[$k1[$i]]["appl_kode"];
?>
	<li>
		<a><?php echo $appl_name; ?></a>
		<ul>
<?php
		/* menu level 2 tanpa submenu */
		if(isset($l3[$appl_kode])){
			if(count($l3[$appl_kode])>0){
				$k3 = array_keys($l3[$appl_kode]);
				for($j=0;$j<count($k3);$j++){
					$menu03 = $l3[$appl_kode][$k3[$j]];
					$buka 	= "buka('".$menu03["appl_kode"]."')";
?>
			<li>
				<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="targetUrl" value="<?php echo $menu03["appl_file"]; 	?>"/>
				<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="targetId"  value="content"/>
				<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="errorId"   value="<?php echo getToken();				?>"/>
				<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="appl_kode" value="<?php echo $menu03["appl_kode"]; 	?>"/>
				<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="appl_name" value="<?php echo $menu03["appl_name"]; 	?>"/>
				<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="appl_file" value="<?php echo $menu03["appl_file"]; 	?>"/>
				<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="appl_proc" value="<?php echo $menu03["appl_proc"]; 	?>"/>
				<a onclick="<?php echo $buka; ?>"><?php echo $menu03["appl_name"]; ?></a>
			</li>
<?php
				}
			}
		}
		/* menu level 2 dengan submenu */
		if(isset($l2[$appl_kode])){
			if(count($l2[$appl_kode])>0){
				$k2 = array_keys($l2[$appl_kode]);
				for($k=0;$k<count($k2);$k++){
					$menu02 = $l2[$appl_kode][$k2[$k]];
?>
			<li>
				<a><?php echo $menu02["appl_name"]; ?></a>
				<ul>
<?php
				/* menu level 3 */
				if(count($l3[$menu02["appl_kode"]])){
					$k3 = array_keys($l3[$menu02["appl_kode"]]);
					for($j=0;$j<count($k3);$j++){
						$menu03 = $l3[$menu02["appl_kode"]][$k3[$j]];
?>
					<li>
						<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="targetUrl" value="<?php echo $menu03["appl_file"]; 	?>"/>
						<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="targetId"  value="content"/>
						<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="errorId"   value="<?php echo getToken();				?>"/>
						<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="appl_kode" value="<?php echo $menu03["appl_kode"]; 	?>"/>
						<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="appl_name" value="<?php echo $menu03["appl_name"]; 	?>"/>
						<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="appl_file" value="<?php echo $menu03["appl_file"]; 	?>"/>
						<input type="hidden" class="<?php echo $menu03["appl_kode"]; ?>" name="appl_proc" value="<?php echo $menu03["appl_proc"]; 	?>"/>
						<a onclick="buka('<?php echo $menu03["appl_kode"]; ?>')"><?php echo $menu03["appl_name"]; ?></a>
					</li>
<?php
					}
				}
?>
				</ul>
			</li>
<?php
				}
			}
		}
?>
		</ul>
	</li>
<?php
	}
}
?>