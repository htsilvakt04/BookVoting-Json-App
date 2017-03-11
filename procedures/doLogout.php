<?php 
require __DIR__."/../inc/bootstrap.php";
use Symfony\Component\HttpFoundation\Cookie;

$exp = time() - 3600;
$access_token = new Cookie("access_token", "Expired", $exp, "/", getenv("COOKIE_DOMAIN"));
response("/login.php", ["cookies" => [$access_token]]);