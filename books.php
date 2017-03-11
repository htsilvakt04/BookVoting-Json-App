<?php
require_once __DIR__ . '/inc/bootstrap.php';
require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/nav.php';
//. check authorrize 
// check user own books or not

$allBooks = getALlBooks();

?>
<div class="container">
    <div class="well">
        <h2>Book List</h2>
        <?php foreach($allBooks as $book): ?>
        	<?php include __DIR__."/inc/book.php"; ?>
        <?php endforeach ?>
    </div>
</div>
<?php
require_once __DIR__ . '/inc/footer.php';