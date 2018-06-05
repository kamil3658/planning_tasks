<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planowanie zadań</title>
    <link href="https://fonts.googleapis.com/css?family=Dosis&amp;subset=latin-ext" rel="stylesheet">
    <link rel="icon" href="images/favicon.ico">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="wrapper">
        <header>
            <img src="photos/planowanie.jpg" alt="widoczek-header"/>
        </header>

<?php
require('ustawienia.php');
        
Ustawienia::setPdo('mysql:host=localhost;dbname=Planowanie', 'root', 'root', array(PDO::MYSQL_ATTR_INIT_COMMAND, "SET_NAMES 'UTF8'", PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION));
        
?>
<div id='products'>        