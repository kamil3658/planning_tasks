<?php

class Ustawienia {
    private static $pdo;
    
    public function __construct(){}
    
	public static function getPdo(){
		return self::$pdo;
	}

	public static function setPdo($baza, $login, $hasloPdo, $options){
		self::$pdo = new PDO($baza, $login, $hasloPdo, $options);
	}
}


?>