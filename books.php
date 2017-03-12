<?php
require_once __DIR__ . '/inc/bootstrap.php';
require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/nav.php';


$isMember = isAuthenticated();

$isAdmin = isAdmin();

$allBooks = getAllBooks();


// $isMember = true => You are Stranger, just have the ability to view

// $isMember = false && $isOwner = false => you are a member, do not have the Edit and Delete right.

// $isMember = false && $isOwner = true || $isAdmin = true => ofcourse you can Edit, Delete the book



?>
<div class="container">
    <div class="well">
        <h2>Book List</h2>
        <?php foreach($allBooks as $book): ?>
			<?php 

			if ($isMember && isOwner($book["owner_id"]) || $isAdmin) {
				include(__DIR__."/inc/book.php");
				
			} elseif ($isMember && !isOwner($book["owner_id"])) {
				include(__DIR__."/inc/bookForMembers.php");
				
			} else {
				include(__DIR__."/inc/bookForAudiences.php");
			}

			?>
        <?php endforeach; ?>
    </div>
</div>
<?php
require_once __DIR__ . '/inc/footer.php';