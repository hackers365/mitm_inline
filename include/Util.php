<?php

class Util {
    const LOG_TIME = 10;
    public static function request($url, $data = array(), $header = array()) {
        if($data) {
            foreach($data as $k=>$v) {
                $tmp[] = "$k=".urlencode($v);
            }

            $pre = strpos($url, '?') === false ? '?' : '&';
            $url .= $pre . implode('&', $tmp);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        if($header)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116');
        $result = curl_exec($ch);

        if($no = curl_errno($ch)) {

            $error = curl_error($ch);
            curl_close($ch);

            throw new \Exception($error , $no);
        }

        #$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        $curl_info = curl_getinfo($ch);

        curl_close($ch);

        return $result ? json_decode($result) : array();
    }

    /*
     public static function request($url) {
         $ch = curl_init();

         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_HEADER, false);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $response = curl_exec($ch);
         curl_close($ch);
         return $response;
     }
    */

    public static function post($url, $param, $header = array()) {
         //print_r($header); echo $param; die('ok');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($header)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116');

        $result = curl_exec($ch);
        if($no = curl_errno($ch)) {

            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception($error, $no);
        }

        #$total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        $curl_info = curl_getinfo($ch);
        if( $curl_info['total_time'] > self::LOG_TIME) {
            self::slowLog($url, $param, $curl_info);
        }

        curl_close($ch);

        return $result;
    }

    public static function slowLog($url, $param, $curl_info) {
        ;
    }

    public static function gbk2utf8($content) {
        return mb_convert_encoding($content, 'UTF-8', "GBK");
    }

}
