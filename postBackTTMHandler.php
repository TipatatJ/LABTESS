<?php
    $postData = $event['postback']['data'];

    switch(true){
        case $postData == 'occupation,1':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' เป็น แพทย์, เภสัช ที่มีใบประกอบวิชาชีพ กรุณากรอกเลข ว. หรือรหัสประจำตัวตามวิชาชีพ เพื่อให้ทีมงานสามารถยืนยันความเป็นแพทย์ของท่านได้จริง

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของท่านจะขึ้นให้ ผู้ใช้ Platform ค้นหาแผนไทย ใกล้บ้านได้ เพื่อไปรับบริการจากท่าน

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
                ถ้าคุณ '.$Uname.' เป็น แพทย์แผนไทย ที่มีใบประกอบวิชาชีพ กรุณากรอกเลข พท. หรือรหัสประจำตัวตามวิชาชีพ เพื่อให้ทีมงานสามารถยืนยันความเป็นแพทย์ของท่านได้จริง

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของท่านจะขึ้นให้ ผู้ใช้ Platform ค้นหาบริการแพทย์แผนไทย ใกล้บ้านได้ เพื่อไปรับบริการจากท่าน

                พิมพ์ X เพื่อซ่อนค่า
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'regist TTM doctor id'),JSON_UNESCAPED_UNICODE), 
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
                ถ้าคุณ '.$Uname.' เคยได้รับการรักษาด้วย TTM
                
                ความเห็นของคุณเป็นสิ่งที่มีคุณค่ามาก กรุณาบอกเราสักนิดว่า คุณได้รับการรักษา TTM จากที่ไหน และมีประสบการณ์การใช้ TTM มาแล้วกี่ปี

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของคุณ และความเห็นจะขึ้นแสดงใน TTM Map เพื่อเป็นข้อมูลให้คนที่ยังไม่รู้จัก TTM สามารถเห็นประสบการณ์การใช้ TTM ในคนไทยว่ามีมากน้อยแค่ไหน

                หรือหากท่านไม่ประสงค์จะให้คนจาก Platform TTM Map ค้นหาท่านเจอ
                
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
        case $postData == 'wechagum,1' || $postData == 'wechagum,2':
            $Uname = json_decode($lastMsg,true)['name'];
            
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "มีใบเภสัชกรรม",
                            "text": "serve herb",
                            "data": "pecahagum,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มี",
                            "text": "no herb",
                            "data": "pecahagum,2"
                        }
                        ],
                        "title": "มีใบเภสัชกรรม แผนไทย?",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);
 
            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('wechagum'=>$postData),JSON_UNESCAPED_UNICODE), 
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
        case $postData == 'pecahagum,1' || $postData == 'pecahagum,2':
            $Uname = json_decode($lastMsg,true)['name'];
            
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "มีใบแผนไทยประยุกต์",
                            "text": "serve apply ttm",
                            "data": "apply ttm,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มี",
                            "text": "no apply ttm",
                            "data": "apply ttm,2"
                        }
                        ],
                        "title": "มีใบแผนไทยประยุกต์?",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('pechagum'=>$postData),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'serve apply ttm'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'apply ttm,1' || $postData == 'apply ttm,2':
            $Uname = json_decode($lastMsg,true)['name'];
            
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "มีใบผดุงครรภ์",
                            "text": "serve labour",
                            "data": "labour,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มี",
                            "text": "no labour",
                            "data": "labour,2"
                        }
                        ],
                        "title": "มีใบผดุงครรภ์?",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('apply ttm'=>$postData),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'serve labour'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'labour,1' || $postData == 'labour,2':
            $Uname = json_decode($lastMsg,true)['name'];
            
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "มีใบนวดไทย",
                            "text": "serve massage",
                            "data": "massage,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มี",
                            "text": "no massage",
                            "data": "massage,2"
                        }
                        ],
                        "title": "มีใบนวดแผนไทย?",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('THlabour'=>$postData),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'serve massage'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'massage,1' || $postData == 'massage,2':
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
            "txt"=>json_encode(array('THmassage'=>$postData),JSON_UNESCAPED_UNICODE), 
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
                ประโยคสั้นๆ เกี่ยวกับบทบาทแพทย์แผนไทยของท่าน 
                ในการทำงานด่านหน้า COVID 
                (ซึ่งจะเห็นได้ชัด เป็น Caption บน Graphic แผนที่ของเรา)
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
            "txt"=>json_encode(array('WTH'=>'user TTM caption'),JSON_UNESCAPED_UNICODE), 
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