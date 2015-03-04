<?php

if(! isset($_FILES['file']))
	die('not exists');

if(! is_uploaded_file($_FILES['file']))
	die('not exists');

$name = mt_rand();

$db->save($name);

// move_uploaded_file($_FILES['file']['tmp'], "uploads/{$_FILES['file']['name']);
move_uploaded_file($_FILES['file']['tmp'], "uploads/{$id}/{$name}.jpg");
