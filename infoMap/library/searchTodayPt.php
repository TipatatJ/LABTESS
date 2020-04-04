<?php
ob_start(); // ensures anything dumped out will be caught  
//@session_start();
ini_set('display_errors', 1);

require_once '../library/config.php';
//echo WEB_ROOT.'library/config.php';

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
$sql = "SELECT user_name, user_role, com_pos
        FROM tbl_user 
        WHERE user_id = $userId";
$result = dbQuery($sql);

if(dbNumRows($result) == 1) {
        $row = dbFetchAssoc($result);
        $userName = $row['user_name']; 
        $userRole = $row['user_role'];
        $com_pos = $row['com_pos'];
    //echo 'com_pos = '.$com_pos.'<br>';
    //echo 'welcome '.$userName.' '.$userRole.'<br>';
} 
else
{
    //echo $useID.' not match for any users<br>';
    die;
}





set_error_handler("myErrorHandler");
  

$q = strtolower($_GET["term"]);

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
  $today = date('Y-m-d');

  $Q1 = "SELECT DISTINCT Name, Surname, siteID, Age FROM $tblFinMx LEFT JOIN $tblCust ON $tblFinMx.hospital_number = $tblCust.siteID WHERE order_date_time LIKE '$today%' ORDER BY order_date_time;";

  //$Q1 = "SELECT * FROM $tblCust WHERE (concat(Name,' ',Surname) LIKE '%".$q."%') OR (siteID LIKE '%".$q."');";
  //echo 'Q1 = '.$Q1.'<br>';
  $rtn = dbQuery($Q1);
  

  $result = array();
    
  /*array_push($result, array("id"=>"New member", "label"=>"$Q1", "value"=>"New member","sex"=>"?","prefix"=>"?","name"=>'Name',"lastname"=>"Last name","age"=>"?","birth_date"=>"dd-mm-yyyy"));  
  echo json_encode($result);
  die;*/
  //echo '<hr>there are '.dbNumRows($rtn).'<hr>';
    
  if(dbNumRows($rtn) == 0) {

		 array_push($result, array("id"=>"New member", "label"=>"New member", "value"=>"New member","sex"=>"?","prefix"=>"?","name"=>'Name',"lastname"=>"Last name","age"=>"?","birth_date"=>"dd-mm-yyyy"));  

  }
  else
  {
  	while($row = dbFetchAssoc($rtn)){
       extract($row);
       
       
        
	   array_push($result, array("id"=>$siteID, "label"=>$Name.' '.$Surname, "value"=>$Name.' '.$Surname,"sex"=>$Sex,"prefix"=>$Prefix,"name"=>$Name,"lastname"=>$Surname,"age"=>$Age,"birth_date"=>$birth_date,"body_weight"=>$body_weight,"body_height"=>$body_height,"birth_time"=>$birth_time));
	}
  } //[1fin]
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