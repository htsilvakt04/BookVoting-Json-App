<?php 

require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;


$bookId = request()->get("bookId");



try {

	deleteBook($bookId);

	response("/books.php");
} catch (Exception $e) {

	response(
		"/procedures/deleteBook.php"
	);

	return $e;
}