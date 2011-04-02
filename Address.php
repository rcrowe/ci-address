<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address {
	
	public $use_curl       = false; //Use cURL (if installed) instead of file_get_contents
	public $curl_available = false; //Is cURL and library available
	
	private $_ci; //Codeigniter instance
	private $_geocode_url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&';
	
	public function __construct()
	{
		//Get CI instance
		$this->_ci =& get_instance();
		
		//Checks whether cURL is installed and Curl library is available
		//Might want to use cURL as sometime file_get_contents is disabled,
		//I'm thinking of you Dreamhost
		if(extension_loaded('curl') && file_exists(APPPATH.'libraries/Curl.php'))
		{
			//Load curl library
			$this->_ci->load->library('curl');
			
			//Make sure method exists
			//Using _simple_call not simple_get, as Curl library uses __call
			if(method_exists($this->_ci->curl, '_simple_call'))
			{
				$this->curl_available = true;
			}
		}
	}
	
	private function _get($url)
	{
		if($this->use_curl && $this->curl_available)
		{
			return $this->_ci->curl->simple_get($url);
		}
		else
		{
			return file_get_contents($url);
		}
	}
	
	public function valid_postcode($postcode)
	{
		$postcode = trim(strtoupper(str_replace(' ', '', $postcode)));
		
		if(preg_match("/^[A-Z]{1,2}[0-9]{2,3}[A-Z]{2}$/", $postcode) ||
		   preg_match("/^[A-Z]{1,2}[0-9]{1}[A-Z]{1}[0-9]{1}[A-Z]{2}$/", $postcode) ||
		   preg_match("/^GIR0[A-Z]{2}$/",$postcode))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//Get details on a UK postcode
	public function from_postcode($postcode, $number = false)
	{
		//Make sure postcode formatted correctly
		$postcode = trim(strtoupper(str_replace(' ', '', $postcode)));
		
		//Build URL to Google webservice
		$url = $this->_geocode_url.'address='.$postcode;
		
		//Get json from webservice
		$data = $this->_get($url);
		$data = json_decode($data);
		
		$address = array(); //Stores address elements
		
		//Get lat/long to get futher details on address
		$lat = $data->results[0]->geometry->location->lat;
		$lon = $data->results[0]->geometry->location->lng;

		//Get first round of address details
		foreach($data->results[0]->address_components as $address_component)
		{
			$type = $address_component->types[0];

			if($type == 'postal_code')
			{
				$address['postcode'] = $address_component->long_name;
			}

			if($type == 'sublocality')
			{
				$address['address2'] = $address_component->long_name;
			}

			if($type == 'locality')
			{
				$address['town'] = $address_component->long_name;
			}

			if($type == 'administrative_area_level_2')
			{
				$address['county'] = $address_component->long_name;
			}

			if($type == 'country')
			{
				$address['country']     = $address_component->long_name;
				$address['country_iso'] = $address_component->short_name;
			}
		}
		
		//Now get even more info
		//Have to call webservice another time
		
		//Build URL to Google webservice
		$url = $this->_geocode_url.'latlng='.$lat.','.$lon;
		
		$data = $this->_get($url);
		$data = json_decode($data);
		
		//Was any relevant data returned
		if(is_array($data->results))
		{
			foreach($data->results as $result)
			{
				foreach($result->address_components as $address_component)
				{
					$type = $address_component->types[0];

					if($type == 'route')
					{
						$long_name = $address_component->long_name;
						
						$address['address1'] = (!$number) ? $long_name : $number.' '.$long_name;
					}

					if($type == 'administrative_area_level_1')
					{
						$address['country'] = $address_component->long_name;
					}
				}
			}
		}
		
		//Return address
		return $address;
	}
}
// END Address Class

/* End of file Address.php */
/* Location: ./application/libraries/Address.php */