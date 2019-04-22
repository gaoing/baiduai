<?php

  class HttpCurl {
    private $ch = null;
    private $headers = array();
    private $proxy = null;
    private $timeout = 10;
    private $httpParams = null;

    public function __construct() {
      $this -> ch = curl_init();
    }

    /**
     * 设置http header
     * @param $header
     * @return $this
     */
    public function serHeader($header){
      if(is_array($header)){
        curl_setopt($this -> ch, CURLOPT_HTTPHEADER, $header);
      }
      return $this;
    }

    /**
     * 设置超时
     * @param $time
     * @return $this
     */
    public function setTimeout($time){
      //设置时间不能小于等于0
      if($time <= 0){
        $time = 10;
      }
      //设置一个秒的数量就可以
      curl_setopt($this -> ch, CURLOPT_TIMEOUT, $time);
      return $this;
    }

    /**
     * 设置代理
     * @param string $proxy
     * @return $this
     */
    public function setProxy($proxy){
      if($proxy){
        curl_setopt($this -> ch, CURLOPT_PROXY, $proxy);
      }
      return $this;
    }

    /**
     * 设置代理端口
     * @param string $port
     * @return $this
     */
    public function setProxyPort($port) {
      if(is_int($port)){
          curl_setopt($this -> ch, CURLOPT_PROXYPORT, $port);
      }
      return $this;
    }

    /**
     * 设置页面来源
     * @param string $referer
     * @return $this
     */
    public function setReferer($referer = ''){
      if(!empty($referer)){
        curl_setopt($this -> ch, CURLOPT_REFERER, $referer);
      }
      return $this;
    }

    /**
     * 设置用户代理
     * @param string $agent
     * @return $this
     */
    public function setUserAgent($agent = "") {
      if ($agent) {
          // 模拟用户使用的浏览器
          curl_setopt($this->ch, CURLOPT_USERAGENT, $agent);
      }
      return $this;
    }

    /**
     * http响应中是否显示header，1表示显示
     * @param $show
     * @return $this
     */
    public function showResponseHeader($show) {
        curl_setopt($this->ch, CURLOPT_HEADER, $show);
        return $this;
    }

    /**
     * 设置http请求的参数,get或post
     * @param array $params
     * @return $this
     */
    public function setParams($params) {
        $this->httpParams = $params;
        return $this;
    }

    /**
     * 设置证书路径
     * @param $file
     */
    public function setCainfo($file){
      curl_setopt($this -> ch, CURLOPT_CAINFO, $file);
    }

    /**
     * get请求
     * @param string $url
     * @param string $dataType
     * @return bool|mixed
     */
    public function get($url, $dataType = 'json') {
      if(stripos($url, 'https://') !== FALSE){
        curl_setopt($this -> ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this -> ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this -> ch, CURLOPT_SSLVERSION, 1);
      }
      if(!empty($this -> httpParams) && is_array($this -> httpParams)){
        if(strpos($url, '?') !== false){
          $url .= http_build_query($this -> httpParams); 
        }else{
          $url .= '?' .http_build_query($this -> httpParams);
        }
      }
      curl_setopt($this -> ch, CURLOPT_URL, $url);
      curl_setopt($this -> ch, CURLOPT_RETURNTRANSFER, 1);
      $content = curl_exec($this -> ch);
      $status = curl_getinfo($this -> ch);
      curl_close($this -> ch);
      if(isset($status['http_code']) && $status['http_code'] == 200){
        if($dataType == 'json'){
          $content = json_decode($content, true);
        }
        return $content;
      } else {
        return FALSE;
      }
    }

    /**
     * post请求
     * @param string $url 
     * @param array $fields
     * @param string $dataType
     * @return mixed
     */
    public function post($url, $dataType = 'json') {
      if(stripos($url, 'https://') !== FALSE) {
        curl_setopt($this -> ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($this -> ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($this -> ch, CURLOPT_SSLVERSION, 1);
      }
      curl_setopt($this -> ch, CURLOPT_URL, $url);
      //设置post body
      if(empty($this -> httpParams)){
        if(is_array($this -> httpParams)){
          curl_setopt($this -> ch, CURLOPT_POSTFIELDS, http_build_query($this -> httpParams));
        } else if(is_string($this -> httpParams)){
          curl_setopt($this -> ch, CURLOPT_POSTFIELDS, $this -> httpParams);
        }
      }
      //end设置post body
      curl_setopt($this -> ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($this -> ch, CURLOPT_POST, true);
      $content = curl_exec($this -> ch);
      $status = curl_getinfo($this -> ch);
      curl_close($this -> ch);
      if(isset($status['http_code']) && $status['http_code'] == 200){
        if($dataType == 'json'){
          $content = json_decode($content, true);
        }
        return $content;
      } else {
        return FALSE;
      }
    }
  }  

 
  function request_post($url = '', $param = '', $callback = ''){
    if (empty($url) || empty($param)) {
        return false;
    }
    $postUrl = $url;
    $curlPost = $param;
    // 初始化curl
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $postUrl);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // post提交方式
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    // 运行curl
    $data = curl_exec($curl);
    curl_close($curl);
    $callback($data);
  }
?>