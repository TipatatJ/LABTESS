<?php
    
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

    require_once '../library/config.php';


    
    $fHN = $_POST;

    if($fHN == null){
        $_SESSION['fHN'] = null;
        unset($_SESSION['fHN']);
        echo 'unset target Pt';
        die;
    }

    $tHN = '';
    foreach($fHN as $key=>$val){
        $tHN = $key;
    }
    
    $_SESSION['fHN'] = formatHN($tHN);

    echo json_encode(formatHN($fHN));

?>