<?php 

require_once __DIR__ . '/../inc/bootstrap.php';
requireAuth();
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;


$currentPassword = request()->get("current_password");

$newPassword = request()->get("new_password");
$newPasswordConfirm = request()->get("new_password_confirm");

if ($newPassword != $newPasswordConfirm) {
	$session->getFlashBag()->add("messages", "New password doesn't match, please try again");
	response("/account.php");
}

$user = findUserByAccessToken();

if(empty($user)) {
	$session->getFlashBag()->add("messages", "There was an error, please login and try again");
	response("/account.php");
}


if (!password_verify($currentPassword, $user["password"])) {

	$session->getFlashBag()->add("messages", "Your current password was wrong, please try again");
	response("/account.php");

}

$newPasswordUpdate = updatePassword($newPassword, $user["id"]);

if (! $newPasswordUpdate) {
	$session->getFlashBag()->add("messages", "Something went wrong, please try to update your password later.");
	response("/account.php");
}

$session->getFlashBag()->add("messages", "Your password updated successfuly");

response("/");