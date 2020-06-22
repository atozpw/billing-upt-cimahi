/** fungsi khusus validasi dsml */
function pilihDsml(opt){
	var sm_lalu		= $('sm_lalu_' + opt).value;
	var sm_kini		= $('form-' + opt).value;
	var pakai_kini 	= Number(sm_kini) - Number(sm_lalu);
	if (pakai_kini>=0){
		setUbah(opt);
		$('pakai_kini_' + opt).innerHTML = pakai_kini;
		return true;
	}
	else{
		setUbah(opt);
		$('pakai_kini_' + opt).innerHTML = "<font color='red'>Negatif</font>";
		return false;
	}
}
function setUbah(opt){
	$('ubah_' + opt).value = 1;
	return true;
}
/* **/