<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="dashboard.php"><?php echo "Home"; ?></a>+
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav me-auto mb-0 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="categories.php"><?php echo "Categories"; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="items.php"><?php echo "Items"; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="members.php"><?php echo "Members"; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="comments.php"><?php echo "Comments"; ?></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="orders.php"><?php echo "Orders"; ?></a>
        </li>
    </ul>
        <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?php echo "More"; ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="../index.php"><?php echo "Visit Shop"; ?></a></li>
            <li><a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>"><?php echo "Edit Profile"; ?></a></li>
            <li><a class="dropdown-item" href="logout.php"><?php echo "Logout"; ?></a></li>
          </ul>
        </li>
      </ul>
</div>
</div>
</nav>
