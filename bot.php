<?php
$access_token = 'rlGsU0aqqoe8RO0VgHOB46l7vcJDjSHuDf4qaLXobD1veApbbIUtgG5D0UeywJOAelIncWkrv9+2CIte40Fs0pqK/Giz7c5BjsID45ZKXtpvsf0phX0OSo5EGFHO5fJzivZeY11NaPBWLVOOBHt4OAdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');

//echo $content.'<br>';
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
    // Loop through each event
    foreach ($events['events'] as $event) {
        // Reply only when message sent is in 'text' format
        if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
            // Get text sent
            $text = $event['message']['text'];
            // Get replyToken
            $replyToken = $event['replyToken'];
            // Build message to reply back
            $messages = [
                'type' => 'text',
                'text' => $content,
            ];

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

            $text = getNearByCAirPM($event['message']['latitude'], $event['message']['longitude']);

            $messages = [
                'type' => 'text',
                'text' => $text,
            ];
        }
        else{
            // Get text sent
            $text = $content;
            // Get replyToken
            $replyToken = $event['replyToken'];
            // Build message to reply back
            $messages = [
                'type' => 'text',
                'text' => 'NON MESSAGE TYPE\n'.$content,
            ];
        }

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
            echo $text. "";
    }
}
echo "OK";


function getNearByCAirPM($Lat,$Long){

    # Our new data
    $data = array(
        'election' => 1,
        'name' => 'Test'
    );
    # Create a connection
    $url = 'http://c-air.siitgis.com/api/v1/pm25.php';
    $content=file_get_contents($url);
    $data=json_decode($content);

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

    

    //$curlResponse = explode('"data": ', $curlResponse);
    //$curlResponse = substr($curlResponse[1], 0, -2);

    //echo '<br>'.$curlResponse;
    //echo '<hr>';

    //$jsonC_air = json_decode('['.$curlResponse.']', true);

    

    //return $jsonC_air;
    return substr('*'.$curlResponse.'*', 0, 400);
}
?>
