<?php
	require "model/setDB.php";
	require "model/logging.php";
	require "fungsi.php";
	require "lib.php";
	cek_pass();
	/** getParam 
		memindahkan semua nilai dalam array POST ke dalam
		variabel yang bersesuaian dengan masih kunci array
	*/
	$nilai	= $_POST;
	$konci	= array_keys($nilai);
	for($i=0;$i<count($konci);$i++){
		$$konci[$i]	= $nilai[$konci[$i]];
	}
	/* getParam **/
	
	/** predefine parameter */
	$mess = false;
	$proc = false;
	$erno = false;
	define('_KODE',$appl_kode);
	define('_NAME',$appl_name);
	define('_FILE',$appl_file);
	define('_PROC',$appl_proc);
	if(!defined('_HOST')){
		define('_HOST',$ipClient);
	}
	if(isset($appl_tokn)){
		define('_TOKN',$appl_tokn);
	}
	else{
		define('_TOKN',getToken());
	}
	/* predefine parameter **/

	/** koneksi database */
	/* link : link baca */
	$mess 	= "user : ".$DUSER." tidak bisa terhubung ke server : ".$DHOST;
	$link 	= mysql_connect($DHOST,$DUSER,$DPASS) or die(errorLog::errorDie(array($mess)));
	try{
		if(!mysql_select_db($DNAME,$link)){
			throw new Exception("user : ".$DUSER." tidak bisa terhubung ke database : ".$DNAME);
		}
		else{
			unset($mess);
		}
	}
	catch (Exception $e){
		errorLog::errorDB(array($e->getMessage()));
		$mess = "Terjadi kesalahan pada sistem<br/>Nomor Tiket : ".substr(_TOKN,-4);
		$klas = "error";
		$erno = true;
	}

	/* Pagination */
	$pref_mess		= "";
	$next_mess		= "";
	$kembali		= "<input type=\"button\" value=\"Kembali\" onclick=\"buka('kembali')\"/>";
	if(isset($pg) and $pg>1){
		$next_page 	= $pg + 1;
		$pref_page 	= $pg - 1;
		$pref_mess	= "<input type=\"button\" value=\"<<\" class=\"form_button\" onClick=\"buka('pref_page')\"/>";
	}
	else{
		$pg 		= 1;
		$next_page 	= 2;
		$pref_page 	= 1;
	}
	if(!isset($jml_perpage)){
		$jml_perpage 	= 10;
		if(isset($proses)){
			$jml_perpage 	= 10;
		}
	}
	$limit_awal	 	= ($pg - 1) * $jml_perpage;
	
	$kembali	= "<input type=\"button\" value=\"Kembali\" class=\"form_button\" onClick=\"buka('kembali')\"/>";
	$tambah		= "<input type=\"button\" class=\"from_button\" value=\"Tambah\" onclick=\"nonghol('tambah')\"/>";
	/* pagination **/
	
	/* periksa proses php aktif */
	if(isset($cekUrl)){
		$proc = $cekUrl;
	}
	else if(isset($errorUrl)){
		$proc = $errorUrl;
	}
	else if(isset($targetUrl)){
		$proc = $targetUrl;
	}
	else{
		$mess = "File proses belum didefinisikan";
		$erno = true;
	}
	
	if($proc){
		if(is_readable($proc)){
			$mess = "File proses : ".$proc." ditemukan";
		}
		else{
			$mess = "File proses : ".$proc." tidak ditemukan";
			$erno = true;
		}
	}
	
	if(isset($dump) and $dump == 1){
		$ernoId = getToken();
?>
<div id="<?php echo $ernoId; ?>">
	<div class="error left">
		<div><?php echo $mess; ?></div>
		<div>[<a title="Tutup pesan ini" onclick="tutup('<?php echo $ernoId; ?>')">TUTUP</a>]</div>
		<br/>
		<div class="notice center"><?php var_dump($_POST); ?></div>
	</div>
</div>
<?php
		$erno = true;
	}
	else if(!$erno){
		require $proc;
	}
	else{
		$ernoId = getToken();
		errorLog::errorDB(array($mess));
		$mess 	= $mess."<br/>Nomor Tiket : ".substr(_TOKN,-4);
?>
<div id="<?php echo $ernoId; ?>">
	<div class="error left">
		<div><?php echo $mess; ?></div>
		<div>[<a title="Tutup pesan ini" onclick="tutup('<?php echo $ernoId; ?>')">TUTUP</a>]</div>
		<br/>
		<div class="notice center"><?php var_dump($_POST); ?></div>
	</div>
</div>
<?php
	}
?>