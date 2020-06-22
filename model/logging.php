<?php
	define("_HOST",$_SERVER['REMOTE_ADDR']);
	class errorLog{
		public static function errorDB($pesan){
			$fileLOG	= "_data/errorDB.cvs";
			$pesan		= array_merge(array(date('Y-m-d H:i:s'),_TOKN,_KODE,_USER,_HOST),$pesan);
			$pesan		= implode(";",$pesan)."\n";
			$openLOG	= fopen($fileLOG, 'a');
			fwrite($openLOG, $pesan);
			fclose($openLOG);
		}
		public static function logDB($mess){
			$fileLOG	= "_data/logDB.cvs";
			$mess		= array_merge(array(date('Y-m-d H:i:s'),_TOKN,_KODE,_USER,_HOST),$mess);
			$mess		= implode(";",$mess)."\n";
			$openLOG	= fopen($fileLOG, 'a');
			fwrite($openLOG, $mess);
			fclose($openLOG);
		}
		public static function logMess($mess){
			$fileLOG	= "_data/logMess.cvs";
			$mess		= array_merge(array(date('Y-m-d H:i:s'),_TOKN,_KODE,_USER,_HOST),$mess);
			$mess		= implode(";",$mess)."\n";
			$openLOG	= fopen($fileLOG, 'a');
			fwrite($openLOG, $mess);
			fclose($openLOG);
		}
		public static function errorDie($mess){
			self::errorDB($mess);
			header("Location: ./error_koneksi.php?appl_tokn="._TOKN."");
		}
	}
?>

