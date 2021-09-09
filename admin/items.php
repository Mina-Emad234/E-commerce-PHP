<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'Items';
	include 'init.php';
	$item_class=new item();
    $do = $_GET['do'] ?? 'Manage';
    if ($do == 'Manage') {
        $items=$item_class->get_items();
        if(!empty($items)){

?>
		<h1>Manage Items</h1>
		<div class="container">
			<table class="table table-light table-hover table-bordered">
				<thead>
					<tr>
						<td>#ID</td>
						<td>Name</td>
						<td>Description</td>
						<td>Price</td>
                        <td>Category</td>
						<td>Username</td>
						<td>Adding Date</td>
						<td>Control</td>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($items as $item) {
						echo "
				<tr>
					<td>" . $item['Item_ID'] . "</td>
					<td>" . $item['Name'] . "</td>
					<td>" . $item['Description'] . "</td>
					<td>" . $item['Price'] . "</td>
					<td>" . $item['Category_Name'] . "</td>
					<td>" . $item['Username'] . "</td>
					<td>" . $item['Add_Date'] . "</td>
					<td><a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success m-1'><i class='fa fa-edit'></i> Edit</a>
					<a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger m-1 confirm'><i class='fa fa-trash'></i> Delete</a>";
					if($item['Approve']==0){ 
                        echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                    }
                    echo "</td>
				</tr>";
					}
					?>
				</tbody>
			</table>
			<a href='items.php?do=Add' class="add btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Item</a>
		</div>
        <?php }else{
            echo "<div class='container'>";
            echo "<div class='nice-messege'>there is no item to show</div>";
            echo "<a href='items.php?do=Add' class='add btn btn-sm btn-primary'><i class='fa fa-plus'></i> Add Item</a>";
            echo "</div>";
        }
        ?>
	<?PHP
    } elseif ($do == 'Add') {?>
        	<h1 class="display-6">Add New Item</h1>
            <div class="container">
                <form class="edit-form" method="post" action="?do=Insert" enctype="multipart/form-data">
                    <div class="mb-3 req">
                        <label  class="form-label">Name</label>
                        <input 
                                type="text" 
                                name="name" 
                                placeholder="Item Name" 
                                class="form-control form-groub-lg" 
                                required>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Description</label>
                        <input 
                                type="text" 
                                name="description" 
                                placeholder="Item Description" 
                                class="form-control form-groub-lg" 
                                required>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Price</label>
                        <input type="text" 
                                name="price" 
                                placeholder="Item Price" 
                                class="form-control form-groub-lg" 
                                required>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Country</label>
                        <input type="text" 
                                name="country" 
                                placeholder="Item Country" 
                                class="form-control form-groub-lg" 
                                required>
                    </div>
                    <div class="mb-3 req">
                    <label  class="form-label">Product Image</label>
                        <input type="file"
                                name="image"
                                placeholder="Item Country"
                                class="form-control form-groub-lg"
                                required>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Status</label>
                        <select class="" name="status">
                            <option selected value="0">Choose One</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                            <option value="5">Very Old</option>
                        </select>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Members</label>
                        <select class="" name="member">
                            <option selected value="0">Choose One</option>
                            <?php
                            $users = getAllFrom('*','users', '', '','','', 'UserID', 'ASC');
                            foreach($users as $user){
                                echo "<option value='".$user['UserID'] ."'>". $user['Username'] ."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Categories</label>
                        <select class="" name="category">
                            <option selected value="0">Choose One</option>
                            <?php
                            $cats = getAllFrom('*', 'categories', 'Parent', '0','','', 'ID', 'ASC');
                            foreach($cats as $cat){
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $Ccats = getAllFrom('*','categories', 'Parent', $cat['ID'],'','', 'ID', 'ASC');
                                foreach($Ccats as $c){
                                    echo "<option value='".$c['ID'] ."'>--". $c['Name'] ."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">tags</label>
                        <input type="text"
                               name="tags"
                               class="form-control form-groub-lg"
                               placeholder="separate tags with (,)">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </form>
            </div>
        <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
            $item_class->add_item($_POST['name'],$_POST['description'],$_POST['price'],$_POST['country'],$_POST['status'],
                $_POST['member'],$_POST['category'],$_POST['tags'],$_FILES['image']);
		} else {
			echo'<div class="container">';
			$theMsg = "<div class='alert alert-danger'>can't be reached</div>";
			redirectPage($theMsg,'back');
			echo '</div>';
		}
		echo "</div>";
    } elseif ($do == 'Edit') {

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $item=$item_class->edit_item($itemid);
		if (isset($item)) {
		?>
			
            <h1 class="display-6">Edit Item</h1>
            <div class="container">
                <form class="edit-form" method="post" action="?do=Update" enctype="multipart/form-data">
                <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">
                    <div class="mb-3 req">
                        <label  class="form-label">Name</label>
                        <input 
                                type="text" 
                                name="name" 
                                placeholder="Item Name" 
                                class="form-control form-groub-lg" 
                                value="<?php echo $item['Name'] ?>"
                                required>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Description</label>
                        <input 
                                type="text" 
                                name="description" 
                                placeholder="Item Description" 
                                class="form-control form-groub-lg"
                                value="<?php echo $item['Description'] ?>" 
                                required>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Price</label>
                        <input type="text" 
                                name="price" 
                                placeholder="Item Price" 
                                class="form-control form-groub-lg" 
                                value="<?php echo $item['Price'] ?>"
                                required>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Country</label>
                        <input type="text" 
                                name="country" 
                                placeholder="Item Country" 
                                class="form-control form-groub-lg" 
                                value="<?php echo $item['Country_Made'] ?>"
                                required>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Product Image</label>
                        <input type="file"
                               name="image"
                               placeholder="Item Country"
                               class="form-control form-groub-lg">
                        <input type="hidden" name="img" value="<?php echo $item['Image'] ?>">

                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Status</label>
                        <select class="" name="status">
                            <option selected value="0">Choose One</option>
                            <option value="1" <?php if($item['Status']==1){echo 'selected';} ?>>New</option>
                            <option value="2" <?php if($item['Status']==2){echo 'selected';} ?>>Like New</option>
                            <option value="3" <?php if($item['Status']==3){echo 'selected';} ?>>Used</option>
                            <option value="4" <?php if($item['Status']==4){echo 'selected';} ?>>Old</option>
                            <option value="5" <?php if($item['Status']==5){echo 'selected';} ?>>Very Old</option>
                        </select>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Members</label>
                        <select class="" name="member">
                            <option selected value="0">Choose One</option>
                            <?php
                            $users = getAllFrom('*','users', '', '','','', 'UserID', 'ASC');
                            foreach($users as $user){
                                echo "<option value='".$user['UserID'] ."'";
                                 if($item['Member_ID']==$user['UserID']){echo 'selected';} 
                                echo ">". $user['Username'] ."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">Members</label>
                        <select class="" name="category">
                            <option selected value="0">Choose One</option>
                            <?php
                            $cats = getAllFrom('*','categories', '', '','','', 'ID', 'ASC');
                            foreach($cats as $cat){
                                echo "<option value='".$cat['ID'] ."'";
                                if($item['Cat_ID']==$cat['ID']){echo 'selected';} 
                                echo ">". $cat['Name'] ."</option>";
                                $Ccats = getAllFrom('*','categories', 'Parent', "{$cat['ID']}",'','', 'ID', 'ASC');
                                foreach($Ccats as $c){
                                    echo "<option value='".$c['ID'] ."'";
                                    if($item['Cat_ID']==$c['ID']){echo 'selected';} 
                                    echo ">--". $c['Name'] ."</option>";
                            }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 req">
                        <label  class="form-label">tags</label>
                        <input type="text" 
                                name="tags" 
                                placeholder="Item Country" 
                                class="form-control form-groub-lg" 
                                value="<?php echo $item['tags'] ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
<?php
        $item_class->get_item_comment($itemid);
        if(!empty($rows)){
?>
		<h1>Manage [<?php echo $item['Name'] ?>] comments</h1>
			<table class="table table-light table-hover table-bordered">
				<thead>
					<tr>
						<td>Comment</td>
						<td>User Name</td>
						<td>Added Date</td>
						<td>Control</td>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($rows as $row) {
						echo "
				<tr>
					<td>" . $row['comment'] . "</td>
					<td>" . $row['member'] . "</td>
					<td>" . $row['comment_date'] . "</td>
					<td><a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success m-1'><i class='fa fa-edit'></i> Edit</a>
					<a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger m-1 confirm'><i class='fa fa-trash'></i> Delete</a>";
						if($row['status']==0){ 
							echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
						}
					echo "</td>
				</tr>";
					}
					?>
				</tbody>
			</table>
		</div>  

        <?php
        }
		} else {
			echo '<div class="container">';
			$theMsg = "<div class='alert alert-danger'>There is no Such ID</div>";
			redirectPage($theMsg,'');
			echo'</div>';
		}
    
    } elseif ($do == 'Update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
            $item_class->update_item($_POST['itemid'],$_POST['name'],$_POST['description'],$_POST['price'],$_POST['country'],$_POST['status'],
                $_POST['member'],$_POST['category'],$_POST['tags'],$_FILES['image'],$_POST['img']);
		} else {
			
			$theMsg = "<div class='alert alert-danger'>can't be reached</div>";
			redirectPage($theMsg, '');
		}
		echo "</div>";
    
    } elseif ($do == 'Delete') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $item_class->delete_item($itemid);

    } elseif ($do == 'Approve') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $item_class->approve_item($itemid);
	}
    
    
}else {
	header('location:index.php');
	exit();
}
ob_end_flush();