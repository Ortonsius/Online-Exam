<?php

class DB{
    private static $conn;

    public static function connect($host,$db,$username,$password){
        self::$conn = new PDO("mysql:host=$host;dbname=$db;",$username,$password);
        self::$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public static function query($q,$arg){
        $s = self::$conn->prepare($q);
        $s->execute($arg);
        return $s;
    }
}

DB::connect("localhost","oe","root","");
?>