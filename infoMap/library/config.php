<?php
ini_set('display_errors', 'On');
//ob_start("ob_gzhandler");
error_reporting(E_ALL ^ E_DEPRECATED);

// start the session
//@session_start();

date_default_timezone_set('Asia/Bangkok');

define("LOCAL_PATH_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("HTTP_PATH_ROOT", isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '_UNKNOWN_'));

$hostName = getenv('HTTP_HOST');

define("mainHIS",null);

define("clinicNameTh",'เวนิตา สหคลินิก');
define("clinicNameEn",'Venita Clinic');
define("addressForSuppplierTh",'1/18 ซ.วัชรพล 2/7, ถ.วัชรพล, แขวง ท่าแร้ง, เขต บางเขน, กทม 10220');
define("addressForSuppplierEn",'1/18 Soi Wacharapol 2/7, Wacharapol Rd., Tharang, Bangkhen district, Bankgok, Thailand, 10220.');
define("clinicTell",'088-6540274');

// database connection config
$dbHost = '203.151.4.117';
$dbUser = 'remote';
$dbPass = 'GT@ui22BNw@55ff';
$dbName = 'demosite';

// database connection config REMOTE
/*$dbHost = 'localhost';
$dbUser = 'venitaclinic';
$dbPass = 'GT@ui22BNw@55ff';
$dbName = 'demosite';*/

define("MAILING_HOST","mail.venitaclinic.com");
define("MAILING_PORT",587);
define("MAILING_USER","admin@venitaclinic.com");
define("MAILING_PWD","Vv11223344");
define("MY_HOST_LOGIN","http://$hostName/Qweb/site1_Wiztech/WiztechSolution/login.php");

//echo 'config $dbName = '.$dbName.'<br>';

$tblProd = 'tbl_product';
$tblFinMx = 'tbl_order_item';
$tblMedRec = 'tbl_pt_hx_rec';
$tblCust = 'tbl_cust';
$tblSupp = 'tbl_supplier';
$tblICD10 = 'icd_table';
$tblPatho = 'biopatho';
$tblSymptom = 'biopredict';
$tblGenome = 'biogenome';
$tblDataSet = 'data_seter';

$cmnDD = 'common_drug_dose';

$roleProductAdd = array('owner','manager','admin','super_admin');

$roleNotifyRemed = array('owner','manager','admin','super_admin','cashier');

$roleStockRcv = array('owner','manager','super_admin','stock');

$roleSubmitOrder = array('owner','manager','admin','super_admin','doctor','site_staff','tcmdoc','ttmdoc','therapist','codev');

$roleClrTxBill = array('owner','manager','admin','super_admin');

$roleRevertTxBill = array('owner','manager','admin','super_admin');

$roleEditMedImg = array('manager','admin','super_admin','doctor','therapist');

//$langMoonSeg = array('DhinTTM','AnthroTTM');

// setting up the web root and server root for
// this shopping cart application
$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];

//THIS IS VERY CRITICAL TO PUT ALL htdoc FILES & FOLDER
//UNDER FOLDER siteN_Wiztech to make everything work correctly
$siteStr = explode('_Wiztech',$thisFile);

//$webRoot  = str_replace(array($docRoot, 'library/config.php'), '', $siteStr[1]);
//$srvRoot  = $siteStr[0].'_Wiztech';//str_replace('library/config.php', '', $siteStr[1]);





if(!function_exists("getWebRoot")){
function getWebRoot(&$siteStr, &$docRoot, &$webRoot, &$srvRoot){
    $thisFile = str_replace('\\', '/', __FILE__);
    $siteStr = explode('_Wiztech',$thisFile);
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    $webRoot  = '..'.str_replace(array($docRoot, 'library/config.php'), '', $siteStr[1]);
    $srvRoot  = $siteStr[0].'_Wiztech';
}
    //echo 'declare function getWebRoot';
}
else
{
    echo 'redeclare function getWebRoot';
}

function sessionX($rtnLocation, $reset){ 
    $siteStr = ''; $docRoot = ''; $webRoot = ''; $srvRoot = '';
    getWebRoot($siteStr, $docRoot, $webRoot, $srvRoot);
    $webRoot = str_replace('..','',$webRoot);
    //echo '>>'.$webRoot.'<<<hr>';
    
    $logLength = 5600; # time in seconds :: 1800 = 30 minutes 
    $ctime = strtotime("now"); # Create a time from a string 
    # If no session time is created, create one 
    if(!isset($_SESSION['sessionX'])){  
        # create session time 
        $_SESSION['sessionX'] = $ctime; 
    }else{ 
        # Check if they have exceded the time limit of inactivity 
        if(((strtotime("now") - $_SESSION['sessionX']) > $logLength) && isLogged){ 
            # If exceded the time, log the user out 
            //logOut(); 
            
            # Redirect to login page to log back in 
            //header("Location: ".$webRoot."login.php");
            session_destroy();
            if($rtnLocation){
                //echo json_encode(array('redirect',$webRoot));
                echo 'not properly login';
            }
            else
            {
                header("Location: ..$webRoot"."login.php");
            }
            //echo "<script>window.location='".$webRoot."login.php';</script>";
            exit; 
        }else{ 
            # If they have not exceded the time limit of inactivity, keep them logged in 
            if($reset){ $_SESSION['sessionX'] = $ctime; }
        } 
    } 
} 



//$webRoot  = str_replace(array($docRoot, 'login.php'), '', $thisFile);
//'http://'.$_SERVER['HTTP_HOST'].'/WiztechSolution/';
//$srvRoot = '';

//echo 'docRoot = '.$docRoot.'<br>';
//echo 'webRoot = '.$webRoot.'<br>';

//define('WEB_ROOT', $webRoot);
//define('SRV_ROOT', $srvRoot);

define('SEARCH_OLD_PLAT_DB', true);
define('CUSTOMER_TB', 'tbl_cust');

define('HTML5_OBJ_TB','tbl_html5_obj_tb');

//define('SECRET_KEY', 'itbookonline');
define('SECRET_KEY', 'thai_epigenomic');

// these are the directories where we will store all
// category and product images
define('CATEGORY_IMAGE_DIR', 'images/category/');
define('PRODUCT_IMAGE_DIR',  'images/product/');
define('POWER_BY','<p>Power by PainCart and edit Thai version by <a href="http://www.swift-tutor.com">swift-tutor.com</a></p>');
// some size limitation for the category
// and product images

// all category image width must not 
// exceed 75 pixels
define('MAX_CATEGORY_IMAGE_WIDTH', 125);

// do we need to limit the product image width?
// setting this value to 'true' is recommended
define('LIMIT_PRODUCT_WIDTH',     true);

// maximum width for all product image
define('MAX_PRODUCT_IMAGE_WIDTH', 300);

// the width for product thumbnail
define('THUMBNAIL_WIDTH',         125);

define('PRIMARY_CASHIER','OHCCashier');
define('SECONDARY_CASHIER','CafeCashier');

define('PRICE_GROUP1','เวนิตา คลินิกเวชกรรม');
define('PRICE_GROUP2','บจ. เมดิญา เอเซีย');

define ("CAT_PRICE_GROUP", serialize (array ("11"=>PRICE_GROUP1,"14"=>PRICE_GROUP1,"15"=>PRICE_GROUP1,"16"=>PRICE_GROUP1,"18"=>PRICE_GROUP1,"19"=>PRICE_GROUP1,"26"=>PRICE_GROUP1,"28"=>PRICE_GROUP1,"29"=>PRICE_GROUP1,"42"=>PRICE_GROUP2)));

//echo CAT_PRICE_GROUP;

define('TX_TABLE','tbl_cash_tx');

define('PHYSICAL_STOCK', json_encode(array('stock ใหญ่หลังร้าน'=>0,'stock หน้า Clinic'=>0, 'stock ห้องผสมน้ำเกลือ'=>0, 'stock master'=>0), JSON_UNESCAPED_UNICODE ));

/*   เอาออก ไม่เช่นนั้นอัพเดทตะกร้าไม่ได้
if (!get_magic_quotes_gpc()) {
	if (isset($_POST)) {
		foreach ($_POST as $key => $value) {
			$_POST[$key] =  trim(addslashes($value));
		}
	}
	
	if (isset($_GET)) {
		foreach ($_GET as $key => $value) {
			$_GET[$key] = trim(addslashes($value));
		}
	}	
} เอาออก
*/
// since all page will require a database access
// and the common library is also used by all
// it's logical to load these library here
//echo 'gonna require db';

//$script_name = str_replace('\\', '/', dirname(__FILE__));

//$script_name = str_replace('\\', '/', dirname(__FILE__));
//$upDir = str_replace('include', '', $script_name);
require_once 'database.php';
//echo 'gonna require common';
/* if(isset($GLOBALS['doNotRequireCommon'])){
    if(!$doNotRequireCommon){
        //echo 'require common<br>';
        include_once 'common.php';
        $shopConfig = getShopConfig();
    }
    else
    {
        //echo 'not require common<br>';
    }
}
else
{
    //echo 'require common<br>';
    include_once 'common.php';
    $shopConfig = getShopConfig();
} */

function formatHN($HN){
    return str_pad($HN,6,'0',STR_PAD_LEFT);
}




    
function arr_print2($Input, $Level = 0, $StrParent = "", $Lead = "&nbsp;&nbsp;",$Result = ""){
		for($i = 0; $i < $Level; $i++)
		{
			$Lead = $Lead."&nbsp;&nbsp;&nbsp;";
		}
		
		foreach($Input as $k=>$Value)
		{
			If(is_array($Value)){
				if($StrParent == ""){
					$Result = $Result.$Lead."index [".$k."]<br>";
				}
				else
				{
					$Result = $Result.$Lead."index ".$StrParent."[".$k."]<br>";
				}
				$Result = $Result.ARR_PRINT($Value, $Level + 1, $StrParent."[".$k."]")."<br>";
			}
			else
			{
				$Result = $Result.substr($Lead,0,-18).'['.$k.'] = '.$Value."<br>";
			} // ******* end of  If(is_array($Value)
		} // ********************* end of  foreach($array as $k=>$each)
		
		return $Result;
	}



//echo 'gonna load shop config';
// get the shop configuration ( name, addres, etc ), all page need it
//echo 'gonna get shop config';
//$shopConfig = getShopConfig();
//echo 'end of config.php<br>';
?>