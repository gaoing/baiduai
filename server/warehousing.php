<?php
  include './request.php';
  include './connectmysql.php';
  $image = str_replace('data:image/png;base64,', '', $_POST['image']);
  $name =  $_POST['name'];
  $synopsis = $_POST['synopsis'];
  $sub_lib = 15958971;
  $brief = null;
  // var_dump($name);
  /**
   *  产生 @param brief 关联本地数据库的字段 
   *  
   **/
  function createRander($arr){
    $num = rand('100000','999999');
    if(in_array($num, $arr)){
      createRander($arr);
    }else{
      return $num;
    }
  }
  $sql = 'select `brief` from `local_dishes`';
  $sqlb = 'select brief from local_dishes where name="'.$name.'"';
  $tempBrief = connectsql($sqlb);
  if($tempBrief){
    $brief = $tempBrief[0][0];
  }else{
    $briefs = connectsql($sql);
    $brief = createRander($briefs);
  }
  $access_token = '24.6a427622b01059c60a15e8e6b1d3a196.2592000.1558515898.282335-15958971';
  $url = 'https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/add?access_token=' . $access_token;
  $bodys = array(
    'image' => $image,
    'sub_lib' => $sub_lib,
    'brief' => $brief
  );
  function callback($res) {
    $res = json_decode($res);
    if(property_exists($res, 'error_code')){
      // echo '{"error": "item has existed"}';
      echo json_encode($res);
      return ;
    }
    global $brief, $image, $synopsis, $name, $tempBrief;
    if(!$tempBrief){
      $mysql = 'insert `local_dishes`(`name`,`brief`,`synopsis`,`image`) values("'.$name.'","'.$brief.'","'.$synopsis.'","'.$image.'")';
      connectsql($mysql);
    }
    $sqlimg = 'insert `local_imgs`(`dish_brief`,`image`,`log_id`,`cont_sign`) values("'.$brief.'","'.$image.'","'.$res->log_id.'","'.$res->cont_sign.'")';
    $result = connectsql($sqlimg);
    if($result){
      echo 'success';
    }else{
      echo 'error';
    }
  }
  request_post($url, $bodys, 'callback');
?>