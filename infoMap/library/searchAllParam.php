<?php
ob_start(); // ensures anything dumped out will be caught  
//@session_start();
ini_set('display_errors', 1);

require_once '../library/config.php';
//echo WEB_ROOT.'library/config.php';
//sessionX(true);

error_reporting(E_ERROR | E_PARSE);

include_once '../library/config.php';
include_once '../library/database.php';
include_once '../library/common.php';
//include_once '../library/category-functions.php';

//include_once($_SERVER['DOCUMENT_ROOT']."/app/ArrayJob.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/app/StringManipulation.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/app/MyDebug.php");
//include_once($_SERVER['DOCUMENT_ROOT']."/CloudConsultant/mydbclass.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['plaincart_customer_id'])){ 
    echo 'not properly login';
    die; 
}
else
{
    //echo 'login success';

}


$userId = $_SESSION['plaincart_customer_id'];
$sql = "SELECT user_name, user_role, user_org, com_pos, hn_alias 
        FROM tbl_user 
        WHERE user_id = $userId";
$result = dbQuery($sql);

if(dbNumRows($result) == 1) {
        $row = dbFetchAssoc($result);
        $userName = $row['user_name']; 
        $userRole = $row['user_role'];
        $com_pos = $row['com_pos'];
        $userOrg = $row['user_org'];
        $myHN = $row['hn_alias'];
    //echo 'com_pos = '.$com_pos.'<br>';
    //echo 'welcome '.$userName.' '.$userRole.'<br>';
} 
else
{
    //echo $useID.' not match for any users<br>';
    die;
}

if($userRole == 'guest'){
    //echo 'GUEST';
    die;
}

if($userRole == 'analyser'){
    $userOrgStr = " AND org_name='$userOrg'";
}
else if($userOrg == 'n/a'){
    $userOrgStr = '';
}
else {
    $userOrgStr = '';
}

function isDate($value) 
{
    if (!$value) {
        return false;
    }

    try {
        new \DateTime($value);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}



set_error_handler("myErrorHandler");
  

$q = strtolower($_GET["term"]);

$isdate = isDate($q);

if (!$q) return;

if(substr($q,-1, 1)=='*' or substr($q,-1, 1)=='.'){
   $q = substr($q, 0, strlen($q)-1); 
}

$items = array();

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
else
{
  $likeName = "%".$q."%";
  $likeHN = "%".$q."";

  //$Q1 = 'SELECT * FROM bill_to_cash WHERE order_date_time LIKE "'.$today.'%" ORDER BY order_date_time;';

    if(isDate($q) && strlen($q) == 10){
        $q = substr($q, 0, 10);
        
        $Q1 = "SELECT DISTINCT($tblFinMx.hospital_number), $tblFinMx.order_date_time, $tblCust.Name AS Name, $tblCust.Surname AS Surname, $tblCust.siteID AS siteID, $tblCust.Age AS Age FROM $tblFinMx LEFT JOIN $tblCust ON $tblFinMx.hospital_number = $tblCust.siteID WHERE order_date_time LIKE '$q%' ORDER BY order_date_time";
        
        //SELECT tbl_order_item.order_date_time, tbl_cust.Name, tbl_cust.Surname FROM tbl_cust LEFT JOIN tbl_order_item ON tbl_order_item.hospital_number = tbl_cust.siteID
    }
    else if($_GET["term"] == 'me'){ 
        $Q1 = "SELECT * FROM $tblCust WHERE (siteID LIKE '%".$myHN."');";
        
        //echo '$Q1 = '.$Q1.'<br>';
    }
    else if($_GET["term"] == 'family'){
            if(isset($_SESSION['fHN'])){
                $fHN = $_SESSION['fHN'];

                /*$Q1 = "SELECT DISTINCT($tblFinMx.hospital_number), $tblFinMx.paid_by_whom, $tblCust.Name AS Name, $tblCust.Surname AS Surname, $tblCust.siteID AS siteID, $tblCust.Age AS Age FROM $tblFinMx LEFT JOIN $tblCust ON $tblFinMx.hospital_number = $tblCust.siteID WHERE siteID='$fHN' OR paid_by_whom='$fHN' LIMIT 20;";*/
                
                $Q1 = "SELECT Name, Surname, Age, siteID FROM $tblCust WHERE siteID IN (SELECT distinct(hospital_number) FROM $tblFinMx WHERE (hospital_number='$fHN' OR paid_by_whom='$fHN') AND process_date_time IS NOT NULL) LIMIT 20;";
                
            }
            else
            {
                die;
            }
            /*$Q1 = "SELECT * FROM $tblCust WHERE (concat(Name,' ',Surname) LIKE '%".$_GET["term"]."%') OR (siteID LIKE '%".$q."');";*/

    }
    else
    {
        $Q1 = "SELECT * FROM $tblCust WHERE (concat(Name,' ',Surname) LIKE '%".$_GET["term"]."%') OR (siteID LIKE '%".$q."')$userOrgStr;";
    }
  //echo 'Q1 = '.$Q1.'<br>';

  $rtn = dbQuery($Q1, $Q1);
  

  $result = array();

  //echo '<hr>there are '.dbNumRows($rtn).'<hr>';
    
  /*if(dbNumRows($rtn) == 0) {

		 array_push($result, array("id"=>"New member", "label"=>"New member", "value"=>"New member","sex"=>"?","prefix"=>"?","name"=>'Name',"lastname"=>"Last name","age"=>"?","birth_date"=>"dd-mm-yyyy"));  

  }
  else
  {*/
    $addedPt = array();
    
  	while($row = dbFetchAssoc($rtn)){
       extract($row);
       
        if(!in_array($Name.' '.$Surname,$addedPt)){
            $addedPt[] = $Name.' '.$Surname;
        
    
        
           array_push($result, array("id"=>$siteID, "label"=>$Name.' '.$Surname, "value"=>$Name.' '.$Surname,"sex"=>$Sex,"prefix"=>$Prefix,"name"=>$Name,"lastname"=>$Surname,"age"=>$Age,"birth_date"=>$birth_date,"body_weight"=>$body_weight,"body_height"=>$body_height,"birth_time"=>$birth_time));
        }
	}
      
    if(count($result) == 0){
        if($userRole == 'super_admin'){
            array_push($result, array("id"=>"New member", "label"=>"$Q1", "value"=>"New member","sex"=>"?","prefix"=>"?","name"=>'Name',"lastname"=>"Last name","age"=>"?","birth_date"=>"dd-mm-yyyy","term"=>$_GET['term']));  
        }
        else
        {
            array_push($result, array("id"=>"New member", "label"=>"New member", "value"=>"New member","sex"=>"?","prefix"=>"?","name"=>'Name',"lastname"=>"Last name","age"=>"?","birth_date"=>"dd-mm-yyyy")); 
        }
        
        
    }
  //} //[1fin]
}

echo json_encode($result);
die;

/*function array_to_json( $array ){

    if( !is_array( $array ) ){
        return false;
    }

    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
    if( $associative ){

        $construct = array();
        foreach( $array as $key => $value ){

            // We first copy each key/value pair into a staging array,
            // formatting each key and value properly as we go.

            // Format the key:
            if( is_numeric($key) ){
                $key = "key_$key";
            }
            $key = "\"".addslashes($key)."\"";

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "\"".addslashes($value)."\"";
            }

            // Add to staging array:
            $construct[] = "$key: $value";
        }

        // Then we collapse the staging array into the JSON form:
        $result = "{ " . implode( ", ", $construct ) . " }";

    } else { // If the array is a vector (not associative):

        $construct = array();
        foreach( $array as $value ){

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "'".addslashes($value)."'";
            }

            // Add to staging array:
            $construct[] = $value;
        }

        // Then we collapse the staging array into the JSON form:
        $result = "[ " . implode( ", ", $construct ) . " ]";
    }

    return $result;
}*/

/*$result = array();

foreach ($items as $key=>$value) {
	//if (strpos(strtolower($key), $q) !== false) {
		array_push($result, array("id"=>$key, "value" => $items[$key]['name'].' '.$items[$key]['lastname'], "pt_dat"=>$value));
		
	//}
	//echo  $value." ".$key." ".strip_tags($key);
	if (count($result) > 11) //limit search with in n = 11 + 1
		break;
}*/

//echo ARR_PRINT($result);
 //echo array_to_json($records);
 echo trim(array_to_json($result));


?>