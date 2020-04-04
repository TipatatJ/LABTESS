<?php
@session_start;
require_once 'config.php';
require_once 'product-functions.php';



/*********************************************************
*                 CHECKOUT FUNCTIONS 
*********************************************************/
function saveOrder()
{
    //echo var_dump($GLOBALS['roleSubmitOrder']).'<hr>';
    $roleSubmitOrder = $GLOBALS['roleSubmitOrder'];
    
    
    if(!isset($_SESSION['fHN'])){
        setError('No Patient Selected.');
        return false;
    }
    if($_SESSION['fHN'] == '' || $_SESSION['fHN'] == 'need_completion'){
        setError('No HN of Patient found. Contact Reception');
        return false;
    }
    else
    {
        setSuccess('ORDER FOR HN'.$_SESSION['fHN'].' POSTED!<br>NURSE TEAM & CASHIER CAN NOW SEE YOUR ORDER.***');
        $_SESSION['plaincart_error'] = array();
    }
    
	$orderId       = 0;		//ในตอนแรกรหัสใบสั่งสินค้าจะเป็น 0 
	$shopConfig = getShopConfig();
	$shippingCost  = $shopConfig['shippingCost'];

	//กำหนด array() ขึ้นมา เพื่อเก็บข้อมูลของการผู้ชื้อและผู้รับสินค้า
	$requiredField = array('hidShippingFirstName', 'hidShippingLastName', 'hidShippingEmail', 'hidShippingAddress1', 'hidShippingCity', 'hidShippingPostalCode',
						   'hidPaymentFirstName', 'hidPaymentLastName', 'hidPaymentEmail', 'hidPaymentAddress1', 'hidPaymentCity', 'hidPaymentPostalCode');
	//ตรวจสอบข้อมูลของผู้ซื้อ และผู้รับสินค้า			
    
    $userName='';
    $userId = $_SESSION['plaincart_customer_id'];
    $sql = "SELECT user_name, user_role
            FROM tbl_user 
            WHERE user_id = $userId";
    //echo '$userId = '.$userId.'<br>';
    $result = dbQuery($sql);
    
    //echo $sql.'<br>';
    //die;

    if (dbNumRows($result) == 1) {
        $row = dbFetchAssoc($result);
        //echo var_dump($row).'<br>';

        $userName = $row['user_name']; 
        $userRole = $row['user_role'];
    }   
    
	if (checkRequiredPost($requiredField) ||
            in_array($userRole,$roleSubmitOrder)
        ) {
	    extract($_POST);
		
		// กำหนดให้อักษรตัวแรกเป็นตัวใหญ่ 
		$hidShippingFirstName = ucwords($hidShippingFirstName);
		$hidShippingLastName  = ucwords($hidShippingLastName);
		$hidPaymentFirstName  = ucwords($hidPaymentFirstName);
		$hidPaymentLastName   = ucwords($hidPaymentLastName);
		$hidShippingCity      = ucwords($hidShippingCity);
		$hidPaymentCity       = ucwords($hidPaymentCity);
				
		//ดึงข้อมูลจากตะกร้าสินค้ามาเก็บที่ตัวแปร $cartContent ซึ่งผลลัพธ์จะเป็น array()	
		$cartContent = getCartContent();
		$numItem     = count($cartContent);
        
        if($userRole == 'manager' ||
              in_array($userRole,$roleSubmitOrder)){
            
            $staffName = $userName;
            $shippingCost = 0;
            
            
        }
        else if($hidShippingFirstName != 'Site_staff' &&
           $hidShippingFirstName != 'doctor' &&
           $hidShippingFirstName != 'manager' 
          ){
            $staffName = $hidShippingFirstName.' '.$hidShippingLastName; 
        }
        else
        {
            $staffName = 'Site_staff';
            $shippingCost = 0;
        }
        
        
		
		//เซฟรายละเอียดการสั่งซื้อ 
		$sql = "INSERT INTO tbl_order(od_date, od_last_update, od_shipping_first_name, od_shipping_last_name, od_shipping_email, od_shipping_address1, 
		                              od_shipping_address2, od_shipping_phone, od_shipping_state, od_shipping_city, od_shipping_postal_code, od_shipping_cost,
                                      od_payment_first_name, od_payment_last_name, od_payment_email, od_payment_address1, od_payment_address2, 
									  od_payment_phone, od_payment_state, od_payment_city, od_payment_postal_code)
                VALUES (NOW(), NOW(), '$staffName', '$staffName', '$hidShippingEmail', '$hidShippingAddress1', 
				        '$hidShippingAddress2', '$hidShippingPhone', '$hidShippingState', '$hidShippingCity', '$hidShippingPostalCode', '$shippingCost',
						'$hidPaymentFirstName', '$hidPaymentLastName', '$hidPaymentEmail', '$hidPaymentAddress1', 
						'$hidPaymentAddress2', '$hidPaymentPhone', '$hidPaymentState', '$hidPaymentCity', '$hidPaymentPostalCode')";
		$result = dbQuery($sql);
		
		//ดึงรหัสใบสั่งซื้อออกมา (ในที่นี้คือ ฟิลด์ od_id)
		$orderId = dbInsertId();
        
        
        
		if ($orderId) {
            $extVenderEmail = array();
			//จัดเก็บรายละเอียดการซื้อว่ามีสินค้าชิ้นใดบ้าง และจำนวนกี่ชิ้นลงในตาราง tbl_order_item
			for ($i = 0; $i < $numItem; $i++) {
                $product = getProductDetail($cartContent[$i]['pd_id'],'allCat');
                extract($product);
                
                if(!is_null($email_ext_vender)){
                    $extVenderEmail[$cartContent[$i]['pd_id']] = array('email'=>$email_ext_vender, 'supplier_id'=>$supplier_operator, 'product_name'=>$pd_name, 'dosage'=>$cartContent[$i]['ct_dose_option'], 'od_qty'=>$cartContent[$i]['ct_qty'], 'unit'=>$unit, 'ppp'=>$piece_per_pack, 'od_id'=>$orderId);
                }
                //echo var_dump($product).'<br>';
                //echo 'fHN = '.$_SESSION['fHN'].'<br>';
                
				$sql = "INSERT INTO tbl_order_item(od_id, hospital_number,    order_date_time,
                        order_by, order_to_com, product_name, unit, product_cat, pd_id, od_qty, full_price, discounted_price, dosage, pay_method, class, subclass, in_group, set_name, oldplat_id, extra_data, sale_acc, email_ext_vender)
						VALUES ($orderId, '{$_SESSION['fHN']}' , NOW(), '$staffName', '$order_to_com', '$pd_name','$unit','$cat_id', {$cartContent[$i]['pd_id']}, {$cartContent[$i]['ct_qty']},$pd_price,{$cartContent[$i]['ct_discounted_price']},'{$cartContent[$i]['ct_dose_option']}','1','$class','$subclass','$in_group','$std_set',0,'$extra_data','$sale_acc','$email_ext_vender')";
                
                //echo '$sql = '.$sql.'<br>';
                //die;
                
				$result = dbQuery($sql);	
                
                //echo 'INSERT INTO tbl_order_item<br>';
                //echo var_dump().'<br>';
			}
		
			
			//อัพเดทสต๊อคสินค้าในตาราง tbl_product
			for ($i = 0; $i < $numItem; $i++) {
                if(!isset($extVenderEmail[$cartContent[$i]['pd_id']])){
                    $sql = "UPDATE tbl_product 
                            SET pd_qty = pd_qty - {$cartContent[$i]['ct_qty']}, pd_last_update = NOW()
                            WHERE pd_id = {$cartContent[$i]['pd_id']}";
                    $result = dbQuery($sql);				
                }
                else{
                    
                }
			}
            
            $sql2 = "SELECT Name, Surname
                    FROM tbl_cust 
                    WHERE siteID = {$_SESSION['fHN']}";

            $result2 = dbQuery($sql2);

            if (dbNumRows($result2) == 1) {
                $row2 = dbFetchAssoc($result2);
                
                $custName = $row2['Name'].' '.$row['Surname'];
            }
			
			
			// ลบรายการสินค้าออกจากตะกร้าสินค้า ซึ่งก็คือลบข้อมูลออกจากตาราง tbl_cart
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "DELETE FROM tbl_cart
				        WHERE ct_id = {$cartContent[$i]['ct_id']}";
				$result = dbQuery($sql);					
			}	
            
            if(count($extVenderEmail) > 0){
                require_once 'PHPMailer/class.phpmailer.php';
                require_once 'PHPMailer/class.smtp.php';
                require_once 'PHPMailer/class.pop3.php';
                
                $venders = array();
                    
                foreach($extVenderEmail as $key=>$prod)    {
                    
                    $supplier = $prod['email'];
                    
                    
                    
                    if(!isset($venders[$supplier])){
                        //$venders[$supplier]['header'] = 'vender email = '.$prod['email'].'<br>';
                        $venders[$supplier]['header'] = 'Order from <b>'.clinicNameEn.'</b><br>';
                        $venders[$supplier]['header'] .= 'คำขอสั่งซื้อจาก <b>'.clinicNameTh.'</b><br>';
                        $venders[$supplier]['header'] .= 'for <br>';
                        $venders[$supplier]['header'] .= "$custName<br>HN{$_SESSION['fHN']} <br>";
                        $venders[$supplier]['header'] .= "ORDER ID $orderId <hr>";
                        
                        $venders[$supplier]['header'] .= '<head>
                        <style>
                        table {
                          border-collapse: collapse;
                        }

                        table.border, td.border, th.border {
                          border: 1px solid black;
                        }
                        
                        th {
                          background-color: #4CAF50;
                          color: white;
                        }
                        </style>
                        </head>';
                        
                        $venders[$supplier]['footer'] .= 'order by <b>'.$staffName.'</b><br>';
                        $venders[$supplier]['footer'] .= 'Please send all purchased item to '.addressForSuppplierEn.'<br>';
                        $venders[$supplier]['footer'] .= 'เมื่อได้รับคำสั่งซื้อแล้ว กรุณาส่งสินค้ามาที่ '.addressForSuppplierTh.'<hr>';
                        $venders[$supplier]['footer'] .= 'Telephone '.clinicTell.'<hr>';
                        
                        $venders[$supplier]['email'] .= $prod['email'];
                        $venders[$supplier]['supplier_id'] .= $prod['supplier_id'];
                        
                        
                    }
                    
                    modDosage($prod['dosage']);
                    
                    $venders[$supplier]['order item'] .= '<tr class="border">';
                    $venders[$supplier]['order item'] .= '<td class="border">'.$prod['product_name'].'</td>';
                    $venders[$supplier]['order item'] .= '<td class="border">'.$prod['dosage'].'</td>';
                    $venders[$supplier]['order item'] .= '<td class="border">'.$prod['od_qty'].'</td>';
                    $venders[$supplier]['order item'] .= '<td class="border">'.$prod['unit'].'</td>';
                    $venders[$supplier]['order item'] .= '<td class="border">'.$prod['ppp'].'/'.$prod['unit'].'</td>';
                    $venders[$supplier]['order item'] .= '</tr>';
                    
                    $venders[$supplier]['od_id'] = $prod['od_id'];
                    
                    
                }
                
                foreach($venders as $key=>$vender){
                    $txtEmail = $vender['email'];
                    
                    
                    
                    $fm = "admin@venitaclinic.com"; // *** ต้องใช้อีเมล์ @yourdomain.com เท่านั้น  ***
                    $owner = "dr.tipatat@gmail.com"; // อีเมล์ที่ใช้รับข้อมูลจากแบบฟอร์ม
                    $to = $txtEmail; // อีเมล์ที่ใช้รับข้อมูลจากแบบฟอร์ม
                    $custemail = $txtEmail; //$_POST['email']; // อีเมล์ของผู้ติดต่อที่กรอกผ่านแบบฟอร์ม

                    $message = $vender['header'];
                    $message .= '<br><br><table class="border"><tr class="border"><th class="border">Product Name</th><th class="border">Prescibed dose</th><th class="border">Amount</th><th class="border">Unit</th><th class="border">บรรจุ/หน่วย</th></tr>';
                    $message .= $vender['order item'];
                    $message .= '</table><br><br>';
                    $message .= $vender['footer'];
                    //$message .= '<br> $venders['.$key.']';
                    
                        
                    $mesg = $message;

                    $mail = new PHPMailer();
                    $mail->CharSet = "utf-8"; 

                    /* ------------------------------------------------------------------------------------------------------------- */
                    /* ตั้งค่าการส่งอีเมล์ โดยใช้ SMTP ของ โฮสต์ */
                    $mail->IsSMTP();
                    $mail->Mailer = "smtp";
                    $mail->SMTPAuth = true;
                    $mail->IsHTML(true);
                    //$mail->SMTPSecure = 'ssl'; // บรรทัดนี้ ให้ Uncomment ไว้ เพราะ Mail Server ของโฮสต์ ไม่รองรับ SSL.
                    $mail->Host = MAILING_HOST; //ใส่ SMTP Mail Server ของท่าน
                    $mail->Port = MAILING_PORT; // หมายเลข Port สำหรับส่งอีเมล์
                    $mail->Username = MAILING_USER; //ใส่ Email Username ของท่าน (ที่ Add ไว้แล้วใน Plesk Control Panel)
                    $mail->Password = MAILING_PWD; //ใส่ Password ของอีเมล์ (รหัสผ่านของอีเมล์ที่ท่านตั้งไว้) 
                    /* ------------------------------------------------------------------------------------------------------------- */

                    $mail->From = $fm;
                    $mail->AddAddress($custemail);
                    $mail->AddBCC($owner);
                    $mail->Subject = 'ORDER OF WEEK '.date("W").'/'.date("Y").' FROM '.clinicNameEn.' [Order ID '.$vender['od_id'].']';
                    $mail->Body     = $mesg;
                    $mail->WordWrap = 50;  

                    //echo $mesg.'<br>';
                    //
                    if($txtEmail != 'none'){
                        if(!$mail->Send()) {
                            //return 'Message was not sent<br>ยังไม่สามารถส่งเมลล์ได้ในขณะนี้ '. $mail->ErrorInfo;
                            //return array('status'=>false,'desc'=>'Mail server error. Please try again');
                            
                            setError('Email to supplier Id '.$vender['supplier_id'].' for '.$prod['product_name']. ' fail.');
                        } else {

                            setSuccess('Email to supplier Id '.$vender['supplier_id'].' for '.$prod['product_name']. ' success.');
                            //return array('status'=>true,'desc'=>"You can login at <a href=\"".MY_HOST_LOGIN."\">Wiztech</a> with user login and password which was sent to your Email ".$txtEmail."<hr>คุณสามารถล็อคอินได้ที่ <a href=\"".MY_HOST_LOGIN."\">Wiztech</a> ด้วยชื่อผู้ใช้และรหัสที่ส่งไปให้ที่ Email $txtEmail ของคุณ");
                        }
                    }
                }
            }
		}					
	}
    else
    {
        //unable to save due to unmatch condition
        //echo '$requiredField ==v';
        //echo checkRequiredPost($requiredField);
        //echo '@@@';
        
    }
	
	//รีเทิร์นค่ารหัสใบสั่งซื้อกลับไปกับฟังก์ชัน
	return $orderId;
}

function modDosage(&$dosage){

    if(substr($dosage,0,13) == '&lt;table&gt;' || substr($dosage,0,15) == '&lt;keeptag&gt;'){
        $dosage = str_replace('&lt;img class=&quot;btn-default&quot; style=&quot;position:relative; left:0px;&quot; src=&quot;../ICONS/delete.png&quot;&gt;','',$dosage);
        $dosage = html_entity_decode($dosage, ENT_QUOTES);
    }
    else{
        //$dosage = '??'.substr($dosage,0,13).'??';
    }
}


/*
	Get order total amount ( total purchase + shipping cost )
*/
function getOrderAmount($orderId)
{
	$orderAmount = 0;
	
	$sql = "SELECT SUM(pd_price * od_qty)
	        FROM tbl_order_item oi, tbl_product p 
		    WHERE oi.pd_id = p.pd_id and oi.od_id = $orderId
			
			UNION
			
			SELECT od_shipping_cost 
			FROM tbl_order
			WHERE od_id = $orderId";
	$result = dbQuery($sql);

	if (dbNumRows($result) == 2) {
		$row = dbFetchRow($result);
		$totalPurchase = $row[0];
		
		$row = dbFetchRow($result);
		$shippingCost = $row[0];
		
		$orderAmount = $totalPurchase + $shippingCost;
	}	
	
	return $orderAmount;	
}

function getCustomerEmail($orderId)
{
	$customerEmail = '';
	$sql = "SELECT od_shipping_email
	        FROM tbl_order
		    WHERE od_id = $orderId";
	$result = dbQuery($sql);
	
	if (dbNumRows($result) == 1) {
		while($row = dbFetchAssoc($result)){
			extract($row);
			$customerEmail = $od_shipping_email;
		}
	}	
	
	return $customerEmail;	
}

function getOrderTableForMail($orderId)
{

	$emailMessage = '';
	// get ordered items
	$sql = "SELECT pd_name, pd_price, od_qty
	    FROM tbl_order_item oi, tbl_product p 
		WHERE oi.pd_id = p.pd_id and oi.od_id = $orderId
		ORDER BY od_id ASC";

	$result = dbQuery($sql);
	$orderedItem = array();
	while ($row = dbFetchAssoc($result)) {
		$orderedItem[] = $row;
	}


	// get order information
	$sql = "SELECT od_date, od_last_update, od_status, od_shipping_first_name, od_shipping_last_name, od_shipping_address1, 
               od_shipping_address2, od_shipping_phone, od_shipping_state, od_shipping_city, od_shipping_postal_code, od_shipping_cost, 
			   od_payment_first_name, od_payment_last_name, od_payment_address1, od_payment_address2, od_payment_phone,
			   od_payment_state, od_payment_city , od_payment_postal_code,
			   od_memo
	    FROM tbl_order 
		WHERE od_id = $orderId";

	$result = dbQuery($sql);
	extract(dbFetchAssoc($result));
	
	$emailMessage .= '<p>&nbsp;</p>
    <table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
        <tr> 
            <td colspan="2" align="center" id="infoTableHeader">รายละเอียดของการสั่งสินค้า</td>
        </tr>
        <tr> 
            <td width="150" class="label">หมายเลขใบสั่งสินค้า</td>
            <td class="content">'.$orderId.'</td>
        </tr>
        <tr> 
            <td width="150" class="label">วันสั่งสินค้า</td>
            <td class="content">'.$od_date.'</td>
        </tr>
    </table>
</form>';


	$emailMessage .= '
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr align="center" class="label"> 
        <td>รายการ</td>
        <td>ราคาต่อหน่วย</td>
        <td>รวมย่อย</td>
    </tr>';

$numItem  = count($orderedItem);
$subTotal = 0;
for ($i = 0; $i < $numItem; $i++) {
	extract($orderedItem[$i]);
	$subTotal += $pd_price * $od_qty;
	$emailMessage .=
    '<tr class="content"> 
        <td>'.$od_qty.' X '.$pd_name.'</td>
        <td align="right">'.displayAmount($pd_price).'</td>
        <td align="right">'.displayAmount($od_qty * $pd_price).'</td>
    </tr>';
}
	$emailMessage .=
    '<tr class="content"> 
        <td colspan="2" align="right">รวม</td>
        <td align="right">'.displayAmount($subTotal).'</td>
    </tr>
    <tr class="content"> 
        <td colspan="2" align="right">ค่าจัดส่ง</td>
        <td align="right">'.displayAmount($od_shipping_cost).'</td>
    </tr>
    <tr class="content"> 
        <td colspan="2" align="right">รวมสุทธิ</td>
        <td align="right">'.displayAmount($od_shipping_cost + $subTotal).'</td>
    </tr>
</table>
<!--
<hr>
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr id="infoTableHeader"> 
        <td colspan="2">Shipping Information</td>
    </tr>
    <tr> 
        <td width="150" class="label">First Name</td>
        <td class="content">'.$od_shipping_first_name.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Last Name</td>
        <td class="content">'.$od_shipping_last_name.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Address1</td>
        <td class="content">'.$od_shipping_address1.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Address2</td>
        <td class="content">'.$od_shipping_address2.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Phone Number</td>
        <td class="content">'.$od_shipping_phone.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Province / State</td>
        <td class="content">'.$od_shipping_state.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">City</td>
        <td class="content">'.$od_shipping_city.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Postal Code</td>
        <td class="content">'.$od_shipping_postal_code.'</td>
    </tr>
</table>
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr id="infoTableHeader"> 
        <td colspan="2">Payment Information</td>
    </tr>
    <tr> 
        <td width="150" class="label">First Name</td>
        <td class="content">'.$od_payment_first_name.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Last Name</td>
        <td class="content">'.$od_payment_last_name.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Address1</td>
        <td class="content">'.$od_payment_address1.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Address2</td>
        <td class="content">'.$od_payment_address2.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Phone Number</td>
        <td class="content">'.$od_payment_phone.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Province / State</td>
        <td class="content">'.$od_payment_state.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">City</td>
        <td class="content">'.$od_payment_city.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Postal Code</td>
        <td class="content">'.$od_payment_postal_code.'</td>
    </tr>
</table>
<p>&nbsp;</p>
-->';
	return $emailMessage;

}

?>