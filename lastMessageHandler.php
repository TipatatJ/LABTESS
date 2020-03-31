<?php

    switch(true){

        case $lastMsg == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว' || $text == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ชื่อของท่านจะถูกระบุเป็น Anonymous

                กรุณาระบุประสบการณ์ของท่านกับ Homeopathy
                ',
            ];

            $jsonMsg = '{
                "type": "template",
                "altText": "this is a buttons template",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "เป็น MD Prescriber",
                            "text": "PMgeneralAdvise",
                            "data": "MD Prescriber"
                        },
                        {
                            "type": "postback",
                            "label": "เป็น Lay Prescriber",
                            "text": "PMriskAdvise",
                            "data": "Lay Prescriber"
                        },
                        {
                            "type": "postback",
                            "label": "เป็นผู้เคยรับยา Homeo",
                            "text": "PMriskAdvise",
                            "data": "Homeo user"
                        }
                        ],
                        "title": "สภาพอากาศพื้นที่ใกล้เคียง",
                        "text": "ท่านจะถูกระบุเป็น Anonymous"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'WTH name as Anonymous', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('name'=>'Anonymous')), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == 'ฉันพร้อมแสดงตัว เพื่อสนับสนุน Homeopathy' || $text == 'ฉันพร้อมแสดงตัว เพื่อสนับสนุน Homeopathy':
            $messages = [
                'type' => 'text',
                'text' => ' 
                กรุณาใส่ชื่อ นามสกุล ของท่าน 
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>'WTH please input user name', 
            "me"=>$me);
            post2WTH($fields);
            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $userId != $me:
            // Build message to reply back
            $messages = [
                'type' => 'text',
                'text' => $text,
            ];
            break;
        default:
            $messages = [
                'type' => 'text',
                'text' => $lastMsg." is your last message
                
                "."$text ($userId)",
            ];
            break;            
    }

?>