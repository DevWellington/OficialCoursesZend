<?php

/**
 * Ensure that download links are not available from unauthenticated pages (for
 *	uthenticated sites where downloads are protected)
 */

// download/livro.php?id=2

if (! $sessao->isLogged() && $usuario->temPermissao() )
	die("Ops, voce não pode baixar");

$usuario->count();

$livro = $db->getById( (int) $_GET['id']);
$caminho = $livro->getFullPath(); // /srv/books/livro2.pdf

$arquivo = file($caminho);


header('Content-Type: application/pdf');

echo $arquivo;