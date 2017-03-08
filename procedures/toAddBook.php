<?php 

require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;


$bookTitle = request()->get("title");
$bookDescription = request()->get("description");


try {
	$newBook = addBook($bookTitle, $bookDescription);

	response("/books.php", "Your book were created successfully", Response::HTTP_FOUND);
} catch (Exception $e) {

	response("/add.php", "Can't add book..", Response::HTTP_BAD_REQUEST);

	return $e;
}