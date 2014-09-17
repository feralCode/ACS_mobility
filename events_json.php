<?php
header('Content-Type: application/json');
$datetime_now = date('Y-m-d'); //formated W3C format datetime

$p_zipCode = null;
$p_city = null;
$p_state = null;

$params = array();

//verify that required fields are passed is not exit
if(isset($_POST['zipCode'])) {
  $p_zipCode = $_POST['zipCode'];
  $params['zipCode'] = $_POST['zipCode'];
}
else if(isset($_POST['city']) && isset($_POST['state']) ) {
  $p_city = $_POST['city '];
  $p_state = $_POST['state'];
  $params['city'] = $_POST['city'];
  $params['state'] = $_POST['state'];
}
else {
  //trigger 500 error
  header("HTTP/1.0 500 Internal Server Error");
}

$wsdl = 'http://webservices.cancer.org/EventsWCFService/EventService.svc?wsdl';
$options = array( 
  'soap_version'=>SOAP_1_1, 
  'exceptions'=>true, 
  'trace'=>1, 
  'cache_wsdl'=>WSDL_CACHE_NONE, 
  'keep_alive'=>false
); 
$soapClient = new SoapClient($wsdl, $options);

//holds included category ids for search
$categoryIds = array();
//retrieve category list from ACS
//print var_dump($soapClient->GetCategoryList()); //print category list
$res = $soapClient->GetCategoryList();
$categoryList = $res->GetCategoryListResult->CategoryDto;
foreach ($categoryList as $c) {
  $catId = $c->CategoryId;
  array_push($categoryIds, $catId);
}

//lets limite to 20 results
$params['endRow'] = 20;
$params['startRow'] = 0;
$params['eventCategory'] = $categoryIds;
$params['sortAscending'] = false;


/*
******************************************
****      Available Search Fields      ***
******************************************
String(313) "struct ExecuteSearchWithFacet {
 string zipCode;
 string city;
 string state;
 string county;
 int radiusDistance;
 ArrayOfint eventCategory;
 string keyword;
 string eventTitle;
 dateTime startDate;
 dateTime endDate;
 int startRow;
 int endRow;
 boolean includeExpired;
 string sortBy;
 boolean sortAscending;
}"*/

$response = $soapClient->ExecuteSearchWithFacet($params);
//print var_dump($response->ExecuteSearchWithFacetResult->Categories->CategoryDto);
//print var_dump($response->ExecuteSearchWithFacetResult->SimpleEvents->SimpleEventDto);

// clear soapClient
$soapClient = null;
echo json_encode($response);
return
/*
// LIST avaible methods from SOAP client
print ("*************************************************************\n".
       "****                     SOAP METHODS                     ***\n". 
       "*************************************************************\n");
var_dump($soapClient->__getFunctions());

last methods pulled 9/16/2014
//ExecuteBasicSearchResponse ExecuteBasicSearch(ExecuteBasicSearch $parameters)"
//ExecuteCityStateSearchResponse ExecuteCityStateSearch(ExecuteCityStateSearch $parameters)"
//ExecuteEventTitleSearchResponse ExecuteEventTitleSearch(ExecuteEventTitleSearch $parameters)"
//ExecuteSearchWithFacetResponse ExecuteSearchWithFacet(ExecuteSearchWithFacet $parameters)"

//GetEventResponse GetEvent(GetEvent $parameters)"
//GetCategoryListResponse GetCategoryList(GetCategoryList $parameters)"
//GetCategoryCountsResponse GetCategoryCounts(GetCategoryCounts $parameters)"

//MatchCityNameResponse MatchCityName(MatchCityName $parameters)"

//GetDaffodilDonationsResponse GetDaffodilDonations(GetDaffodilDonations $parameters)"
//GetDaffodilOrderAndDeliveryResponse GetDaffodilOrderAndDelivery(GetDaffodilOrderAndDelivery $parameters)"
//GetDaffodilLocationsResponse GetDaffodilLocations(GetDaffodilLocations $parameters)"
//GetEventPromoInfoResponse GetEventPromoInfo(GetEventPromoInfo $parameters)"


print ("*************************************************************\n".
       "****                     SOAP METHODS                     ***\n". 
       "*************************************************************\n");
var_dump($soapClient->__getTypes());

//print  "\n\r\n\r";

//$SimpleEventDto['City'] = 'atlanta';
//var_dump($soapClient->SimpleEventDto(););





//holds included category ids for search
$categoryIds = array();

//retrieve category list from ACS
//print var_dump($soapClient->GetCategoryList()); //print category list
$res = $soapClient->GetCategoryList();
$categoryList = $res->GetCategoryListResult->CategoryDto;
foreach ($categoryList as $c) {
  $catId = $c->CategoryId;
  array_push($categoryIds, $catId);
}



//*provide city state and radiusDistance or zipCode prop to get results
//$params = array(
 //'zipCode' => '30305',
 //'startDate' => $datetime_now,
 //'eventCategory' => $categoryIds
 //);

//print  "-------------------Bacis Search Results-------------------\n\r";
//$response = $soapClient->ExecuteSearchWithFacet($params);
//categories in results
//print var_dump($response->ExecuteSearchWithFacetResult->Categories->CategoryDto);
//simple events
//print var_dump($response->ExecuteSearchWithFacetResult->SimpleEvents->SimpleEventDto);

// clear soapClient
//$soapClient = null;

//print json_encode($arr);
*/
?>
