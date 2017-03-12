 <div class="media well">
 	<div class="media-left">
 		<div class="btn-group-vertical" role="group">
 		<a href="/procedures/vote.php?vote=up&bookId=<?php echo $book['id'] ?>">
 			<span class="glyphicon glyphicon-triangle-top"></span>
 		</a>
 		<span><?php score($book); ?></span>
 		<a href="/procedures/vote.php?vote=down&bookId=<?php echo $book['id'] ?>">
 			<span class="glyphicon glyphicon-triangle-bottom"></span>
 		</a>
 	</div>
 	</div>
    <div class="media-body">
      <h4 class="media-heading"><?php echo $book["name"]; ?></h4>
      <p><?php echo $book["description"]; ?></p>
    </div>
</div>
    