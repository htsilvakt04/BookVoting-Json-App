<?php 

require_once __DIR__ . '/../inc/bootstrap.php';
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;


$emailRequest = request()->get("email");

$passwordRequest = request()->get("password");

if (empty($user = findUserByEmail($emailRequest))) {
	$session->getFlashBag()->add("messages", "User was not found");
	$session->getFlashBag()->add("old", [$emailRequest]);

	response("/login.php", "Your crudential doesn't match, please try again");
}


if(!password_verify($passwordRequest, $user["password"])) {
	$session->getFlashBag()->add("messages", "Your password doesn't match, try again");
	$session->getFlashBag()->add("old", [$emailRequest]);
	response("/login.php", "Your crudential doesn't match, please try again");
}



$exp = time() + 3600;


$jwt = JWT::encode([
	"iss" => request()->getBaseUrl(),
	"sub" => "{$user["id"]}",
	"exp" => $exp,
	"iat" => time(),
	"nbf" => time(),
	"is_admin" => $user["role_id"] == 1,
], getenv("SECRET_KEY"), "HS256");

$accessToken = new Cookie("access_token", $jwt, $exp, "/", getenv("COOKIE_DOMAIN"));

response("/", ["cookies" => [$accessToken]]);


