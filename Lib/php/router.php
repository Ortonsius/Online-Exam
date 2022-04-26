<?php

class ApiRoute{
    private static $default = ["data" => [],"msg" => [],"status" => ""];

    private static function parseUrl(){
        for($i = 0; $i < strlen($_SERVER["REQUEST_URI"]); $i++){
            if($_SERVER["REQUEST_URI"][$i] == "?"){
                $url = $_SERVER["REQUEST_URI"];
                $real = substr($url,0,strpos($url,"?"));
                $arg = substr($url,strpos($url,"?") + 1,strlen($url) - 1);
                $ed = explode("&",$arg);
                foreach($ed as $i){
                    $kv = explode("=",$i);
                    if(count($kv) == 2){
                        $_GET[$kv[0]] = $kv[1];
                    }
                }

                return strtolower($real);
            }
        }
        return strtolower($_SERVER["REQUEST_URI"]);
    }

    public static function get($url,$func){
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            if(self::parseUrl() == strtolower($url)){
                header("Content-Type: application/json");
                $func(self::$default);
                exit();
            }
        }
    }
    
    public static function post($url,$func){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(self::parseUrl() == strtolower($url)){
                header("Content-Type: application/json");
                $func(self::$default);
                exit();
            }
        }
    }
}

class Route{
    private static function parseUrl(){
        for($i = 0; $i < strlen($_SERVER["REQUEST_URI"]); $i++){
            if($_SERVER["REQUEST_URI"][$i] == "?"){
                $url = $_SERVER["REQUEST_URI"];
                $real = substr($url,0,strpos($url,"?"));
                $arg = substr($url,strpos($url,"?") + 1,strlen($url) - 1);
                $ed = explode("&",$arg);
                foreach($ed as $i){
                    $kv = explode("=",$i);
                    if(count($kv) == 2){
                        $_GET[$kv[0]] = $kv[1];
                    }
                }

                return strtolower($real);
            }
        }
        return strtolower($_SERVER["REQUEST_URI"]);
    }

    public static function get($url,$load){
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            if(self::parseUrl() == strtolower($url)){
                echo "<script src='/lib/js/req.js'></script>";
                echo "<script>";
                include_once $load;
                echo "</script>";
                exit();
            }
        }
    }
    
    public static function post($url,$load){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(self::parseUrl() == strtolower($url)){
                echo "<script src='/lib/js/req.js'></script>";
                echo "<script>";
                include_once $load;
                echo "</script>";
                exit();
            }
        }
    }
}

?>