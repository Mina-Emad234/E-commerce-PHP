<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
	$pageTitle = 'Members';
	include 'init.php';
	$_member=new member();
	// echo 'welcome ' . $_SESSION['username'];
	$do = $_GET['do'] ?? 'Manage';
	if ($do == 'Manage') {
		$rows=$_member->get_members();
        if (!empty($rows)){

?>
		<h1>Manage Members</h1>
		<div class="container">
			<table class="table table-light manage-member table-hover table-bordered">
				<thead>
					<tr>
						<td>#ID</td>
						<td>Profile</td>
						<td>Username</td>
						<td>Email</td>
						<td>Full Name</td>
						<td>Register Date</td>
						<td>Control</td>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($rows as $row) {
						echo "
				<tr>
					<td>" . $row['UserID'] . "</td>";
					echo "<td>";
                    if(empty($row['Image'])){
                        echo "No Profile";
                    }else{
                        echo "<img src='uploads/profiles/" . $row['Image'] . "' alt = ''>";
                    }
                    echo "</td>";
					echo "<td>" . $row['Username'] . "</td>
					<td>" . $row['Email'] . "</td>
					<td>" . $row['FullName'] . "</td>
					<td>" . $row['Date'] . "</td>
					<td><a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success m-1'><i class='fa fa-edit'></i> Edit</a>
					<a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger m-1 confirm'><i class='fa fa-trash'></i> Delete</a>";
						if($row['RegStatus']==0){ 
							echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
						}
					echo "</td>
				</tr>";
					}
					?>
				</tbody>
			</table>
			<a href='members.php?do=Add' class="add btn btn-primary"><i class="fa fa-plus"></i> Add Member</a>
		</div>
        <?php 
        }else{
            echo "<div class='container'>";
            echo "<div class='nice-messege'>there is no Member to show</div>";
            echo '<a href="members.php?do=Add" class="add btn btn-primary"><i class="fa fa-plus"></i> Add Member</a>';
            echo "</div>";
        } ?>
	<?PHP
	} elseif ($do == 'Add') {
	?>

		<h1 class="display-6">Add Member</h1>
		<div class="container">
			<form class="edit-form" method="post" action="?do=Insert" enctype="multipart/form-data">
				<div class="mb-3 req">
					<label for="exampleInputEmail1" class="form-label">Username</label>
					<input type="text" name="username" placeholder="Member Username" autocomplete="off" class="form-control form-groub-lg" id="exampleInputEmail1" aria-describedby="emailHelp" required>
				</div>
				<div class="mb-3 req">
					<label for="exampleInputPassword1" class="form-label">Password</label>
					<input type="password" name="password" autocomplete="new-password" placeholder="Password must be comblex" class="password form-control" id="exampleInputPassword1" required>
					<i class="show-pass fa fa-eye"></i>
				</div>
				<div class="mb-3 req">
					<label for="exampleInputEmail1" class="form-label">Email address</label>
					<input type="email" name="email" placeholder="email must be valid" class="form-control" autocomplete="off" id="exampleInputEmail1" aria-describedby="emailHelp" required>
					<div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
				</div>
				<div class="mb-3 req">
					<label for="exampleInputEmail1" class="form-label">Full Name</label>
					<input type="text" name="full" placeholder="Full Name" class="form-control" autocomplete="off" required>
				</div>
				<div class="mb-3 req">
					<label for="exampleInputEmail1" class="form-label">Profile Image</label>
					<input type="file" name="profile"  class="form-control" required>
				</div>
				<button type="submit" class="btn btn-primary">Add Member</button>
			</form>
		</div>

		<?php
	} elseif ($do == 'Insert') {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $_member->add_member($_FILES['profile'], $_POST['username'],$_POST['password'],$_POST['email'],$_POST['full']);
		} else {
			echo'<div class="container">';
			$theMsg = "<div class='alert alert-danger'>can't be reached</div>";
			redirectPage($theMsg);
			echo '</div>';
		} 
		echo "</div>";
	} elseif ($do == 'Edit') {
		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
		$row = $_member->edit_member($userid);
		if(isset($row)){
		?>
			<h1 class="display-6">Edit Member</h1>
			<div class="container">
				<form class="edit-form" method="post" action="?do=update" enctype="multipart/form-data">
					<div class="mb-3 req">
						<label for="exampleInputEmail1" class="form-label">Username</label>
						<input type="hidden" name="userid" value="<?php echo $userid; ?>">
						<input type="text" name="username" value="<?php echo $row['Username'] ?>" autocomplete="off" class="form-control form-groub-lg" id="exampleInputEmail1" aria-describedby="emailHelp" required>
					</div>
					<div class="mb-3">
						<label for="exampleInputPassword1" class="form-label">Password</label>
						<input type="hidden" name="oldPassword" value="<?php echo $row['Password'] ?>">
						<input type="password" name="newPassword" autocomplete="new-password" placeholder="Leave it blank if you don't want to change it" class="form-control" id="exampleInputPassword1">
					</div>
					<div class="mb-3 req">
						<label for="exampleInputEmail1" class="form-label">Email address</label>
						<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" autocomplete="off" id="exampleInputEmail1" aria-describedby="emailHelp" required>
						<div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
					</div>
					<div class="mb-3 req">
						<label for="exampleInputEmail1" class="form-label">Full Name</label>
						<input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" autocomplete="off" required>
					</div>
                    <div class="mb-3 req">
                        <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                        <input type="file" name="profile"   class="form-control" >
                        <input type="hidden" name="image" value="<?php echo $row['Image'] ?>">
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
        $_member->update_member($_FILES['profile'],$_POST['userid'],$_POST['username'],$_POST['email'],$_POST['full'],$_POST['newPassword'],$_POST['oldPassword'],$_POST['image']);
        } else {
			
			$theMsg = "<div class='alert alert-danger'>can't be reached</div>";
			redirectPage($theMsg, '');
		}
        echo "</div>";
    } elseif ($do == 'Delete') {

		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
		$_member->delete_member($userid);
	}elseif ($do == 'Activate') {
		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $_member->activate_member($userid);
	}
	
} else {
	header('location:index.php');
	exit();
} 
ob_end_flush();