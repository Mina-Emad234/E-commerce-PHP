<?php
    ob_start();
    session_start();

    $pageTitle='Create New Ad';

    include "init.php";
    $product=new new_ad();
        if (isset($_SESSION['user'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
            $product->insert_product($_SERVER['REQUEST_METHOD'],$_FILES['image'],$_POST['name'],
            $_POST['description'],$_POST['price'], $_POST['country'],$_POST['status'],$_POST['category'],$_POST['tags']);
    }
    ?>
    <h1><?php echo $pageTitle; ?></h1>
    <div class="my-advertisments block">
        <div class="container">
            <div class="card">
                <div class="card-header bg-primary"><?php echo $pageTitle; ?></div>
                            <div class="card-body">
                                    <div class="row">
                                    <div class="col-md-8">
                                    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                <div class="mb-3 req">
                                    <label  class="form-label">Name</label>
                                    <input 
                                            pattern="{4,}"
                                            title="Item name mustn,t be less than 4 chars"
                                            type="text" 
                                            name="name" 
                                            placeholder="Item Name" 
                                            class="form-control form-groub-lg live"
                                            data-class=".live-title" 
                                            required>
                                </div>
                                <div class="mb-3 req">
                                    <label  class="form-label">Description</label>
                                    <input 
                                            pattern="{10,}"
                                            title="Item description mustn,t be less than 10 chars"
                                            type="text" 
                                            name="description" 
                                            placeholder="Item Description" 
                                            class="form-control form-groub-lg live" 
                                            data-class=".live-desc" 
                                            required>
                                </div>
                                <div class="mb-3 req">
                                    <label  class="form-label">Price</label>
                                    <input type="text" 
                                            name="price" 
                                            placeholder="Item Price" 
                                            class="form-control form-groub-lg live"
                                            data-class=".live-price"  
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
                                    <label  class="form-label">image</label>
                                    <input type="file"
                                            name="image"
                                            class="form-control form-groub-lg"
                                           onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])"                                    required>
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
                                    <label  class="form-label">Category</label>
                                    <select class="" name="category">
                                        <option selected value="0">Choose One</option>
                                        <?php
                                         $cats = getAllFrom('*', 'categories', 'Parent', 0,'','', 'ID', 'ASC');
                                            foreach($cats as $cat){
                                             echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>"; 
                                                 $Ccats = getAllFrom('*','categories', 'Parent', "{$cat['ID']}",'','', 'ID', 'ASC');
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
                                <button type="submit" class="btn btn-primary mb-2">Add Item</button>
                            </form>
                        </div>
                        <div class='col-sm-4 ad_new'>
                            <div class='card item-box'>
                            <span class='price-tag'>
                                $<span class="live-price">0</span>
                            </span>;
                                <img id="output" src="" class="card-img-top" alt="">';
                                <div class='card-body'>
                                <h3  class='card-title live-title'>Title</h3>
                                    <p class='card-text live-desc'>Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php
                        $product->get_status();
                        ?>
                </div>
            </div>
        </div>
    </div>
    


    <?php 
    }else {
        header("location:login.php");
        exit();
    }

    include $tpl . "footer.php"; 
    ob_end_flush();
    ?>