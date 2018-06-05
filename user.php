<?php
require_once('ustawienia.php');

class User {
    private static $email;
    private $haslo;
    private $haslo2;
    private $salt;
    
    public function __construct(){
        $this->salt = strval("UHGALJN!@#KDS@#$%ZC80640329%^&*kmlfsa");
    }
    public static function getMail(){
        return self::$email;
    }
    public static function setMail($email){
        self::$email = $email;
    }
    public function getHaslo(){
        return $this->haslo;
    }
    public function setHaslo($haslo){
        $this->haslo = $haslo;
    }
    public function getHaslo2(){
        return $this->haslo2;
    }
    public function setHaslo2($haslo2){
        $this->haslo2 = $haslo2;
    }

    public static function wyciaganieUserId($email){
        $stmt = Ustawienia::getPDO()->prepare('SELECT * FROM users_z_sha512 WHERE email=:email');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row['id'];
        }
    }  
    public function sprawdzCzyPoprawneHaslo() {
        $this->haslo = hash("sha512", $this->salt.$this->haslo);

        $stmt = Ustawienia::getPDO()->prepare('SELECT count(*) FROM users_z_sha512 WHERE email=:email AND haslo=:haslo');
        $stmt->bindValue(':email', self::$email, PDO::PARAM_STR);
        $stmt->bindValue(':haslo', $this->haslo, PDO::PARAM_STR);
        $stmt->execute();

        $num_rows = $stmt->fetchColumn();
            if($num_rows == 1) {
                return true;
            }
            else {
                return false;
            }  
        }

    public function sprawdzanieMailaWBazie(){
            $stmt = Ustawienia::getPDO()->prepare('SELECT * FROM users_z_sha512 WHERE email=:email');
            $stmt->bindValue(':email', self::$email, PDO::PARAM_STR);
            $stmt->execute();

            $num_rows = $stmt->fetchColumn();
            if($num_rows == 0) {
                return true;
            }
            else {
                return false;
            }
        }

    public function dodawanieDoBazyDanych(){
            $this->haslo = hash("sha512", $this->salt.$this->haslo);

            $stmt = Ustawienia::getPDO()->prepare('INSERT INTO users_z_sha512 (email, haslo) VALUES (:email, :haslo)');
            $stmt->bindValue(':email', self::$email, PDO::PARAM_STR);
            $stmt->bindValue(':haslo', $this->haslo, PDO::PARAM_STR);
            $stmt->execute();

        }
 
}



?>