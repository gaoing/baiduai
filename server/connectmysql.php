<?php
  function connectsql($mysql){
    $servename = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'baiduai';

    $mySQL = new mysqli($servename, $username, $password, $dbname);
    mysqli_set_charset($mySQL, 'utf8');
    if($mySQL -> connect_error){
      die('数据库连接失败:' . $mySQL -> connect_error);
    }

    $sql = $mysql;

    $res = $mySQL -> query($sql);

    if(gettype($res) == 'object' || gettype($res) == 'array' ){
      $data = $res -> fetch_all();
    }else{
      $data = $res;
    }
    $mySQL -> close();
    return $data;
  }

  // $mysql = "select brief from local_dishes where name='番茄炒蛋'";
  // $cedata = connectsql($mysql);
  // var_dump($cedata);
?>