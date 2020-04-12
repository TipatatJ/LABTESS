<?php

  // /$strAccessToken = "+t5E3u2f0eW3JKhGPyKqGX4M1M6uuvErtuexSZn6D3017/ONS2n+Nqc3KjF37A0K4tv3QZ0BD6kyrzoCXmKa+L2ys817BnmeTwqXPujiaX9+yEpjMBxT2OH60T4W41rZXsUqJ6QidfaesO3AOAb93wdB04t89/1O/w1cDnyilFU=";
  $strAccessToken = "PU9uufmJe508EhejtcuRyn68hzOFqG20rdhTCMqDxxarz+JpVfblWt+me5E7WuBo/n4nNeUwpoiw6TyZDwvfpTglp24CVLJOCC4fFV6ylYRxSpwTg7HqjC/J6K38+WUDWdXhbiQGJX8eYfNPvTqUBgdB04t89/1O/w1cDnyilFU=";
  
  $content = file_get_contents('php://input');
  $arrJson = json_decode($content, true);

  $strUrl = "https://api.line.me/v2/bot/message/reply";

  $arrHeader = array();
  $arrHeader[] = "Content-Type: application/json";
  $arrHeader[] = "Authorization: Bearer {$strAccessToken}";
  $_msg = $arrJson['events'][0]['message']['text'];

/*   if($arrJson['events'][0]['message']['type'] == 'location'){
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
            //echo '#'.$text.'#';

            $messages = [
                'type' => 'text',
                'text' => ' 
                พื้นที่ใกล้ๆ '.$event['message']['address'].' 

                มีปริมาณ PM2.5 ที่ระดับ '.$text['pm2.5'].' mcg/m3',
            ];

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

            justMsg($messages, $replyToken, $access_token);
            die;
        } */


  $api_key="xX3Bhqg9gp1ds9yfrPfgzaa8BlYS2igP";
  $url = 'https://api.mongolab.com/api/1/databases/data/collections/datas?apiKey='.$api_key.'';
  $json = file_get_contents('https://api.mongolab.com/api/1/databases/data/collections/datas?apiKey='.$api_key.'&q={"question":"'.$_msg.'"}');
  $data = json_decode($json);
  $isData=sizeof($data);

  if (strpos($_msg, 'H.E.L.E.N') !== false) 
  {
    if (strpos($_msg, 'H.E.L.E.N') !== false) 
    {
      $x_tra = str_replace("H.E.L.E.N","", $_msg);
      $pieces = explode("|", $x_tra);
      $_question=str_replace("[","",$pieces[0]);
      $_answer=str_replace("]","",$pieces[1]);
      //Post New Data
      $newData = json_encode(
        array(
          'question' => $_question,
          'answer'=> $_answer
        )
      );
      $opts = array(
        'http' => array(
            'method' => "POST",
            'header' => "Content-type: application/json",
            'content' => $newData
         )
      );
      $context = stream_context_create($opts);
      $returnValue = file_get_contents($url,false,$context);
      $arrPostData = array();
      $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
      $arrPostData['messages'][0]['type'] = "text";
      $arrPostData['messages'][0]['text'] = 'สวัสดีค่ำ ฉันชื่อ H.E.L.E.N คุณเรียกฉันหรือคะ?';
    }
  }
  else
  {
    if($isData >0){
       foreach($data as $rec){
        $arrPostData = array();
        $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] = $rec->answer;
       }
    }
    else
    {
        $arrPostData = array();
        $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] = 'H.E.L.E.N คุณสามารถสอนให้ฉันฉลาดได้เพียงพิมพ์: H.E.L.E.N[คำถาม|คำตอบ]';
    }
  }

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
  echo "sucess full";


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

function post2WTH($fields){
        //######################################################################################################

        // Make a POST to save SMS to Wiztech LINE sms
        $url = 'https://www.venitaclinic.com/Qweb/site1_wiztech/WiztechSolution/include/smsInp.php';
        //$fields = array("userId"=>$userId,"txt"=>$text, "me"=>$me);

        $fields['mapType'] = 'TCM';

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

    $rtnWTH = file_get_contents($url.'?userId='.$userId.'&mapType=TCM');
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
