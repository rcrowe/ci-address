<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address {
	
	public $using_curl = true;
	
	public function __construct()
	{
		$this->using_curl = (extension_loaded('curl') && file_exists(APPPATH.'libraries/Curl.php')) ? true : false;
	}
}
// END Address Class

/* End of file Address.php */
/* Location: ./application/libraries/Address.php */