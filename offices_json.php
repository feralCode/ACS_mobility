<?php
header('Content-Type: text/javascript');
$wsdl = 'http://webservices.cancer.org/OfficesWCFService/OfficesService.svc?wsdl';
$options = array( 
  'soap_version'=>SOAP_1_1, 
  'exceptions'=>true, 
  'trace'=>1, 
  'cache_wsdl'=>WSDL_CACHE_NONE 
); 
$soapClient = new SoapClient($wsdl, $options);

// LIST avaible methods from SOAP client
print ("*************************************************************\n".
       "****                     SOAP METHODS                     ***\n". 
       "*************************************************************\n");
var_dump($soapClient->__getFunctions());
/*
last methods pulled 9/16/2014
  GetOfficesByZipCodeResponse GetOfficesByZipCode(GetOfficesByZipCode $parameters)"
  GetOfficesByCityStateResponse GetOfficesByCityState(GetOfficesByCityState $parameters)"
  GetOfficeByOfficeIdResponse GetOfficeByOfficeId(GetOfficeByOfficeId $parameters)"
  GetStateListResponse GetStateList(GetStateList $parameters)"
  GetDivisionByCityResponse GetDivisionByCity(GetDivisionByCity $parameters)"
  GetDivisionByZipCodeResponse GetDivisionByZipCode(GetDivisionByZipCode $parameters)"
*/

//print  "\n\r\n\r";
print ("*************************************************************\n".
       "****                     SOAP METHODS                     ***\n". 
       "*************************************************************\n");
var_dump($soapClient->__getTypes());

//print  "\n\r\n\r";

//$SimpleEventDto['City'] = 'atlanta';
//var_dump($soapClient->SimpleEventDto(););

$datetime_now = date('Y-m-d'); //formated W3C format datetime

////////////////////////////////////////////////////////////////////////////
/////                             State List                     ///////////
////////////////////////////////////////////////////////////////////////////
  //ret state list from ACS
  /*print ("*************************************************************\n".
         "****                     State List                       ***\n". 
         "*************************************************************\n")
  State List struct
  string(103) "struct StateDto {
   string CountryInitials;
   string StateId;
   string StateInitials;
   string StateName;
  }"
  GetStateListResult->StateDto  = state Array*
  ////START EXAMPLE/////
    object(stdClass)#5 (4) {
        ["CountryInitials"]=>
        string(2) "US"
        ["StateId"]=>
        string(1) "2"
        ["StateInitials"]=>
        string(2) "AK"
        ["StateName"]=>
        string(6) "Alaska"
    }
  /////END EXAMPLE/////
  print var_dump($soapClient->GetStateList());
  */
  // ACS state list 
  $GetStateList = $soapClient->GetStateList();


/* Office info
"struct OfficeDto {
 string Address1;
 string Address2;
 string City;
 string DayHours;
 string Fax;
 double Latitude;
 double Longitude;
 string OfficeDesc;
 int OfficePk;
 string PublishedPhone;
 string State;
 string ZipCode;
}"*/


$params = array(
  'zipCode' => '30305'
);

$response = $soapClient->GetOfficesByZipCode($params);
print var_dump($response->GetOfficesByZipCodeResult->OfficeDto);
$response = null;

$params = array(
  'city' => 'atlanta',
  'state' => 'GA'
);

$response = $soapClient->GetOfficesByCityState($params);
print var_dump($response->GetOfficesByCityStateResult->OfficeDto);
$response = null;

// clear soapClient
$soapClient = null;

//print json_encode($arr);
?>