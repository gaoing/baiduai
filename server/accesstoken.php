<?php
  include 'request.php';
  //语音模块
  $speech = new stdClass();
  $speech->APP_ID = '15939009';
  $speech->API_KEY = 'PPpq9Wn2ijYj5xWu6MrfBhNd';
  $speech->SECRET_KEY = 'Wf9g4YMnTZf4NmTZ69OPdjjKFWUmKfpF';
  //图像模块
  $image = new stdClass();
  $image->APP_ID = '15958971';
  $image->API_KEY = 'DML4HYyRuH61QpsXu9plQQ8h';
  $image->SECRET_KEY = '6x4ChhkHLLGEleNlOIEtcp79OABoBSDD';

  $speechUrl = 'https://openapi.baidu.com/oauth/2.0/token';
  $speechBody = array(
    'grant_type' => 'client_credentials',
    'client_id' => $speech->API_KEY,
    'client_secret' => $speech->SECRET_KEY
  );
  function callback($data){
    $data = json_decode($data);
    var_dump($data->access_token);
  }
  request_post($speechUrl, $speechBody, 'callback');
?>