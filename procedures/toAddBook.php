<?php 

require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;


$bookTitle = request()->get("title");
$bookDescription = request()->get("description");


try {
	$newBook = addBook($bookTitle, $bookDescription);

	response("/books.php");
} catch (Exception $e) {

	response("/add.php");

	return $e;
}