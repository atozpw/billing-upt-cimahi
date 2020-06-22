function pintasan(e){
	// IE8 and earlier
	if(window.event){
		var tombol = e.keyCode;
	}
	// IE9/Firefox/Chrome/Opera/Safari
	else if(e.which){
		var tombol = e.which;
	}
	try{
		var keyProses0 = $('keyProses0').value;
	}
	catch(err){
		var keyProses0 = 0;
	}
	try{
		var keyProses1 = $('keyProses1').value;
	}
	catch(err){
		var keyProses1 = 0;
	}
	var proses=keyProses0.toString() + keyProses1.toString();
	console.log('Kode proses : ' + proses + ', kode pintasan : ' + tombol);

	switch(proses){
		case 'FF':
			switch(tombol){
				case 49:
					var className	= '080301';
					var pintas 		= document.getElementsByClassName(className);
					if (pintas.length>0){
						buka(className);
					}
					else{
						alert('Anda tidak memiliki hak akses : Pembayaran Berjalan');
					}
					break;
				case 50:
					var className	= '080401';
					var pintas 		= document.getElementsByClassName(className);
					if (pintas.length>0){
						buka(className);
					}
					else{
						alert('Anda tidak memiliki hak akses : Pembayaran Tunggakan');
					}					
					break;
				case 51:
					var className	= '080501';
					var pintas 		= document.getElementsByClassName(className);
					if (pintas.length>0){
						buka(className);
					}
					else{
						alert('Anda tidak memiliki hak akses : Pembayaran Non Denda');
					}					
					break;
				case 52:
					var className	= '080601';
					var pintas 		= document.getElementsByClassName(className);
					if (pintas.length>0){
						buka(className);
					}
					else{
						alert('Anda tidak memiliki hak akses : Pembayaran Kolektif');
					}					
					break;
				default:
					return false;
			}
			break;
		case '01':
		/* [01] mode view dengan pencarian */
			switch(tombol){
				case 13:
				/* mode pencarian */
					var activeForm=$('aktiveFind').value;
					var jumlahForm=$('jumlahFind').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveFind').value=activeForm;
					$('find-'+activeForm).focus();
					return true;
					break;
				case 37:
					buka('pref_page');
					return true;
					break;
				case 38:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<=1){
						activeForm=jumlahForm;
					}
					else{
						activeForm--;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				case 40:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				default:
					return false;
			}
			break;
		case '02':
		/* [02] mode hasil pencarian dengan tambah */
			switch(tombol){
				case 37:
					buka('pref_page');
					return true;
					break;
				case 38:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<=1){
						activeForm=jumlahForm;
					}
					else{
						activeForm--;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				case 40:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 66:
					buka('kembali');
					return true;
					break;
				case 78:
					nonghol('tambah');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '03':
		/* mode rincian pada edit DSML */
			switch(tombol){
				case 13:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 37:
					buka('pref_page');
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				case 66:
					buka('kembali');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '04':
		/* [04] mode pencarian pada edit DSML */
		
		/* [01] mode view dengan pencarian */
		/* [04] mode pencarian pada info pelanggan */
			switch(tombol){
				case 37:
					buka('pref_page');
					return true;
					break;
				case 38:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<=1){
						activeForm=jumlahForm;
					}
					else{
						activeForm--;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				case 40:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 66:
					buka('kembali');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '05':
		/* [05] mode rincian pada rute DSML */
			switch(tombol){
				case 13:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 37:
					buka('pref_page');
					return true;
					break;
				case 38:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<=1){
						activeForm=jumlahForm;
					}
					else{
						activeForm--;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				case 40:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 66:
					buka('kembali');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '06':
		/* [06] mode rincian pada pembayaran rekening normal */
			switch(tombol){
				case 38:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)>1){
						activeForm--;
						$('aktiveForm').value=activeForm;
						$('form-'+activeForm).focus();
					}
					return true;
					break;
				case 40:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
						$('aktiveForm').value=activeForm;
						$('form-'+activeForm).focus();
					}
					return true;
					break;
				case 66:
					buka('kembali');
					return true;
					break;
				case 68:
					nonghol('kalkulator');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '07':
		/* [07] mode form cetak rekening */
			switch(tombol){
				case 66:
					buka('kembali');
					return true;
					break;
				case 80:
					window.print();
					return true;
					break;
				default:
					return false;
			}
			break;
		case '08':
		/* [08] mode view dengan pencarian */
			switch(tombol){
				case 13:
				/* mode pencarian */
					var activeForm=$('aktiveFind').value;
					var jumlahForm=$('jumlahFind').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveFind').value=activeForm;
					$('find-'+activeForm).focus();
					return true;
					break;
				case 37:
					buka('pref_page');
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '09':
		/* [08] mode view dengan pencarian */
		/* [09] mode rincian dengan kembali */
			switch(tombol){
				case 37:
					buka('pref_page');
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				case 66:
					buka('kembali');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '0A':
		/* [0A] mode basic view */
			switch(tombol){
				case 37:
					buka('pref_page');
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '0B':
		/* [0B] mode pesan peringatan */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '0C':
		/* [0C] mode form basic */
			switch(tombol){
				case 13:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					if($('form-'+activeForm).type=='text'){
						$('form-'+activeForm).select();
					}
					else{
						$('form-'+activeForm).focus();
					}
					return true;
					break;
				default:
					return false;
			}
			break;
		case '0D':
		/* [0D] mode null result */
			switch(tombol){
				case 66:
					buka('kembali');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '0E':
		/* [0D] mode result SPT */
			switch(tombol){
				case 37:
					buka('pref_page');
					return true;
					break;
				case 39:
					buka('next_page');
					return true;
					break;
				case 66:
					buka('kembali');
					return true;
					break;
				case 80:
					nonghol('cetak');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '0F':
		/* [0F] mode result batal */
			switch(tombol){
				case 13:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 66:
					buka('kembali');
					return true;
					break;
				default:
					return false;
			}
			break;
		/* [10] mode form registrasi pelanggan */
		case '10':
			switch(tombol){
				case 13:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '11':
		/* [11] mode form edit dengan delete */
			switch(tombol){
				case 13:
					var activeForm=$('aktiveRayn').value;
					var jumlahForm=$('jumlahRayn').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveRayn').value=activeForm;
					$('rayn-'+activeForm).focus();
					return true;
					break;
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				case 46:
					buka('delete');
					return true;
					break;
				default:
					return false;
			}
			break;
		case '12':
		/* [02] mode hasil pencarian dengan tambah */
		/* [12] mode form edit dengan delete */
			switch(tombol){
				case 13:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '13':
		/* mode rincian pada edit DSML */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '14':
		/* mode pencarian pada edit DSML */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '15':
		/* mode rincian pada rute DSML */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '16':
		/* [16] mode kalkulator pada pembayaran rekening */
			switch(tombol){
				case 13:
					var activeCalc=$('aktiveCalc').value;
					var jumlahCalc=$('jumlahCalc').value;
					if(Number(activeCalc)<Number(jumlahCalc)){
						activeCalc++;
					}
					else{
						activeCalc=1;
					}
					$('aktiveCalc').value=activeCalc;
					if(activeCalc==1){
						$('calc-'+activeCalc).select();
					}
					else{
						$('calc-'+activeCalc).focus();
					}
					return true;
					break;
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '18':
		/* [08] mode view dengan pencarian */
		/* [18] mode form dengan tutup */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '19':
		/* [08] mode view dengan pencarian */
		/* [09] mode rincian dengan kembali */
		/* [19] mode form dengan tutup */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '1A':
		/* [0A] mode view dengan pencarian */
		/* [1A] mode form dengan tutup */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '1B':
		/* [1B] mode form dengan tutup */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '1C':
		/* [0C] mode form basic */
		/* [1C] mode cetak dengan tutup */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				case 80:
					window.print();
					return true;
					break;
				default:
					return false;
			}
			break;
		case '22':
		/* mode cetak info pelanggan */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				case 80:
					window.print();
					return true;
					break;
				default:
					return false;
			}
			break;
		case '24':
		/* [24] mode cetak info pelanggan */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				case 80:
					window.print();
					return true;
					break;
				default:
					return false;
			}
			break;
		case '28':
		/* [08] mode view cetak info kolektif */
		/* [28] mode cetak info kolektif */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				case 80:
					window.print();
					return true;
					break;
				default:
					return false;
			}
			break;
		case '29':
		/* [09] mode rinci cetak info kolektif */
		/* [29] mode cetak info kolektif */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				case 80:
					window.print();
					return true;
					break;
				default:
					return false;
			}
			break;
		case '2E':
		/* [0E] mode view spt */
		/* [2E] mode cetak dengan tutup */
			switch(tombol){
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				case 80:
					window.print();
					return true;
					break;
				default:
					return false;
			}
			break;
		case '30':
		/* mode rincian rayon */
			switch(tombol){
				case 13:
					var activeForm=$('aktiveForm').value;
					var jumlahForm=$('jumlahForm').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveForm').value=activeForm;
					$('form-'+activeForm).focus();
					return true;
					break;
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		case '32':
		/* [02] mode hasil pencarian dengan tambah */
		/* [32] mode form tambah */
			switch(tombol){
				case 13:
					var activeForm=$('aktiveRayn').value;
					var jumlahForm=$('jumlahRayn').value;
					if(Number(activeForm)<Number(jumlahForm)){
						activeForm++;
					}
					else{
						activeForm=1;
					}
					$('aktiveRayn').value=activeForm;
					$('rayn-'+activeForm).focus();
					return true;
					break;
				case 27:
					var formId=$('tutup').value;
					tutup(formId);
					return true;
					break;
				default:
					return false;
			}
			break;
		default:
			return false;
	}
}