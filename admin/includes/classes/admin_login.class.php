<?php
class admin_login{
    /**
     * admin login
     * @param $user
     * @param $pass
     */
    public function login($user,$pass){
        global $conn;
        $username = $user;
        $password = $pass;
        $hasedpass=sha1($password);
        $stmt=$conn->prepare("SELECT  UserID,Username,Password FROM users WHERE Username=? AND Password=? AND GroupID=1 LIMIT 1");
        $stmt->execute(array($username,$hasedpass));
        $row=$stmt->fetch();
        $count=$stmt->rowCount();
        if ($count > 0) {
            $_SESSION['username']=$username;
            $_SESSION['ID']=$row['UserID'];
            header('location:dashboard.php');
            exit();
        }
    }
}