<?php 
namespace App\Services;
use GuzzleHttp, Config, Auth;
use \DOMDocument, \SimpleXMLElement;

class Stringtoint{

	public function __construct()
	{
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function string_to_int($str) {
	    $number = 0;
	    for ($i = 3; $i < strlen($str); $i++) {
	        $number = 10 * $number + (ord($str[$i]));
	    }
	    return $number;
	}
}


