<?php
	/* link tulis */
	$PHOST	= "127.0.0.1";
	$PUSER	= "root";
	$PPASS	= "";
	$PNAME	= "pdam_uptcimahi";
	/* link baca */
	$DHOST	= "127.0.0.1";
	$DUSER	= "root";
	$DPASS	= "";
	$DNAME	= "pdam_uptcimahi";
	
	/** konfigurasi PDO */
	$PSPDO[0]	= "mysql:dbname=".$PNAME.";host=".$PHOST;
	$PSPDO[1]	= $PUSER;
	$PSPDO[2] 	= $PPASS;
	$DSPDO[0]	= "mysql:dbname=".$DNAME.";host=".$DHOST;
	$DSPDO[1]	= $DUSER;
	$DSPDO[2] 	= $DPASS;
	/* konfigurasi database **/
?>
