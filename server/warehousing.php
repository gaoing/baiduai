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
  function createBrief() {
    global $name,$synopsis,$image;
    $sql = 'select `brief` from `local_dishes`';
    $sqlb = 'select brief from local_dishes where name="'.$name.'"';
    $tempBrief = connectsql($sqlb);
    if($tempBrief){
      $brief = $tempBrief[0][0];
    }else{
      $briefs = connectsql($sql);
      $brief = createRander($briefs);
      $mysql = 'insert `local_dishes`(`name`,`brief`,`synopsis`,`image`) values("'.$name.'","'.$brief.'","'.$synopsis.'","'.$image.'")';
      connectsql($mysql);
    }
    return $brief;
  } 
  $brief = createBrief();
  $access_token = '24.22caf2059fcc80e2e45e45c84fab6700.2592000.1557472059.282335-15958971';
  $url = 'https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/add?access_token=' . $access_token;
  $bodys = array(
    'image' => $image,
    'sub_lib' => $sub_lib,
    'brief' => $brief
  );
  function callback($res) {
    $res = json_decode($res);
    if(property_exists($res, 'error_code')){
      echo '{"error": "item has existed"}';
      return ;
    }
    global $brief, $image;
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