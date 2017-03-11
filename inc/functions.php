<?php 

use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * @return \Symfony\Component\HttpFoundation\Request
 */
function request() 
{
    return Request::createFromGlobals();
}

function getAllUsers() 
{
	global $db;

	try {
		$query = "SELECT * FROM users";
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		throw $e;
	}
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
function response($path, $extra = []) {
	$response = Response::create(null, Response::HTTP_FOUND, ["Location" => $path]);
	if (array_key_exists("cookies", $extra)) {
		foreach ($extra["cookies"] as $cookie) {
			$response->headers->setCookie($cookie);
		}
	}
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

function findUser($id) 
{
	global $db;
	try {
		$query = "SELECT * FROM users WHERE id = :id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":id", $id);
	 	$stmt->execute();	
	 	return $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (Exception $e) {
		throw $e;
	}
}

function findUserByAccessToken() 
{
	global $db;

	try {
		$userId = decodeJwt("sub");
	} catch (Exception $e) {
		throw $e;
	}

	try {
		$query = "SELECT * FROM users WHERE id = :id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":id", $userId);
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
function isAuthenticated() 
{
	// check if the user have the access_token avaiable or not
	
	if (! request()->cookies->has("access_token")) {
		return false;
	}

	try {
		decodeJwt();
		return true;
	} catch (Exception $e) {
		return false;
	}

}


function decodeJwt($prop = null) 
{
	JWT::$leeway = 10;
	$token = request()->cookies->get("access_token");
	$decode = JWT::decode($token, getenv("SECRET_KEY"), ["HS256"]);
	if (func_num_args() == 0) {
		return $decode;
	}
	return $decode->{$prop}; 
	
}


function requireAuth() 
{
	if (! isAuthenticated()) {
		$exp = time() - 3600;
		$access_token = new Cookie("access_token", "Expired", $exp, "/", getenv("COOKIE_DOMAIN"));
		response("/login.php", ["cookies" => [$access_token]]);
	}

	//loginUsingId($decode["user_id"]);
}

function display_message($type = null) 
{
	global $session;

	if (! $session->getFlashBag()->has("messages")) {
		return;
	}

	$messages = $session->getFlashBag()->get("messages");

	$div = "<div class='alert alert-danger alert-dismissable'>";

	if ($type == "success") {
		$div = '<div class="alert alert-success alert-dismissable">';
	}
	foreach ($messages as $message) {
		$div .= "{$message}<br />";
	}
	$div .= "</div>";

	return $div;

}

function updatePassword($password, $userId) 
{
	global $db;
	$password = password_hash($password, PASSWORD_DEFAULT);
	try {
		$query = "UPDATE users SET password = :password WHERE id = :id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":password", $password);
		$stmt->bindParam(":id", $userId);
	 	$stmt->execute();

	 	return true;
	} catch (Exception $e) {
		return false;
	}
}

function promote($id) 
{
	global $db;
	try {
		$query = "UPDATE users SET role_id = 1 WHERE id = :id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":id", $id,PDO::PARAM_INT);
	 	$stmt->execute();

	 	return true;
	} catch (Exception $e) {
		throw $e;
	}
}
function demote($id) 
{
	global $db;
	try {
		$query = "UPDATE users SET role_id = 2 WHERE id = :id";
		$stmt = $db->prepare($query);
		$stmt->bindParam(":id", $id,PDO::PARAM_INT);
	 	$stmt->execute();
	 	
	 	return true;
	} catch (Exception $e) {
		throw $e;
	}
}

function old() 
{
	global $session;

	if ($session->getFlashBag()->has("old")) {
		foreach ($session->getFlashBag()->get("old") as $item) {
			return $item[0];
		}
	}
}

function requireAdmin() 
{
	global $session;

	if (! isAuthenticated()) {
		$exp = time() - 3600;
		$access_token = new Cookie("access_token", "Expired", $exp, "/", getenv("COOKIE_DOMAIN"));
		response("/login.php", ["cookies" => [$access_token]]);
	}
	// check if the user's token is admin.
	try {
		if (! decodeJwt("is_admin")) {
		$session->getFlashBag()->add("messages", "You do not have permission to access this page");
		response("/");
		}
	} catch (Exception $e) {
		$access_token = new Cookie("access_token", "Expired", $exp, "/", getenv("COOKIE_DOMAIN"));
		response("/login.php", ["cookies" => [$access_token]]);
	}
}

function isAdmin() 
{
	if (! isAuthenticated()) {
		return false;
	}
	try {
		$isAdmin = decodeJwt("is_admin");
	} catch (Exception $e) {
		return false;
	}

	return (boolean) $isAdmin;
}

function isOwner($bookOwnerId) 
{
	// this function have the main purpose is to check whether the book is owned by the particular user or not. if not, this user can't modify the book.
	if (!isAuthenticated()) {
		return false;
	}

	try {

		$userId = decodeJwt("sub");

	} catch (Exception $e) {
		throw $e;
	}

	return $ownerId == $userId;

}








