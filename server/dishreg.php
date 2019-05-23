<?php
  include './request.php';
  include './connectmysql.php';
  $image = str_replace('data:image/png;base64,', '', $_POST['image']);
  $sub_lib = 15958971;
  $access_token = '24.6a427622b01059c60a15e8e6b1d3a196.2592000.1558515898.282335-15958971';
  $url = 'https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/search?access_token=' . $access_token;
  $bodys = array(
    'image' => $image,
    'sub_lib' => $sub_lib
  );
  function callback($res) {
    // echo $res;
    $res = json_decode($res);
    $resultArr = [];
    $briefArr = [];
    for($i = 0; $i < $res->result_num; $i++){
      $resultItem = new stdClass();
      $resultItem->location =  $res->result[$i]->location;
      $resultItem->brief =  $res->result[$i]->dishes[0]->brief;
      if($res->result[$i]->dishes[0]->score > 0.6){
        array_push($briefArr ,$resultItem->brief);
      }else{
        $resultItem->name = '此菜品没有入库';
      }
      array_push($resultArr, $resultItem);
    }
    if($briefArr){
      $sql = 'select brief,name from local_dishes where brief in ('. implode(',',$briefArr) .')';
      $nameArr = connectsql($sql);
    }else{
      $nameArr = [];
    }
    for($j = 0; $j < count($resultArr); $j++){
      for($k = 0; $k < count($nameArr); $k++){
        if($nameArr[$k][0] == $resultArr[$j]->brief){
          $resultArr[$j]->name = $nameArr[$k][1];
        }
      } 
    }
    echo json_encode($resultArr);
  }
  request_post($url, $bodys, 'callback');
?>