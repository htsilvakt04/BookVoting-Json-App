<?php 
require_once __DIR__ . '/inc/bootstrap.php';
require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/nav.php';

$bookId = request()->get("bookId");
$book = getBook($bookId);

$bookOwnerId = $book["owner_id"];


if (!isOwner($bookOwnerId) && !isAdmin()) {
	$session->getFlashBag()->add("messages", "You don't have permission to access");
	response("/books.php");
}
 	
?>
<div class="container">
    <div class="well">
        <h2>Edit Book</h2>
        <form action="/procedures/editBook.php" method="POST" class="form-horizontal">
           <?php include __DIR__."/inc/bookForm.php"; ?>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/inc/footer.php';