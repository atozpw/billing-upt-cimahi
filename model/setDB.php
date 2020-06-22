<?php
	/* link tulis */
	$PHOST	= "103.75.237.45";
	$PUSER	= "root";
	$PPASS	= "pohodeui";
	$PNAME	= "pdam_billing_upt_cimahi";
	/* link baca */
	$DHOST	= "103.75.237.45";
	$DUSER	= "root";
	$DPASS	= "pohodeui";
	$DNAME	= "pdam_billing_upt_cimahi";
	
	/** konfigurasi PDO */
	$PSPDO[0]	= "mysql:dbname=".$PNAME.";host=".$PHOST;
	$PSPDO[1]	= $PUSER;
	$PSPDO[2] 	= $PPASS;
	$DSPDO[0]	= "mysql:dbname=".$DNAME.";host=".$DHOST;
	$DSPDO[1]	= $DUSER;
	$DSPDO[2] 	= $DPASS;
	/* konfigurasi database **/
?>
