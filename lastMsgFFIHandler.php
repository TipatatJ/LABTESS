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
        case $text == 'Supplier':
            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "หาวัตถุดิบ(1)",
                    "text": "Raw material",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Meat supply",
                        "label": "Meat supply"
                        },
                        {
                        "type": "message",
                        "text": "Veg & Fruit",
                        "label": "Veg & Fruit"
                        },
                        {
                        "type": "message",
                        "text": "Seasoning",
                        "label": "Seasoning"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/RawMaterial.jpg"
                    },
                    {
                    "title": "หาวัตถุดิบ(2)",
                    "text": "Raw material",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Bakery supply",
                        "label": "Bakery supply"
                        },
                        {
                        "type": "message",
                        "text": "...",
                        "label": "..."
                        },
                        {
                        "type": "message",
                        "text": "...",
                        "label": "..."
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/RawMaterial.jpg"
                    }
                ]
                },
                "altText": "this is a carousel template",
                "type": "template"
            }';
            $msg1 = json_decode($jsonMsg, true);
            //##########################################

            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "Pre - Post processing",
                    "text": "equipment",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Equipment",
                        "label": "Equipmnet"
                        },
                        {
                        "type": "message",
                        "text": "Packaging",
                        "label": "Packaging"
                        },
                        {
                        "type": "message",
                        "text": "...",
                        "label": "..."
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/Equipment.jpg"
                    },
                    {
                    "title": "Support team",
                    "text": "teams",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Food R&D",
                        "label": "Food R&D"
                        },
                        {
                        "type": "message",
                        "text": "Logistic",
                        "label": "Logistic"
                        },
                        {
                        "type": "message",
                        "text": "...",
                        "label": "..."
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/Delivery.jpg"
                    },
                    {
                    "title": "Construction",
                    "text": "teams",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Architect",
                        "label": "Architect"
                        },
                        {
                        "type": "message",
                        "text": "Interior",
                        "label": "Interior"
                        },
                        {
                        "type": "message",
                        "text": "บ.รับเหมา",
                        "label": "Contractor"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/Contractor.jpg"
                    }
                ]
                },
                "altText": "this is a carousel template",
                "type": "template"
            }';
            $msg2 = json_decode($jsonMsg, true);
            //##########################################
             

            $arrPostData = array();
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'] = [$msg1,$msg2];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"choose Supplier type" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
            break;
        case $text == 'Media':
            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "Marketing",
                    "text": "consultant",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Branding",
                        "label": "Branding"
                        },
                        {
                        "type": "message",
                        "text": "Creative",
                        "label": "Creative"
                        },
                        {
                        "type": "message",
                        "text": "Photograph",
                        "label": "Photograph"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/FoodShot.jpg"
                    }
                ]
                },
                "altText": "this is a carousel template",
                "type": "template"
            }';
            $msg1 = json_decode($jsonMsg, true);
            //##########################################
             

            $arrPostData = array();
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'] = [$msg1, $msg2];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"choose Food Vs Supplier" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
        case $lastMsg == 'ฉันทำงานร้านยาจีน' || $text == 'ฉันทำงานร้านยาจีน':

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
        case $lastMsg == 'ฉันเป็นหมอจีน หรือหมอผู้รักษาด้วย TCM' || $text == 'ฉันเป็นหมอจีน หรือหมอผู้รักษาด้วย TCM':
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
                            "text": "MD TCM",
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
                            "label": "งานร้านแผนจีน",
                            "text": "TCM pharmacist",
                            "data": "occupation,3"
                        }
                        ],
                        "title": "ประสบการณ์กับ TCM",
                        "text": "ท่านจะถูกระบุเป็น '.$text.'"
                    }
                }';

            $messages = json_decode($jsonMsg, true);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('name'=>$text),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields, $lastMsg.'
            '.$text);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{"WTH":"regist medical license id"}' || $lastMsg == '{"WTH":"regist TCM doctor id"}':
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

            if($lastMsg == '{"WTH":"regist medical license id"}'){
                $fields = array(
                "userId"=>$userId,
                "txt"=>'{ "license id":"'.$text.'" }', 
                "me"=>$me);
                post2WTH($fields, $lastMsg.'
                '.$text);
            }
            else{
                $fields = array(
                "userId"=>$userId,
                "txt"=>'{ "tcm id":"'.$text.'" }', 
                "me"=>$me);
                post2WTH($fields);
            }

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"serve acup" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;

        case $lastMsg == '{"WTH":"please input user tel"}':
            $Uname = json_decode($lastMsg,true)['name'];
            
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
        case $lastMsg == '{"WTH":"TCM pharmacist"}':
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
            "txt"=>'{ "TCM pharmacist":"'.$text.'" }', 
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
            post2WTH($fields, $lastMsg.'
            '.$text);

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
            post2WTH($fields, $lastMsg.'
            '.$text);

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
                            "label": "มากกว่า 10 ปี",
                            "text": "More than 10 Yr",
                            "data": "eval,3"
                        },
                        {
                            "type": "postback",
                            "label": "5-10 ปี",
                            "text": "5-10 Yr exp",
                            "data": "eval,2"
                        },
                        {
                            "type": "postback",
                            "label": "น้อยกว่า 5 ปี",
                            "text": "Less than 5 Yr",
                            "data": "eval,1"
                        },
                        {
                            "type": "postback",
                            "label": "เพิ่งจบใหม่",
                            "text": "no exp",
                            "data": "eval,X"
                        }
                        ],
                        "title": "ประสบการณ์ในวงการ TCM",
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
            "txt"=>'{ "WTH":"please eval TCM" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{"WTH":"user TCM clinic"}':
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
            "txt"=>'{ "user TCM clinic":"'.$text.'" }', 
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
            "txt"=>'{ "user TCM clinic":"'.$text.'" }', 
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
                $message = '>>'.$defaultMsg.'<<';
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