<?php
    $mapId = MD5($userId.SECRET_KEY);
    $defaultMsg = ' 
                ขอบคุณสำหรับข้อมูลประสบการณ์การใช้ การรักษแพทย์แผนจีนของท่าน

                ไปดูความเห็นของท่านบนแผนที่กันเลยที่ Link นี้
                https://homeo-map.herokuapp.com/infoMap/include/TCMmap.php?mapId='.$mapId.'

                ท่านสามารถเปลี่ยนแปลงข้อมูลของท่านได้ตลอด ด้วยการเริ่มกรอกข้อมูลใหม่ตั้งแต่ต้นที่ ริชเมนู
                หมายเหตุ:
                - ระบบจะจดจำข้อมูลหลังสุดที่ท่านกรอกเท่านั้น
                - ท่านเลือกเป็นได้แค่ MD trained TCM, TCM doctor, User อย่างใดอย่างหนึ่ง
                - ข้อมูลของ Anonymous จะถูกกลั่นกรองว่ามีความน่าเชื่อถือน้อยกว่าข้อมูลที่เจ้าของแสดงตัวตน
                - ด้วยเหตุผลทางกฏหมาย ระบบจะไม่อนุญาตให้มีการติดต่อ MD ที่ไม่มีเลข ว. หรือไม่แสดงตัวตน เพื่อกันปัญหาการแอบอ้างเป็นแพทย์
                
                หมายเหตุเพิมเติม:
                - หากท่านมีข้อเสนออื่นใด ทางทีมงานยินดีรับฟัง ด้วยการพิมพ์คำว่า "ข้อเสนอแนะ" แล้วทางทีมงานจะนำข้อเสนอนั้นไปพิจารณาปรับปรุง Platform TCM Map อย่างต่อเนื่องต่อไป
                - หากท่านมีข้อร้องเรียน เกี่ยวกับข้อมูลที่ไม่ถูกต้อง กรุณาพิมพ์ "ร้องเรียน" เพื่อให้ทีมงานได้รับทราบ เพื่อพิจารณาแก้ไขข้อมูลให้ตรงตามความเป็นจริงต่อไป
                - หากอยากทราบรายละเอียดเกี่ยวกับทีมงาน กรุณาพิมพ์ "About Us" เพื่อรู้จักเราให้มากขึ้น

                ขอบคุณจากใจ
                หมอปอง & ทีมงาน TCM Map
                ';

    switch(true){
        case $lastMsg == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว' || $text == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ชื่อของท่านจะถูกระบุเป็น Anonymous

                กรุณาระบุประสบการณ์ของท่านกับ Homeopathy
                ',
            ];

            /* $jsonMsg = '{
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
                        "text": "ท่านจะถูกระบุเป็น Anonymous"
                    }
                }'; */

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
        //##############################################
        case $lastMsg == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว' || $text == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว':
/*             $messages = [
                'type' => 'text',
                'text' => ' 
                ชื่อของท่านจะถูกระบุเป็น Anonymous

                กรุณาระบุประสบการณ์ของท่านกับ TCM
                ',
            ];
 */
            $jsonMsg = '{
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "MD Trained TCM",
                            "text": "MD Prescriber",
                            "data": "occupation,1"
                        },
                        {
                            "type": "postback",
                            "label": "พจ. TCM doctor",
                            "text": "TCM doctor",
                            "data": "occupation,2"
                        },
                        {
                            "type": "postback",
                            "label": "เคยรับการรักษา TCM",
                            "text": "TCM user",
                            "data": "occupation,3"
                        }
                        ],
                        "title": "ประสบการณ์กับ TCM",
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
        case $lastMsg == 'ฉันพร้อมแสดงตัว เพื่อสนับสนุน TCM' || $text == 'ฉันพร้อมแสดงตัว เพื่อสนับสนุน TCM':
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
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "MD Trained TCM",
                            "text": "MD Prescriber",
                            "data": "occupation,1"
                        },
                        {
                            "type": "postback",
                            "label": "พจ. TCM doctor",
                            "text": "TCM doctor",
                            "data": "occupation,2"
                        },
                        {
                            "type": "postback",
                            "label": "เคยรับการรักษา TCM",
                            "text": "TCM user",
                            "data": "occupation,3"
                        }
                        ],
                        "title": "ประสบการณ์กับ TCM",
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
        case $lastMsg == '{"WTH":"regist medical license id"}':
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "ฝังเข็มได้",
                            "text": "serve acup",
                            "data": "acup,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มีบริการฝังเข็ม",
                            "text": "no acup",
                            "data": "acup,2"
                        }
                        ],
                        "title": "บริการฝังเข็มหรือไม่",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "license id":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user tel" }', 
            "me"=>$me);
            post2WTH($fields); */

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{"WTH":"regist TCM doctor id"}':
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "ฝังเข็มได้",
                            "text": "serve acup",
                            "data": "acup,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มีบริการฝังเข็ม",
                            "text": "no acup",
                            "data": "acup,2"
                        }
                        ],
                        "title": "บริการฝังเข็มหรือไม่",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "tcm id":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user tel" }', 
            "me"=>$me);
            post2WTH($fields); */

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{"WTH":"serve acup"}':
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "สั่งสมุนไพรจีนได้",
                            "text": "serve herb",
                            "data": "herb,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มีบริการสมุนไพรจีน",
                            "text": "no herb",
                            "data": "herb,2"
                        }
                        ],
                        "title": "สั่งยาสมุนไพรได้หรือไม่",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "tcm id":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields); */

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user tel" }', 
            "me"=>$me);
            post2WTH($fields); */

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{"WTH":"serve herb"}':
            $jsonMsg = '{ 
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "ให้บริการนวดได้",
                            "text": "serve herb",
                            "data": "tuina,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่มีบริการนวด",
                            "text": "no herb",
                            "data": "tuina,2"
                        }
                        ],
                        "title": "บริการ Tuina หรือไม่",
                        "text": "เป็นการระบุบริการของท่าน"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "tcm id":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields); */

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user tel" }', 
            "me"=>$me);
            post2WTH($fields); */

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{"WTH":"serve tuina"}':
            $Uname = json_decode($lastMsg,true)['name'];
            
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                กรุณาแจ้งเบอร์โทรศัพท์

                หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
                ',
            ];
            
            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "license id":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields); */

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user tel" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{"WTH":"use case experience"}':
            $Uname = json_decode($lastMsg,true)['name'];
            
            /* $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                กรุณาแจ้งเบอร์โทรศัพท์

                กรณีของการติดต่อกลับมีได้ 2 กรณี
                1) เพื่อยืนยันความน่าเชื่อถือของข้อมูลที่ท่านให้มา
                2) เพื่อติดตามผลการใช้ยา Homeopathy เชิงการวิจัย เพื่อพัฒนา

                หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
                หากยินดีให้ติดต่อ กรุณาพิมพ์เบอร์โทรศํพท์ของท่าน 
                ',
            ]; */

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
            "txt"=>'{ "user exp":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user tel" }', 
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
        case $lastMsg == '{ "WTH":"please input user tel" }':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                ทาง Email

                หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
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
        case $lastMsg == '{ "WTH":"please input user email xxx" }':
        $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                ทาง LINE

                หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
                ',
            ];
            
            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "email":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user Line ID" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{ "WTH":"please input user Line ID xxx" }':
        $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' อยากให้ทีมงานติดต่อกลับได้
                ทาง Facebook

                หากไม่อยากรับการติดต่อ กรุณาพิมพ์ X
                ',
            ];
            
            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "Line ID":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input user FB" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        //LONG INPUT case $lastMsg == '{ "WTH":"please input user FB" }':
        case $lastMsg == '{ "WTH":"please input user email" }':
            $jsonMsg = '{
                "type": "template",
                "altText": "ระบบยังไม่รองรับ LINE DESKTOP กรุณาใช้ LINE APP บนมือถือ",
                    "template": {
                        "type": "buttons",
                        "actions": [

                        {
                            "type": "postback",
                            "label": "ดี",
                            "text": "Good",
                            "data": "eval,3"
                        },
                        {
                            "type": "postback",
                            "label": "เฉยๆ",
                            "text": "Neutral",
                            "data": "eval,2"
                        },
                        {
                            "type": "postback",
                            "label": "แย่",
                            "text": "Bad",
                            "data": "eval,1"
                        },
                        {
                            "type": "postback",
                            "label": "ไม่เคยใช้ Homeo",
                            "text": "no exp",
                            "data": "eval,X"
                        }
                        ],
                        "title": "ความรู้สึกหลังได้ใช้ Homeo",
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
        case $lastMsg == '{"WTH":"user homeo caption"}':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ขั้นตอนสุดท้ายแล้ว

                ทางเราขอให้ท่านกดแชร์ตำแหน่ง Location ของท่านให้เรา เพื่อทำให้ระบบสามารถ Plot ข้อมูลที่ท่านให้มาลงบนแผนที่ และแสดงสิ่งที่ท่านได้

                ท่านจำเป็นต้องแชร์ตำแหน่งปัจจุบันของท่าน เพื่อให้มีการแสดงผลใน platform HomeoMap
                ',
            ];
            
            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "user homeo caption":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please share your location" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $text == '{"WTH":"MyLocation"}':
            $messages = $defaultMsg;

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "user homeo caption":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please share your location" }', 
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
        case $lastMsg == '{ "WTH":"LocationShare" }':
            if($userId != $me){
                $message = $defaultMsg;
            }
            else{
        
                $messages = [
                    'type' => 'text',
                    'text' => $lastMsg." is your last message
                    
                    "."$text ($userId)"."
                    ".$defaultMsg,
                ];
            }
        default:
            if($userId != $me){
                $message = $defaultMsg;
            }
            else{
        
                $messages = [
                    'type' => 'text',
                    'text' => $lastMsg." is your last message
                    
                    "."$text ($userId)"."
                    ".$defaultMsg,
                ];
            }



            break;            
    }

?>