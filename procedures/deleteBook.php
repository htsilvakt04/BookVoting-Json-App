<?php 

require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;


$bookId = request()->get("bookId");



try {

	deleteBook($bookId);

	response(
		"/books.php", 
		"Your book were deleted successfully", 
		Response::HTTP_FOUND
	);
} catch (Exception $e) {

	response(
		"/procedures/deleteBook.php",
		"Can't delete book..", 
		Response::HTTP_BAD_REQUEST
	);

	return $e;
}