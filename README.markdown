ci-address
==========

ci-address provides helper functions for addresses. Some functions may only work for UK addresses/postcode.

Default uses file_get_contents, but can be enabled to use cURL. If you enable cURL, make sure you have installed
https://github.com/philsturgeon/codeigniter-curl in your libraries folder.


Enable cURL
-----------

	if($this->address->curl_available)
	{
		echo 'Using cURL';
		$this->address->use_curl = true;
	}
