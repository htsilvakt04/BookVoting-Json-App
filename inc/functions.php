<?php 

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * @return \Symfony\Component\HttpFoundation\Request
 */
function request() 
{
    return Request::createFromGlobals();
}

function addBook($title, $des)
{
	global $db;
	$ownerId = 0;
	try {
		$query = "INSERT INTO books (name, description, owner_id) VALUES (:name, :description, :owner_id)";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":name", $title);
		$stmt->bindParam(":description", $des);
		$stmt->bindParam(":owner_id", $ownerId);
		$stmt->execute();
		return $stmt->fetch();
	} catch (Exception $e) {
		throw $e;
	}
}
function response($path, $content = "success", $type = Response::HTTP_OK) {
	$response = Response::create($content, $type, ["Location" => $path]);
	$response->send();
	exit;
}

function getAllBooks() 
{
	global $db;
 try {
 	$query = "SELECT books.*, SUM(votes.value) as score FROM books LEFT JOIN votes ON books.id = votes.book_id GROUP BY books.id ORDER BY score DESC";
 	$stmt = $db->prepare($query);
 	$stmt->execute();

 	return $stmt->fetchAll();
 } catch (Exception $e) {
 	throw $e;
 }

}
function getBook($id) 
{
	global $db;

	try {
		$query = "SELECT * FROM books WHERE id = :id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":id", $id);
	 	$stmt->execute();
	 	return $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		throw $e;
	}


}

function updateBook($id, $title, $des) 
{
	global $db;

	try {
		$query = "UPDATE books SET name = :name, description = :description WHERE id = :id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":name", $title);
		$stmt->bindParam(":description", $des);
		$stmt->bindParam(":id", $id);
	 	$stmt->execute();
	 	return $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		throw $e;
	}
}
function score($book) 
{
	$score = $book["score"];

	if ($score <= 0) {
		echo "O";
	} else {
		echo number_format($book["score"]);
	}

}
function vote($type, $bookId) 
{
	global $db;

	switch ($type) {
		case 'up':
			$type = 1;
			break;
		case 'down':
			$type = -1;
			break;

	}

	try {
		$query = "INSERT INTO votes (book_id, user_id, value) VALUES (:book_id, 0, :value)";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":book_id", $bookId);
		$stmt->bindParam(":value", $type, PDO::PARAM_INT);
	 	$stmt->execute();	
	 	return true;

	} catch (Exception $e) {
		throw $e;
	}
}

function deleteBook($bookId)
{
	global $db;

	try {
		$query = "DELETE FROM books WHERE id = ?";
		$stmt = $db->prepare($query);
		$stmt->bindParam(1, $bookId);
	 	$stmt->execute();	
	 	return true;
	} catch (Exception $e) {
		throw $e;
	}

}
function findUserByEmail($email) 
{
	global $db;
	try {
		$query = "SELECT * FROM users WHERE email = :email";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":email", $email);
	 	$stmt->execute();	
	 	return $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		throw $e;
	}
}
function createUser($email, $hash) 
{
	global $db;
	try {

		$query = "INSERT INTO users (email, password, role_id) VALUES (:email, :password, 2)";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":email", $email);
		$stmt->bindParam(":password", $hash);
	 	$stmt->execute();	

	 	return findUserByEmail($email);
	} catch (Exception $e) {
		throw $e;
	}
}