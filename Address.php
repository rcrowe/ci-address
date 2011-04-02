<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address {
	
	public $using_curl = false;
	
	public function __construct()
	{
		//$this->using_curl = (extension_loaded('curl') && file_exists(APPPATH.'libraries/Curl.php')) ? true : false;
		
		//Using Curl library
		//$this->load
		
		if(extension_loaded('curl') && file_exists(APPPATH.'libraries/Curl.php'))
		{
			$this->load->library('curl');
			
			if(method_exists($this->curl, 'simple_get'))
			{
				$this->using_curl = true;
			}
		}
	}
}
// END Address Class

/* End of file Address.php */
/* Location: ./application/libraries/Address.php */