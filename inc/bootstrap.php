<?php 
require_once __DIR__. "/../vendor/autoload.php";
require_once __DIR__. "/connection.php";
require_once __DIR__. "/functions.php";

use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Session\Session;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$session = new Session();
$session->start();