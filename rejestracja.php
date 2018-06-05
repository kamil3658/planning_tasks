<?php
require('header.php');
require_once('user.php');
?>
        <nav>
            <a class="menu" href="twojeZadania.php">Lista Twoich zadań</a><a class="menu" href="dodajZadanie.php">Dodaj zadanie</a><?php if(isset($_SESSION['zalogowany']) == 0){echo '<a class="menu active" href="logowanie.php">Logowanie</a>';} else{ echo '<a class="menu" href="logowanie.php?akcja=wyloguj">Wyloguj się</a>';}?>
        </nav><br>


<form action="rejestracja.php" method="POST">
<div id='lewa'> 
    <h2> Założ konto </h2>
    <h5>Wpisz swoje dane i załóż konto.</h5><br>
    <div id='lewa3'>
        Adres e-mail:
        <Input type="tekst" name="mail"><br><br></Input>
        Hasło:
        <Input type="password" name="haslo"><br/></Input>
        Powtórz hasło:
        <Input type="password" name="haslo2"><br/><br/><br/></Input>
        <Input type=hidden value='1' name=send></Input>
        <Input type="submit" name="rejestracja" value="Zarejestruj się"><br></Input><br>
</div></div>  
<?php
    if(isset($_POST['send']) == 1) {
        $user = new User();
        User::setMail(filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL));
        $user->setHaslo(filter_var($_POST['haslo'], FILTER_SANITIZE_STRING));
        $user->setHaslo2(filter_var($_POST['haslo2'], FILTER_SANITIZE_STRING));
        
        if(!empty (User::getMail()) && !empty ($user->getHaslo())) {
            if($user->sprawdzanieMailaWBazie()) {
                if(strlen(trim($user->getHaslo())) >= 5){
                    if($user->getHaslo() === $user->getHaslo2()){
                    $user->dodawanieDoBazyDanych();
                    header('Location: logowanie.php');
                    }
                    else {
                        echo "<div id='tekst'>"."Hasła nie są takie same."."</div>";
                    }
                }
                else {
                    echo "<div id='tekst'>"."Twoje hasło musi mieć więcej niż 5 znaków."."</div>";
                }
            }
            else {
                echo "<div id='tekst'>"."Twój e-mail jest już używany."."</div>";
            }
            
        }
        else {
            echo "<div id='tekst'>"."Któreś pole nie zostało wypełnione"."<br>"."albo Twój e-mail jest niepoprawny."."</div>";
        }
    }
    
?>