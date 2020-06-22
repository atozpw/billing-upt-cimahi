<?php
	# Versi Aplikasi
	$appl_ver 			= "3.0" ;
	# Nama Aplikasi
	$appl_nama 			= "Billing System";
	# Owner Aplikasi
	$appl_owner 		= "UPT AIR MINUM KOTA CIMAHI";
	# Pengembang Aplikasi
	$appl_developer 	= "ASPER-1";
	# Nama Lengkap Aplikasi
	//$application_nama = $appl_owner." &bull; ".$appl_nama." (Release: V".$appl_ver.")";
	//$application_name = $appl_owner." &bull; ".$appl_nama." (Release: V".$appl_ver.")";
	$application_nama 	= $appl_nama." &bull; ".$appl_owner;
	$application_name 	= $appl_nama." &bull; ".$appl_owner;
	# Logo Aplikasi
	$appl_logo 			= "favicon.ico";

	$phpSelf			= $_SERVER['PHP_SELF'];
	$ipClient			= $_SERVER['REMOTE_ADDR'];
	# Mangrupaning Tatanggalan
	date_default_timezone_set('Asia/Jakarta');
	$hari 				= array('Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Every Day');
	$bulan 				= array('','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	$tanggal			= date('Y-m-d');
	$jam				= date('H:i:s');
	$today 				= getdate(); 
	$month 				= date('m');
	$mday 				= date('d'); 
	$year 				= $today['year']; 
	$hday 				= date("w");
	$tgl_sekarang		= "$mday/$month/$year";
	$tgl_sekarang_full	= "$mday ".$bulan[date('n')]." $year";
	$tgl_entry 			= "$year-$month-$mday";
	$nama_direktur 		= "AGUS HILMAN E.S,ST";
?>
