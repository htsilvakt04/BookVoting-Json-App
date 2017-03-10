<?php 
require_once __DIR__. "/../vendor/autoload.php";
require_once __DIR__. "/connection.php";
require_once __DIR__. "/functions.php";


use Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();