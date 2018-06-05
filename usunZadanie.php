<?php
require('header.php');
require_once('zadanie.php');

Zadanie::setId(isset($_GET['id_zadania']) ? intval($_GET['id_zadania']) : 0);
if (Zadanie::getId() > 0) {
    Zadanie::usuwanieZadan(Zadanie::getId());
    header('Location: twojeZadania.php');  
}
else {
    header('Location: twojeZadania.php');
}


?>