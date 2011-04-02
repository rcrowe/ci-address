<?php

if(extension_loaded('curl') && file_exists(APPPATH.'libraries/Curl.php'))
{
	//Use cURL
	//	Some servers have file_get_contents disabled
	
	$this->CI->load->library('curl');

	$json = $this->CI->curl->simple_get($url);
}
else
{
	//Use file_get_contents as fall back
}

$num       = 6;
$postcode  = 'tq5 8ne';
$postcode  = trim(strtoupper(str_replace(' ', '', $postcode)));

$postcode_url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=';
$latlong_url  = 'http://maps.google.com/maps/api/geocode/json?sensor=false&latlng=';

$delay = false;

$address = array(); //Holds address details

//Get details for postcode from google
$data = file_get_contents($postcode_url.$postcode);
$data = json_decode($data);


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

if($delay)
{
	sleep(2);
}

//Get more info
$data = file_get_contents($latlong_url.$lat.','.$lon);
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
				$address['address1'] = $num.' '.$address_component->long_name;
			}
			
			if($type == 'administrative_area_level_1')
			{
				$address['country'] = $address_component->long_name;
			}
		}
	}
}

print_r($address);

?>