<?php
require('header.php');
require_once('zadanie.php');
session_start();
?>

        <nav>
            <a class="menu" href="twojeZadania.php">Lista Twoich zadań</a><a class="menu active" href="dodajZadanie.php">Dodaj zadanie</a><?php if(isset($_SESSION['zalogowany']) == 0){echo '<a class="menu" href="logowanie.php">Logowanie</a>';} else{ echo '<a class="menu" href="logowanie.php?akcja=wyloguj">Wyloguj się</a>';}?>
        </nav>

    
<?php
if(isset($_SESSION['zalogowany']) == 0){
    echo "<br>"."Musisz się zalogować, żeby moc dodać zadanie";
}   
else {
    $zadanie = new Zadanie();
    Zadanie::setId(isset($_GET['id_zadania']) ? intval($_GET['id_zadania']) : 0);

        if(Zadanie::getId() > 0) {
            $stmt = Ustawienia::getPdo()->prepare('SELECT * FROM zadania WHERE id=:id');
            $stmt->bindValue(':id', Zadanie::getId(), PDO::PARAM_INT);
            $stmt->execute();
        
        $row = $stmt->fetch();
        }
    echo '<form action="dodajZadanie.php?id_zadania='.Zadanie::getId().'" method="POST">' ?>
        <?php
            if(Zadanie::getId() > 0) {
                echo "<input type='hidden' name='idZadania' value='".Zadanie::getId()."'>";
            }
        ?>
        <div id='dodajZadanie'>
    <br>
    <h2> Dodaj swoje zadanie </h2><br><br>
        Nazwa zadania:
        <Input type="tekst" name="nazwa_zadania" <?php if(isset($row['nazwa'])){echo "value='".$row['nazwa']."'";} ?>><br><br></Input>
        Treść zadania:<br>
        <textarea name='tresc' cols='100' rows='20'> <?php if(isset($row['tresc'])){echo $row['tresc'];} ?></textarea><br>
        <?php if(Zadanie::getId() == 0) {?>
        <br>Data realizji zadania:
        <input type="number" min="1" max="30" value="1" name="dzien" /> <?php } ?>
        <Input type='hidden' value='1' name='send'></Input><br><br> 
        <Input type="submit" name="rejestracja" value="Dodaj zadanie"><br></Input><br><br><br>
        </div>
    <?php
    if(isset($_POST['send']) == 1){ 
        $zadanie->setNazwa(filter_var($_POST['nazwa_zadania'], FILTER_SANITIZE_STRING));
        $zadanie->setTresc(filter_var($_POST['tresc'], FILTER_SANITIZE_STRING));
        $zadanie->setDzien(filter_var($_POST['dzien'], FILTER_SANITIZE_STRING));
        if(isset($_POST['nazwa_zadania']) && isset($_POST['tresc'])) {
            if(!empty($_POST['nazwa_zadania']) && !empty($_POST['tresc'])) {
                if(Zadanie::getId() > 0) {
                    if($zadanie->sprawdzanieIdZadaniaIUsera(User::wyciaganieUserId($_SESSION['mail']))){
                        $zadanie->edytujZadania();
                        header('Location: twojeZadania.php');
                    } else {
                        header('Location: logowanie.php?akcja=wyloguj');
                    }
                }
                else {    
                    $zadanie->dodajDoBazyDanych();
                }
            }
            else {
                echo "Któreś z pól nie zostało wypełnione.";
            }
        }
    }
}

require('footer.php');
?>