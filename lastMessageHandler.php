<?php

    switch(true){

        case $lastMsg == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว' || $text == 'ซ่อนตัวตนของฉัน เพื่อความเป็นส่วนตัว':
            $messages = [
                'type' => 'text',
                'text' => ' 
                ชื่อของท่านจะถูกระบุเป็น Anonymous
                ',
            ];

            $fields = array(
            "userId"=>$userId,
            "txt"=>'WTH name as Anonymous', 
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
            "txt"=>'WTH please input user name', 
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
        default:
            $messages = [
                'type' => 'text',
                'text' => $lastMsg." is your last message
                
                "."$text ($userId)",
            ];
            break;            
    }

?>