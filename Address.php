<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address {
	
	private $_ci;
	
	public $using_curl = false;
	
	public function __construct()
	{
		$this->_ci =& get_instance();
		
		if(extension_loaded('curl') && file_exists(APPPATH.'libraries/Curl.php'))
		{
			$this->_ci->load->library('curl');
			
			//Using _simple_call not simple_get, as Curl uses __call
			if(method_exists($this->_ci->curl, '_simple_call'))
			{
				$this->using_curl = true;
			}
		}
	}
}
// END Address Class

/* End of file Address.php */
/* Location: ./application/libraries/Address.php */