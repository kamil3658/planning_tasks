<?php
require_once('user.php');
require_once('ustawienia.php');

class Zadanie {
    private static $id;
    private $uid;
    private $nazwa;
    private $tresc;
    private $dzien;
    
    public function __construct(){}
    
    public static function getId(){
		return self::$id;
	}

	public static function setId($id){
		self::$id = $id;
	}

	public function getUid(){
		return $this->uid;
	}

	public function setUid($uid){
		$this->uid = $uid;
	}

	public function getNazwa(){
		return $this->nazwa;
	}

	public function setNazwa($nazwa){
		$this->nazwa = $nazwa;
	}

	public function getTresc(){
		return $this->tresc;
	}

	public function setTresc($tresc){
		$this->tresc = $tresc;
	}

	public function getDzien(){
		return $this->dzien;
	}

	public function setDzien($dzien){
		$this->dzien = $dzien;
	}
    
    public static function wyswietlanieZadan() {
        $stmt = Ustawienia::getPDO()->prepare('SELECT * FROM zadania WHERE user_id=:user_id');
        $stmt->bindValue(':user_id', User::wyciaganieUserId($_SESSION['mail']), PDO::PARAM_INT);
        $stmt->execute();

         echo "<table border='1'>";
         echo "<tr><th>Nazwa zadania</th><th>Treść zadania</th><th>Czas do końca zadania</th><th>Edytuj</th><th>Usuń zadanie</th></tr>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr><td>".$row['nazwa'];
            echo "</td><td>";
            echo $row['tresc'];
            echo "</td><td>";
            Zadanie::czasDoKonca($row['id']);
            echo "</td><td>";
            self::$id = $row['id'];
            echo "<center>"."<a href='dodajZadanie.php?id_zadania=".self::$id."'>Edytuj</a>"."</center>";
            echo "</td><td>";
            echo "<center>"."<a href='usunZadanie.php?id_zadania=".self::$id."'>Usun</a>"."</center>";
            echo "</td></tr>";
        }
        echo "</table>"."<br><br>";
    }
    public static function czasDoKonca($id){
        $stmt = Ustawienia::getPDO()->prepare('SELECT data FROM zadania WHERE id=:id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        while($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sekudny = substr($data['data'], 17, 2);
            $minuty = substr($data['data'], 14, 2);
            $godziny = (substr($data['data'], 11, 2)) - 2;
            $dzien = substr($data['data'], 8, 2);
            $miesiac = substr($data['data'], 5, 2);
            $rok = substr($data['data'], 0, 4);
        }
        $difference = mktime($godziny, $minuty, $sekudny, $miesiac, $dzien, $rok) - time();

        if ($difference < 0){
            $time = -$difference; 
        }
        else {
            $time = $difference; 
        }

        $days = floor($time/86400);
        $hours = floor(($time-($days*86400))/3600);
        $mins = floor (($time-($days*86400)-($hours*3600))/60); 
        $secs = floor ($time-($days*86400)-($hours*3600)-($mins*60)); 

        if ($difference <= 0) { 
          echo '<font color="red">'.'Od zakończenia upłyneło '.'</font>';
        } else {
          echo '<font color="green">'.'Do końca zostało '.'</font>';
        }
        if($days == 0 && $hours != 0) {
            echo $hours.' godzin '.$mins.' minut '. $secs.' sekund';
        }
        elseif ($days == 0 && $hours == 0 && $mins != 0) {
                echo $mins.' minut '. $secs.' sekund';
        }
        elseif ($days == 0 && $hours == 0 && $mins == 0 && $secs != 0) {
                    echo $secs.' sekund';
        }
        else {
            echo $days.' dni '.$hours.' godzin '.$mins.' minut '. $secs.' sekund';
        }
    }


    public function dodajDoBazyDanych() {
        $stmt = Ustawienia::getPDO()->prepare('INSERT INTO zadania (nazwa, tresc, data, user_id) VALUES (:nazwa, :tresc, ADDDATE(now(),:dzien), :user_id)');
        $stmt->bindValue(':nazwa', $this->nazwa, PDO::PARAM_STR);
        $stmt->bindValue(':tresc', $this->tresc, PDO::PARAM_STR);
        $stmt->bindValue(':dzien', $this->dzien, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', User::wyciaganieUserId($_SESSION['mail']), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function edytujZadania(){
        $stmt = Ustawienia::getPDO()->prepare('UPDATE zadania SET nazwa=:nazwa, tresc=:tresc, user_id=:user_id WHERE id=:id');
        $stmt->bindValue(':nazwa', $this->nazwa, PDO::PARAM_STR);
        $stmt->bindValue(':tresc', $this->tresc, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', User::wyciaganieUserId($_SESSION['mail']), PDO::PARAM_INT);
        $stmt->bindValue(':id', self::$id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function usuwanieZadan(){
        $stmt = Ustawienia::getPDO()->prepare('DELETE FROM zadania WHERE id=:id');
        $stmt->bindValue(':id', self::$id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function sprawdzanieIdZadaniaIUsera(){
        $stmt = Ustawienia::getPDO()->prepare('SELECT count(*) FROM zadania WHERE id=:id and user_id=:user_id');
        $stmt->bindValue(':id', self::$id, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', User::wyciaganieUserId($_SESSION['mail']), PDO::PARAM_INT);
        $stmt->execute();

        $num_rows = $stmt->fetchColumn();
        if($num_rows == 1) {
            return true;
        }
        else {
            return false;
        }    
    }

    
    
}

?>