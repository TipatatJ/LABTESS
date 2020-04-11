<?php
    $postData = $event['postback']['data'];

    switch(true){
        case $postData == 'occupation,1':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' เป็น แพทย์, เภสัช ที่มีใบประกอบวิชาชีพ กรุณากรอกเลข ว. หรือรหัสประจำตัวตามวิชาชีพ เพื่อให้ทีมงานสามารถยืนยันความเป็นแพทย์ของท่านได้จริง

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของท่านจะขึ้นให้ ผู้ใช้ Platform ค้นหา TCM ใกล้บ้านได้ เพื่อไปรับบริการจากท่าน

                พิมพ์ X เพื่อซ่อนค่า 
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'regist medical license id'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'occupation,2':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' เป็น แพทย์แผนจีน ที่มีใบประกอบวิชาชีพ กรุณากรอกเลข พจ. หรือรหัสประจำตัวตามวิชาชีพ เพื่อให้ทีมงานสามารถยืนยันความเป็นแพทย์ของท่านได้จริง

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของท่านจะขึ้นให้ ผู้ใช้ Platform ค้นหา TCM ใกล้บ้านได้ เพื่อไปรับบริการจากท่าน

                พิมพ์ X เพื่อซ่อนค่า
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'regist TCM doctor id'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'occupation,3':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' เคยได้รับการรักษาด้วย TCM
                
                ความเห็นของคุณเป็นสิ่งที่มีคุณค่ามาก กรุณาบอกเราสักนิดว่า คุณได้รับการรักษา TCM จากที่ไหน และมีประสบการณ์การใช้ TCM มาแล้วกี่ปี

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของคุณ และความเห็นจะขึ้นแสดงใน TCM Map เพื่อเป็นข้อมูลให้คนที่ยังไม่รู้จัก TCM สามารถเห็นประสบการณ์การใช้ TCM ในคนไทยว่ามีมากน้อยแค่ไหน

                หรือหากท่านไม่ประสงค์จะให้คนจาก Platform TCM Map ค้นหาท่านเจอ
                
                พิมพ์ X เพื่อปิดการค้นหา 
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'use case experience'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'acup,1' || $postData == 'acup,2':
            $Uname = json_decode($lastMsg,true)['name'];
            
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "มีสมุนไพรจีน",
                            "text": "serve herb",
                            "data": "herb,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มี",
                            "text": "no herb",
                            "data": "herb,2"
                        }
                        ],
                        "title": "มีสมุนไพรจีน?",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);
 
            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('acup'=>$postData),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'serve herb'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'herb,1' || $postData == 'herb,2':
            $Uname = json_decode($lastMsg,true)['name'];
            
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "มี Tuina",
                            "text": "serve tuina",
                            "data": "tuina,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มี",
                            "text": "no tuina",
                            "data": "tuina,2"
                        }
                        ],
                        "title": "มีนวด Tuina?",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('herb'=>$postData),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'serve tuina'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'tuina,1' || $postData == 'tuina,2':
            $Uname = json_decode($lastMsg,true)['name'];
            
             $messages = [
                 'type' => 'text',
                 'text' => ' 
                 ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                 กรุณาแจ้งเบอร์โทรศัพท์

                 หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
                 ',
             ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('tuina'=>$postData),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'please input user tel'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'eval,3' || $postData == 'eval,2' || $postData == 'eval,1' || $postData == 'eval,X':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ประโยคสั้นๆ ที่ท่านอยากบอกเกี่ยวกับการแพทย์แผนจีน (ซึ่งจะเห็นได้ชัด เป็น Caption บน Graphic แผนที่ของเรา)
                ไม่เกิน 50 ตัวอักษร
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('eval'=>$postData),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'user TCM caption'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        
        case array_key_exists('name', json_decode($postData,true)):
            $Uname = json_decode($lastMsg,true)['name'];
            
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                กรุณาแจ้งเบอร์โทรศัพท์

                หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
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
    }

?>