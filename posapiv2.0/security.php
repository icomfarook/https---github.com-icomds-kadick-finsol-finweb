<?php
class Security {
	public static function encrypt($input, $key) {
		error_log("1");
		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB); 
		error_log("2");
		$input = Security::pkcs5_pad($input, $size); 
		error_log("3");
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, ''); 
		error_log("4");
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND); 
		error_log("5");
		mcrypt_generic_init($td, $key, $iv); 
		error_log("6");
		$data = mcrypt_generic($td, $input); 
		error_log("7");
		mcrypt_generic_deinit($td); 
		error_log("8");
		mcrypt_module_close($td);
		error_log("9"); 
		$data = base64_encode($data); 
		return $data; 
	} 

	private static function pkcs5_pad ($text, $blocksize) { 
		$pad = $blocksize - (strlen($text) % $blocksize); 
		return $text . str_repeat(chr($pad), $pad); 
	} 

	public static function decrypt($sStr, $sKey) {
		error_log("1");
		$decrypted = mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$sKey, 
			base64_decode($sStr), 
			MCRYPT_MODE_ECB
		);
		error_log("2");
		$dec_s = strlen($decrypted); 
		error_log("3");
		$padding = ord($decrypted[$dec_s-1]); 
		error_log("4");
		$decrypted = substr($decrypted, 0, -$padding);
		return $decrypted;
	}	
}
?>