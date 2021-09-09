<?php
    ob_start();
    session_start();

    $pageTitle = 'Show Items';

    include "init.php";
    $_item=new items();
$item=$_item->get_item_data($_GET['itemid']);


    ?>
    <h1><?php echo  $item['Name'];?></h1>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                <img src="<?php echo 'uploads/images/' .$item['Image'] ?>" class="img-thumbnail" alt="">
                </div>
                <div class="col-md-9 item-info">
                <ul class="list-unstyled">
                    <h2><?php echo $item['Name'];?></h2>
                    <p><?php echo $item['Description'];?></p>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Added Date</span> : <?php echo $item['Add_Date']; ?>
                    </li>
                    <li>
                        <i class="fa fa-money-bill-wave fa-fw"></i>
                        <span>Price</span> : <?php echo $item['Price']; ?>
                        </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Made In</span> : <?php echo $item['Country_Made']; ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID']; ?>"><?php echo $item['Category_Name']; ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By</span> : <a href="#"><?php echo $item['Username']; ?></a>
                    </li>
                    <li class="tags-item">
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Tags</span> : 
                        <?php
                            $alltags=explode(",",$item['tags']);
                            foreach($alltags as $tag){
                                $tag = strtolower(str_replace(' ','', $tag));
                                echo "<a href='tags.php?name={$tag}'>" . $tag . "</a>";
                            }
                        ?>
                    </li>
                    <?php  if(isset($_SESSION['user'])){ if($item['Member_ID']!==$_SESSION['uid']){ ?>

                    <li>
                         <a class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" href="pay.php?item_price=<?php echo $item['Price'] ?>&item_id=<?php echo $item['Item_ID'] ?>">Buy</a>
                    </li>
                <?php }} ?>
                    </ul>
                </div>
            </div>
            <hr class="custom">
            <?php 
            if(isset($_SESSION['user'])){
            ?>
            <div class="row">
                <div class="col-md-3 offset-md-3">
                    <div class="add-comment">
                        <h3>Add Your Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="post">
                            <textarea name="comment" required></textarea>
                            <input type="submit" class="btn btn-primary" value="Add Comment">
                        </form>
                        <?php 
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $_item->insert_comment($_POST['comment'],$item['Item_ID'],$_SESSION['uid']);
                            }
                        ?>
                    </div>
                </div>
            </div>
            <hr class="custom">
            <?php
            $comments=$_item->get_comment();
            foreach($comments as $comment){
            ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img src="/admin/uploads/profiles/<?php echo $comment['img'] ?>" class="img-thumbnail rounded-circle" alt="">
                        <?php  echo $comment['member'] ?>
                    </div>
                    <div class="col-sm-10">
                        <p class="load"><?php echo $comment['comment'] ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to buy this product
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <?php 
        }else {
        echo "<a href='login.php'>login</a> to add comment";
    } 



    include $tpl . "footer.php";
    ob_end_flush();
    ?>
