<?php 
require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;

$bookId = request()->get("bookId");
$type = request()->get("vote");


try {
	vote($type, $bookId);

	response("/books.php");
} catch (Exception $e) {

	response("/books.php");

	return $e;
}