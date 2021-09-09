<?php
    session_start();

    $pageTitle='Profile';

    include "init.php";
    $profile=new profile();
    if (isset($_SESSION['user'])) {
        $info=$profile->get_user($sessionUser);
    ?>
    <h1>My Profile</h1>
    <div class="information block">
        <div class="container">
            <div class="card">
                <div class="card-header bg-primary">My Information</div>
                <div class="card-body">
                    <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Name</span> : <?php echo $info['Username']; ?>
                    </li>
                    <li>
                        <i class="fa fa-envelope fa-fw"></i>
                        <span>Email</span> : <?php echo $info['Email']; ?>
                        </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Full Name</span> : <?php echo $info['FullName']; ?>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Register Date</span> : <?php echo $info['Date']; ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Favourite Categry</span> : </li>
                    </ul>
                    <a href="#" class="btn btn-info mt-2 float-end">Edit Information</a>
                </div>
            </div>
        </div>
    </div>

    <div id="myitem" class="my-advertisments block">
        <div class="container">
            <div class="card">
                <div class="card-header bg-primary">My Items</div>
                <div class="card-body">
                
        <?php
            if(!empty( getItems('Member_ID',$info['UserID'])) ){
                echo '<div class="row">';
            foreach( getItems('Member_ID',$info['UserID']) as $item){
                echo "<div class='col-sm-6 col-md-3'>";
                    echo "<div class='card item-box'>";
                        echo "<span class='price-tag'>" . $item['Price'] . "</span>";
                         echo '<img src=uploads/images/' . $item['Image'] .' classcard-img-top" alt="">';
                        echo "<div class='card-body'>";
                            echo "<h3  class='card-title'><a href='items.php?itemid=" . $item['Item_ID'] . "'>" . $item['Name'] . "</a></h3>";
                            echo "<p class='card-text'>" . $item['Description'] . "</p>";
                            echo "<div class='date'>" . $item['Add_Date'] . "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
             }
             echo "</div>";
            }else {
                echo "sorry there's no Ads to show, Create <a href='new-advertise.php'>New Ad</a>";
            }
        ?>
    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="my-comments block">
        <div class="container">
            <div class="card card-primary">
                <div class="card-header bg-primary">My Comments</div>
                <div class="card-body">
                    <?php
                    $profile->get_comment($info['UserID']);
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
