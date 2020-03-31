<?php

    switch(true){

        case $lastMsg == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว' || $text == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว':
/*             $messages = [
                'type' => 'text',
                'text' => ' 
                ชื่อของท่านจะถูกระบุเป็น Anonymous

                กรุณาระบุประสบการณ์ของท่านกับ Homeopathy
                ',
            ];
 */
            $jsonMsg = '{
                "type": "template",
                "altText": "this is a buttons template",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "เป็น MD Prescriber",
                            "text": "MD Prescriber",
                            "data": "{ "occupation":1}"
                        },
                        {
                            "type": "postback",
                            "label": "เป็น Lay Prescriber",
                            "text": "Lay Prescriber",
                            "data": "{ "occupation":2}"
                        },
                        {
                            "type": "postback",
                            "label": "เป็นผู้เคยรับยา Homeo",
                            "text": "Homeo user",
                            "data": "{ "occupation":3}"
                        }
                        ],
                        "title": "ประสบการณ์ของท่านกับ Homeopathy",
                        "text": "ท่านจะถูกระบุเป็น Anonymous"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH": "name as Anonymous" }', 
            "me"=>$me);
            post2WTH($fields); */

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
            "txt"=>'{ "WTH":"please input user name" }', 
            "me"=>$me);
            post2WTH($fields);
            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{ "WTH":"please input user name" }':
            $jsonMsg = '{
                "type": "template",
                "altText": "this is a buttons template",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "เป็น MD Prescriber",
                            "text": "MD Prescriber",
                            "data": "occupation,1"
                        },
                        {
                            "type": "postback",
                            "label": "เป็น Lay Prescriber",
                            "text": "Lay Prescriber",
                            "data": "occupation,2"
                        },
                        {
                            "type": "postback",
                            "label": "เป็นผู้เคยรับยา Homeo",
                            "text": "Homeo user",
                            "data": "occupation,3"
                        }
                        ],
                        "title": "ประสบการณ์ของท่านกับ Homeopathy",
                        "text": "ท่านจะถูกระบุเป็น '.$text.'"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH": "name as Anonymous" }', 
            "me"=>$me);
            post2WTH($fields); */

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('name'=>$text),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case array_key_exists('name', json_decode($lastMsg,true)):
            $Uname = json_decode($lastMsg,true)['name'];
            
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                กรุณาแจ้งเบอร์โทรศัพท์

                กรณีของการติดต่อกลับมีได้ 3 กรณี
                1) เพื่อยืนยันความน่าเชื่อถือของข้อมูลที่ท่านให้มา
                2) เพื่อติดตามผลการใช้ยา Homeopathy เชิงการวิจัย เพื่อพัฒนา
                3) เพื่อประสานงานเครือข่าย เช่น ให้ไปรับ Homeo ใกล้บ้าน

                หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
                หากยินดีให้ติดต่อ กรุณาพิมพ์เบอร์โทรศํพท์ของท่าน 
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user tel" }', 
            "me"=>$me);
            post2WTH($fields);
            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{ "WTH":"please input user tel" }':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                ทาง Email

                กรณีของการติดต่อกลับมีได้ 4 กรณี
                1) เพื่อยืนยันความน่าเชื่อถือของข้อมูลที่ท่านให้มา
                2) เพื่อติดตามผลการใช้ยา Homeopathy เชิงการวิจัย เพื่อพัฒนา
                3) เพื่อประสานงานเครือข่าย เช่น ให้ไปรับ Homeo ใกล้บ้าน
                4) เพื่อแจ้งข่าวสาร ที่อาจมีมาจากสมาคม และชมรมต่างๆ ของ Homeopathy

                หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
                หากยินดีให้ติดต่อ กรุณาพิมพ์ Email ของท่าน 
                ',
            ];
            
            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "tel":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user email" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{ "WTH":"please input user email" }':
            $jsonMsg = '{
                "type": "template",
                "altText": "this is a buttons template",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "ดีมาก",
                            "text": "Very good",
                            "data": "evaluation,5"
                        },
                        {
                            "type": "postback",
                            "label": "ดี",
                            "text": "Good",
                            "data": "evaluation,4"
                        },
                        {
                            "type": "postback",
                            "label": "เฉยๆ",
                            "text": "Neutral",
                            "data": "evaluation,3"
                        },
                        {
                            "type": "postback",
                            "label": "แย่",
                            "text": "Bad",
                            "data": "evaluation,2"
                        },
                        {
                            "type": "postback",
                            "label": "แย่มาก",
                            "text": "Very bad",
                            "data": "evaluation,1"
                        }
                        ],
                        "title": "ความรู้สึกหลังใช้ Homeo",
                        "text": "แขร์ประสบการณ์ของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);
            
            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "email":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please eval Homeo" }', 
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