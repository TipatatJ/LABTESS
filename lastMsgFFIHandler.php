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
        case $text == 'Share location ของคุณเพื่อปักหมุดว่าคุณคือใคร':
            $jsonMsg = '{
            "type": "image",
            "originalContentUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/ShareLocate.jpg",
            "previewImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/ShareLocate.jpg",
            "animated": false
            }';

            $msg1 = json_decode($jsonMsg, true);

            $arrPostData = array();
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'] = [$msg1];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'NEW INPUT2', 
            "me"=>$me);
            post2WTH($fields);
            exit;
            break;
        case $text == 'Share location ของคุณ เพื่อคนหา Network':
            $jsonMsg = '{
            "type": "image",
            "originalContentUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/ShareLocate.jpg",
            "previewImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/ShareLocate.jpg",
            "animated": false
            }';

            $msg1 = json_decode($jsonMsg, true);

            $arrPostData = array();
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'] = [$msg1];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'SEARCH FOR NETWORK', 
            "me"=>$me);
            post2WTH($fields);
            exit;
            break;
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
            "txt"=>'{ "WTH":"choose Marketing consultant" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
        case $text == 'Reviewer':
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
                        "text": "Blogger",
                        "label": "Blogger"
                        },
                        {
                        "type": "message",
                        "text": "Youtuber",
                        "label": "Youtuber"
                        },
                        {
                        "type": "message",
                        "text": "Live video",
                        "label": "Live video"
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
                        "text": "Commercial",
                        "label": "Commercial"
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
            "txt"=>'{ "WTH":"choose Reviewer type" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
        case $lastMsg == '{ "WTH":"please input user name" }':
            $jsonMsg = '{

                "type": "text",
                "text": "โปรดเลือกว่าคุณทำอะไรในวงการอาหาร"
            }';
            $msg0 = json_decode($jsonMsg, true);
            //##########################################

            

            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "คุณทำอะไร",
                    "text": "เลือกประเภท",
                    "type": "buttons",
                    "actions": [
                        {
                        "type": "postback",
                        "text": "I Supply",
                        "label": "I Supply",
                        "data": "I Supply"
                        },
                        {
                        "type": "postback",
                        "text": "I Media",
                        "label": "I Media",
                        "data": "I Media"
                        },
                        {
                        "type": "message",
                        "text": "I Serve",
                        "label": "I Serve",
                        "data": "I Serve"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/chain.png"
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
            $arrPostData['messages'] = [$msg0, $msg2];
            //$arrPostData['messages'][0]['type'] = "text";
            //$arrPostData['messages'][0]['text'] = '$messages';

            multiMsg($access_token, $replyToken, $arrPostData);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "name":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"tell who I am (1)" }', 
            "me"=>$me);
            post2WTH($fields);
            exit;
        case $text == 'I Serve':
            //$Uname = json_decode($lastMsg,true)['name'];
            
            $jsonMsg = '{
                "template": {
                "type": "carousel",
                "actions": [],
                "columns": [
                    {
                    "title": "STREET FOOD",
                    "text": "shop representative",
                    "actions": [
                        {
                        "type": "message",
                        "text": "I Street",
                        "label": "I Street"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/StreetFood.jpg"
                    },
                    {
                    "title": "RESTAURANT",
                    "text": "shop representative",
                    "actions": [
                        {
                        "type": "message",
                        "text": "I Rest.",
                        "label": "I Rest."
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/Restaurant.jpg"
                    },
                    {
                    "title": "FINE DINING",
                    "text": "shop representative",
                    "actions": [
                        {
                        "type": "message",
                        "text": "I FineDine",
                        "label": "I FineDine"
                        }
                    ],
                    "thumbnailImageUrl": "https://www.venitaclinic.com/LABTESS/infoMap/images/FineDining.jpg"
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
            "txt"=>'{ "WTH":"I cook & serve" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"Restautant type" }', 
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
        case $lastMsg == '{ "WTH":"my Media specialist" }':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ชื่อบริษัท, Agency หรือชื่อติดต่องานโฆษณาประชาสัมพันธ์ของคุณคือ?
                ',
            ];

            switch($text){
                case 'I Brand':
                    $mediaType = 'Media,1';
                    break;
                case 'I Creat':
                    $mediaType = 'Media,2';
                    break;
                case 'I Photo':
                    $mediaType = 'Media,3';
                    break;
                case 'I Graphic':
                    $mediaType = 'Media,4';
                    break;
                case 'I Video':
                    $mediaType = 'Media,5';
                    break;
                case 'I Plan':
                    $mediaType = 'Media,6';
                    break;
                case 'I Blog':
                    $mediaType = 'Media,7';
                    break;
                case 'I Youtube':
                    $mediaType = 'Media,8';
                    break;
                case 'I Live':
                    $mediaType = 'Media,9';
                    break;
                case 'I Commerc':
                    $mediaType = 'Media,10';
                    break;
            }

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "MediaType":"'.$mediaType.'" }', 
            "me"=>$me);
            post2WTH($fields);


            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input agency name" }', 
            "me"=>$me);
            post2WTH($fields);
            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{ "WTH":"Restautant type" }':
            //$Uname = json_decode($lastMsg,true)['name'];
            
            $messages = [
                'type' => 'text',
                'text' => ' 
                ชื่อร้านอาหรของคุณคือ?
                ',
            ];

            switch($text){
                case 'I Street':
                    $foodType = 'Shop,1';
                    break;
                case 'I Rest.':
                    $foodType = 'Shop,2';
                    break;
                case 'I FineDine':
                    $foodType = 'Shop,3';
                    break;
            }

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "FoodType":"'.$foodType.'" }', 
            "me"=>$me);
            post2WTH($fields);


            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please input shop name" }', 
            "me"=>$me);
            post2WTH($fields);
            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{ "WTH":"please input shop name" }':
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
            "txt"=>'{ "ShopName":"'.$text.'" }', 
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
        case $lastMsg == '{ "WTH":"please input agency name" }':
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
            "txt"=>'{ "AgencyName":"'.$text.'" }', 
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
        case $lastMsg == '{ "WTH":"regist company name" }' || substr($lastMsg,0,15) == '{"supply_type":':
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
                        "title": "ประสบการณ์ในวงการอาหาร",
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
            "txt"=>'{ "WTH":"please eval your exp" }', 
            "me"=>$me);
            post2WTH($fields);

            justMsg($messages, $replyToken, $access_token);
            exit;
            break;
        case $lastMsg == '{"WTH":"user caption"}':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ขอบคุณที่มาร่วมเป็น่ส่วนหนึ่งในเครือข่าย FFI MAP

                 ไปดูความเห็นของท่านบนแผนที่กันเลยที่ Link นี้
                http://www.mediyaasia.com/LABTESS/infoMap/include/FFImap.php?mapId='.$mapId.'

                ท่านสามารถเปลี่ยนแปลงข้อมูลของท่านได้ตลอด ด้วยการเริ่มกรอกข้อมูลใหม่ตั้งแต่ต้นที่ ริชเมนู
                หมายเหตุ:
                - ระบบจะจดจำข้อมูลหลังสุดที่ท่านกรอกเท่านั้น

                ',
            ];
            
            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "user caption":"'.$text.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'SUM DATA', 
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
        case $lastMsg == '{ "WTH":"choose Supplier type" }' ||
            $lastMsg == '{ "WTH":"choose Marketing consultant" }' ||
            $lastMsg == '{ "WTH":"choose Reviewer type" }':
            $messages = $defaultMsg;

            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "Query for Closseset Network Member":"find location" }', 
            "me"=>$me);
            post2WTH($fields); */

            $userLL = getLastUserLocation($userId);

            $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "Lat Lng MapId":"'.$userLL.'" }', 
            "me"=>$me);
            post2WTH($fields);

            $lat = json_decode($userLL)['lat'];
            $lng = json_decode($userLL)['lng'];
            

            $officerName = getNearestFFI($lat,$lng);

            $arrPostData = array();
            $arrPostData['replyToken'] = $replyToken;
            $arrPostData['messages'] = [$msg1];
            $arrPostData['messages'][0]['type'] = "text";
            $arrPostData['messages'][0]['text'] = 'NEAREST FOOD NETWORK
            '.$officerName;

            multiMsg($access_token, $replyToken, $arrPostData);


            /* $fields = array(
            "userId"=>$userId,
            "txt"=>'{ "WTH":"please share your location" }', 
            "me"=>$me);
            post2WTH($fields); */

            //justMsg($messages, $replyToken, $access_token);
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