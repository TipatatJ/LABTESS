<?php
define('SECRET_KEY', 'thai_epigenomic');
$me = "Ub3f6b90b35b51d817a89835f9afaf8c7";
$access_token = 'hEDZi+zGiSIa3KF236hviPyddJfmvzBLx8aaRV0rRY2ESb0p7Kf/JgZkvM8UMX+FoYvyqi5AhATM7UrNjfESc76DxSJ0oKywtveD2RRGwkycRMariyJIRPZ/ctDWXhsNwg99GMYnMMrqYxrTLOXF0QdB04t89/1O/w1cDnyilFU=';
$arrBreakKW = array(
            '
            ขอบคุณที่สนใจเป็นส่วนหนึ่งของ OMAC FOOD MAP เริ่มต้นกรอกข้อมูลโดยการเลือกที่ Rich menu ด้านล่าง
            
            หรือพิมพ์ ?
            ',
            'PMgeneralAdvise',
            'PMriskAdvise',
            //'occupation,1','occupation,2','occupation,3',
            //'acup,1','acup,2',
            'serve acup','no acup',
            //'CHherb,1','CHherb,2',
            //'serve herb','no herb',
            //'tuina,1','tuina,2',
            'serve tuina','no tuina',
            'eval,1','eval,2','eval,3','eval,X',
            'MD TCM','TCM doctor','TCM pharmacist',
            'Good','Neutral','Bad', 'no exp',

            'supply Meat','VegFruitSell','SeasonSell','ทำ Bakery','SellEquip','SellPackage','รับ Food R&D','รับ Logistic',
            'มี Architect','มี Interior','เป็นรับเหมา'
            );

// Get POST body content
$content = file_get_contents('php://input');

//echo $content.'<br>';
// Parse JSON

@session_start();
$userId = 'n/a';

$events = json_decode($content, true);

// Validate parsed JSON data
if (!is_null($events['events'])) {
    // Loop through each event
    foreach ($events['events'] as $event) {
        $userId = $event['source']['userId'];

        $lastMsg = getUserLastMessage($userId);

        

        // Reply only when message sent is in 'text' format
        if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
            // Get text sent
            $text = $event['message']['text'];
            // Get replyToken
            $replyToken = $event['replyToken'];

            /* if($userId != $me){
                // Build message to reply back
                $messages = [
                    'type' => 'text',
                    'text' => $text,
                ];
            }
            else{
                $messages = [
                    'type' => 'text',
                    'text' => $lastMsg." is your last message
                    
                    "."$text ($userId)",
                ];
            } */

            


             /* switch(true){
                case $text == 'PMgeneralAdvise':
                    exit;
                    break;
                case $text == 'PMriskAdvise':
                    exit;
                    break; 
                default;
                    $fields = array(
                    "userId"=>$userId,
                    "txt"=>'$$'.json_encode(json_decode($lastMsg, true)['events']), 
                    "me"=>$me);
                    post2WTH($fields);
                    //$text = json_encode(json_decode($text, true)['events'][0]);
            }  */

            //BREAK ALL POST BACK form eventType "Postback"

            if(in_array($text, $arrBreakKW)){
                exit;
            }

findWhat:
            
            include_once('lastMsgOMACfnbHandler.php');

            //$userMessage = $text; // เก็บค่าข้อความที่ผู้ใช้พิมพ์
             
            /* switch($userMessage){
                case     "test":
                    $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                    
                default:
                    $url = "https://bots.dialogflow.com/line/<Agent-ID>/webhook";
                    $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        //          file_put_contents('headers.txt',json_encode($headers, JSON_PRETTY_PRINT));          
        //          file_put_contents('body.txt',file_get_contents('php://input'));
                    $headers['Host'] = "bots.dialogflow.com";
                    $json_headers = array();
                    foreach($headers as $k=>$v){
                        $json_headers[]=$k.":".$v;
                    }
                    $inputJSON = file_get_contents('php://input');
                    // ส่วนของการส่งการแจ้งเตือนผ่านฟังก์ชั่น cURL
                    $ch = curl_init();
                    curl_setopt( $ch, CURLOPT_URL, $url);
                    curl_setopt( $ch, CURLOPT_POST, 1);
                    curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $inputJSON);
                    curl_setopt( $ch, CURLOPT_HTTPHEADER, $json_headers);
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2); // 0 | 2 ถ้าเว็บเรามี ssl สามารถเปลี่ยนเป้น 2
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1); // 0 | 1 ถ้าเว็บเรามี ssl สามารถเปลี่ยนเป้น 1
                    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
                    $result = curl_exec( $ch );
                    curl_close( $ch );
                    exit;               
                    break;
            } */
            
        }
        else if($event['message']['type'] == 'location'){
            // Get text sent
            $text = $content;
            // Get replyToken
            $replyToken = $event['replyToken'];
            // Build message to reply back

            $myLat = $event['message']['latitude'];
            $myLong = $event['message']['longitude'];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array(
                'my location'=>array(
                    "lat"=>$myLat,
                    "long"=>$myLong,
                    "mapId"=>MD5($userId.SECRET_KEY)
                    )
                ),JSON_UNESCAPED_UNICODE),
            "lastMsg"=>$lastMsg, 
            "me"=>$me);
            post2WTH($fields);

            $text = getNearByCAirPM($event['message']['latitude'], $event['message']['longitude']);

            $text == 'Share location ของคุณ เพื่อคนหา Network';
            goto findWhat;
            //echo '#'.$text.'#';

            /* $messages = [
                'type' => 'text',
                'text' => ' 
                พื้นที่ใกล้ๆ '.$event['message']['address'].' 

                มีปริมาณ PM2.5 ที่ระดับ '.$text['pm2.5'].' mcg/m3',
            ]; */

            /* $jsonMsg = '{
                "type": "template",
                "altText": "this is a buttons template",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "แชร์ตำแหน่งบน TCM Map",
                            "text": "Share on TCM Map",
                            "data": "MyLocation,1"
                        },
                        {
                            "type": "postback",
                            "label": "คำแนำนำสำหรับคนปกติ",
                            "text": "PMgeneralAdvise",
                            "data": "PMgeneralAdvise,'.$text['pm2.5'].'"
                        },
                        {
                            "type": "postback",
                            "label": "คำแนะนำคนมีความเสี่ยง",
                            "text": "PMriskAdvise",
                            "data": "PMriskAdvise,'.$text['pm2.5'].'"
                        }
                        ],
                        "title": "สภาพอากาศพื้นที่ใกล้เคียง",
                        "text": "มีปริมาณ PM2.5 ที่ระดับ '.$text['pm2.5'].' mcg/m3"
                    }
                }'; */
            if($lastMsg == 'NEW INPUT2'){
                $messages = [
                    'type' => 'text',
                    'text' => ' 
                    กรุณาใส่ชื่อ นามสกุล ของท่าน 
                    ',
                ];

                $fields = array(
                "userId"=>$userId,
                "txt"=>'{ "WTH":"please input user name" }', 
                "me"=>$me);
                post2WTH($fields);
                justMsg($messages, $replyToken, $access_token);
                exit;
                break;
            }
            else if($lastMsg == 'SUM DATA' || $lastMsg == '{ "WTH":"LocationShare" }'){
                $jsonMsg = '{
                    "type": "template",
                    "altText": "this is a buttons template",
                        "template": {
                            "type": "buttons",
                            "actions": [
                            {
                                "type": "postback",
                                "label": "แชร์ตำแหน่งบน TCM Map",
                                "text": "Shared on TCM Map",
                                "data": "MyLocation,1"
                            }
                            ],
                            "title": "กดปุ่มยืนยัน",
                            "text": "ยืนยันการแชร์ Location บน TCM map"
                        }
                    }';
                
                $messages = json_decode($jsonMsg, true);
                $text = '{ "WTH":"LocationShare" }'; //json_encode($text,JSON_UNESCAPED_UNICODE);
        
                $fields = array(
                "userId"=>$userId,
                "txt"=>$text, 
                "me"=>$me);
                post2WTH($fields);

                

                exit;
            }
            
            
            
            /*
            $jsonMsg = '{
                "type": "template",
                "altText": "this is a buttons template",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "หาของกิน",
                            "text": "Share on TCM Map",
                            "data": "MyLocation,1"
                        },
                        {
                            "type": "postback",
                            "label": "หา Supplier",
                            "text": "PMgeneralAdvise",
                            "data": "PMgeneralAdvise,'.$text['pm2.5'].'"
                        },
                        {
                            "type": "postback",
                            "label": "จะสร้างร้านอาหาร",
                            "text": "PMriskAdvise",
                            "data": "PMriskAdvise,'.$text['pm2.5'].'"
                        }
                        ],
                        "title": "OMACfnb Network",
                        "text": "คุณหาอะไร?"
                    }
                }';


            $messages = json_decode($jsonMsg, true); */
            $text = '{ "WTH":"Ask location" }'; //json_encode($text,JSON_UNESCAPED_UNICODE);
    
            $fields = array(
            "userId"=>$userId,
            "txt"=>$text, 
            "me"=>$me);
            post2WTH($fields);



            /* $jsonMsg = '{
                "type": "template",
                "altText": "this is a buttons template",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "หาของกิน",
                            "text": "Share on TCM Map",
                            "data": "MyLocation,1"
                        },
                        {
                            "type": "postback",
                            "label": "หา Supplier",
                            "text": "PMgeneralAdvise",
                            "data": "PMgeneralAdvise,'.$text['pm2.5'].'"
                        },
                        {
                            "type": "postback",
                            "label": "จะสร้างร้านอาหาร",
                            "text": "PMriskAdvise",
                            "data": "PMriskAdvise,'.$text['pm2.5'].'"
                        }
                        ],
                        "title": "OMACfnb Network",
                        "text": "คุณหาอะไร?"
                    }
                }';

            $msg1 = json_decode($jsonMsg, true);  */

            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "หาของกิน",
                    "text": "Food style search",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Street food",
                        "label": "Street food"
                        },
                        {
                        "type": "message",
                        "text": "Restaurant",
                        "label": "Restaurant"
                        },
                        {
                        "type": "message",
                        "text": "Fine dining",
                        "label": "Fine dining"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/FindFood.jpg"
                    }
                ]
                },
                "altText": "this is a carousel template",
                "type": "template"
            }';
            $msg1 = json_decode($jsonMsg, true);
            //##########################################

            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "หาคนวงการอาหาร",
                    "text": "เลือกประเภท",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Supplier",
                        "label": "Supplier"
                        },
                        {
                        "type": "message",
                        "text": "Media",
                        "label": "Media"
                        },
                        {
                        "type": "message",
                        "text": "Reviewer",
                        "label": "Reviewer"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/chain.png"
                    }
                ]
                },
                "altText": "this is a carousel template",
                "type": "template"
            }';
            $msg2 = json_decode($jsonMsg, true);
            //##########################################
             

            $arrPostData = array();
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'] = [$msg1, $msg2];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"choose Food Vs Supplier" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
            //justMsg($messages, $replyToken, $access_token);

        }
        else if($event['type'] == 'postback'){
            // Get text sent
            $text = $content;
            // Get replyToken
            $replyToken = $event['replyToken'];
            // Build message to reply back

            $data = $event['data'];
            



            /* $messages = [
                'type' => 'text',
                'text' => '>>'.json_encode($event['postback']['data'], true),
            ]; */

            include_once('postBackOMACfnbHandler.php');

            //BREAK ALL POST BACK form eventType "Postback"
            

            if(in_array($text, $arrBreakKW)){
                exit;
            }
        }
        else{
            //if("Ub3f6b90b35b51d817a89835f9afaf8c7"){
                // Get text sent
                $text = $content;
                // Get replyToken
                $replyToken = $event['replyToken'];
                // Build message to reply back
                /* $messages = [
                    'type' => 'text',
                    'text' => 'NON MESSAGE TYPE\n'.$content,
                ]; */



                //$decContent = json_decode($content, true);
                $userMsgType = $event['message']['type'];


                if($userMsgType == 'memberJoined'){
                    $messages = [
                        'type' => 'text',
                        'text' => '
                        เริ่มต้นด้วยการเลือก Click Rich Menu ด้านล่าง
                        หรือพิมพ์

                        ?
                        ',
                    ];

                    justMsg($messages, $replyToken, $access_token);
                    die;
                }

                /* $messages = [
                    'type' => 'text',
                    'text' => '
                    เรายังไม่สามารถเข้าใจ '.$userMsgType.' ได้
                    ต้องรบกวนตอบคำถามด้วยการพิมพ์ตอบอีกครั้ง
                    ',
                ];
                */

                
                $fields = array(
                "userId"=>$userId,
                "txt"=>json_encode(array('NoReaction'=>$userMsgType),JSON_UNESCAPED_UNICODE), 
                "me"=>$me);
                post2WTH($fields);

                /*
                justMsg($messages, $replyToken, $access_token); */
                exit;
                break;
            //}
        }

        //######################################################################################################

        // Make a POST Request to Messaging API to reply to sender
/*         $url = 'https://api.line.me/v2/bot/message/reply';
        $data = [
            'replyToken' => $replyToken,
            'messages' => [$messages]
        ];
        $post = json_encode($data);
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch); */

/*         //######################################################################################################

        // Make a POST to save SMS to Wiztech LINE sms
        $url = 'https://www.venitaclinic.com/Qweb/site1_wiztech/WiztechSolution/include/smsInp.php';
*/

/*         $fields = array(
            "userId"=>$userId,
            "txt"=>$text.' :-)', 
            "me"=>$me);
        post2WTH($fields); */

/*
        //url-ify the data for the POST
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string,'&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        $rtnWTH = curl_exec($ch);

        //###################################################################################################### */
    }
}

function justMsg($messages, $replyToken, $access_token){
    

    // Make a POST Request to Messaging API to reply to sender
    $url = 'https://api.line.me/v2/bot/message/reply';
    $data = [
        'replyToken' => $replyToken,
        'messages' => [$messages]
    ];
    $post = json_encode($data);
    $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    $fields = array(
    "userId"=>'function debug',
    "txt"=>'justMsg '.$messages['text'], 
    "me"=>'function debug');
    //post2WTH($fields);
}

function multiMsg($strAccessToken, $replyToken, $arrPostData){

        /* 
      //DATA PATTERN

      $arrPostData = array();
      $arrPostData['replyToken'] = $replyToken;
      $arrPostData['messages'][0]['type'] = "text";
      $arrPostData['messages'][0]['text'] = 'สวัสดีค่ำ ฉันชื่อ PHHC คุณเรียกฉันหรือคะ?';
      $arrPostData['messages'][1]['type'] = "text";
      $arrPostData['messages'][1]['text'] = 'NEAREST OFFICER
        '; */

    $arrPostData['replyToken'] = $replyToken;
   

    $strUrl = "https://api.line.me/v2/bot/message/reply";

    $arrHeader = array();
    $arrHeader[] = "Content-Type: application/json";
    $arrHeader[] = "Authorization: Bearer {$strAccessToken}";

    $channel = curl_init();
    curl_setopt($channel, CURLOPT_URL,$strUrl);
    curl_setopt($channel, CURLOPT_HEADER, false);
    curl_setopt($channel, CURLOPT_POST, true);
    curl_setopt($channel, CURLOPT_HTTPHEADER, $arrHeader);
    curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($arrPostData));
    curl_setopt($channel, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($channel);
    curl_close ($channel);

}

function post2WTH($fields){
        //######################################################################################################

        // Make a POST to save SMS to Wiztech LINE sms
        $url = 'https://www.venitaclinic.com/Qweb/site1_wiztech/WiztechSolution/include/smsInp.php';
        //$fields = array("userId"=>$userId,"txt"=>$text, "me"=>$me);

        $fields['mapType'] = 'OMACfnb';

        //url-ify the data for the POST
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.urlencode($value).'&'; }
        rtrim($fields_string,'&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

        //execute post
        $rtnWTH = curl_exec($ch);

        //######################################################################################################
}

function getUserLastMessage($userId){
    // Make a POST Request to Wiztech LINE sms
    $url = 'https://www.venitaclinic.com/Qweb/site1_wiztech/WiztechSolution/include/smsOfUser.php';
    $fields = array("userId"=>$userId);


    //url-ify the data for the POST
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string,'&');

    $rtnWTH = file_get_contents($url.'?userId='.$userId.'&mapType=OMACfnb');
    //$lastSMS = json_decode($rtnWTH);
    return $rtnWTH;
}

function getLastUserLocation($userId){
    // Make a POST Request to Wiztech LINE sms
    $url = 'https://www.venitaclinic.com/Qweb/site1_wiztech/WiztechSolution/include/smsUserLoc.php';
    $fields = array("userId"=>$userId);


    //url-ify the data for the POST
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string,'&');

    $rtnWTH = file_get_contents($url.'?userId='.$userId.'&mapType=OMACfnb');
    //$lastSMS = json_decode($rtnWTH, true);
    return $rtnWTH;
}

function getNearestOMACfnb($lat,$lng, $lookFor,$group, $curUser){
    // Make a POST Request to Wiztech LINE sms
    $url = 'https://www.venitaclinic.com/Qweb/site1_wiztech/WiztechSolution/include/nearestNetwork.php';
    //$fields = array("lat"=>$lat,"lng"=>$lng,"lookFor"=>$lookFor,"group"=>$group);


    //url-ify the data for the POST
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string,'&');

    $rtnWTH = file_get_contents($url.'?lat='.$lat.'&lng='.$lng.'&lookFor='.$lookFor.'&group='.$group.'&me='.$curUser.'&mapType=OMACfnb');
    //$lastSMS = json_decode($rtnWTH);
    return $rtnWTH;
}

function getNearByCAirPM($lat1,$lon1){

    # Our new data
    $data = array(
        'election' => 1,
        'name' => 'Test'
    );
    # Create a connection
    //$url = 'http://c-air.siitgis.com/api/v1/pm25.php';
    $url = 'https://c-air.siitgis.com/api/v1/pm25.php';
    $curlResponse=file_get_contents($url);
    //$data=json_decode($content);

    /* $groupID = 2; //$_POST['groupLINE'];
    switch($groupID){
        case 1:
            //NOTIFY GROUP Wiztech Proactive Health Notify
            $sToken = "7wtLo2RFBGMm66XPBZi0CvtSgQG8T9hjDJtIMX03njD";
            break;
        case 2:
            //NOTIFY GROUP ห้องเรียนหมอปอง
            $sToken = "wq02YAYx6MTsdIQPLcpl3PhxygYZ0gNuCWg5khwdTzf";
            break;
        case 3:
            //NOTIFY GROUP Wiztech BnB
            $sToken = 'eunEDKajKCG5BBpwKuRJsShQZhgh9cOsVNf7gkJqPG4';
            break;
    } */

    

    $curlResponse = explode('"data": ', $curlResponse);
    $curlResponse = substr($curlResponse[1], 0, -2);

    //echo '<br>'.$curlResponse;
    //echo '<hr>';

    $jsonC_air = json_decode('['.$curlResponse.']', true);

    $closestStation = array('distance'=>'9999');

    foreach($jsonC_air as $key=>$arrStation){
        $lat2 = $arrStation['latitude'];
        $lon2 = $arrStation['longitude'];
        $arrStation['distance'] = distance($lat1, $lon1, $lat2, $lon2, 'K');
        if($arrStation['distance'] < $closestStation['distance']){
            $closestStation = $arrStation;
        }
    }

    //return $jsonC_air;
    return $closestStation;
}

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at https://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: https://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2018                  :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}
?>
