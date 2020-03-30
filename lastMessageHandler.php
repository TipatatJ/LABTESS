<?php

    switch($lasrMsg){

        case 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว':
            $jsonMsg = '{
                "type": "template",
                "altText": "this is a buttons template",
                    "template": {
                        "type": "buttons",
                        "actions": [
                        {
                            "type": "postback",
                            "label": "คำแนำนำสำหรับคนปกติ",
                            "text": "PMgeneralAdvise",
                            "data": "PMgeneralAdvise,'.$text['pm2.5'].'"
                        },
                        {
                            "type": "postback",
                            "label": "คำแนะนำคนมีความเสี่ยง",
                            "text": "PMriskAdvise",
                            "data": "PMriskAdvise,'.$text['pm2.5'].'"
                        }
                        ],
                        "title": "สภาพอากาศพื้นที่ใกล้เคียง",
                        "text": "มีปริมาณ PM2.5 ที่ระดับ '.$text['pm2.5'].' mcg/m3"
                    }
                }';

                $messages = json_decode($jsonMsg, true);
                
            break;
        case 'ฉันพร้อมแสดงตัว เพื่อสนับสนุน Homeopathy':

            break;
    }

?>