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