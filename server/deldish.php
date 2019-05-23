<?php
  include './request.php';
  include 'connectmysql.php';
  $brief = $_GET['brief'];
  $cont_sign = null;
  if(isset($_GET['cont_sign'])){
    $cont_sign = $_GET['cont_sign'];
  }
  $sub_lib = 15958971;
  $access_token = '24.6a427622b01059c60a15e8e6b1d3a196.2592000.1558515898.282335-15958971';
  $url = 'https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/delete?access_token=' . $access_token;
  if(!$cont_sign){
    $sql = 'select cont_sign from `local_imgs` where dish_brief="'.$brief.'"';
    $res = connectsql($sql);
    function callback($res) {};
    for($i = 0; $i < count($res); $i++){
      $bodys = array(
        'cont_sign' => $res[$i][0],
        'sub_lib' => $sub_lib
      );
      request_post($url, $bodys, 'callback');
    }
    $delsql = 'delete from `local_dishes` where brief="'.$brief.'"';
    $delssql = 'delete from `local_imgs` where dish_brief="'.$brief.'"';
    connectsql($delsql);
    connectsql($delssql);
    echo 'ok';
  }else{
    $bodys = array(
      'cont_sign' => $cont_sign,
      'sub_lib' => $sub_lib
    );
    function callback($res) {}
    request_post($url, $bodys, 'callback');
    $delssql = 'delete from `local_imgs` where cont_sign="'.$cont_sign.'"';
    connectsql($delssql);
    echo 'ok';
  }
?>