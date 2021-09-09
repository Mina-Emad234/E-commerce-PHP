<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
	$pageTitle = 'Categories';
	include 'init.php';
    $category=new category();
    $do = $_GET['do'] ?? 'Manage';
	if ($do == 'Manage') {

        $sort = 'ASC';
        $array_sort= array('ASC','DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'],$array_sort)) {
            $sort=$_GET['sort'];
        }
        $stmt = $conn->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        if(!empty($cats)){
?>
		<h1 class="display-6">Manage Category</h1>
		<div class="container categories">
			<div class="card">
				<div class="card-header">
					<i class="fa fa-edit"></i>Manage Category
					<span class="option">
						<b>ordering</b>
						<a class="btn btn-sm <?php if($sort=='ASC'){echo 'Active';} ?>" href="?sort=ASC"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
						<a class="btn btn-sm <?php if($sort=='DESC'){echo 'Active';} ?>" href="?sort=DESC"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
						<b>View :</b>
						<span  data-view="full" class="active">Full</span> 
						<span data-view="classic">Classic</span>
					</span>
				</div>
				<div class="card-body">
					<?php
					foreach ($cats as $cat) {
						echo "<div class='cat'>";
							echo "<div class='hidden-buttons'>";
								echo "<a href='categories.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-sm btn-success'><i class='fa fa-edit'></i> Edit</a>";
								echo "<a href='categories.php?do=Delete&catid=". $cat['ID'] ."' class='confirm btn btn-sm btn-danger'><i class='fa fa-trash'></i> Delete</a>";
							echo "</div>";
							echo "<h3>" . $cat['Name'] . "</h3>";
							echo"<div class='full-view'>";
								echo "<p>"; if($cat['Description']==''){echo "This category has No Description";}else{echo $cat['Description'];} echo "</p>";
								if($cat['Visibility']==1){echo "<span class='visibility'><i class='fa fa-eye'></i> Hidden</span>";}
								if($cat['Allow_Comment']==1){echo "<span class='comment'><i class='fa fa-times'></i> Comments disabled</span>";}
								if($cat['Allow_Ads']==1){echo "<span class='ads'><i class='fa fa-times'></i> Ads disabled</span>";}
                                $childCat = getAllFrom('*','categories', 'Parent', "{$cat['ID']}",'','', 'ID', 'ASC');
                        if (!empty($childCat)) {
                        echo "<h4 class = 'child-head'>ChildCategory</h4>";
                        echo "<ul class = 'list-unstyled child-cats'>";
                        foreach($childCat as $C){
                                echo "<li class = 'child-link'>
                                <a href='categories.php?do=Edit&catid=" . $C['ID'] . "'>" . $C['Name'] . "</a>
                                <a href='categories.php?do=Delete&catid=". $C['ID'] ."' class='confirm show-delete'>Delete</a>
                                </li>";
                            }
                        echo "</ul>";
                        }
							echo "</div>";
						echo "</div>";
						echo "<hr>";
					}
					?>
				</div>
			</div>
			<a class="add btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
		</div>
        <?php

	    }else {
                echo "<div class='container'>";
                echo "<div class='nice-messege'>there is no category to show</div>";
                echo '<a class="add btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>';
                echo "</div>";
            }
        ?>
	<?php
	} elseif ($do == 'Add') {
	?>
		<h1 class="display-6">Add New Category</h1>
		<div class="container">
			<form class="edit-form" method="post" action="?do=Insert">
				<div class="mb-3 req">
					<label for="exampleInputEmail1" class="form-label">Name</label>
					<input type="text" name="name" placeholder="Category Name" autocomplete="off" class="form-control form-groub-lg" id="exampleInputEmail1" required>
				</div>
				<div class="mb-3 req">
					<label class="form-label">Description</label>
					<input type="text" name="description" placeholder="category description" class="form-control form-groub-lg">
				</div>
				<div class="mb-3 req">
					<label class="form-label">Ordering</label>
					<input type="text" name="ordering" placeholder="category order" class="form-control form-groub-lg">
				</div>
                <div class="mb-3 req">
                        <label  class="form-label">Parnt</label>
                        <select class="" name="parent">
                            <option selected value="0">Choose Parent Category</option>
                            <?php
                            $_cats = getAllFrom('*', 'categories', 'Parent', '0','','', 'ID', 'ASC');
                            foreach($_cats as $_cat){
                                echo "<option value='" . $_cat['ID'] . "'>" . $_cat['Name'] . "</option>";
                                $Ccats = getAllFrom('*','categories', 'Parent', "{$_cat['ID']}",'','', 'ID', 'ASC');
                                foreach($Ccats as $c){
                                    echo "<option value='".$c['ID'] ."'>--". $c['Name'] ."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
				<div class="mb-3 req">
					<label class="form-label">Visability</label>
					<div class="form-check">
						<input type="radio" name="visibility" value="0" class="form-check-input" id="exampleCheck1" checked>
						<label class="form-check-label" for="exampleCheck1">Yes</label>
					</div>
					<div class="form-check">
						<input type="radio" name="visibility" value="1" class="form-check-input" id="exampleCheck2">
						<label class="form-check-label" for="exampleCheck2">No</label>
					</div>
				</div>
				<div class="mb-3 ">
					<label class="form-label">Allow Commenting</label>
					<div class="form-check">
						<input type="radio" name="commenting" value="0" class="form-check-input" id="exampleChec1" checked>
						<label class="form-check-label" for="exampleChec1">Yes</label>
					</div>
					<div class="form-check">
						<input type="radio" name="commenting" value="1" class="form-check-input" id="exampleChec2">
						<label class="form-check-label" for="exampleChec2">No</label>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">Allow Ads</label>
					<div class="form-check">
						<input type="radio" name="ads" value="0" class="form-check-input" id="exampleChe1" checked>
						<label class="form-check-label" for="exampleChe1">Yes</label>
					</div>
					<div class="form-check">
						<input type="radio" name="ads" value="1" class="form-check-input" id="exampleChe2">
						<label class="form-check-label" for="exampleChe2">No</label>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">Add Category</button>
			</form>
		</div>

<?php
	} elseif ($do == 'Insert') {

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $category->insert_cat($_POST['name'],$_POST['description'],$_POST['ordering'],$_POST['parent'],$_POST['visibility'],
            $_POST['commenting'],$_POST['ads']);
		} else {
			echo '<div class="container">';
			$theMsg = "<div class='alert alert-danger'>can't be reached</div>";
			redirectPage($theMsg, 'back');
			echo '</div>';
		}
		echo "</div>";
	} elseif ($do == 'Edit') {
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $cat=$category->edit_cat($_GET['catid']);
        if(isset($cat)){
		?>
			<h1 class="display-6">Edit Category</h1>
		<div class="container">
			<form class="edit-form" method="post" action="?do=Update">
				<div class="mb-3 req">
					<label for="exampleInputEmail1" class="form-label">Name</label>
					<input type="hidden" name="catid" value="<?php echo $catid; ?>">
					<input type="text" name="name" placeholder="Category Name" autocomplete="off" value="<?php echo $cat["Name"] ?>" class="form-control form-groub-lg" id="exampleInputEmail1" required>
				</div>
				<div class="mb-3 req">
					<label class="form-label">Description</label>
					<input type="text" name="description" placeholder="category description" value="<?php echo $cat["Description"] ?>" class="form-control form-groub-lg">
				</div>
				<div class="mb-3 req">
					<label class="form-label">Ordering</label>
					<input type="text" name="ordering" placeholder="category order" value="<?php echo $cat["Ordering"] ?>" class="form-control form-groub-lg">
				</div>
                <div class="mb-3 req">
                        <label  class="form-label">Parent</label>
                        <select class="" name="parent">
                            <option selected value="0">Choose Parent Category</option>
                            <?php
                            $_cats = getAllFrom('*', 'categories', 'Parent', '0','','', 'ID', 'ASC');
                            foreach($_cats as $_cat){
                                echo "<option value='" . $_cat['ID'] . "'>" . $_cat['Name'] . "</option>";
                                $Ccats = getAllFrom('*','categories', 'Parent', "{$_cat['ID']}",'','', 'ID', 'ASC');
                                foreach($Ccats as $c){
                                    echo "<option value='".$c['ID'] ."'>--". $c['Name'] ."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
				<div class="mb-3 req">
					<label class="form-label">Visibility</label>
					<div class="form-check">
						<input type="radio" name="visibility" value="0" class="form-check-input" id="exampleCheck1" value="0" <?php if($cat["Visibility"]==0){echo 'checked';}?> >
						<label class="form-check-label" for="exampleCheck1">Yes</label>
					</div>
					<div class="form-check">
						<input type="radio" name="visibility" value="1" class="form-check-input" id="exampleCheck2" <?php if($cat["Visibility"]==1){echo 'checked';}?>>
						<label class="form-check-label" for="exampleCheck2">No</label>
					</div>
				</div>
				<div class="mb-3 ">
					<label class="form-label">Allow Commenting</label>
					<div class="form-check">
						<input type="radio" name="commenting" value="0" class="form-check-input" id="exampleChec1" <?php if($cat["Allow_Comment"]==0){echo 'checked';}?>>
						<label class="form-check-label" for="exampleChec1">Yes</label>
					</div>
					<div class="form-check">
						<input type="radio" name="commenting" value="1" class="form-check-input" id="exampleChec2" <?php if($cat["Allow_Comment"]==1){echo 'checked';}?>>
						<label class="form-check-label" for="exampleChec2">No</label>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">Allow Ads</label>
					<div class="form-check">
						<input type="radio" name="ads" value="0" class="form-check-input" id="exampleChe1" <?php if($cat["Allow_Ads"]==0){echo 'checked';}?>>
						<label class="form-check-label" for="exampleChe1">Yes</label>
					</div>
					<div class="form-check">
						<input type="radio" name="ads" value="1" class="form-check-input" id="exampleChe2" <?php if($cat["Allow_Ads"]==1){echo 'checked';}?>>
						<label class="form-check-label" for="exampleChe2">No</label>
					</div>
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
	} elseif ($do == 'Update') {
		echo " <h1 class='display-6'>Update Member</h1>";
		echo " <div class='container'>";
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $category->update_cat($_POST['catid'],$_POST['name'],$_POST['description'],$_POST['ordering'],$_POST['parent'],
                $_POST['visibility'],$_POST['commenting'],$_POST['ads']);
		} else {
			
			$theMsg = "<div class='alert alert-danger'>can't be reached</div>";
			redirectPage($theMsg, '');
		}
		echo "</div>";
	} elseif ($do == 'Delete') {
        $category->delete_cat($_GET['catid']);
}
} else {
	header('location:index.php');
	exit();
}
ob_end_flush();
