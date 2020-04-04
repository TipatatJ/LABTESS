<?php
/*
	Contain the common functions 
	required in shop and admin pages
*/
require_once 'config.php';
require_once 'database.php';

/*
	Make sure each key name in $requiredField exist
	in $_POST and the value is not empty
*/

/*function isJSON(...$arg){
    json_decode(...$arg);
    return (json_last_error()===JSON_ERROR_NONE);
}*/

function isJSON($string) //json_validate($string)
{
    // decode the JSON data
    $result = json_decode($string);

    // switch and check possible JSON errors
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $error = ''; // JSON is valid // No error has occurred
            break;
        case JSON_ERROR_DEPTH:
            $error = 'The maximum stack depth has been exceeded.';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            $error = 'Invalid or malformed JSON.';
            break;
        case JSON_ERROR_CTRL_CHAR:
            $error = 'Control character error, possibly incorrectly encoded.';
            break;
        case JSON_ERROR_SYNTAX:
            $error = 'Syntax error, malformed JSON.';
            break;
        // PHP >= 5.3.3
        case JSON_ERROR_UTF8:
            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_RECURSION:
            $error = 'One or more recursive references in the value to be encoded.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_INF_OR_NAN:
            $error = 'One or more NAN or INF values in the value to be encoded.';
            break;
        case JSON_ERROR_UNSUPPORTED_TYPE:
            $error = 'A value of a type that cannot be encoded was given.';
            break;
        default:
            $error = 'Unknown JSON error occured.';
            break;
    }

    if ($error !== '') {
        // throw the Exception or exit // or whatever :)
        //exit($error);
        
        return 'false';
    }

    // everything is OK
    return 'true';
}

function mysql_escape_mimic($inp) { 
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 

    if(!empty($inp) && is_string($inp)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
    } 

    return $inp; 
}

function mysql_unreal_escape_string($string) {
    $characters = array('x00', 'n', 'r', '\\', '\'', '"','x1a');
    $o_chars = array("\x00", "\n", "\r", "\\", "'", "\"", "\x1a");
    for ($i = 0; $i < strlen($string); $i++) {
        if (substr($string, $i, 1) == '\\') {
            foreach ($characters as $index => $char) {
                if ($i <= strlen($string) - strlen($char) && substr($string, $i + 1, strlen($char)) == $char) {
                    $string = substr_replace($string, $o_chars[$index], $i, strlen($char) + 1);
                    break;
                }
            }
        }
    }
    return $string;
}

function isLike($haystack, $needle) {
    $regex = '#^'.preg_quote($needle, '#').'$#i';
    //add support for wildcards
    $regex = str_replace(array('%', '_'), array('.*?', '.?'), $regex);
    return 0 != preg_match($regex, $haystack);
}

function to_utf8( $string ) { 
// From http://w3.org/International/questions/qa-forms-utf-8.html 
    if ( preg_match('%^(?: 
      [\x09\x0A\x0D\x20-\x7E]            # ASCII 
    | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte 
    | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs 
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte 
    | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates 
    | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3 
    | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15 
    | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16 
)*$%xs', $string) ) { 
        return $string; 
    } else { 
        return iconv( 'CP1252', 'UTF-8', $string); 
    } 
}

/*if (!function_exists('ARR_PRINT')) {
    function ARR_PRINT($Input, $Level = 0, $StrParent = "", $Lead = "&nbsp;&nbsp;",$Result = ""){
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
}*/

//if(!function_exists('HN2DB')){
    function HN2DB($params, &$PtFullName){
        /*if(!isset($params['host'])){ 
            $params['host'] = 'localhost'; 
            $params['DB'] = 'hf_cust';

        }
        else
        {*/
            //echo 'use input value ** <br>';
            /*$dbUser = $params['user'];
            $dbPass = $params['pwd'];
            $dbHost = $params['host'];
            $dbName = $params['DB'];*/
        //}

        //echo var_dump($params).'<br>';



        //echo '$dbHost = '.$dbUser.'<br>';
        //echo '$dbName = '.$dbPass.'<br>';

        //DBi::$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

        {
            $table = $params['TB'];

            //if($table == 'hf_cust'){
                //$where = 'hospital_number = "'.$params['fHN'].'"';
            //}
            //else
            //{
                $where = 'serverID = "'.$params['HN'].'" OR siteID = "'.$params['fHN'].'"';
            //}

        }


        dbQuery('SET NAMES utf8');
        $qHN = 'SELECT Name,Surname FROM '.$table.' WHERE '.$where;

        //echo '$qHN = '.$qHN.'<br>';

        $result = dbQuery($qHN);

        if (dbNumRows($result) > 0) {
            $records = dbFetchAssoc($result);
            $PtFullName = $records['Name'].' '.$records['Surname'];
            return $records;
        }
        else
        {
            $PtFullName = "HN".$params['fHN'];
            return;
        }


    }
//}

function decodeDose($dose, $lang, $unit = ' เม็ด'){
    //echo 'orig -> '.$dose.'<hr>';
    
    $dose = ' '.strtolower ($dose).' ';
    
    $dose = nl2br($dose,false);
    
    $abbWord = array();
    
    $arrRoute = array(
        "tpn"=>array("Th"=>"total parenteral neutrition",
                     "En"=>"total parenteral neutrition"),
        
        "tts"=>array("Th"=>"แปะให้ดูดซึมทางผิวหนัง",
                     "En"=>"transdermal therapeutic system"),
        
        "transdermal"=>array("Th"=>"แปะให้ดูดซึมทางผิวหนัง",
                             "En"=>"transdermal therapeutic system"),
        
        "nb"=>array("Th"=>"สูดพ่นด้วย Nebulizer",
                    "En"=>"nebulized"),
        
        "parenteral"=>array("Th"=>"ให้อาหารทางเส้นเลือด",
                            "En"=>"parenteral administration"),
        
        "acupoint"=>array("Th"=>"ตามตำแหน่งจุดฝังเข็ม",
                          "En"=>"acupuncture point"),
        
        "iv"=>array("Th"=>"ฉีดเข้าเส้นเลือดดำ",
                    "En"=>"intravenous"),
        
        "mdi"=>array("Th"=>"สูดพ่นด้วย Inhaler",
                     "En"=>"metered dose inhaler"),
        
        "supp"=>array("Th"=>"เหน็บทางทวารหนัก",
                      "En"=>"anal suppository"),
        
        "vag"=>array("Th"=>"เหน็บทางช่องคลอด",
                     "En"=>"vaginal suppository"),
        
        "it"=>array("Th"=>"ฉีดเข้าไขสันหลัง",
                    "En"=>"intratecal"),
        
        "intradermal"=>array("Th"=>"ฉีดในชั้น Dermis",
                             "En"=>"intradermal injection"),
        
        "ip"=>array("Th"=>"ฉีดเข้าช่องท้อง",
                    "En"=>"intraperitonium"),
        
        "buccal"=>array("Th"=>"อมในกระพุ้งแก้ม",
                        "En"=>"buccal"),
        
        "urethral"=>array("Th"=>"เหน็บท่อปัสสาวะ",
                          "En"=>"urethral suppository"),
        
        "sc"=>array("Th"=>"ฉีดใต้ผิวหนัง",
                    "En"=>"subcutaneous"),
        
        "irrigation"=>array("Th"=>"ล้าง Irrigate",
                            "En"=>"irrigation"),
        
        "subdermal"=>array("Th"=>"ฝังใต้ผิวหนัง",
                           "En"=>"subdermal implant"),
        
        "im"=>array("Th"=>"ฉีดเข้ากล้าม",
                    "En"=>"intramuscular"),
        
        "is"=>array("Th"=>"ฉีดเข้าข้อ",
                    "En"=>"intrasynovial"),
        
        "sl"=>array("Th"=>"อมใต้ลิ้น",
                    "En"=>"sublingual"),
        
        "periodontal implant"=>array("Th"=>"ฝังในปาก",
                                     "En"=>"periodontal implant"),
        
        "nasal spray"=>array("Th"=>"พ่นจมูก",
                             "En"=>"nasal spray"),
        
        "otic"=>array("Th"=>"หยอดหู",
                      "En"=>"ear drop"),
        
        "throat spray"=>array("Th"=>"พ่นคอ",
                              "En"=>"throat spray"),
        
        "po"=>array("Th"=>"กิน",
                    "En"=>"taken orally"),
        
        "inj"=>array("Th"=>"ฉีด",
                     "En"=>"injection"),
        
        "o"=>array("Th"=>"กิน",
                   "En"=>"taken orally"));
    
    $dose = str_replace('1x1 ac',"1 x 1 o ac",$dose);
    $dose = str_replace('2x1 ac',"2 x 1 o ac",$dose);
    $dose = str_replace('3x1 ac',"3 x 1 o ac",$dose);
    $dose = str_replace('4x1 ac',"4 x 1 o ac",$dose);
    $dose = str_replace('5x1 ac',"5 x 1 o ac",$dose);
    $dose = str_replace('6x1 ac',"6 x 1 o ac",$dose);
    $dose = str_replace('7x1 ac',"7 x 1 o ac",$dose);
    $dose = str_replace('8x1 ac',"8 x 1 o ac",$dose);
    $dose = str_replace('9x1 ac',"9 x 1 o ac",$dose);
    
    $dose = str_replace('1 x 1 ac',"1 x 1 o ac",$dose);
    $dose = str_replace('2 x 1 ac',"2 x 1 o ac",$dose);
    $dose = str_replace('3 x 1 ac',"3 x 1 o ac",$dose);
    $dose = str_replace('4 x 1 ac',"4 x 1 o ac",$dose);
    $dose = str_replace('5 x 1 ac',"5 x 1 o ac",$dose);
    $dose = str_replace('6 x 1 ac',"6 x 1 o ac",$dose);
    $dose = str_replace('7 x 1 ac',"7 x 1 o ac",$dose);
    $dose = str_replace('8 x 1 ac',"8 x 1 o ac",$dose);
    $dose = str_replace('9 x 1 ac',"9 x 1 o ac",$dose);
    
    //echo 'STEP 1 mod dose = '.$dose.'<br>';
    
    if($lang == 'Th'){
        $dose = str_replace('x1 day'," เป็นเวลา s1 วัน",$dose);
        $dose = str_replace('x2 day'," เป็นเวลา 2 วัน",$dose);
        $dose = str_replace('x3 day'," เป็นเวลา 3 วัน",$dose);
        $dose = str_replace('x4 day'," เป็นเวลา 4 วัน",$dose);
        $dose = str_replace('x5 day'," เป็นเวลา 5 วัน",$dose);
        $dose = str_replace('x6 day'," เป็นเวลา 6 วัน",$dose);
        $dose = str_replace('x7 day'," เป็นเวลา 7 วัน",$dose);
        $dose = str_replace('x1 mn'," เป็นเวลา 1 เดือน",$dose);
        $dose = str_replace('x2 mn'," เป็นเวลา 2 เดือน",$dose);
        $dose = str_replace('x3 mn'," เป็นเวลา 3 เดือน",$dose);
        $dose = str_replace('x4 mn'," เป็นเวลา 4 เดือน",$dose);
        $dose = str_replace('x5 mn'," เป็นเวลา 5 เดือน",$dose);
        $dose = str_replace('x6 mn'," เป็นเวลา 6 เดือน",$dose);
        $dose = str_replace('x7 mn'," เป็นเวลา 7 เดือน",$dose);
        $dose = str_replace('x8 mn'," เป็นเวลา 8 เดือน",$dose);
        $dose = str_replace('x9 mn'," เป็นเวลา 9 เดือน",$dose);
        $dose = str_replace('x10 mn'," เป็นเวลา 10 เดือน",$dose);
        $dose = str_replace('x11 mn'," เป็นเวลา 11 เดือน",$dose);
        $dose = str_replace('x12 mn'," เป็นเวลา 12 เดือน",$dose);
        
        $dose = str_replace('x 1 day'," เป็นเวลา 1 วัน",$dose);
        $dose = str_replace('x 2 day'," เป็นเวลา 2 วัน",$dose);
        $dose = str_replace('x 3 day'," เป็นเวลา 3 วัน",$dose);
        $dose = str_replace('x 4 day'," เป็นเวลา 4 วัน",$dose);
        $dose = str_replace('x 5 day'," เป็นเวลา 5 วัน",$dose);
        $dose = str_replace('x 6 day'," เป็นเวลา 6 วัน",$dose);
        $dose = str_replace('x 7 day'," เป็นเวลา 7 วัน",$dose);
        $dose = str_replace('x 1 mn'," เป็นเวลา 1 เดือน",$dose);
        $dose = str_replace('x 2 mn'," เป็นเวลา 2 เดือน",$dose);
        $dose = str_replace('x 3 mn'," เป็นเวลา 3 เดือน",$dose);
        $dose = str_replace('x 4 mn'," เป็นเวลา 4 เดือน",$dose);
        $dose = str_replace('x 5 mn'," เป็นเวลา 5 เดือน",$dose);
        $dose = str_replace('x 6 mn'," เป็นเวลา 6 เดือน",$dose);
        $dose = str_replace('x 7 mn'," เป็นเวลา 7 เดือน",$dose);
        $dose = str_replace('x 8 mn'," เป็นเวลา 8 เดือน",$dose);
        $dose = str_replace('x 9 mn'," เป็นเวลา 9 เดือน",$dose);
        $dose = str_replace('x 10 mn'," เป็นเวลา 10 เดือน",$dose);
        $dose = str_replace('x 11 mn'," เป็นเวลา 11 เดือน",$dose);
        $dose = str_replace('x 12 mn'," เป็นเวลา 12 เดือน",$dose);
    }
    
    //echo 'orig2 -> '.$dose.'<hr>';
    
    if($lang == 'Th'){
        $dose = str_replace('1x1 ac',"1 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        $dose = str_replace('2x1 ac',"2 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        $dose = str_replace('3x1 ac',"3 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        $dose = str_replace('4x1 ac',"4 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        $dose = str_replace('5x1 ac',"5 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        $dose = str_replace('6x1 ac',"6 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        $dose = str_replace('7x1 ac',"7 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        $dose = str_replace('8x1 ac',"8 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        $dose = str_replace('9x1 ac',"9 $unit กินวันละครั้ง ก่อนอาหาร",$dose);
        
        $dose = str_replace('1x1 pc',"1 $unit กินวันละครั้ง หลังอาหาร",$dose);
        $dose = str_replace('2x1 pc',"2 $unit กินวันละครั้ง หลังอาหาร",$dose);
        $dose = str_replace('3x1 pc',"3 $unit กินวันละครั้ง หลังอาหาร",$dose);
        $dose = str_replace('4x1 pc',"4 $unit กินวันละครั้ง หลังอาหาร",$dose);
        $dose = str_replace('5x1 pc',"5 $unit กินวันละครั้ง หลังอาหาร",$dose);
        $dose = str_replace('6x1 pc',"6 $unit กินวันละครั้ง หลังอาหาร",$dose);
        $dose = str_replace('7x1 pc',"7 $unit กินวันละครั้ง หลังอาหาร",$dose);
        $dose = str_replace('8x1 pc',"8 $unit กินวันละครั้ง หลังอาหาร",$dose);
        $dose = str_replace('9x1 pc',"9 $unit กินวันละครั้ง หลังอาหาร",$dose);
        
        $dose = str_replace('1x2 ac',"1 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('2x2 ac',"2 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('3x2 ac',"3 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('4x2 ac',"4 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('5x2 ac',"5 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('6x2 ac',"6 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('7x2 ac',"7 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('8x2 ac',"8 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('9x2 ac',"9 $unit กิน เช้า เย็น ก่อนอาหาร",$dose);
        
        $dose = str_replace('1x2 pc',"1 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        $dose = str_replace('2x2 pc',"2 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        $dose = str_replace('3x2 pc',"3 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        $dose = str_replace('4x2 pc',"4 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        $dose = str_replace('5x2 pc',"5 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        $dose = str_replace('6x2 pc',"6 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        $dose = str_replace('7x2 pc',"7 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        $dose = str_replace('8x2 pc',"8 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        $dose = str_replace('9x2 pc',"9 $unit กิน เช้า เย็น หลังอาหาร",$dose);
        
        $dose = str_replace('1x3 ac',"1 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('2x3 ac',"2 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('3x3 ac',"3 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('4x3 ac',"4 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('5x3 ac',"5 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('6x3 ac',"6 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('7x3 ac',"7 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('8x3 ac',"8 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        $dose = str_replace('9x3 ac',"9 $unit กิน เช้า กลางวัน เย็น ก่อนอาหาร",$dose);
        
        $dose = str_replace('1x3 pc',"1 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
        $dose = str_replace('2x3 pc',"2 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
        $dose = str_replace('3x3 pc',"3 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
        $dose = str_replace('4x3 pc',"4 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
        $dose = str_replace('5x3 pc',"5 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
        $dose = str_replace('6x3 pc',"6 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
        $dose = str_replace('7x3 pc',"7 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
        $dose = str_replace('8x3 pc',"8 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
        $dose = str_replace('9x3 pc',"9 $unit กิน เช้า กลางวัน เย็น หลังอาหาร",$dose);
                
        /*$dose = str_replace('1x4 ac',"1 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('2x4 ac',"2 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('3x4 ac',"3 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('4x4 ac',"4 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('5x4 ac',"5 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('6x4 ac',"6 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('7x4 ac',"7 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('8x4 ac',"8 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('9x4 ac',"9 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        
        $dose = str_replace('1x4 pc',"1 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('2x4 pc',"2 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('3x4 pc',"3 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('4x4 pc',"4 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('5x4 pc',"5 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('6x4 pc',"6 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('7x4 pc',"7 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('8x4 pc',"8 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('9x4 pc',"9 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        
        $dose = str_replace('1x4 ac',"1 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('2x4 ac',"2 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('3x4 ac',"3 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('4x4 ac',"4 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('5x4 ac',"5 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('6x4 ac',"6 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('7x4 ac',"7 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('8x4 ac',"8 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('9x4 ac',"9 $unit กินก่อนอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        
        $dose = str_replace('1x4 pc',"1 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('2x4 pc',"2 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('3x4 pc',"3 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('4x4 pc',"4 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('5x4 pc',"5 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('6x4 pc',"6 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('7x4 pc',"7 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('8x4 pc',"8 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);
        $dose = str_replace('9x4 pc',"9 $unit กินหลังอาหาร เช้า กลางวัน เย็น ก่อนนอน",$dose);*/
        
        
        $dose = str_replace('x1'," $unit ((X))((Y)) ",$dose);
        $dose = str_replace('x2'," $unit ((X))((Y)) เช้า เย็น ",$dose);
        $dose = str_replace('x3'," $unit ((X))((Y)) เช้า กลางวัน เย็น ",$dose);
        $dose = str_replace('x4'," $unit ((X))((Y)) เช้า กลางวัน เย็น ก่อนนอน ",$dose);
        $dose = str_replace('x5'," $unit ((X))((Y)) วันละ 5 ครั้ง ",$dose);
        $dose = str_replace('x6'," $unit ((X))((Y)) วันละ 6 ครั้ง ",$dose);
        $dose = str_replace('x7'," $unit ((X))((Y)) วันละ 7 ครั้ง ",$dose);
        $dose = str_replace('x8'," $unit ((X))((Y)) วันละ 8 ครั้ง ",$dose);
        $dose = str_replace('x9'," $unit ((X))((Y)) วันละ 9 ครั้ง ",$dose);

        $dose = str_replace('x 1'," $unit ((X))((Y)) ",$dose);
        $dose = str_replace('x 2'," $unit ((X))((Y)) เช้า เย็น ",$dose);
        $dose = str_replace('x 3'," $unit ((X))((Y)) เช้า กลางวัน เย็น ",$dose);
        $dose = str_replace('x 4'," $unit ((X))((Y)) เช้า กลางวัน เย็น ก่อนนอน ",$dose);
        $dose = str_replace('x 5'," $unit ((X))((Y)) วันละ 5 ครั้ง ",$dose);
        $dose = str_replace('x 6'," $unit ((X))((Y)) วันละ 6 ครั้ง ",$dose);
        $dose = str_replace('x 7'," $unit ((X))((Y)) วันละ 7 ครั้ง ",$dose);
        $dose = str_replace('x 8'," $unit ((X))((Y)) วันละ 8 ครั้ง ",$dose);
        $dose = str_replace('x 9'," $unit ((X))((Y)) วันละ 9 ครั้ง ",$dose);
        
        //echo 'STEP 2 mod dose = '.$dose.'<br>';
    }
    
    if($lang == 'En'){
        $dose = str_replace('1x1 ac',"1 $unit take once daily before meal",$dose);
        $dose = str_replace('2x1 ac',"2 $unit take once daily before meal",$dose);
        $dose = str_replace('3x1 ac',"3 $unit take once daily before meal",$dose);
        $dose = str_replace('4x1 ac',"4 $unit take once daily before meal",$dose);
        $dose = str_replace('5x1 ac',"5 $unit take once daily before meal",$dose);
        $dose = str_replace('6x1 ac',"6 $unit take once daily before meal",$dose);
        $dose = str_replace('7x1 ac',"7 $unit take once daily before meal",$dose);
        $dose = str_replace('8x1 ac',"8 $unit take once daily before meal",$dose);
        $dose = str_replace('9x1 ac',"9 $unit take once daily before meal",$dose);
        
        $dose = str_replace('1x1 pc',"1 $unit take once daily after meal",$dose);
        $dose = str_replace('2x1 pc',"2 $unit take once daily after meal",$dose);
        $dose = str_replace('3x1 pc',"3 $unit take once daily after meal",$dose);
        $dose = str_replace('4x1 pc',"4 $unit take once daily after meal",$dose);
        $dose = str_replace('5x1 pc',"5 $unit take once daily after meal",$dose);
        $dose = str_replace('6x1 pc',"6 $unit take once daily after meal",$dose);
        $dose = str_replace('7x1 pc',"7 $unit take once daily after meal",$dose);
        $dose = str_replace('8x1 pc',"8 $unit take once daily after meal",$dose);
        $dose = str_replace('9x1 pc',"9 $unit take once daily after meal",$dose);
        
        $dose = str_replace('1x2 ac',"1 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('2x2 ac',"2 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('3x2 ac',"3 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('4x2 ac',"4 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('5x2 ac',"5 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('6x2 ac',"6 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('7x2 ac',"7 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('8x2 ac',"8 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('9x2 ac',"9 $unit take before breakfast and dinner",$dose);
        
        $dose = str_replace('1x2 pc',"1 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('2x2 pc',"2 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('3x2 pc',"3 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('4x2 pc',"4 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('5x2 pc',"5 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('6x2 pc',"6 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('7x2 pc',"7 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('8x2 pc',"8 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('9x2 pc',"9 $unit take after breakfast and dinner",$dose);
        
        $dose = str_replace('1x3 ac',"1 $unit take before three meals",$dose);
        $dose = str_replace('2x3 ac',"2 $unit take before three meals",$dose);
        $dose = str_replace('3x3 ac',"3 $unit take before three meals",$dose);
        $dose = str_replace('4x3 ac',"4 $unit take before three meals",$dose);
        $dose = str_replace('5x3 ac',"5 $unit take before three meals",$dose);
        $dose = str_replace('6x3 ac',"6 $unit take before three meals",$dose);
        $dose = str_replace('7x3 ac',"7 $unit take before three meals",$dose);
        $dose = str_replace('8x3 ac',"8 $unit take before three meals",$dose);
        $dose = str_replace('9x3 ac',"9 $unit take before three meals",$dose);
        
        $dose = str_replace('1x3 pc',"1 $unit take after three meals",$dose);
        $dose = str_replace('2x3 pc',"2 $unit take after three meals",$dose);
        $dose = str_replace('3x3 pc',"3 $unit take after three meals",$dose);
        $dose = str_replace('4x3 pc',"4 $unit take after three meals",$dose);
        $dose = str_replace('5x3 pc',"5 $unit take after three meals",$dose);
        $dose = str_replace('6x3 pc',"6 $unit take after three meals",$dose);
        $dose = str_replace('7x3 pc',"7 $unit take after three meals",$dose);
        $dose = str_replace('8x3 pc',"8 $unit take after three meals",$dose);
        $dose = str_replace('9x3 pc',"9 $unit take after three meals",$dose);
        
        $dose = str_replace('1x1 ac',"1 $unit take once daily before meal",$dose);
        $dose = str_replace('2x1 ac',"2 $unit take once daily before meal",$dose);
        $dose = str_replace('3x1 ac',"3 $unit take once daily before meal",$dose);
        $dose = str_replace('4x1 ac',"4 $unit take once daily before meal",$dose);
        $dose = str_replace('5x1 ac',"5 $unit take once daily before meal",$dose);
        $dose = str_replace('6x1 ac',"6 $unit take once daily before meal",$dose);
        $dose = str_replace('7x1 ac',"7 $unit take once daily before meal",$dose);
        $dose = str_replace('8x1 ac',"8 $unit take once daily before meal",$dose);
        $dose = str_replace('9x1 ac',"9 $unit take once daily before meal",$dose);
        
        $dose = str_replace('1x1 pc',"1 $unit take once daily after meal",$dose);
        $dose = str_replace('2x1 pc',"2 $unit take once daily after meal",$dose);
        $dose = str_replace('3x1 pc',"3 $unit take once daily after meal",$dose);
        $dose = str_replace('4x1 pc',"4 $unit take once daily after meal",$dose);
        $dose = str_replace('5x1 pc',"5 $unit take once daily after meal",$dose);
        $dose = str_replace('6x1 pc',"6 $unit take once daily after meal",$dose);
        $dose = str_replace('7x1 pc',"7 $unit take once daily after meal",$dose);
        $dose = str_replace('8x1 pc',"8 $unit take once daily after meal",$dose);
        $dose = str_replace('9x1 pc',"9 $unit take once daily after meal",$dose);
        
        $dose = str_replace('1x2 ac',"1 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('2x2 ac',"2 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('3x2 ac',"3 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('4x2 ac',"4 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('5x2 ac',"5 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('6x2 ac',"6 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('7x2 ac',"7 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('8x2 ac',"8 $unit take before breakfast and dinner",$dose);
        $dose = str_replace('9x2 ac',"9 $unit take before breakfast and dinner",$dose);
        
        $dose = str_replace('1x2 pc',"1 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('2x2 pc',"2 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('3x2 pc',"3 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('4x2 pc',"4 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('5x2 pc',"5 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('6x2 pc',"6 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('7x2 pc',"7 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('8x2 pc',"8 $unit take after breakfast and dinner",$dose);
        $dose = str_replace('9x2 pc',"9 $unit take after breakfast and dinner",$dose);
        
        $dose = str_replace('1x3 ac',"1 $unit take before three meals",$dose);
        $dose = str_replace('2x3 ac',"2 $unit take before three meals",$dose);
        $dose = str_replace('3x3 ac',"3 $unit take before three meals",$dose);
        $dose = str_replace('4x3 ac',"4 $unit take before three meals",$dose);
        $dose = str_replace('5x3 ac',"5 $unit take before three meals",$dose);
        $dose = str_replace('6x3 ac',"6 $unit take before three meals",$dose);
        $dose = str_replace('7x3 ac',"7 $unit take before three meals",$dose);
        $dose = str_replace('8x3 ac',"8 $unit take before three meals",$dose);
        $dose = str_replace('9x3 ac',"9 $unit take before three meals",$dose);
        
        $dose = str_replace('1x3 pc',"1 $unit take after three meals",$dose);
        $dose = str_replace('2x3 pc',"2 $unit take after three meals",$dose);
        $dose = str_replace('3x3 pc',"3 $unit take after three meals",$dose);
        $dose = str_replace('4x3 pc',"4 $unit take after three meals",$dose);
        $dose = str_replace('5x3 pc',"5 $unit take after three meals",$dose);
        $dose = str_replace('6x3 pc',"6 $unit take after three meals",$dose);
        $dose = str_replace('7x3 pc',"7 $unit take after three meals",$dose);
        $dose = str_replace('8x3 pc',"8 $unit take after three meals",$dose);
        $dose = str_replace('9x3 pc',"9 $unit take after three meals",$dose);
        
        $dose = str_replace('1x4 ac',"1 $unit take before three meals +  before going to bed",$dose);
        $dose = str_replace('2x4 ac',"2 $unit take before three meals +  before going to bed",$dose);
        $dose = str_replace('3x4 ac',"3 $unit take before three meals +  before going to bed",$dose);
        $dose = str_replace('4x4 ac',"4 $unit take before three meals +  before going to bed",$dose);
        $dose = str_replace('5x4 ac',"5 $unit take before three meals +  before going to bed",$dose);
        $dose = str_replace('6x4 ac',"6 $unit take before three meals +  before going to bed",$dose);
        $dose = str_replace('7x4 ac',"7 $unit take before three meals +  before going to bed",$dose);
        $dose = str_replace('8x4 ac',"8 $unit take before three meals +  before going to bed",$dose);
        $dose = str_replace('9x4 ac',"9 $unit take before three meals +  before going to bed",$dose);
        
        $dose = str_replace('1x4 pc',"1 $unit take after three meals +  before going to bed",$dose);
        $dose = str_replace('2x4 pc',"2 $unit take after three meals +  before going to bed",$dose);
        $dose = str_replace('3x4 pc',"3 $unit take after three meals +  before going to bed",$dose);
        $dose = str_replace('4x4 pc',"4 $unit take after three meals +  before going to bed",$dose);
        $dose = str_replace('5x4 pc',"5 $unit take after three meals +  before going to bed",$dose);
        $dose = str_replace('6x4 pc',"6 $unit take after three meals +  before going to bed",$dose);
        $dose = str_replace('7x4 pc',"7 $unit take after three meals +  before going to bed",$dose);
        $dose = str_replace('8x4 pc',"8 $unit take after three meals +  before going to bed",$dose);
        $dose = str_replace('9x4 pc',"9 $unit take after three meals +  before going to bed",$dose);
        
        $dose = str_replace('x1'," $unit ((X))((Y))",$dose);
        $dose = str_replace('x2'," $unit ((X))((Y)) breakfast dinner",$dose);
        $dose = str_replace('x3'," $unit ((X))((Y)) breakfast lunch dinner",$dose);
        $dose = str_replace('x4'," $unit ((X))((Y)) breakfast lunch dinner and before going to bed ",$dose);
        $dose = str_replace('x5'," $unit ((X))((Y)) five times a day",$dose);
        $dose = str_replace('x6'," $unit ((X))((Y)) six times a day",$dose);
        $dose = str_replace('x7'," $unit ((X))((Y)) seven times a day",$dose);
        $dose = str_replace('x8'," $unit ((X))((Y)) eight times a day",$dose);
        $dose = str_replace('x9'," $unit ((X))((Y)) nine times a day",$dose);

        $dose = str_replace('x 1'," $unit ((X))((Y))",$dose);
        $dose = str_replace('x 2'," $unit ((X))((Y)) breakfast dinner",$dose);
        $dose = str_replace('x 3'," $unit ((X))((Y)) breakfast lunch dinner",$dose);
        $dose = str_replace('x 4'," $unit ((X))((Y)) breakfast lunch dinner and before going to bed ",$dose);
        $dose = str_replace('x 5'," $unit ((X))((Y)) five times a day",$dose);
        $dose = str_replace('x 6'," $unit ((X))((Y)) six times a day",$dose);
        $dose = str_replace('x 7'," $unit ((X))((Y)) seven times a day",$dose);
        $dose = str_replace('x 8'," $unit ((X))((Y)) eight times a day",$dose);
        $dose = str_replace('x 9'," $unit ((X))((Y)) nine times a day",$dose);
        
        //echo 'STEP 2 mod dose = '.$dose.'<br>';
    }
    
    //echo 'STEP 2 mod dose = '.$dose.'<br>';
    
    $arrPrep = array("sac"=>array("Th"=>"ซอง ",
                                    "En"=>"sachet "),
                     "cab"=>array("Th"=>"ฝา ",
                                    "En"=>"cab "),
                     "cap"=>array("Th"=>"แคปซูล ",
                                    "En"=>"capsule "),
                     "tab"=>array("Th"=>"เม็ด ",
                                    "En"=>"tablet "),
                     "pump"=>array("Th"=>"ปั๊ม ",
                                    "En"=>"pump "),
                     "supp."=>array("Th"=>"เหน็บทางทวารหนัก",
                      "En"=>"anal suppository")
                     );
    foreach($arrPrep as $abb=>&$translate){
        //if(strpos($dose,' '.$abb.' ') !== false){
            //echo 'try mod '.$dose."<br>($abb) with $translate[$lang]<br>";
            $dose = str_ireplace(' '.$abb, ' '.$translate[$lang], $dose);
        //}
    }
    
    //echo 'STEP 3 mod dose = '.$dose.'<br>';
    
    $arrFreq = array("prn q"=>array("Th"=>"เมื่อมีอาการ สามารถให้ได้ทุก ",
                                    "En"=>"when needed, extra dose can be taken every "),
                     "ad lib"=>array("Th"=>"กินได้ตามต้องการ",
                                     "En"=>"as needed"),
                     "bd"=>array("Th"=>"วันละ 2 ครั้ง",
                                 "En"=>"twice daily"),
                     "bid"=>array("Th"=>"วันละ 2 ครั้ง",
                                  "En"=>"twice daily"),
                     "tds"=>array("Th"=>"วันละ 3 ครั้ง",
                                  "En"=>"three times daily"),
                     "tid"=>array("Th"=>"วันละ 3 ครั้ง",
                                  "En"=>"three times daily"),
                     "qid"=>array("Th"=>"วันละ 4 ครั้ง",
                                  "En"=>"four times daily"),
                     "qds"=>array("Th"=>"วันละ 4 ครั้ง",
                                  "En"=>"four times daily"),
                     "OD"=>array("Th"=>"วันละครั้ง",
                                 "En"=>"once daily"),
                     "hrly"=>array("Th"=>"ทุกชั่วโมง",
                                   "En"=>"hourly"),
                     "wkly"=>array("Th"=>"ทุกสัปดาห์",
                                   "En"=>"weekly"),
                     "ววว"=>array("Th"=>"วันเว้นวัน",
                                  "En"=>"every other day"),
                     "eod"=>array("Th"=>"วันเว้นวัน",
                                  "En"=>"every other day"),
                     "od"=>array("Th"=>"วันละครั้ง",
                                 "En"=>"once daily"),
                     "prn"=>array("Th"=>"ให้เมื่อ",
                                  "En"=>"when"),
                     "mthly"=>array("Th"=>"ทุกเดือน", "En"=>"monthly"),
                     "hr"=>array("Th"=>"ชั่วโมง",
                                "En"=>"hour"),
                     "h."=>array("Th"=>"Homeo. ",
                                 "En"=>"Homeo. "),
                     "wk"=>array("Th"=>"สัปดาห์",
                                 "En"=>"week"),
                     "hs"=>array("Th"=>"ก่อนนอน",
                                 "En"=>"bedtime"),
                     "q"=>array("Th"=>"ให้ทุก",
                                "En"=>"every"),
                     "yrly"=>array("Th"=>"ทุกปี",
                                   "En"=>"yearly"),
                     "stat"=>array("Th"=>"ทันที",
                                   "En"=>"immediately"),
                     "mth"=>array("Th"=>"เดือน",
                                  "En"=>"month"),
                     "min"=>array("Th"=>"นาที",
                                  "En"=>"minute"));
    
    foreach($arrFreq as $abb=>&$translate){
        //if(strpos($dose,' '.$abb.' ') !== false){
            //echo 'try mod '.$dose."<br>($abb) with $translate[$lang]<br>";
            $dose = str_ireplace(' '.$abb, ' '.$translate[$lang], $dose);
        //}
    }
    
    //echo 'STEP 4 mod dose = '.$dose.'<br>';
    
    /*$arrPrn = array("prn q"=>array("Th"=>"ให้ได้ทุก ((PRN)) เมื่อมีอาการ",
                                    "En"=>"extra dose can be taken every ((PRN)) when needed"));*/
    
    foreach($arrPrn as $abb=>&$translate){
        if(strpos($dose,' '.$abb.' ') !== false){
            //echo 'try mod '.$dose."<br>($abb) with $translate[$lang]<br>";
            $dose = str_ireplace('((PRN))', ' '.$translate[$lang].' ', $dose);
            $dose = str_ireplace(' '.$abb.' ', ' ', $dose);
        }
    }
    
    $dose = str_replace('  ',' ',$dose);
    
    //echo 'STEP 5 mod dose = '.$dose.'<br>';
    
    $arrHs = array('Th'=>'ก่อนนอน',
                    'En'=>'before going to bed');
    
    //echo 'gonna replace +hs '.$dose.'<hr>';
    $dose = str_replace(' + hs',' '.$arrHs[$lang],$dose);
    
    $arrLM = array('Th'=>'(สูตร LM',
                  'En'=>'(formular LM');
    $dose = str_replace('(สูตร lm',$arrLM[$lang],$dose);
    
    $arrSucc = array('Th'=>'(ทุบ',
                  'En'=>'(succusion');
    $dose = str_replace('(ทุบ',$arrSucc[$lang],$dose);
    
    $arrTimes = array('Th'=>'ครั้ง)',
                  'En'=>'times)');
    $dose = str_replace('ครั้ง)',$arrTimes[$lang],$dose);
    
    
    
    
    //$spaceCut = explode(' ',$dose);
    $arrDecodeDose = array('Th'=>'','En'=>'');
    
    //echo '$lang = '.$lang.'<br>';
                      
    foreach($arrRoute as $abb=>&$translate){
        if(strpos($dose,' '.$abb.' ') !== false){
            //echo 'try mod '.$dose."<br>($abb) with $translate[$lang]<br>";
            $dose = str_ireplace('((X))', ' '.$translate[$lang].' ', $dose);
            $dose = str_ireplace(' '.$abb.' ', ' ', $dose);
        }
    }
    
    //echo 'STEP 6 mod dose = '.$dose.'<br>';
    
    $arrThen = array('Th'=>'<br><br>หลังจากนั้น<br><br>',
                    'En'=>'<br><br>then<br><br>');
    $dose = str_ireplace(' then ', ' '.$arrThen[$lang].' ', $dose);
    
    //echo 'org '.$dose.'<br>';
    
    $arrACPCOD = array('ac'=>array('Th'=>'ก่อนอาหาร',
                                  'En'=>'before meal'),
                      'pc'=>array('Th'=>'หลังอาหาร',
                                 'En'=>'after meal'),
                      'od'=>array('Th'=>'วันละครั้ง',
                                 'En'=>'once daily'));
    foreach($arrACPCOD as $abb=>&$translate){
        if(strpos($dose,' '.$abb.' ') !== false){
            //echo 'try mod '.$dose."<br>($abb) with $translate[$lang]<br>";
            $dose = str_ireplace('((Y))', ' '.$translate[$lang].' ', $dose);
            $dose = str_ireplace(' '.$abb.' ', ' ', $dose);
            //echo 'mod dose to '.$dose.' with '.$abb.'<br>';
        }
    }
    
    //echo 'STEP 7 mod dose = '.$dose.'<br>';
    
    $dose = str_replace(' ((Y))', '',$dose);
    
    //echo 'final -> '.$dose.'<hr>';
    
    /*if(strpos($dose,' x ') === false){
        $decodeAbb = abbDecode($dose);
        //echo '## '.$decodeAbb['Th'].' ##<br>';
        $arrDecodeDose['Th'] = $arrDecodeDose['Th'].$decodeAbb['Th'];
    }
    else
    {
        $arrPos = array();
        $arrPos = strMultiPos($dose, ' x ');

        foreach($arrPos as $key=>$pos){
            if($key == 0){
                $s = 0;
                $n = $pos - $s;
                $unitDose = substr($dose,$s,$n);
                $arrDecodeDose['Th'] = $arrDecodeDose['Th'].$unitDose.' เม็ด ';
                if($key < count($arrPos)){
                    $fl = $arrPos[$key + 1];    
                }
                else
                {
                    $fl = strlen($dose);
                }
                
                $followUnit = substr($dose,$n+3,$fl);
                $arrDecodeDose['Th'] = $arrDecodeDose['Th'].'<'.$followUnit.'>';
            }
            else
            {
                $s = $arrPos[$key-1];
                $n = $pos - $s;
                $unitDose = substr($dose,$s,$n);
                $arrDecodeDose['Th'] = $arrDecodeDose['Th'].$unitDose.' เม็ด ';
                if($key < count($arrPos)){
                    $fl = $arrPos[$key + 1];    
                }
                else
                {
                    $fl = strlen($dose);
                }
                
                $followUnit = substr($dose,$n+3,$fl);
                $arrDecodeDose['Th'] = $arrDecodeDose['Th'].'<'.$followUnit.'>';
            }
        } //END OF foreach($arrPos)
    }*/
    
    
    //$results = dbQuery($sql);
    
    //echo 'array of abbWord --v<br>';
    //echo var_dump($abbWord).'<br>';
    //echo '<hr>';
    return $dose;
    //return $arrDecodeDose;
}

function strMultiPos($hayStack, $toFind){
    echo "find #$toFind# in >$hayStack<<br>";
    $str = $hayStack;
    $start = 0;
    $arrPos = array();
    
    while(strpos($str,$toFind,$start) !== false) {
            $pos = strpos($str,$toFind,$start);
            $arrPos[] = $pos;
            $start = $pos+1; // start searching from next position.
    }
    
    return $arrPos;
}

function abbDecode($abb){
    /*$dbHost = 'localhost';
    $dbUser = 'root';
    $dbPass = 'hfbn';
    $dbName = 'mediyaclinic';*/

    //DBi::$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    //echo 'connecting '.$connectionParam['host'].'<br>';
    dbQuery('SET NAMES utf8;');
    
    $arrAbb = explode(' ',$abb);
    
    $arrDecodeDose = array('Th'=>'','En'=>'');
    
    foreach($arrAbb as $key=>$abb){
    
        $sql = "SELECT * FROM prescribtion_abb WHERE abb = '$abb'";

        //echo 'ABB $sql = '.$sql.'<br>';

        $results =dbQuery($sql);

        if(dbNumRows($results) > 0){
            while ($records[] = dbFetchAssoc($results)) {
                //echo '>>TH->'.$records[count($records)-1]['meaning_th'].'<<<br>';
                //echo '>>EN->'.$records[count($records)-1]['meaning_en'].'<<<br>';

                $arrDecodeDose['Th'] = $arrDecodeDose['Th'].' '. $records['meaning_th'];
                $arrDecodeDose['En'] = $arrDecodeDose['En'].' '. $records['meaning_en'];
                      
            }
        }
        else
        {
            $arrDecodeDose['Th'] = $arrDecodeDose['Th'].' '. $abb;
            $arrDecodeDose['En'] = $arrDecodeDose['En'].' '. $abb;
        }
    }
    
    return $arrDecodeDose;
}

function strim($str,$charlist=" ",$option=0){
    if(is_string($str))
    {
        // Translate HTML entities
        $return = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
        // Remove multi whitespace
        $return = preg_replace("@\s+\s@Ui"," ",$return);
        // Choose trim option
        switch($option)
        {
            // Strip whitespace (and other characters) from the begin and end of string
            default:
            case 0:
                $return = trim($return,$charlist);
            break;
            // Strip whitespace (and other characters) from the begin of string 
            case 1:
                $return = ltrim($return,$charlist);
            break;
            // Strip whitespace (and other characters) from the end of string 
            case 2:
                $return = rtrim($return,$charlist);
            break;
                
        }
    }
    return $return;
}

function jstreeFormat($arrObj, $Root, $l = 0){
    //echo var_dump($arrObj).'<hr>';
    
    $fObj = array('children'=>array());
    
    if($l < 1){
        $catOpen = true;
    }
    else
    {
        $catOpen = false;
    }
    
    $disable = false;
    
    
        
            if($Root != '#' && $Root != '*' && $Root != 'from Root' && $Root != 'rem'){
                foreach($arrObj as $key=>$elem){
                    
                    $fObj['text'] = $Root;
                    $fObj['icon'] = "../ICONS/Info.gif";
                    //$fObj['raw'] = $arrObj;

                    foreach($elem as $dat=>$member){
                        switch (true){
                            case $dat == 'text':
                                $fObj['text'] = $member;
                            case $dat == 'adj':
                                $fObj['adj'] = $member;
                                break;
                            case $dat == 'th_k':
                                $fObj['th_k'] = $member;
                                break;
                            case substr($dat,0,1) == '#':
                                array_push($fObj['children'],jstreeFormat($member, 'from Root', $l+1));
                                break;
                            case $dat == 'rem':
                                $fObj['rem'] = $member;
                                break;
                            default:
                                $fObj['text'] = $dat.':'.$member;
                                break;
                        }
                    } //END of foreach($elem as $member)
                }
            }
            else
            {
                $mother = $arrObj;
                $remTxt = '';
                $remStr = '';
                
                if(is_string($arrObj)){
                    //echo 'str '.$arrObj.'<br>';
                    $fObj['text'] = $arrObj;
                    $fObj['icon'] = array('image'=>"../ICONS/ArchiPlain.ico");
                }
                else
                {
                    foreach($arrObj as $key=>$elem){
                        switch (true){
                            case $key == 'text':
                                if(!is_null($arrObj['adj'] && $arrObj['adj'] != '')){
                                    $adj = '<adj class="hidden">'.$arrObj['adj'].'</adj> ';
                                }
                                else
                                {
                                    $adj = '';
                                }

                                $fObj['text'] = $adj.$elem;

                            case $key == 'adj':
                                $fObj['adj'] = $elem;
                                /*jstreeFormat(
                                        array('rem'=>'use adjective','w'=>'w','adj'=>'adj*')
                                    , $fObj['adj'], $l+1);*/
                                break;
                            case $key == 'th_k':
                                $fObj['th_k'] = $elem;
                                break;
                            case is_array($elem):

                                if(substr($key,0,1) == '#'){
                                        array_push($fObj['children'],jstreeFormat($elem, '#', $l+1));
                                }
                                else
                                {
                                    array_push($fObj['children'],jstreeFormat($elem, '*', $l+1));
                                }

                                break;

                            case $key == 'rem':
                                if($elem != 'n/a'){

                                    /*$rem = ucfirst(substr($mother['rem'],1));
                                    $w = $mother['w'];
                                    
                                    switch ($w){
                                        case 1:
                                            $fObj['text'] = strtolower($rem);
                                            break;
                                        case 2:
                                            $fObj['text'] = $rem;
                                            break;
                                        case 3:
                                            $fObj['text'] = '<i>'.$rem.'</i>';
                                            break;
                                        case 4:
                                            $fObj['text'] = '<b>'.$rem.'</b>';
                                            break;
                                    }*/
                                    
                                    array_push($fObj['children'],jstreeFormat(
                                        array('rem'=>'use adjective','w'=>'w','adj'=>'adj*')
                                    , $mother['adj'], $l+1));
                                    //$disable = true;
                                }
                                else
                                {
                                    //$fObj['text'] = 'no remedies';
                                    array_push($fObj['children'],jstreeFormat(
                                        array('rem'=>'use adjective','w'=>'w','adj'=>'adj*')
                                    , $mother['adj'], $l+1));
                                    //$disable = true;
                                }

                                break;
                            default:


                                if(substr($key,0,1) == '_'){
                                    /*//IF KEY IS REMEDY NAME (START WITH UNDER SCORE)
                                    $w = $elem;
                                    array_push($fObj['children'],jstreeFormat(
                                        array('rem'=>$key,'w'=>$w,'adj'=>$mother['adj'])
                                    , 'rem', $l+1));
                                    break;*/
                                    $w = $elem;
                                    
                                    $rem = ucfirst(substr($key,1));
                                    switch ($w){
                                        case 1:
                                            $remTxt = '<r>'.strtolower($rem).'</r>';
                                            break;
                                        case 2:
                                            $remTxt = '<r><i>'.$rem.'</i></r>';
                                            break;
                                        case 3:
                                            $remTxt = '<r><b>'.$rem.'</b></r>';
                                            break;
                                        case 4:
                                            $remTxt = '<r><b>'.strtoupper($rem).'</b></r>';
                                            break;
                                    }
                                    //echo '$remStr = '.$remTxt.'<br>';
                                    
                                    $fObj['text'] = $fObj['text'].','.$remTxt;
                                    $fObj['attr'] = array('rel'=>'remedies');
                                    $fObj['icon'] = "../ICONS/AcuAnim.gif";
                                }
                                else
                                {
                                    //no use
                                    
                                }
                                
                                //echo 'key = '.$key.'<br>';
                                //echo substr($dat,0,1).'<hr>';
                                break;
                        }
                    }

                    if(substr($fObj['text'],0,1) == ','){ $fObj['text'] = substr($fObj['text'],1); };
                    if($fObj['icon'] == "../ICONS/AcuAnim.gif"){
                        $fObj['text'] = '<afterrub></afterrub><br><i>remedies</i>&#x21B4;<rem class="ui-state-highlight" style="display:block; padding:5px">'.$fObj['text'].'</rem>&nbsp;';
                    }
                    else
                    {
                        $fObj['icon'] = "../ICONS/Info.gif";
                        
                    }
                }
            }
            
            
            $fObj['state'] = array('opened'=>$catOpen,'disabled'=>$disable);
            
            /*foreach($elem as $dat=>$member){
                switch (true){
                    case $dat == 'text':
                        $fObj['text'] = $member;
                    case $dat == 'adj':
                        $fObj['adj'] = $member;
                        break;
                    case $dat == 'th_k':
                        $fObj['th_k'] = $member;
                        break;
                    case substr($dat,0,1) == '#':
                        array_push($fObj['children'],jstreeFormat($member, ''));
                        break;
                    case $dat == 'rem':
                        $fObj['rem'] = $member;
                        break;
                    default:
                        $fObj['text'] = $dat.':'.$member;
                        //echo 'dat = '.$dat.'<br>';
                        //echo substr($dat,0,1).'<hr>';
                        break;
                }
            } //END of foreach($elem as $member)*/
        
    
    
    
    
    //echo var_dump($fObj).'<hr>';
    
    return $fObj;
}
?>