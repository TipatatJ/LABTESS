<?PHP
	error_reporting(E_ALL & ~(E_WARNING|E_NOTICE));
	
	ob_start(); // ensures anything dumped out will be caught

    $dataFilter = $_POST;
    $com_pos = $dataFilter['com_pos'];
	
	if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

	if(!isset($_SESSION['uName']) || !isset($_SESSION['uPs'])){ 
		die; 
	};

    $q = strtolower($_GET["term"]);

    $dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = 'hfbn';
    $dbName = 'mediyaclinic';
    $tblName = 'hf_cl_product';

    $filter = "extra_data <> 'notify_SET' AND extra_data <> 'notify_ACC' AND in_use = 1 AND (product_name LIKE '%$q%' OR in_group LIKE '%$q%')";
	
	include_once '../CloudConsultant/database.php';
    include_once '../CloudConsultant/common.php';
    include_once '../CloudConsultant/common2.php';
	
	
	if(SEARCH_OLD_PLAT_DB == 'true'){
	  $searchOldPlatform = true;
	}
	else
	{
	  $searchOldPlatform = false;
	}

    $sql = "SELECT record_id, product_name, en_name, bplus_id, purchasing_price, dose_option FROM $tblName WHERE $filter ORDER BY product_name";

    dbQuery('SET NAMES utf8;');
    $results = dbQuery($sql);

    $arrRtn = array();

    while ($records[] = dbFetchAssoc($results)) { 
          extract($records[count($records)-1]);
          //echo var_dump($records[count($records)-1]).'<hr>';
          
          if(isJSON($dose_option)){
              $dose_option = 'WZT_DEFAULT_DOSE';
          }
        
          array_push($arrRtn, array("id"=>$record_id, "label"=>$product_name, "value"=>$product_name,"enname"=>$en_name,"bplus_id"=>$bplus_id,"cost"=>$purchasing_price,"dose"=>$dose_option));  

    }

    echo json_encode($arrRtn , JSON_UNESCAPED_UNICODE );