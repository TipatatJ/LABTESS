<?php 
  $access_token = 'rlGsU0aqqoe8RO0VgHOB46l7vcJDjSHuDf4qaLXobD1veApbbIUtgG5D0UeywJOAelIncWkrv9+2CIte40Fs0pqK/Giz7c5BjsID45ZKXtpvsf0phX0OSo5EGFHO5fJzivZeY11NaPBWLVOOBHt4OAdB04t89/1O/w1cDnyilFU=';
  $url = 'https://api.line.me/v1/oauth/verify';
  $headers = array('Authorization: Bearer ' . $access_token);
  $ch = curl_init($url);curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);curl_close($ch);
  echo $result;
?>
