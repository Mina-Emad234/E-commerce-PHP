<?php
class login{

    /**
     * login
     * @param $user_name
     * @param $pass
     */
    public function get_login_data($user_name,$pass){
        global $conn;
                $user = $user_name;
                $hashedpass=sha1($pass);
                $stmt=$conn->prepare("SELECT  UserID,Username,Password FROM users WHERE Username=? AND Password=?");
                $stmt->execute(array($user,$hashedpass));
                $get=$stmt->fetch();
                $count=$stmt->rowCount();
                if ($count > 0) {
                    $_SESSION['user']=$user;
                    $_SESSION['uid']=$get['UserID'];
                    header('location:index.php');
                    exit();
                }

            }

            /**
             * register
             * @param $user_name
             * @param $pass
             * @param $password2
             * @param $e_mail
             */
            public function get_register($user_name,$pass,$password2,$e_mail){
                global $conn;
                global $formErr;
                global $successMsg;

                $username   = $user_name;
                $pass1      = $pass;
                $pass2      = $password2;
                $email      = $e_mail;

                $formErr=[];
                if (isset($username)) {
                    $fuser=filter_var($username, FILTER_SANITIZE_STRING);
                    if (strlen($fuser) < 4) {
                        $formErr[]="Username must be more than <strong>4 characters</strong>";
                    }
                }
                if (isset($pass1) && isset($pass2)) {
                    if (empty($pass1)) {
                        $formErr[]="password mustn't be <strong>Empty</strong>";
                    }
                    if (sha1($pass1) !== sha1($pass2)) {
                        $formErr[]="Sorry password <strong>isn't Match</strong>";
                    }
                }
                if (isset($email)) {
                    $filteredemail=filter_var($email, FILTER_SANITIZE_EMAIL);
                    if (filter_var($filteredemail,FILTER_VALIDATE_EMAIL) != true) {
                        $formErr[]="Email <strong>Isn't Valid</strong>";
                    }
                }
                if (empty($formErr)) {
                    $check= CheckDB("Username", "users", $username);
                    if ($check == 1) {
                        $formErr[] = "User Exists";
                    } else {
                        $stmt = $conn->prepare("INSERT INTO users (Username,Password, Email, RegStatus, Date) VALUES (:user,:pass,:mail, 0, now() )");
                        $stmt->execute(array(":user" => $fuser, ":pass" =>sha1($pass1), ":mail" => $filteredemail));
                        $successMsg = "User Registered";
                    }
                }

            }

            /**
             * get error & success status
             */
            public function get_error(){
                global $formErr;
                global $successMsg;
                if (!empty($formErr)) {
                    foreach ($formErr as $err) {
                        echo '<div class="the-errors text-center">
                         <div class="msg error">' . $err  . '</div>
                           </div>';
                    }
                }
                if (isset( $successMsg)) {
                    echo '<div class="the-errors text-center">
                         <div class="msg error">' . $successMsg  . '</div>
                           </div>';
                }
            }


}