<?php 
require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;

$bookId = request()->get("bookId");
$bookTitle = request()->get("title");
$bookDescription = request()->get("description");


try {
	updateBook($bookId,$bookTitle, $bookDescription);

	response("/books.php");
} catch (Exception $e) {

	response("/edit.php");

	return $e;
}