<?php
require_once '../library/config.php';
require_once '../library/common.php';
require_once '../library/category-functions.php';
require_once '../library/product-functions.php';
require_once '../library/cart-functions.php';
//require_once '../library/wz-product-functions.php';

echo var_dump($_POST);


if(isset($_POST['prodID'])){	//ตรวจสอบรหัสสินค้าที่ส่งเข้ามา
	$productId = $_POST['prodID'];
    $sid = $_POST['sessID'];
    $newAmount = (float)$_POST['newAmount'];
   
    
    dbQuery('SET NAMES utf8;');
    $sql = "UPDATE tbl_cart 
		        SET ct_qty = '".$newAmount."'
				WHERE ct_session_id = '$sid' AND pd_id = $productId";
    
    echo 'newAmount->'.$newAmount.'<br>';
				
    $result = dbQuery($sql);
    
    
    //echo $sid.'->'.$newDose;
}
die;
    
/*//ตรวจดูว่ามีสินค้านี้อยู่ในร้านค้าหรือไม่ 
	$sql = "SELECT pd_id, pd_qty
	        FROM tbl_product
			WHERE pd_id = '$productId'";
    
	$result = dbQuery($sql);
    
    //echo 'add to mini cart '.$result.'<br>';
    
	$numRow = dbNumRows($result);

	if (dbNumRows($result) != 1) {
		//ถ้าไม่มีสินค้ารายการนี้อยู่ให้ออกไปเลย
		exit();
	} else {
		//ตรวจดูว่ามีสินค้าชิ้นนี้อยู่กี่ชิ้นในสต็อกสินค้า
		$row = dbFetchAssoc($result);
		$currentStock = $row['pd_qty'];

		if ($currentStock == 0) {
			//ถ้าไม่มีสินค้าในสต็อก ให้กำหนดค่า error
			setError('ขออภัย ไม่มีสินค้านี้อยู่ในสต็อกแล้ว');
			//ออกไปเลย
			exit;
		}

	}		
	
	//เก็บค่า session id 
	$sid = session_id();
	
	//ตรวจดูว่าสินค้ารายการนี้ อยู่ในตะกร้าสินค้าอยู่ก่อนแล้วหรือไม่
	//โดยเข้าไปดูในตาราง tbl_cart โดยตรวจสอบว่า  session เดียวกันนี้มีรายการสินค้านี้แล้วหรือยัง
	$sql = "SELECT pd_id
	        FROM tbl_cart
			WHERE pd_id = '$productId' AND ct_session_id = '$sid'";
	$result = dbQuery($sql);
	
	//ถ้าไม่เคยมีสินค้ารายการนี้มาก่อนในตะกร้า
	if (dbNumRows($result) == 0) {
        setError('can\'t update drug dose in database');
        exit;
    }
    else
    {
        //แต่ถ้ามีสินค้านี้ในตะกร้าแล้ว เพิ่มจำนวนสินค้าในตะกร้า
		$sql = "UPDATE tbl_cart 
		        SET ct_dose_option = '$newDose'
				WHERE ct_session_id = '$sid' AND pd_id = $productId";		
				
		$result = dbQuery($sql);
    }
    
    echo $sid.'->'.$newDose;
}*/
            
?>