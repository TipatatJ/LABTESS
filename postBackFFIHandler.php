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
            "txt"=>json_encode(array('SupplyType'=>$postData),JSON_UNESCAPED_UNICODE), 
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
                        "text": "I Brand",
                        "label": "I Brand"
                        },
                        {
                        "type": "message",
                        "text": "I Creat",
                        "label": "I Creat"
                        },
                        {
                        "type": "message",
                        "text": "I Photo",
                        "label": "I Photo"
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
                        "text": "I Graphic",
                        "label": "I Graphic"
                        },
                        {
                        "type": "message",
                        "text": "I Video",
                        "label": "I Video"
                        },
                        {
                        "type": "message",
                        "text": "I Plan",
                        "label": "I Plan"
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

            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "Reviewer",
                    "text": "on media",
                    "actions": [
                        {
                        "type": "message",
                        "text": "I Blog",
                        "label": "I Blog"
                        },
                        {
                        "type": "message",
                        "text": "I Youtube",
                        "label": "I Youtube"
                        },
                        {
                        "type": "message",
                        "text": "I Live",
                        "label": "I Live"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/Review.jpg"
                    },
                    {
                    "title": "Marketing",
                    "text": "consultant",
                    "actions": [
                        {
                        "type": "message",
                        "text": "I Commerc",
                        "label": "I Commerc"
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
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/Review.jpg"
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
            $arrPostData['messages'] = [$msg1, $msg2];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"my Media specialist" }', 
            "me"=>$me);
            post2WTH($fields);
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
