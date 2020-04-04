<?php
require_once 'config.php';
require_once 'database.php';



/*********************************************************
*                 Customer FUNCTIONS 
**********************************************************/

/*
	Check if a session customer id exist or not. If not set redirect
	to login page. If the user session id exist and there's found
	$_GET['logout'] in the query string logout the user
*/
function checkCustomerUser()
{
	// if the session id is not set, redirect to login page
	if (!isset($_SESSION['plaincart_customer_id'])) {
		header('Location: ' . WEB_ROOT . 'login.php');
		exit;
	}
	
	// the user want to logout
	if (isset($_GET['logout'])) {
		doCustomerLogout();
	}
}

function checkCustomerLogin()
{
	// if the session id is not set, redirect to login page
	if (isset($_SESSION['plaincart_customer_id'])) {
		return true;
	} else {
		return false;
	}
}

function doCustomerLogin()
{
	// กำหนดตัวแปรสำหรับเก็บข้อความเพื่อใช้บอกลูกค้าว่าล็อกอินไม่ผ่าน
	$errorMessage = '';
	
	$userName = $_POST['txtUserName'];
	$password = $_POST['txtUserPassword'];
	$hashPassword = md5($password.SECRET_KEY);	  //นำเอารหัสผ่านมาเข้ารหัส
	
	// ตรวจสอบว่าชื่อ Username ไม่เป็นค่าว่างๆ
	if ($userName == '') {
		$errorMessage = 'You must enter your username';
	} else if ($password == '') {
		$errorMessage = 'You must enter the password';
	} else {
		// ตรวจสอบกับฐานข้อมูลว่า Username และรหัสผ่านถูกต้อง
		$sql = "SELECT user_id, user_lang, user_role, user_exp, user_rx_option 
		        FROM tbl_user 
				WHERE user_name = '$userName' AND user_password = '$hashPassword'";
		$result = dbQuery($sql);
        
        
	
		//หากตรวจพบ User
		if (dbNumRows($result) == 1) {
			$row = dbFetchAssoc($result);
            
            //echo var_dump($row).'<hr>';
            //$_SESSION['user_exp'] = $row['user_exp'];
            
            if($row['user_exp'] != '0000-00-00'){
                $_SESSION['user_exp'] = $row['user_exp'];
                //return $errorMessage;
                
               $expiry_date = $row['user_exp'];
               $today = date('d-m-Y',time()); 
               $exp = date('d-m-Y',strtotime($expiry_date));
               $expDate =  date_create($exp);
               $todayDate = date_create($today);
               $diff =  date_diff($todayDate, $expDate);
               if($diff->format("%R%a")>= 0){
                   $_SESSION['user_exp'] = "active remain ".$diff->format("%R%a days");
               }else{
                   $_SESSION['user_exp'] = "inactive";
                   $errorMessage = 'Username หรือรหัสผ่าน ไม่ถูกต้อง  หรือหมดอายุ';
                   return $errorMessage;
               }
               
            }
            
            
			$_SESSION['plaincart_customer_id'] = $row['user_id'];
            $_SESSION['user_lang'] = $row['user_lang'];
            $_SESSION['user_rx_option'] = $row['user_rx_option'];
            
            //echo $_SESSION['user_rx_option'].'!!!<hr>';
            
            $userRole = $row['user_role'];
            
            // บันทึกค่า Autherize Category Str เพื่อใช้ทุกครั้งในการ Search หาข้อมูล
			$sql = "SELECT cat_id FROM tbl_category
					WHERE auth_role LIKE '%$userRole%'";
			$authCat = dbQuery($sql,'undefine autherized cat');
            
            $_SESSION['user_auth_cat'] = '';
            while($record = dbFetchAssoc($authCat)){
                $_SESSION['user_auth_cat'] = $_SESSION['user_auth_cat'].' OR cat_id='.$record['cat_id'];
            }
            $_SESSION['user_auth_cat'] = $_SESSION['user_auth_cat'].' OR cat_id=19'; //ENABLE SEARCH LAB FOR EVERY USER
            
            if(substr($_SESSION['user_auth_cat'],0,4) == ' OR '){
                $_SESSION['user_auth_cat'] = substr($_SESSION['user_auth_cat'],4);
            }
            
            
            if($_SESSION['user_auth_cat'] != ''){ 
                $_SESSION['user_auth_cat'] = '('.$_SESSION['user_auth_cat'].')'; 
            }
			
			// นำเอาเวลาที่ลูกค้าล็อกอินเขียนลงฐานข้อมูล
			$sql = "UPDATE tbl_user 
			        SET user_last_login = NOW() 
					WHERE user_id = '{$row['user_id']}'";
			dbQuery($sql);

			//กลับไปยังหน้าเว็บเพจเดิม ก่อนที่จะมีการล็อกอิน

			$shoppingReturnUrl = isset($_SESSION['shop_return_url']) ? $_SESSION['shop_return_url'] : 'index.php';
            //session_id(uniqid()); 
			header('Location: '.$shoppingReturnUrl);

		} else {
			//ล็อกอินไม่ผ่าน กำหนดข้อความลงใน $errorMessage
			$errorMessage = 'Username หรือรหัสผ่าน ไม่ถูกต้อง';
		}		
			
	}
	
	return $errorMessage;	//รีเทิร์นข้อความกลับไป
}

/*
	Customer Logout
*/
function doCustomerLogout()
{
	if (isset($_SESSION['plaincart_customer_id'])) {
		unset($_SESSION['plaincart_customer_id']);
		//ยกเลิก session
	}
	//$shoppingReturnUrl = isset($_SESSION['shop_return_url']) ? $_SESSION['shop_return_url'] : SRV_ROOT.'index.php';
    unset($_SESSION['notify_stock']);
    $shoppingReturnUrl = 'login.php';
    
    session_destroy();
    //echo $shoppingReturnUrl;
	header('Location: '.$shoppingReturnUrl);	//กลับไปยังหน้าล่าสุดที่เข้ามา
	exit;
}

function doCustomerAddToDatabase()
{
    	$userName = $_POST['txtUserName'];
		$password = $_POST['txtUserPassword'];
		$firstName = $_POST['txtUserFirstName'];
		$lastName = $_POST['txtUserLastName'];
		$email = $_POST['txtUserEmail'];
		$address = $_POST['txtUserAddress'].' '.$_POST['txtUserAddress2'];
		$phone = $_POST['txtUserPhone'];
		$city = $_POST['txtUserCity'];
		$state = $_POST['txtUserState'];
		$postalCode = $_POST['txtUserPostalCode'];
		$hashPassword = md5($password.SECRET_KEY);
		$role = 'customer';	

	//ตรวจสอบว่ามีชื่อ Username นี้อยู่ในระบบแล้วหรือไม่
		$sql = "SELECT user_name
	        FROM tbl_user
			WHERE user_name = '$userName'";
		$result = dbQuery($sql);
	
		if (dbNumRows($result) == 1) {
			header('Location: register.php?error=' . urlencode('Username มีอยู่แล้ว กรุณาเลือกชื่ออื่น'));		//ส่งค่า error กลับไปแสดงให้ลูกค้าทราบ
		} else if(checkPassword($password)==false) {
		    header('Location: register.php?error=' . urlencode('!ผิดพลาด รหัสผ่านต้องมีทั้งอักษรและตัวเลข และยาวอย่างน้อย 6 ตัวอักษร'));
		//ตรวจสอบว่าอีเมลถูกต้องหรือไม่
	    } else if(checkEmail($email)==false){
	    	header('Location: register.php?error=' . urlencode('!ผิดพลาด คุณกรอกอีเมล์ไม่ถูกต้อง'));
		//ตรวจสอบว่าเบอร์โทรศัพท์ถูกต้องหรือไม่
	    } else if(checkPhone($phone) == false && $phone != ''){
	    	header('Location: register.php?error=' . urlencode('!ผิดพลาด คุณกรอกเบอร์โทรศัพท์ไม่ถูกต้อง'));
		//ตรวจสอบว่ารหัสไปรษณีย์ถูกต้องหรือไม่
	    } else if(checkPostalCode($postalCode) == false && $postalCode != ''){
	    	header('Location: register.php?error=' . urlencode('!ผิดพลาด คุณกรอกรหัสไปรษณีย์ไม่ถูกต้อง'));
	    } else {		
			$sql   = "INSERT INTO tbl_user (user_name, user_password, user_regdate, user_role, user_first_name,
						user_last_name, user_email, user_address, user_phone, user_city, user_state, user_postal_code)
		          VALUES ('$userName', '$hashPassword', NOW(), '$role', '$firstName', 
		                '$lastName', '$email', '$address', '$phone', '$city', '$state', '$postalCode')";
	
			dbQuery($sql);
			
			//ลบข้อมูลชั่วคราวที่ได้จากฟอร์มออกไป 
			if(isset($_SESSION['txtUserName'])){
    	 		unset($_SESSION['txtUserName']);
    	 		unset($_SESSION['txtUserEmail']);
    			unset($_SESSION['txtUserFirstName']);
    			unset($_SESSION['txtUserLastName']);
    			unset($_SESSION['txtUserAddress']);
    			unset($_SESSION['txtUserPhone']);
    			unset($_SESSION['txtUserCity']);
    			unset($_SESSION['txtUserState']);
    			unset($_SESSION['txtUserPostalCode']);
    		}
			
			//ถ้าทุกอย่างเรียบร้อยก็จะแสดงข้อความบอกให้ลูกค้าทราบ
			setSuccess('ขอบคุณที่กรุณาลงทะเบียนกับทางเว็บไซต์');
			header('Location: index.php');	
	    
	    }
 
}

/*
	customer edit profile at front end 
*/
function doCustomerProfileUpdate()
{
	$userId = $_SESSION['plaincart_customer_id'];
	$firstName = $_POST['txtUserFirstName'];
	$lastName = $_POST['txtUserLastName'];
	$email = $_POST['txtUserEmail'];
	$address = $_POST['txtUserAddress'];
	$phone = $_POST['txtUserPhone'];
	$city = $_POST['txtUserCity'];
	$state = $_POST['txtUserState'];
	$postalCode = $_POST['txtUserPostalCode'];
	
	if(checkEmail($email) == false){
		setError('คุณกรอกอีเมล์ไม่ถูกต้อง');
	}else if(checkPhone($phone) == false && $phone != ''){
		setError('คุณกรอกเบอร์โทรศัพท์ไม่ถูกต้อง');
	}else if(checkPostalCode($postalCode) == false && $postalCode != '' ){
		setError('คุณกรอกรหัสไปรษณีย์ไม่ถูกต้อง');
	} else {
	
		//เมื่อตรวจสอบผ่านทุกอย่างแล้ว ก็ให้อัพเดทฐานข้อมูล
		$sql = "UPDATE tbl_user
			SET user_first_name = '$firstName',
				user_last_name = '$lastName',
				user_email = '$email',
				user_address = '$address',
				user_phone = '$phone',
				user_city = '$city',
				user_state = '$state',
				user_postal_code = '$postalCode'
			WHERE user_id = $userId";
		if($result = dbQuery($sql)){
			setSuccess('อัพเดทข้อมูลเรียบร้อยแล้ว');
		} else {
			setError('เกิดข้อผิดพลาดในฐานข้อมูล');
		}
	}
 
}

/*
	customer change password at front end 
*/
function customerChangePassword()
{
	$userId  = (int)$_POST['hidUserId'];	
	$oldPass = $_POST['txtOldPassword'];
	$hashOldPassword = md5($oldPass.SECRET_KEY);
	$confirmPassword = $_POST['txtConfirmPassword'];
	$password = $_POST['txtPassword'];
	$hashPassword = md5($password.SECRET_KEY);
	//ตรวจรหัสผ่านเก่าว่าตรงกับที่อยู่ในฐานข้อมูลหรือไม่
	$sql = "SELECT user_id
		        FROM tbl_user 
				WHERE user_password = '$hashOldPassword' AND user_id = $userId";
	$result = dbQuery($sql);
	if ($password != $confirmPassword) {			//เมื่อรหัสผ่านไม่ตรง
		setError('รหัสผ่านใหม่กับการยืนยันรหัสผ่านไม่ตรงกัน');	//กำหนดข้อความที่จะบอกผู้ใช้
	} else if (dbNumRows($result) == 1) {		//ถ้ารหัสผ่านตรง
	
		if(checkPassword($password)) {			//ตรวจสอบว่ารหัสผ่านมีข้อความผสมตัวเลข
	
			$sqlUpdate   = "UPDATE tbl_user 
	          SET user_password = '$hashPassword'
			  WHERE user_id = $userId";

			dbQuery($sqlUpdate);		//อัพเดทฐานข้อมูล
			setSuccess('รหัสผ่านถูกเปลี่ยนเรียบร้อยแล้ว');	//บอกข้อความให้ผู้ใช้ทราบ
		} else {
			setError('รหัสผ่านต้องมีทั้งอักษรและตัวเลขผสมกัน และยาวอย่างน้อย 6 ตัวอักษร');
		}
	} else {
		if($_POST['txtOldPassword'] == ''){
			setError('คุณยังไม่ได้กรอกรหัสผ่านเดิม');
		} else {
			setError('คุณกรอกรหัสผ่านเดิมไม่ถูกต้อง ');
		}
	}	

}

function checkPassword($password)
{
	if(strlen($password) < 6 || !preg_match('/^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]+$/i', $password)) {
	   return false;
	} else {
	   return true;
	}
}

function checkEmail($email)
{
    $email = strtolower($email);
    
	if(preg_match('/\A[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}\z/', $email)
        && preg_match('/^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/', $email)) {
	   return true;
	} else {
	   return false;
	}
}

function checkPostalCode($postalCode)
{
	if(!preg_match('/\b[1-9]{1}?\d{3}[0]\b/i', $postalCode)) {
	   return false;
	} else {
	   return true;
	}
}

function checkPhone($phone)
{
	if(!preg_match('/(\b[0]{1}?\d{2}|\b[0]{1}?[2]{1})[-.]?(\d{3}[-.]?\d{4}\b|\d{3}[-.]?\d{3}\b)/i', $phone)) {
	   return false;
	} else {
	   return true;
	}
}
// ป้องกันไม่ให้เข้ามายังหน้าเพจนี้โดยตรง  โดยสมาชิกที่ล็อคอินจะ register ไม่ได้
function customerWantRegister()
{
	if (isset($_SESSION['plaincart_customer_id'])) {
		$shoppingReturnUrl = isset($_SESSION['shop_return_url']) ? $_SESSION['shop_return_url'] : 'index.php';
		header('Location: '.$shoppingReturnUrl);
		exit;
	} else {
		header('Location: ' . WEB_ROOT . 'register.php');		//ไปยังหน้าลงทะเบียน
		exit;
	} 

}

function checkAvailableCampaign()
{
    //$myHost = 'WiztechSolution';
    //คลาสสำหรับการรับส่งเมล์
    
    require_once 'PHPMailer/class.phpmailer.php';
    require_once 'PHPMailer/class.smtp.php';
    require_once 'PHPMailer/class.pop3.php';
    
    $txtEmail = $_POST['txtEmail'];
    
    if(checkEmail($txtEmail) == false){
		//setError('คุณกรอกอีเมล์ไม่ถูกต้อง');
        
        return array('status'=>false,'desc'=>'คุณกรอกอีเมล์ไม่ถูกต้อง โปรดตรวจสอบ Email '.$txtEmail.' อีกครั้งหนึ่ง<hr>Incorrect email. Please check '.$txtEmail.' and correct it\'s format.');
    }
    
    $txtCampaign = $_POST['txtCampaign'];
    
    $sql = "SELECT user_name
	        FROM tbl_user
			WHERE user_email = '$txtEmail' AND CONCAT(user_org,org_lot) = '$txtCampaign'";
    //echo '$sql ='.$sql;
    $result = dbQuery($sql);

    //ถ้าพบ Username ชื่อเดียวกัน แสดงว่ามี user นี้อยู่แล้ว
    if (dbNumRows($result) == 1) {
        return array('status'=>false,'desc'=>'Campaign สำหรับ User Email '.$txtEmail.' ได้ใช้ไปแล้ว<br>กรุณาติดต่อเจ้าหน้าที่ผู้ดูแลแคมเปญของคุณ<hr>THIS CAMPAIGN EMAIL  '.$_POST['txtEmail'].' HAD BEEN USED.<br>Please contact you campaign assistance.');
    }
    
    $now = date('Y-m-d');
    //$now = date('Y-m-d', strtotime('2020-02-21') );
    /*$earlyBird = date('Y-m-d', strtotime('2020-01-20') );
    
    if($earlyBird == $now){
    };*/
    
    $sql = "SELECT user_id, org_lot, user_name
		        FROM tbl_user 
				WHERE CONCAT(user_org,org_lot) = '$txtCampaign' AND user_email = '' AND org_lot <> 'n/a' AND user_exp >= '$now' ORDER BY user_id ASC";
    $result = dbQuery($sql);

    if ($row = dbFetchAssoc($result)){
        $userID = $row['user_id'];
        $userName = $row['user_name'];
        
        $password = bin2hex(openssl_random_pseudo_bytes(4));
        //$password = 'hi1234';
        $hashPassword = md5($password.SECRET_KEY);
        
        $sql2 = "UPDATE tbl_user
		        SET user_email = '$txtEmail', user_password='$hashPassword' WHERE user_id='$userID'";
        $result2 = dbQuery($sql2);
        
        
        
        $message .= "USER NAME: $userName<br>";
        $message .= "PASSWORD: $password<hr>";
        $message .= "You can login at <a href=\"".MY_HOST_LOGIN."\">Wiztech</a>";
        
        $fm = "admin@venitaclinic.com"; // *** ต้องใช้อีเมล์ @yourdomain.com เท่านั้น  ***
        $owner = "dr.tipatat@gmail.com"; // อีเมล์ที่ใช้รับข้อมูลจากแบบฟอร์ม
        $to = $txtEmail; // อีเมล์ที่ใช้รับข้อมูลจากแบบฟอร์ม
        $custemail = $txtEmail; //$_POST['email']; // อีเมล์ของผู้ติดต่อที่กรอกผ่านแบบฟอร์ม

        
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
        $mail->Subject = 'WELCOME TO WIZTECH';
        $mail->Body     = $mesg;
        $mail->WordWrap = 50;  
        
        //echo $mesg.'<br>';
        //
        if(!$mail->Send()) {
            //return 'Message was not sent<br>ยังไม่สามารถส่งเมลล์ได้ในขณะนี้ '. $mail->ErrorInfo;
            return array('status'=>false,'desc'=>'Mail server error. Please try again');
        } else {

            return array('status'=>true,'desc'=>"You can login at <a href=\"".MY_HOST_LOGIN."\">Wiztech</a> with user login and password which was sent to your Email ".$txtEmail."<hr>คุณสามารถล็อคอินได้ที่ <a href=\"".MY_HOST_LOGIN."\">Wiztech</a> ด้วยชื่อผู้ใช้และรหัสที่ส่งไปให้ที่ Email $txtEmail ของคุณ");
        }
        
        //return array('username'=>$userName, 'pwd'=>$password, 'SECRET'=>SECRET_KEY, 'hash'=>$hashPassword);
    }
    else
    {
        //echo $sql;
        return array('status'=>false,'desc'=>'ไม่พบแคมเปญ '.$_POST['txtCampaign'].' ที่คุณต้องการค้นหา หรือแคมเปญนี้หมดไปแล้ว<br>กรุณาติดต่อเจ้าหน้าที่ผู้ดูแลแคมเปญของคุณ<hr>CANNOT FIND CAMPAIGN '.$_POST['txtCampaign'].'. Or this campaign had been finished.<br>Please contact you campaign assistance.');
    }
}


function getCustomerProfile()
{

	if(isset($_SESSION['plaincart_customer_id'])){
		$userName='';
		$userId = $_SESSION['plaincart_customer_id'];
		$sql = "SELECT user_id, user_name, user_first_name, user_last_name, user_password, user_address, user_role, 
				user_email, user_state, user_city, user_phone, user_postal_code
		        FROM tbl_user 
				WHERE user_id = $userId";
		$result = dbQuery($sql);
	
		if (dbNumRows($result) == 1) {
			$row = dbFetchAssoc($result);
			 
			return $row;
		} 
	}

}
?>