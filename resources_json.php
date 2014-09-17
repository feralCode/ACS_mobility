<?php

header('Content-Type: text/javascript');
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



$datetime_now = date('Y-m-d'); //formated W3C format datetime
$wsdl = 'http://webservices.cancer.org/ResourcesWCFService/CancerResourcesService.svc?wsdl';
$options = array( 
  'soap_version'=>SOAP_1_1, 
  'exceptions'=>true, 
  'trace'=>1, 
  'cache_wsdl'=>WSDL_CACHE_NONE 
); 
$soapClient = new SoapClient($wsdl, $options);

////////////////////////////////////////////////////////////////////////////
  /* list avaible methods from SOAP client
  print ("*************************************************************\n".
         "****                     SOAP METHODS                     ***\n". 
         "*************************************************************\n");
  var_dump($soapClient->__getFunctions());
  */

  /*
  SearchResourcesResponse SearchResources(SearchResources $parameters)
  SearchResourcesLiteResponse SearchResourcesLite(SearchResourcesLite $parameters)
  SearchProgramCategoryCountResponse SearchProgramCategoryCount(SearchProgramCategoryCount $parameters)

  GetStateListResponse GetStateList(GetStateList $parameters)
  GetProgramCategoryListResponse GetProgramCategoryList(GetProgramCategoryList $parameters)
  GetResourceResponse GetResource(GetResource $parameters)
  GetResourceCountsByCityResponse GetResourceCountsByCity(GetResourceCountsByCity $parameters)
  GetResourceCountsByZipResponse GetResourceCountsByZip(GetResourceCountsByZip $parameters)

  ExecuteResourceSearchResponse ExecuteResourceSearch(ExecuteResourceSearch $parameters)
  ExecuteResourceSearchWithDetailResponse ExecuteResourceSearchWithDetail(ExecuteResourceSearchWithDetail $parameters)

  GetSessionSchedulesResponse GetSessionSchedules(GetSessionSchedules $parameters)
  GetProgramTypeListResponse GetProgramTypeList(GetProgramTypeList $parameters)
  GetResourceByResourceBKResponse GetResourceByResourceBK(GetResourceByResourceBK $parameters)
  GetSessionSchedulesBySessionBKResponse GetSessionSchedulesBySessionBK(GetSessionSchedulesBySessionBK $parameters)
  */



////////////////////////////////////////////////////////////////////////////
  // list avaible types from SOAP client
  print  "\n\r\n\r";
  print ("*************************************************************\n".
         "****                    SOAP TYPES                        ***\n". 
         "*************************************************************\n");
  var_dump($soapClient->__getTypes());

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


//holds Program Category List IDs for search
$eventCategoryIds = array();
//retrieve category list from ACS
$GetProgramCategoryList = $soapClient->GetProgramCategoryList();
/*
print ("*************************************************************\n".
       "****                GetProgramCategoryList()              ***\n". 
       "*************************************************************\n");
var_dump($GetProgramCategoryList); //print category list
*/

$catArr = $GetProgramCategoryList->GetProgramCategoryListResult->ProgramCategoryDto;
foreach ($catArr as $c) {
  $catId = $c->ProgramCategoryId;
  //print "Adding id " . $catId . " to eventCategoryIds\n";
  array_push($eventCategoryIds, $catId);
}

//$GetResourceByResourceBK = $soapClient->GetResourceByResourceBK(52); 
//print ("*************************************************************\n".
  //     "****             GetResourceByResourceBK()                ***\n". 
  //     "*************************************************************\n");
//var_dump($GetResourceByResourceBK);


/*print ("*************************************************************\n".
       "****      ExecuteResourceSearchWithDetail()          ***\n". 
       "*************************************************************\n");
 Resource Search Parameters
string(327) "struct ResourceSearchParameters {
 string City;
 string County;
 int EndRow;
 string Keyword;
 int OrganizationId;
 int ProgramCategoryId;
 ArrayOfNullableOfint ProgramCategoryIds;
 int ProgramTypeBK;
 int ResourceBK;
 int ResourceId;
 boolean SortAscending;
 string SortBy;
 int StartRow;
 string StateCode;
 string ZipCode;
}"
$params = array( 'searchParams' => array(
  "City"  => 'atlanta',
  "County"  => '',
  "EndRow"  => 50,
  "Keyword"  => 'cancer',
  "ProgramCategoryIds"  => eventCategoryIds,
  "SortAscending" => false,
  "SortBy"  => 'Distance',
  "StartRow"  => 0,
  "StateCode"  => 'GA',
  "ZipCode"  => '30305'
 ));*/


$params = array( 'searchParams' => array(
 'StartRow' => 0,
 'EndRow'  => 20,
 'ZipCode' => '30305',
 'SortBy' => 'Distance',
 'ProgramCategoryIds' => $eventCategoryIds
 ));

$response = $soapClient->SearchResourcesLite($params);

//SearchResourcesLite -> use this for simple view without sessions and all

//SearchResources -> use to return all result properties
// use ResourcePk to get specific results
/*params = array( 'searchParams' => array(
 'StartRow' => 0,
 'EndRow'  => 10,
 'ZipCode' => '30305',
 'SortBy' => 'Distance',
 'ProgramCategoryIds' => $eventCategoryIds
 ));*/

//print var_dump($response->SearchResourcesLiteResult->SimpleResourceDto);

/*program props
SearchResourcesLite
important
  Name
  OrganizationName
  ProgramTitle
  LocationName
  ZipCode
  ResourcePk *for details view

details view
  ProgramTitle
  Name
  OrganizationName
  description
  distance
  LocationWebSite
  EligibilityProcedures
  InsuranceCoverage
  HandicappedAccessible -> icon
  CostFees
  ProgramDescription
  DivisionInstructions
  LastVerifyDate(last update)

session detail view 
  Sessions

"struct ResourceDto {
 string Address1;
 string Address2;
 string Affiliations;
 string City;
 string Comments;
 string CostFees;
 string Country;
 string Directions;
 double Distance;
 string DivisionInstructions;
 string EligibilityProcedures;
 string GeoCodeQuality;
 boolean HandicappedAccessible;
 string InsuranceCoverage;
 boolean IsACSProgram;
 boolean IsPartnerWithACS;
 dateTime LastVerifyDate;
 double Latitude;
 string LocationInformation;
 string LocationName;
 string LocationWebSite;
 double Longitude;
 string Name;
 string NcicInstructions;
 string OrganizationName;
 string OriginalAuthorName;
 dateTime OriginalCreateDate;
 string ProgramCategory;
 string ProgramDescription;
 string ProgramTitle;
 string ProgramType;
 int ProgramTypeBk;
 boolean PublishToWeb;
 int ResourceBk;
 int ResourceId;
 int SessionBk;
 int SessionId;
 ArrayOfSessionDto Sessions;
 string State;
 string ZipCode;
*/

//null soapClient
$soapClient = null;

//print as json
print json_encode($response->SearchResourcesLiteResult->SimpleResourceDto);
?>
