<?php
@session_start();
require_once '../library/config.php';
require_once '../library/category-functions.php';
require_once '../library/product-functions.php';
require_once '../library/cart-functions.php';
//require_once '../library/wz-product-functions.php';

//echo var_dump($_GET);

//echo var_dump($_POST).'<br>';

modCartPrice($_POST);

function modCartPrice($arrFieldVal)
{
	// make sure the product id exist
	/*if (isset($_GET['p']) && (int)$_GET['p'] > 0) {
		$productId = (int)$_GET['p'];
	} else {
		header('Location: index.php');
	}*/
	
	// does the product exist ?
	/*$sql = "SELECT pd_id, pd_qty
	        FROM tbl_product
			WHERE pd_id = '$productId'";
    
	$result = dbQuery($sql);*/
    
	
	/*if (dbNumRows($result) != 1) {
		// the product doesn't exist
		header('Location: cart.php');
	} else {
		// how many of this product we
		// have in stock
		$row = dbFetchAssoc($result);
		$currentStock = $row['pd_qty'];

		if ($currentStock == 0) {
			// we no longer have this product in stock
			// show the error message
			setError('The product you requested is no longer in stock');
			header('Location: cart.php');
			exit;
		}

	}*/		
	
	// current session id
	$sid = session_id();
	
	// check if the product is already
	// in cart table for this session
	/*$sql = "SELECT pd_id
	        FROM tbl_cart
			WHERE pd_id = '$productId' AND ct_session_id = '$sid'";
	$result = dbQuery($sql);
    
    $product = getProductDetail($productId,'allCat');
    extract($product);*/
    //echo var_dump($arrFieldVal).'<br>';
	
	/*if (dbNumRows($result) == 0) {
		$result = "can't update this query condition";
        return $result;
	} else {*/
		// update product quantity in cart table
        foreach($arrFieldVal as $productId=>$discountPrice){
            //echo 'updateprodId '.$productId.'<br>';
            
            $sql = "UPDATE tbl_cart
                    SET ct_discounted_price = '" .round($discountPrice,2)."'
                    WHERE ct_session_id = '$sid' AND pd_id = $productId";

            //echo '$sql = '.$sql.'<br>';
            $result = dbQuery($sql);	
        }
	//}	
	
	// an extra job for us here is to remove abandoned carts.
	// right now the best option is to call this function here
	//deleteAbandonedCart();
	
	//header('Location: ' . $_SESSION['shop_return_url']);				
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
			WHERE pd_id = $productId AND ct_session_id = '$sid'";
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