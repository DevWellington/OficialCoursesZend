<?php

$dbh = new PDO("mysql:host=localhost;dbname=usuario", "root", "root");

/* Execute a prepared statement by passing an array of values */
$sql = 'SELECT name, colour, calories
    FROM fruit
    WHERE calories < :calories AND colour = :colour';

$sth = $dbh->prepare($sql);


$calories = (int) $_GET['calories'];
$colour = (string) $_GET['colour'];

/*
	
\' OR \'\'=\'

*/


$sth->execute(
	array(
		':calories' => $calories, 
		':colour' => $colour
	)
);
$red = $sth->fetchAll();



$sth = $dbh->prepare($sql);

// Ja faz a conversao, nao sendo necessario parsear o valor antes (int), (string), etc
$sth->bindParam(':calories', $_GET['calories'], PDO::PARAM_INT);
$sth->bindParam(':colour', $_GET['colour'], PDO::PARAM_STR, 12); // 12 tamanho maximo do parametro

$sth->execute();

$yellow = $sth->fetchAll();