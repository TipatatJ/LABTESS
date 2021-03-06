<?php
    $postData = $event['postback']['data'];

    switch(true){
        case $postData == 'occupation,1':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' เป็น แพทย์, เภสัช ที่มีใบประกอบวิชาชีพ กรุณากรอกเลข ว. หรือรหัสประจำตัวตามวิชาชีพ เพื่อให้ทีมงานสามารถยืนยันความเป็นแพทย์ของท่านได้จริง

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของท่านจะขึ้นให้ ผู้ใช้ Platform ค้นหา Homeopath ใกล้บ้านได้ เพื่อไปรับบริการจากท่าน

                หรือหากท่านไม่ประสงค์จะให้คนจาก Platform HomeoMap ค้นหาท่านเจอ ให้พิมพ์ X เพื่อปิดการค้นหา 
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
                ถ้าคุณ '.$Uname.' เคยเรียนและใช้ Homeopathy ดูแลผู้อื่น กรุณาบอกเราสักนิดว่า คุณได้เรียน Homeopathy จากที่ไหน และมีประสบการณ์การใช้ Homeopathy มาแล้วกี่ปี

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของท่านจะขึ้นให้ผู้ดูแล Platform Homeomap ค้นหา Homeopath ใกล้บ้านได้ เพื่อติดต่อพูดคุยเป็นแนวร่วมกันในการพัฒนาวงการ Homeopathy ให้ดียิ่งขึ้น

                หรือหากท่านไม่ประสงค์จะให้คนจาก Platform HomeoMap ค้นหา ท่านเจอ ให้พิมพ์ X เพื่อปิดการค้นหา 
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'regist lay prescriber experience'),JSON_UNESCAPED_UNICODE), 
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
                ถ้าคุณ '.$Uname.' เคยได้รับการรักษาด้วย Homeopathy
                
                ความเห็นของคุณเป็นสิ่งที่มีคุณค่ามาก กรุณาบอกเราสักนิดว่า คุณได้รับ Homeopathy จากที่ไหน และมีประสบการณ์การใช้ Homeopathy มาแล้วกี่ปี

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของคุณ และความเห็นจะขึ้นแสดงใน HomeoMap เพื่อเป็นข้อมูลให้คนที่ยังไม่รู้จัก Homeopathy สามารถเห็นประสบการณ์การใช้ Homeopathy ในคนไทยว่ามีมากน้อยแค่ไหน

                หรือหากท่านไม่ประสงค์จะให้คนจาก Platform HomeoMap ค้นหาท่านเจอ ให้พิมพ์ X เพื่อปิดการค้นหา 
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
        case $postData == 'eval,3' || $postData == 'eval,2' || $postData == 'eval,1' || $postData == 'eval,X':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ประโยคสั้นๆ ที่ท่านอยากบอกเกี่ยวกับ Homeopathy (ซึ่งจะเห็นได้ชัด เป็น Caption บน Graphic แผนที่ของเรา)
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
            "txt"=>json_encode(array('WTH'=>'user homeo caption'),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == '{ "WTH":"please input user name" }':
            $jsonMsg = '{
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
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
                            "label": "คนทั่วไปที่สั่งยา Homeo",
                            "text": "Non MD prescriber",
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