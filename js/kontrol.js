function classToHash(opt){
	var param = new Hash();
	$A(document.getElementsByClassName(opt)).each(function(s){
		param.set(s.name,s.value);
	});
	return param;
}
function getSync(targetUrl,targetId,errorId,param){
	var errMess = false;
	new Ajax.Request(targetUrl, {
		method: 'post',
		parameters: param,
		onload: $('load').show(),
		onComplete: function(response) {
			$(targetId).innerHTML = response.responseText;
			if($(errorId)!=null){
				var errMess = $(errorId).value;
			}
			if(errMess){
				var param 	= 'pesan=' + errMess;
				getMess('pesan.php','load',param);
			}
			else{
				$('load').hide();
			}
		}
	});
}
function getAsync(targetUrl,targetId,param){
	new Ajax.Request(targetUrl, {
		asynchronous: false,
		method: 'post',
		parameters: param,
		onComplete: function(response) {
			$(targetId).innerHTML = response.responseText;
		}
	});
}
function getMess(targetUrl,targetId,param){
	new Ajax.Request(targetUrl, {
		method: 'post',
		parameters: param,
		onComplete: function(response) {
			$(targetId).insert(response.responseText);
		}
	});
}
function getErr(targetUrl,targetId,param){
	new Ajax.Request(targetUrl, {
		method: 'post',
		parameters: param,
		onload: $('load').show(),
		onComplete: function(response) {
			$(targetId).insert(response.responseText);
			$('load').hide();
		}
	});
}
function buka(opt){
	var param 		= new Hash();
	var param     	= classToHash(opt);
	var targetId	= param.get("targetId");
	var errorId		= param.get("errorId");
	param.unset("targetId");
	getSync('interface.php',targetId,errorId,param);
	$('mainBody').focus();
}
function anyar(opt){
	var param 		= new Hash();
	var param     	= classToHash(opt);
	var targetId	= param.get("targetId");
	param.unset("targetId");
	new Ajax.Request('interface.php', {
		method: 'post',
		parameters: param,
		onComplete: function(response) {
			$(targetId).innerHTML = response.responseText;
			$('mainBody').focus();
		}
	});
}
function nonghol(opt){
	var param 		= new Hash();
	var param     	= classToHash(opt);
	var targetId	= param.get("targetId");
	if(targetId==undefined){
		targetId = 'peringatan';
		$('peringatan').show();
	}
	getErr('interface.php',targetId,param);
	$('mainBody').focus();
}
function dashboard(opt){
	var param 	= new Hash();
	var param 	= classToHash(opt);
	var targetId  = param.get("targetId");
	new Ajax.PeriodicalUpdater(targetId, "interface.php",{
		method: "post", frequency: 1, decay: 1, parameters: param
	});
}
function pilihan(opt){
    var param     	= classToHash(opt);
    var targetUrl 	= param.get('targetUrl');
	var targetId 	= param.get('targetId');
    if($(opt).checked){
        param.set('pilihan',1);
    }
    getAsync('interface.php',targetId,param);
}
function pilihin(opt){
    if($(opt).checked){
        $(opt).value = 1;
    }
	else{
        $(opt).value = 0;
	}
}
function tutup(opt){
	var param 	= new Hash();
	$(opt).remove();
	getAsync('kosong.php','peringatan',param);
	getAsync('kosong.php','load',param);
	$('peringatan').hide();
	$('load').hide();
	try{
		var norefresh = $('norefresh').value;
		$('mainBody').focus();
	}
	catch(err){
		//anyar.delay(1,'refresh');
		anyar('refresh');
	}
}
function cetakin(opt){
	var param 		= classToHash(opt);
	var targetId 	= param.get('targetId');
	var targetUrl	= param.get('targetUrl');
	param.unset('targetId');
	param.unset('targetUrl');
	param = '?' + param.toQueryString();
	window.open(targetUrl + param);
}

/** fungsi pembayaran */
function bayarRek(opt){
	var pilih 	= document.getElementsByClassName('pilih');
	var j		= opt;
	var total	= 0;
	if(pilih[opt].checked == true){
		j	= Number(opt) + 1;
	}
	for(i=0;i<pilih.length;i++){
		if(i<j){
			$('pilih_' + i).value 	= 1;
			var total				= Number(total) + Number($F('total_' + i));
			pilih[i].checked 		= true;
		}
		else{
			$('pilih_' + i).value 	= 0;
			pilih[i].checked 		= false;
		}
	}
	$('bayar').value = total;
}
function bayarKol(opt){
	var pilih 	= document.getElementsByClassName('pilih');
	var total	= 0;
	if(pilih[opt].checked == true){
		$('pilih_' + opt).value = 1;
	}
	else{
		$('pilih_' + opt).value = 0;
	}
	for(i=0;i<pilih.length;i++){
		if($('pilih_' + i).value == '1'){
			var total = Number(total) + Number($F('total_' + i));
		}
	}
	$('bayar').value = total;
}
/* fungsi pembayaran **/