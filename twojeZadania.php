<?php
require('header.php');
require_once('user.php');
require_once('zadanie.php');
session_start();
?>
        <nav>
            <a class="menu active" href="twojeZadania.php">Lista Twoich zadań</a><a class="menu" href="dodajZadanie.php">Dodaj zadanie</a><?php if(isset($_SESSION['zalogowany']) == 0){echo '<a class="menu" href="logowanie.php">Logowanie</a>';} else{ echo '<a class="menu" href="logowanie.php?akcja=wyloguj">Wyloguj się</a>';}?>
        </nav>

<?php
if(isset($_SESSION['zalogowany']) == 0){
    echo "<br>"."Musisz się zalogować, żeby moc widzieć swoje zadania";
}
else {
    echo "<br>"."Twoje zadania:"."<br><br>";
    Zadanie::wyswietlanieZadan(User::wyciaganieUserId($_SESSION['mail']));
}


require('footer.php');
?>
