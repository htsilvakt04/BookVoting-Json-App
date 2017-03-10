<?php 
require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;

$bookId = request()->get("bookId");
$type = request()->get("vote");


try {
	vote($type, $bookId);

	response("/books.php", "Your book were updated successfully", Response::HTTP_FOUND);
} catch (Exception $e) {

	response("/books.php", "Can't do this vote, try again please!", Response::HTTP_BAD_REQUEST);

	return $e;
}