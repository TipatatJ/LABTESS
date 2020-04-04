<?php
require_once 'config.php';

/*********************************************************
*                 PRODUCT FUNCTIONS 
**********************************************************/


/*
	Get detail information of a product
*/
function getProductDetail($pdId, $catId, $pdTab = 'tbl_product')
{
	
	$_SESSION['shoppingReturnUrl'] = $_SERVER['REQUEST_URI'];
	
	// get the product information from database
	$sql = "SELECT pd_name, pd_description, order_to_com, pd_price, pd_image, pd_qty, piece_per_pack, cat_id, dose_option, class, subclass, in_group, std_set, extra_data, unit, sale_acc, email_ext_vender, supplier_operator
			FROM $pdTab
			WHERE pd_id = '$pdId'";
    
    //echo '$sql = '.$sql.'<br>';
	
	$result = dbQuery($sql);
	$row    = dbFetchAssoc($result);
	extract($row);
	
	$row['pd_description'] = nl2br($row['pd_description']);
	
	if ($row['pd_image']) {
		$row['pd_image'] = WEB_ROOT . 'images/product/' . $row['pd_image'];
	} else {
		$row['pd_image'] = WEB_ROOT . 'images/no-image-large.png';
	}
	
	$row['cart_url'] = "cart.php?action=add&p=$pdId";
	
    if(!is_null(mainHIS)){
        $row['his_pd_id'] = 'external his product id';
    }
    
	return $row;			
}


//echo 'end product-functions.php<br>';


?>