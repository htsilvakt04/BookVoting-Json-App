<?php
require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/nav.php';
?>
<div class="container">
    <div class="well">
        <h2>Add a book</h2>
        <form action="/procedures/toAddBook.php" method="POST" class="form-horizontal">
           <?php include __DIR__."/inc/bookForm.php"; ?>
        </form>
    </div>
</div>
<?php
require_once __DIR__ . '/inc/footer.php';