<?php 
require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;

$bookId = request()->get("bookId");
$bookTitle = request()->get("title");
$bookDescription = request()->get("description");


try {
	updateBook($bookId,$bookTitle, $bookDescription);

	response("/books.php", "Your book were updated successfully", Response::HTTP_FOUND);
} catch (Exception $e) {

	response("/edit.php", "Can't edit book..", Response::HTTP_BAD_REQUEST);

	return $e;
}