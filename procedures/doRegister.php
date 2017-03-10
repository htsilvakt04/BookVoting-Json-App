<?php 
require_once __DIR__ . '/../inc/bootstrap.php';
use Symfony\Component\HttpFoundation\Response;

$email = request()->get("email");
$password = request()->get("password");
$confirmPassword = request()->get("confirm_password");

if ($password != $confirmPassword) {
	return response(
		"/register.php",
		"Password doesn't match",
		 Response::HTTP_BAD_REQUEST
	);
}

if(! empty($user = findUserByEmail($email))) {
	return response(
		"/register.php",
		"Email already exists",
		 Response::HTTP_BAD_REQUEST
	);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$user = createUser($email, $hash);

response("/");