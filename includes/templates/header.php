<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getTitle(); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $css; ?>all.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $css; ?>jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $css; ?>front.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $css; ?>jquery.tagsinput-revisited.css" />
</head>
<body>
    <div class="upper-bar">
        <div class="container">
        <?php
            if(isset($_SESSION['user'])){ ?>
                <img src="https://via.placeholder.com/300/#00f" class="my-img img-thumbnail rounded-circle" alt="">
                <div class="btn-group">
                <button type="button" class="myb btn btn-default dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $sessionUser ?>
                </button>
                <ul class="dropdown-menu">

                    <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                    <li><a class="dropdown-item" href="new-advertise.php">New AD</a></li>
                    <li><a class="dropdown-item" href="profile.php#myitem">My Item</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </div>
                <?php
                /* echo "welcome " . $sessionUser;
                echo " <a href='profile.php'>My Profile</a>";
                echo " - <a href='new-advertise.php'>New Item</a>";
                echo " - <a href='logout.php'>Logout</a>";
                $userStatus = checkUserStatus($sessionUser);
                if ($userStatus == 1) {
                    # user is not activated
                } */
            }else{
            echo '<span class="d-flex justify-content-end"><a href="login.php">login</a></span>';
            }
            ?>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Homepage</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="float-end" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-0 mb-lg-0">
            <?php
            foreach(getAllFrom('*','categories', 'Parent', 0,'','', 'ID', 'ASC') as $cat){
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="categories.php?pageid=' . $cat['ID'] . '"> ' . $cat['Name'] . '</a></li>';
            }
            ?>
        </ul>
    </div>
    </div>
    </nav>
