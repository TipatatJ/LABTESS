<?php
    $postData = $event['postback']['data'];

    switch(true){
        case $postData == 'I Supply':
            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "ขายวัตถุดิบ(1)",
                    "text": "Raw material",
                    "actions": [
                        {
                        "type": "postback",
                        "text": "supply Meat",
                        "label": "ขายเนื้อสัตว์",
                        "data": "supply,1"
                        },
                        {
                        "type": "postback",
                        "text": "VegFruitSell",
                        "label": "ขายผัก ผลไม้",
                        "data": "supply,2"
                        },
                        {
                        "type": "postback",
                        "text": "SeasonSell",
                        "label": "มีเครื่องเทศ",
                        "data": "supply,3"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/RawMaterial.jpg"
                    },
                    {
                    "title": "ขายวัตถุดิบ(2)",
                    "text": "Raw material",
                    "actions": [
                        {
                        "type": "postback",
                        "text": "ทำ Bakery",
                        "label": "ทำ Bakery",
                        "data": "supply,4"
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
                    "type": "buttons",
                    "actions": [
                        {
                        "type": "postback",
                        "text": "SellEquip",
                        "label": "เครื่องครัว",
                        "data": "supply,5"
                        },
                        {
                        "type": "postback",
                        "text": "SellPackage",
                        "label": "SellPackage",
                        "data": "supply,6"
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
                    "type": "buttons",
                    "actions": [
                        {
                        "type": "postback",
                        "text": "รับ Food R&D",
                        "label": "รับ Food R&D",
                        "data": "supply,7"
                        },
                        {
                        "type": "postback",
                        "text": "รับ Logistic",
                        "label": "รับ Logistic",
                        "data": "supply,8"
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
                    "type": "buttons",
                    "actions": [
                        {
                        "type": "postback",
                        "text": "มี Architect",
                        "label": "มี Architect",
                        "data": "supply,9"
                        },
                        {
                        "type": "postback",
                        "text": "มี Interior",
                        "label": "มี Interior",
                        "data": "supply,10"
                        },
                        {
                        "type": "postback",
                        "text": "เป็นรับเหมา",
                        "label": "เป็นรับเหมา",
                        "data": "supply,11"
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
            "txt"=>'{ "WTH":"my Supplier type" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
            break;
        case substr($postData,0,7) == 'supply,':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ใส่ชื่อกิจการ ห้างร้าน หรือบริษัทของคุณ

                พิมพ์ X เพื่อซ่อนค่า

                (แต่ถ้าท่านมี Supply อย่างอื่น ท่านสามารถกดเลือกจากเมนูข้างบนเพิ่มอีกได้ ก่อนที่จะมากรอกชื่อกิจการเพื่อไปยังขั้นตอนถัดไป)
                ',
            ];
            //####################################################

            $arrPostData = array();
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'] = [$msg0];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('supply_type'=>$postData),JSON_UNESCAPED_UNICODE), 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"regist company name" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'I Media':
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
                    },
                    {
                    "title": "Marketing",
                    "text": "consultant",
                    "actions": [
                        {
                        "type": "message",
                        "text": "Graphic",
                        "label": "Graphic"
                        },
                        {
                        "type": "message",
                        "text": "Video man",
                        "label": "Video man"
                        },
                        {
                        "type": "message",
                        "text": "Media plan",
                        "label": "Media plan"
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
            $arrPostData['messages'] = [$msg1];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"my Media specialist" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
        
        case $postData == 'I Serve':
            $Uname = json_decode($lastMsg,true)['name'];
            
            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "เลือกประเภทร้านอาหาร",
                    "text": "Choose your food style",
                    "type": "buttons",
                    "actions": [
                        {
                        "type": "postback",
                        "text": "I Street F",
                        "label": "I Street F",
                        "data": "Shop,1"
                        },
                        {
                        "type": "postback",
                        "text": "I Restau.",
                        "label": "I Restau.",
                        "data": "Shop,2"
                        },
                        {
                        "type": "postback",
                        "text": "I FineDine",
                        "label": "I FineDine",
                        "dat": "Shop,3"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/FindFood.jpg"
                    }
                ]
                },
                "altText": "this is a carousel template",
                "type": "template"
            }';

            $messages = json_decode($jsonMsg, true);
 
            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"I cook & serve" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"Reastautant type" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $postData == 'occupation,1':
            $Uname = json_decode($lastMsg,true)['name'];
            $messages = [
                'type' => 'text',
                'text' => ' 
                ถ้าคุณ '.$Uname.' เป็น แพทย์ ที่มีใบประกอบวิชาชีพ กรุณากรอกเลข ว. หรือรหัสประจำตัวตามวิชาชีพ เพื่อให้ทีมงานสามารถยืนยันความเป็นแพทย์ของท่านได้จริง

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
                ถ้าคุณ '.$Uname.' เป็นเภสัชกร หรืองานร้านยาจีน
                
                กรุณาบอกเราสักนิดว่า ร้านยาของคุณมีประสบการณ์การใช้ TCM มาแล้วกี่ปี เน้นยากลุ่มใด เช่น ต้มยา, ยาเกล็ดผง ฯลฯ

                ประโยชน์ที่จะได้รับในกรณีนี้ก็คือ รายชื่อของคุณ และความเห็นจะขึ้นแสดงใน TCM Map เพื่อสะดวกให้ พจ. ที่จะสั่งยาสามารถติดต่อคุณได้

                หรือหากท่านไม่ประสงค์จะให้คนจาก Platform TCM Map ค้นหาท่านเจอ
                
                พิมพ์ X เพื่อปิดการค้นหา 
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>json_encode(array('WTH'=>'TCM pharmacist'),JSON_UNESCAPED_UNICODE), 
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
                คำบรรยายสั้นๆ
                ซึ่งจะเห็นได้ชัด เป็น Caption บน Graphic แผนที่ของเรา
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
            "txt"=>'{"WTH":"user caption"}', 
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
        default:
            $messages = [
                'type' => 'text',
                'text' => ' 
                ???
                '.$postData,
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "???":"'.$postData.'" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
            break;           
    }

?>
