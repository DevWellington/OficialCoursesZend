<?php

// form.php

$token = md5(mt_rand());
$_SESSION['token'] = $token;

?>
<form action="processar.php" method="post">
	<input type="hidden" value="<?=$token;?>" name="token" />
	<input type="submit" />
</form>

<!-- -->


<!-- processar.php -->

<?php

if (! isset($_SESSION['token']) || (! isset($_POST['token']) || $_POST['token'] !== $_SESSION['token'] ) )
	die("Acesso negado");

unset($_SESSION['token']);

/** efeturar compra **/