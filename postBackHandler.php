<?php
    $postData = $event['postback']['data'];

    switch(true){
        case $postData == 'occupation,1':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' เป็น แพทย์, เภสัช ที่มีใบประกอบวิชาชีพ
                กรุณากรอกเลข ว. หรือรหัสประจำตัวตามวิชาชีพ เพื่อให้
                ทีมงานสามารถยืนยันความเป็นแพทย์ของท่านได้จริง

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของท่านจะขึ้นให้
                ผู้ใช้ Platform ค้นหา Homeopath ใกล้บ้านได้ เพื่อไปรับบริการ
                จากท่าน

                หรือหากท่านไม่ประสงค์จะให้คนจาก Platform HomeoMap ค้นหา
                ท่านเจอ ให้พิมพ์ X เพื่อปิดการค้นหา 
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
                ถ้าคุณ '.$Uname.' เคยเรียนและใช้ Homeopathy ดูแลผู้อื่น
                กรุณาบอกเราสักนิดว่า คุณได้เรียน Homeopathy จากที่ไหน
                และมีประสบการณ์การใช้ Homeopathy มาแล้วกี่ปี

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของท่านจะขึ้นให้
                ผู้ดูแล Platform Homeomap ค้นหา Homeopath ใกล้บ้านได้ เพื่อ
                ติดต่อพูดคุยเป็นแนวร่วมกันในการพัฒนาวงการ Homeopathy
                ให้ดียิ่งขึ้น

                หรือหากท่านไม่ประสงค์จะให้คนจาก Platform HomeoMap ค้นหา
                ท่านเจอ ให้พิมพ์ X เพื่อปิดการค้นหา 
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
        case $postData == '{ "WTH":"please input user name" }':
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
        case array_key_exists('name', json_decode($postData,true)):
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
    }

?>