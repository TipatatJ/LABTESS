<?PHP
	error_reporting(E_ALL & ~(E_WARNING|E_NOTICE));
	
	ob_start(); // ensures anything dumped out will be caught

    $dataFilter = $_POST;
    $com_pos = $dataFilter['com_pos'];
	
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

    $q = strtolower($_GET["term"]);

    /*$dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = 'hfbn';
    $dbName = 'mediyaclinic';*/
    $tblName = 'tbl_product';
    $record_id = 'pd_id';
    $product_name = 'pd_name';

    $filter = "extra_data <> 'notify_SET' AND extra_data <> 'notify_ACC' AND in_use = 1 AND (mix_name LIKE '%$q%' OR in_group LIKE '%$q%') AND cat_id = 32";
	
	/*include_once '../CloudConsultant/database.php';
    include_once '../CloudConsultant/common.php';
    include_once '../CloudConsultant/common2.php';*/

    include_once '../library/config.php';
    include_once '../library/database.php';
    include_once '../library/common.php';
    include_once '../library/common2.php';

	
	if(SEARCH_OLD_PLAT_DB == 'true'){
	  $searchOldPlatform = true;
	}
	else
	{
	  $searchOldPlatform = false;
	}

    $sql = "SELECT $record_id,pd_name,en_name, cat_id, CONCAT($product_name, ' (', en_name,')') 'mix_name', bplus_id, purchasing_price, dose_option, extra_data, in_use, in_group FROM $tblName HAVING $filter ORDER BY $product_name";

    //echo $sql; 

    dbQuery('SET NAMES utf8;');
    $results = dbQuery($sql);

    //echo var_dump($results);

    $arrRtn = array();

    while ($records[] = dbFetchAssoc($results)) { 
          extract($records[count($records)-1]);
          //echo var_dump($records[count($records)-1]).'<hr>';
          if(isJSON($dose_option)){
              $dose_option = 'WZT_DEFAULT_DOSE';
          }
          
          //if(!isJSON($dose_option)){
              array_push($arrRtn, array("id"=>$record_id, "label"=>$mix_name, "value"=>$mix_name,"product_name"=>$pd_name,"ch_name"=>$en_name,"mix_name"=>$mix_name,"bplus_id"=>$bplus_id,"cost"=>$purchasing_price,"dose"=>$dose_option));  
          //}
    }

    echo json_encode($arrRtn , JSON_UNESCAPED_UNICODE );