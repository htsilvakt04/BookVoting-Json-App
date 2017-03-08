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