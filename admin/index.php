<?php
    ob_start();
    session_start();
    $noNavbar='';
    $pageTitle='Login';
    if (isset($_SESSION['username'])) {
        header('location:dashboard.php');
    }
    include "init.php";
    $login=new admin_login();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $login->login($_POST['user'],$_POST['pass']);
    }
    ob_end_flush();
?>

<form class="login" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <h4>Admin login</h4>
  <div class="mb-3">
    <input type="text" class="form-control" name="user" placeholder="username" autocomplete="off">
  </div>
  <div class="mb-3">
    <input type="password" class="form-control" name="pass" placeholder="Password" autocomplete="off">
  </div>
  <div class="d-grid gap-2">
  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
  </div>
</form>