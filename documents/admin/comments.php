<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
	$pageTitle = 'Comments';
	include 'init.php';
	// echo 'welcome ' . $_SESSION['username'];
    $_comment=new comment();
	$do = $_GET['do'] ?? 'Manage';
	if ($do == 'Manage') {
        $comments=$_comment->get_comments();
        if(!empty($comments)){

?>
		<h1>Manage comments</h1>
		<div class="container">
			<table class="table table-light table-hover table-bordered">
				<thead>
					<tr>
						<td>#ID</td>
						<td>Comment</td>
						<td>Item Name</td>
						<td>User Name</td>
						<td>Added Date</td>
						<td>Control</td>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($comments as $comment) {
						echo "
				<tr>
					<td>" . $comment['c_id'] . "</td>
					<td>" . $comment['comment'] . "</td>
					<td>" . $comment['item_name'] . "</td>
					<td>" . $comment['member'] . "</td>
					<td>" . $comment['comment_date'] . "</td>
					<td><a href='comments.php?do=Edit&comid=" . $comment['c_id'] . "' class='btn btn-success m-1'><i class='fa fa-edit'></i> Edit</a>
					<a href='comments.php?do=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger m-1 confirm'><i class='fa fa-trash'></i> Delete</a>";
						if($comment['status']==0){ 
							echo "<a href='comments.php?do=Approve&comid=" . $comment['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
						}
					echo "</td>
				</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
        <?php
        }else{
            echo "<div class='container'>";
            echo "<div class='nice-messege'>there is no Comment to show</div>";
            echo "</div>";
        }
        ?>
	<?PHP
	} elseif ($do == 'Edit') {
		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
	    $row=$_comment->edit_comment($comid);
	    if (isset($row)){
		?>
			<h1 class="display-6">Edit comment</h1>
			<div class="container">
				<form class="edit-form" method="post" action="?do=update">
					<div class="mb-3 req">
						<label for="exampleInputEmail1" class="form-label">Comment</label>
						<input type="hidden" name="comid" value="<?php echo $comid; ?>">
						<textarea class="form-control form-groub-lg" name="comment"><?php echo $row['comment'] ?></textarea>
					</div>
					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>
        <?php
		} else {
			echo '<div class="container">';
			$theMsg = "<div class='alert alert-danger'>There is no Such ID</div>";
			redirectPage($theMsg,'');
			echo'</div>';
		}
	} elseif ($do == 'update') {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_comment->update_comment($_POST['comid'],$_POST['comment']);
		} else {
			$theMsg = "<div class='alert alert-danger'>can't be reached</div>";
			redirectPage($theMsg, '');
		}
		echo "</div>";
	} elseif ($do == 'Delete') {
		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $_comment->delete_comment($comid);
	}elseif ($do == 'Approve') {
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
        $_comment->approve_comment($comid);
	}
} else {
	header('location:index.php');
	exit();
}
ob_end_flush();