<?php
ob_start();
session_start();
$pageTitle='Login';
if (isset($_SESSION['user'])) {
    header('location:index.php');
}
    include "init.php";
    $login_class = new login();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['login'])) {
            $login_class->get_login_data( $_POST['username'], $_POST['password']);
        }  else {
            $login_class->get_register($_POST['username'], $_POST['password'], $_POST['password2'], $_POST['email']);
        }
    }

?>
<div class="contaoner login-page">
<h1>
    <span class="selected" data-class="login">Login</span> |
    <span data-class="signup">Signup</span>
</h1>
<form class="login" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
  <div class="input-container mb-3">
    <input type="text" class="form-control" name="username"  autocomplete="off" required placeholder="username">
  </div>
  <div class="input-container mb-3">
    <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password" required>
  </div>
  <button type="submit" name="login" class="btn btn-primary form-control">login</button>
</form>

<form class="signup"  method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
  <div class="input-container mb-3">
    <input type="text" class="form-control" pattern="{4,}" title="Username must be more than 4 chars" name="username" placeholder="username" autocomplete="off" required>
  </div>
  <div class="input-container mb-3">
    <input type="password" minlength="4" class="form-control" name="password" placeholder="Password" autocomplete="new-password" required>
  </div>
  <div class="input-container mb-3">
    <input type="password" minlength="4" class="form-control" name="password2" placeholder="Password again" autocomplete="new-password" required>
  </div>
  <div class="input-container mb-3">
    <input type="email" class="form-control" name="email" placeholder="email" autocomplete="off" required>
  </div>
  <button type="submit" name="signup" class="btn btn-primary form-control">signup</button>
</form>
<?php
$login_class->get_error();
?>
</div>
<?php include $tpl . "footer.php"; 
ob_end_flush();
?>