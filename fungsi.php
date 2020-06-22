 <?php
	function printRight($stringCetak,$lebar){
		return str_repeat(" ",$lebar-strlen($stringCetak)).$stringCetak;
	}
	function printLeft($stringCetak,$lebar){
		return $stringCetak.str_repeat(" ",$lebar-strlen($stringCetak));
	}
	function printCenter($stringCetak,$lebar){
		$stringLebar = strlen($stringCetak);
		$stringSpasi = $lebar-$stringLebar;
		if($stringSpasi>1){
			$leftSpasi = floor($stringSpasi/2);
			$rightSpasi = ceil($stringSpasi/2);
			return str_repeat(" ",$leftSpasi).$stringCetak.str_repeat(" ",$rightSpasi);
		}
		else{
			return $stringCetak.str_repeat(" ",$lebar-strlen($stringCetak));
		}
	}
	function getToken(){
		$acak	= mt_rand(1,9999);
		return date('ymdHis').str_repeat('0',4-strlen($acak)).$acak;
	}
	function pilihan($data,$param){
		$disabled	= "";
		$readonly 	= "";
		$selected	= "";
		$hasil		= "<select ";
		$paramKey	= array_keys($param);
		for($i=0;$i<count($param);$i++){
			if($paramKey[$i] == "disabled" or $paramKey[$i] == "selected" or $paramKey[$i] == "readonly"){
				if(isset($param['disabled'])){
					$disabled = "disabled";
				}
				if(isset($param['readonly'])){
					$readonly = "readonly";
				}
				$selected = $param['selected'];
			}
			else{
				$hasil	.= $paramKey[$i]."=\"".$param[$paramKey[$i]]."\" ";
			}
		}
		$hasil 		.= $disabled." ".$readonly.">";
		for($i=0;$i<count($data);$i++){
			if(is_array($data[$i])){
				$dataKey	= array_keys($data[$i]);
				$pilihan 	= "";
				if($data[$i][$dataKey[0]] == $selected){
					$pilihan = "selected";
				}
				$hasil .= "<option value=\"".$data[$i][$dataKey[0]]."\" ".$pilihan.">".$data[$i][$dataKey[1]]."</option>";
			}
		}
		$hasil .= "</select>";
		return $hasil;
	}
	function cek_pass(){
		session_start();
		if(!isset($_SESSION["User_c"])){
			header("Location: ./login.php");
		}
		else{
			define("_USER",$_SESSION["User_c"]);
			define("_NAMA",$_SESSION["Name_c"]);
			define("_GRUP",$_SESSION["Grup_c"]);
			define("_KOTA",$_SESSION["Kota_c"]);
			define("_PRIN",$_SESSION['Prin_c']);
			define("_HINT","1");
			return true;
		}
	}

	/** Konversi dari angka ke text */
	function n2c( $nAngkaNumeric, $satuan ) /* Numeric to Character*/
	{ 
		
		$stringAngka = $nAngkaNumeric; 
		return cMilyar( $stringAngka ).$satuan; 
	} 

	function cMilyar( $strAngka ) 
	{ 
		$nLenAngka = strlen($strAngka); 
		$nHasil       = floor($nLenAngka / 3); 
		$nSisa     = $nLenAngka - ($nHasil*3); 
		if( $nLenAngka <= 9) return(cJutaan($strAngka)); 
		if( $nSisa == 0 ) $nSisa = 3; 
		$cRetVal = num2char(substr($strAngka, 0, $nSisa), 1, $strAngka); 
		if( $cRetVal == '' ) 
		{ 
			if( substr($strAngka, 0, $nSisa) != '000' ) 
				$cRetVal = $cRetVal.'milyar '; 
		} 
		else 
		{ 
			$cRetVal = $cRetVal.'milyar '; 
		} 
		$cRetVal = $cRetVal.cJutaan(substr($strAngka, strlen($strAngka)-9, 9)); 
		return $cRetVal; 
	} 

	function cJutaan( $strAngka ) 
	{ 
		$nLenAngka = strlen($strAngka); 
		$nHasil       = floor($nLenAngka / 3); 
		$nSisa     = $nLenAngka - ($nHasil*3); 
		if( $nLenAngka <= 6) return(cRibuan($strAngka)); 
		if( $nSisa == 0 ) $nSisa = 3; 
		$cRetVal = num2char(substr($strAngka, 0, $nSisa), 1, $strAngka); 
		if( $cRetVal == '' ) 
		{ 
			if( substr($strAngka, 0, $nSisa) != '000' ) 
				$cRetVal = $cRetVal.'juta '; 
		} 
		else 
		{ 
			$cRetVal = $cRetVal.'juta '; 
		} 
		$cRetVal = $cRetVal.cRibuan(substr($strAngka, strlen($strAngka)-6, 6)); 
		return $cRetVal; 
	} 

	function cRibuan( $strAngka ) 
	{ 
		$nLenAngka = strlen($strAngka); 
		$nHasil       = floor($nLenAngka / 3); 
		$nSisa     = $nLenAngka - ($nHasil*3); 
		if( $nLenAngka <= 3) return(num2char($strAngka, 0, $strAngka)); 
		if( $nSisa == 0 ) $nSisa = 3; 
		$cRetVal = num2char(substr($strAngka, 0, $nSisa), 0, $strAngka); 
		if( $cRetVal == '' ) 
		{ 
			if( substr($strAngka, 0, $nSisa) != '000' ) 
				$cRetVal = $cRetVal.'ribu '; 
		} 
		else 
		{ 
			$cRetVal = $cRetVal.'ribu '; 
		} 

		$cRetVal = $cRetVal.num2char(substr($strAngka, strlen($strAngka)-3, 3), 1, $strAngka); 
		return $cRetVal; 
	} 
	function num2char( $strNumber, $boolJuta, $strAsli ) 
	{ 
		$acKataKata = array("", "se", "dua", "tiga ", "empat ", "lima ", "enam ","tujuh ", "delapan ", "sembilan "); 
		$strString = $strNumber; 
		$iPanjangStr = 0; 
		$strKataRatus = 'z'; 
		if( strlen( $strString ) == 3 ) 
		{ 
			$nAngkaRatus = intval( substr($strString, 0, 1) ); 
			if( $nAngkaRatus == 0){$strRatus = '';} 
			else{$strRatus = 'ratus ';} 
			$strKataRatus = $acKataKata[$nAngkaRatus].$strRatus; 
			$strString = substr($strString, strlen($strString)-2, 2); 
		} 

		$strKataPuluh = 'z'; 
		$iPanjangStr = strlen($strString); 
		if( $iPanjangStr <= 2 ) 
		{ 
			$nAngkaL = intval(substr($strString, 0, 1)); 
			$nAngkaR = intval(substr($strString, strlen($strString)-1, 1)); 

			if( $nAngkaL == 0){$strPuluh = ''; } 
			else{$strPuluh = ' puluh ';} 

			if( $nAngkaL > 0 ) 
			{ 
				if( $iPanjangStr == 2 ) 
				{ 
					if( ($nAngkaL == 1) && ($nAngkaR != 0) ) 
					{ 
						$strKataPuluh = $acKataKata[$nAngkaR].'belas '; 
					} 
					else 
					{ 
						if( $nAngkaR == 1 ){$strTemp = 'satu ';} 
						else{$strTemp = $acKataKata[$nAngkaR];} 
						$strKataPuluh = $acKataKata[$nAngkaL].$strPuluh.$strTemp; 
					} 
				} 
			} 

			if( $strKataPuluh == 'z' ) 
			{ 
				if( $nAngkaR == 1 ) 
				{ 
						if( ($boolJuta == 0) && (strlen($strAsli) > 1) ) 
						{$strTemp = 'se'; } 
						else{$strTemp = 'satu '; } 
				} 
				else { $strTemp = $acKataKata[$nAngkaR]; } 
				$strKataPuluh = $strTemp; 
			} 
		} 		
		if( $strKataRatus != 'z' ){$strRetVal = $strKataRatus;} 
			else{$strRetVal = '';} 
		$strRetVal = $strRetVal.$strKataPuluh; 
		return $strRetVal; 
	} 
	/* End of : Konversi dari angka ke text **/
?>