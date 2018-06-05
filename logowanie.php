<?php
require('header.php');
require('user.php');
session_start();
?>
        <nav>
            <a class="menu" href="twojeZadania.php">Lista Twoich zadań</a><a class="menu" href="dodajZadanie.php">Dodaj zadanie</a><?php if(isset($_SESSION['zalogowany']) == 0){echo '<a class="menu active" href="logowanie.php">Logowanie</a>';} else{ echo '<a class="menu" href="logowanie.php?akcja=wyloguj">Wyloguj się</a>';}?>
        </nav>

<?php
if (isset($_GET['akcja']) == 'wyloguj'){
    $_SESSION['zalogowany'] = 0;
    session_destroy();
    header('Location: logowanie.php');
    echo "Zostałeś poprawnie wylogowany";
}
if ((isset($_SESSION['zalogowany']) == 1) &&  (time() - $_SESSION['time'] > 15*60)){
    $_SESSION['zalogowany'] = 0;
    session_destroy();
    echo "<br>"."Czas minął. Zostałeś wylogowany";
    echo "<br>"."<br>"."<a href='logowanie.php'> Odswiez </a><br/>";
}    
if ((isset($_SESSION['zalogowany']) == 1) && ($_SESSION['info_o_komp'] != $_SERVER['HTTP_USER_AGENT'])){
    $_SESSION['zalogowany'] = 0;
    session_destroy();
    echo "<br>"."Prosimy o ponowne zalogowanie";
}

if((isset($_POST['mail']) && isset($_POST['haslo'])) || isset($_SESSION['zalogowany']) == 1 ) {
    if((!empty($_POST['mail']) && !empty($_POST['haslo'])) || isset($_SESSION['zalogowany']) == 1) {
        $user = new User();
        User::setMail(filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL));
        $user->setHaslo(filter_var($_POST['haslo'], FILTER_SANITIZE_STRING));

        if($user->sprawdzCzyPoprawneHaslo() || isset($_SESSION['zalogowany']) == 1) {  
            echo "<br>"."Zalogowałeś się na swoje konto ".User::getMail()."<br>";
                echo "<br>"."<Input type='button' id='button_1' onclick=location.href='logowanie.php?akcja=wyloguj' value='Wyloguj się'></Input>"."<br><br>";
            $_SESSION['zalogowany'] = 1;
            $_SESSION['time'] = time();
            $_SESSION['info_o_komp'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['mail'] = User::getMail();
        }
        else {
            echo "<div id='tekst2'>"."Zły login lub hasło"."</div>";
        }
    }
    else {
        echo "<div id='tekst2'>"."Nie wpisałeś loginu lub hasła."."</div>";
    }
} 

if(isset($_SESSION['zalogowany']) == 0){
?>
    <form action="logowanie.php" method="POST">
    <div id='lewa'>
        <br>
        <h2> Mam już konto </h2>
        <h5>Masz już konto? Wpisz swoje dane.</h5><br>
        <div id='lewa3'>
        Login (e-mail): 
        <Input type="tekst" name="mail"><br><br></Input>
        Haslo: 
        <Input type="password" name="haslo"><br/><br/><br/></Input>
        <Input type="submit" name="send" value="Zaloguj sie"><br><br><br></Input>
    </div></div>
        <div id='prawa'>
        Nie masz konta?
        <a href='rejestracja.php'>Zarejestruj się!</a>
    </div>
<?php
}
?>                