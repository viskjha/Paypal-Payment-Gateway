<?php
// Include config file
require_once('includes/config.php');

// Store request params in an array
$request_params = array
					(
					'METHOD' => 'DoDirectPayment', 
					'USER' => 'usinesstest511_api1.gmail.com', 
					'PWD' => 'MJQDEVGUH3ATVGS9', 
					'SIGNATURE' => 'A5chQGWFzbBxFMFGFAUJExcfjMl9AKYhgLUv8IKdRri4gtNepBwXIRSW', 
					'VERSION' => '85.0', 
					'PAYMENTACTION' => 'Sale', 					
					'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
					'CREDITCARDTYPE' => 'MasterCard', 
					'ACCT' => '552234000606363', 						
					'EXPDATE' => '022013', 			
					'CVV2' => '456', 
					'FIRSTNAME' => 'Tester', 
					'LASTNAME' => 'Testerson', 
					'STREET' => '707 W. Bay Drive', 
					'CITY' => 'Largo', 
					'STATE' => 'FL', 					
					'COUNTRYCODE' => 'US', 
					'ZIP' => '33770', 
					'AMT' => '100.00', 
					'CURRENCYCODE' => 'USD', 
					'DESC' => 'Testing Payments Pro' 
					);
					
// Loop through $request_params array to generate the NVP string.
$nvp_string = '';
foreach($request_params as $var=>$val)
{
	$nvp_string .= '&'.$var.'='.urlencode($val);	
}

// Send NVP string to PayPal and store response
$curl = curl_init();
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);

$result = curl_exec($curl);
echo $result.'<br /><br />';
curl_close($curl);

// Parse the API response
$result_array = NVPToArray($result);

echo '<pre />';
print_r($result_array);

// Function to convert NTP string to an array
function NVPToArray($NVPString)
{
	$proArray = array();
	while(strlen($NVPString))
	{
		// name
		$keypos= strpos($NVPString,'=');
		$keyval = substr($NVPString,0,$keypos);
		// value
		$valuepos = strpos($NVPString,'&') ? strpos($NVPString,'&'): strlen($NVPString);
		$valval = substr($NVPString,$keypos+1,$valuepos-$keypos-1);
		// decoding the respose
		$proArray[$keyval] = urldecode($valval);
		$NVPString = substr($NVPString,$valuepos+1,strlen($NVPString));
	}
	return $proArray;
}