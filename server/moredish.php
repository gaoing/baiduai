<?php
  include './connectmysql.php';
  include './request.php';
  $name = $_POST['name'];
  $image = str_replace('data:image/png;base64,', '', $_POST['image']);
  $sub_lib = 15958971;
  /**
   *  查找对应的brief参数 
   **/ 
  function searchBrief(){
    global $name;
    $sql = 'select brief from local_dishes where name="'.$name.'"';
    $brief = connectsql($sql);
    return $brief[0][0];
  }
  $brief = searchBrief();
  $access_token = '24.6a427622b01059c60a15e8e6b1d3a196.2592000.1558515898.282335-15958971';
  $url = 'https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/add?access_token=' . $access_token;
  $bodys = array(
    'image' => $image,
    'sub_lib' => $sub_lib,
    'brief' => $brief
  );
  function callback($res) {
    var_dump($res);
  }
  request_post($url, $bodys, 'callback');
?>