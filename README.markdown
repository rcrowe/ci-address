ci-address
==========

ci-address provides helper functions for addresses. Some functions may only work for UK addresses/postcode.

Default uses file_get_contents, but can be enabled to use cURL. If you enable cURL, make sure you have installed
'https://github.com/philsturgeon/codeigniter-curl' in your libraries folder.


Enable cURL
-----------

	if($this->address->curl_available)
	{
		$this->address->use_curl = true;
	}


Validate postcode
-----------------

	$this->address->valid_postcode('BH20 ###');
	
	
Address details from postcode
-----------------------------

	$address = $this->address->from_postcode('bh20 ###', 14);


Distance/Time between two addresses
-----------------------------------

	list($meters, $seconds) = $this->address->driving_distance('address 1', 'address 2');