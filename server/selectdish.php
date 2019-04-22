<?php
  include 'connectmysql.php';
  $get = $_GET['brief'];
  if($get == 'all'){
    $sql = 'select name,brief,synopsis,image from `local_dishes`';
    $res = connectsql($sql);
    echo json_encode($res);
  }else{
    $sql = 'select image,cont_sign from `local_imgs` where dish_brief="'.$get.'"';
    $res = connectsql($sql);
    echo json_encode($res);
  }
  
?>