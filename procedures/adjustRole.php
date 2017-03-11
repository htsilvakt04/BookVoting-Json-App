<?php 

require_once __DIR__ . '/../inc/bootstrap.php';
requireAdmin();
use Symfony\Component\HttpFoundation\Response;

$role = request()->get("role");
$userId = request()->get("userId");

switch (strtolower($role)) {
	case 'demote':
		demote($userId);
		$session->getFlashBag()->add("messages", "Demoted from Admin");
		break;
	
	case 'promote':
		promote($userId);
		$session->getFlashBag()->add("messages", "Promoted to Admin");
		break;
}


response("/admin.php");

// try {

// 	demoteAdmin();

// } catch (Exception $e) {

// 	throw $e;
// }