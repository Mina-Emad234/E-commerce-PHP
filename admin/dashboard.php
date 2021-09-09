<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'Dashboard';
    include 'init.php';
    // echo 'welcome ' . $_SESSION['username'];
    $numUsers = 3;
    $latestUsers = getLatest('*', 'users', 'UserID', $numUsers);
    $numItems = 3;
    $latestItems = getLatest('*', 'items', 'Item_ID', $numItems);
    $numComments = 2;
?>
    <div class="home-stats">
        <div class="container">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Member
                            <span><a href="members.php"><?php echo countItems('UserID', 'users'); ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Member
                            <span><a href="members.php?do=Manage&page=pending">
                                    <?php echo CheckDB('RegStatus', 'users', 0); ?>
                                </a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span>
                                <a href="items.php"><?php echo countItems('Item_ID', 'items'); ?></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <span>
                                <a href="items.php"><?php echo countItems('c_id', 'comments'); ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card m-2">
                        <div class="card-header">
                            <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Regestered Users
                            <span class="toggle-info float-end">
                                <i class='fa fa-plus fa-lg'></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-group latest-users">
                                <?php
                                if(!empty($latestUsers)){
                                foreach ($latestUsers as $user) {
                                    echo "<li class='list-group-item'>" . $user['Username'];

                                    echo "<a href='members.php?do=Edit&userid=" . $user['UserID'] . "'><span class='btn btn-success lue'><i class='fa fa-edit'></i> Edit";
                                    if ($user['RegStatus'] == 0) {
                                        echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Activate</a>";
                                    }
                                    echo "</span></a></li>";
                                }
                            }else {
                                echo "there is no user to show";
                            }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card m-2">
                        <div class="card-header">
                            <i class="fa fa-tag"></i> Latest <?php echo $numItems; ?> Items
                            <span class="toggle-info float-end">
                                <i class='fa fa-plus fa-lg'></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-group latest-users">
                                <?php
                                if(!empty($latestItems)){
                                foreach ($latestItems as $item) {
                                    echo "<li class='list-group-item'>" . $item['Name'];

                                    echo "<a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "'><span class='btn btn-success lue'><i class='fa fa-edit'></i> Edit";
                                    if ($item['Approve'] == 0) {
                                        echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                                    }
                                    echo "</span></a></li>";
                                }
                            }else {
                                echo "there is no Items to show";
                            }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="card m-2">
                        <div class="card-header">
                            <i class="fa fa-comment-o"></i> Latest <?php $numComments ?> comments
                            <span class="toggle-info float-end">
                                <i class='fa fa-plus fa-lg'></i>
                            </span>
                        </div>
                        <div class="card-body">
                            <?php
                            $dashboard=new dashboard();
                            $dashboard->get_comment($numComments);
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
<?php
} else {
    header('location:index.php');
    exit();
}
ob_end_flush();
