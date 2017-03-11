<?php 
require_once __DIR__ . '/inc/bootstrap.php';
requireAdmin();
require_once __DIR__ . '/inc/head.php';
require_once __DIR__ . '/inc/nav.php';


?>
<div class="container">
    <div class="well">
        <h2>Admin</h2>
        <?php echo display_message(); ?>

        <div class="panel">
        	<h4>Users</h4>
        	<table class="table table-striped">
        		<thead>
        			<tr>
        				<th>Email</th>
        				<th>Registerd</th>
        				<th>Promote/Demote</th>
        			</tr>
        		</thead>
        		<tbody>
        			<?php foreach (getAllUsers() as $user) : ?>
        				<tr>
        					<td><?php echo $user["email"]; ?></td>
        					<td><?php echo $user["created_at"]; ?></td>
        					<td><?php echo $user["role_id"]; ?></td>
        					<td>
        					<?php if ($user["role_id"] == 1) : ?>
        						<a href="/procedures/adjustRole.php?role=demote&userId=<?php echo $user["id"]; ?>">Demote from Admin</a>
        					<?php else: ?>
        						<a href="/procedures/adjustRole.php?role=promote&userId=<?php echo $user["id"]; ?>">Promote to Admin</a>
        					<?php endif; ?>
        					</td>
        				</tr>
        			<?php endforeach ?>
        		</tbody>
        	</table>

        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/inc/footer.php';