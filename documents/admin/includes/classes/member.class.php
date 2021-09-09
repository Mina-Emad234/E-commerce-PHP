<?php
class member{

    /**
     * get members
     * @return array
     */
    public function get_members(){
        global $conn;
        $query='';
        if(isset($_GET['page']) && $_GET['page']=='pending'){
            $query='AND RegStatus = 0';
        }
        $stmt = $conn->prepare("SELECT * FROM users WHERE GroupID !=1 $query ORDER BY UserID DESC");
        $stmt->execute();
        return $rows = $stmt->fetchAll();
    }

    /**
     * add new member
     * @param $profile
     * @param $username
     * @param $password
     * @param $_email
     * @param $fullname
     */
    public function add_member($profile,$username,$password,$_email,$fullname){
        global $conn;
        echo " <h1 class='display-6'>Add Member</h1>";
        echo " <div class='container'>";

        $profileName = $profile['name'];
        $ProfileSize = $profile['size'];
        $ProfileTmp  = $profile['tmp_name'];
        $ProfileType = $profile['type'];
        $Allowed_exe = array("jpeg", "jpg", "png", "gif");
        $profile_exe = explode('.', strtolower($profileName));
        $exe = end($profile_exe);

        $user   = $username;
        $pass   = $password;
        $email  = $_email;
        $name   = $fullname;
        $hashedPass = sha1($password);
        $formErr = array();
        if (strlen($user) < 4) {
            $formErr[] = "Username can't be less than <strong>4 Caracters</strong>";
        }
        if (strlen($user) > 20) {
            $formErr[] = "Username can't be more than <strong>20 Caracters</strong>";
        }
        if (empty($user)) {
            $formErr[] = "Username can't be <strong>Empty</strong>";
        }
        if (empty($pass)) {
            $formErr[] = "Password can't be <strong>Empty</strong>";
        }
        if (empty($email)) {
            $formErr[] = "Email can't be <strong>Empty</strong>";
        }
        if (empty($name)) {
            $formErr[] = "Full Name can't be <strong>Empty</strong>";
        }
        if (!empty($profileName) && !in_array($exe, $Allowed_exe) ) {
            $formErr[] = "this Exetension isn't  <strong>Allowed</strong>";
        }
        if (empty($profileName)) {
            $formErr[] = "Profile is <strong>Required</strong>";
        }
        if ($ProfileSize > 4194304) {
            $formErr[] = "Image must be larger than <strong>4MB</strong>";
        }

        foreach ($formErr as $err) {
            echo "<div class='alert alert-danger'>" . $err . "</div>";
        }

        if (empty($formErr)) {
            $profile = rand(0,1000000000) . "_" . $profileName;
            move_uploaded_file($ProfileTmp, "uploads\profiles\\" . $profile);
            $check = CheckDB("Username", "users", $user);
            if ($check == 1) {
                $theMsg = "<div class='alert alert-danger'>User Exists</div>";
                redirectPage($theMsg,'back');

            } else {
                $stmt = $conn->prepare("INSERT INTO users (Username,Password, Email, Fullname,RegStatus, Date,Image) VALUES (:user,:pass,:mail,:full, 1, now(),:profile )");
                $stmt->execute(array(":user" => $user, ":pass" => $hashedPass, ":mail" => $email, ":full" => $name,":profile" =>$profile));
                $theMsg = "<div class='alert alert-success'>Member Added</div>";
                redirectPage($theMsg,'back');
            }
        }
    }

    /**
     * edit member
     * @param $userid
     * @return mixed
     */
    public function edit_member($userid){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserID=? LIMIT 1");
        $stmt->execute(array($userid));
        return $row = $stmt->fetch();
    }

    /**
     * update member
     * @param $profile
     * @param $user_id
     * @param $username
     * @param $_email
     * @param $fullname
     * @param $newpass
     * @param $oldpass
     */
    public function update_member($_profile,$user_id,$username,$_email,$fullname,$newpass,$oldpass,$img){
        global $conn;
        echo " <h1 class='display-6'>Update Member</h1>";
        echo " <div class='container'>";
        $profileName = $_profile['name'];
        $ProfileSize = $_profile['size'];
        $ProfileTmp  = $_profile['tmp_name'];
        $ProfileType = $_profile['type'];
        $Allowed_exe = array("jpeg", "jpg", "png", "gif");
        $profile_exe = explode('.', strtolower($profileName));
        $exe = end($profile_exe);

        $id     = $user_id;
        $user   = $username;
        $email  = $_email;
        $name   = $fullname;
        if(!empty($profileName)) {
            $oldProfile = getImage($id);
        }
        $pass = empty($newpass) ?  $oldpass : sha1($newpass);

        $formErr = array();
        if (strlen($user) < 4) {
            $formErr[] = "Username can't be less than <strong>4 Caracters</strong>";
        }
        if (strlen($user) > 20) {
            $formErr[] = "Username can't be more than <strong>20 Caracters</strong>";
        }
        if (empty($user)) {
            $formErr[] = "Username can't be <strong>Empty</strong>";
        }
        if (empty($email)) {
            $formErr[] = "Email can't be <strong>Empty</strong>";
        }
        if (empty($name)) {
            $formErr[] = "Full Name can't be <strong>Empty</strong>";
        }
        if (!empty($profileName) && !in_array($exe, $Allowed_exe) ) {
            $formErr[] = "this Exetension isn't  <strong>Allowed</strong>";
        }
        if ($ProfileSize > 4194304) {
            $formErr[] = "Image must be larger than <strong>4MB</strong>";
        }

        foreach ($formErr as $err) {
            echo "<div class='alert alert-danger'>" . $err . "</div>";
        }

        if (empty($formErr)) {
            if (isset($oldProfile)) {
                unlink($oldProfile);
                $profile = rand(0, 1000000000) . "_" . $profileName;
                move_uploaded_file($ProfileTmp, "uploads\profiles\\" . $profile);
            }else{
                $profile=$img;
            }
            $stmt2=$conn->prepare("SELECT*FROM users WHERE Username=? AND UserID != ?");
            $stmt2->execute(array($user,$id));
            $count = $stmt2->rowCount();
            if($count == 1){
                $theMsg = "<div class='alert alert-danger'>Sorry this User is exist</div>";
                redirectPage($theMsg, 'back');
            }else{
                $stmt = $conn->prepare("UPDATE users SET Username=?, Email=?, Fullname=?, Password=?, Image=? WHERE UserID=?");
                $stmt->execute(array($user, $email, $name, $pass, $profile,$id));
                $theMsg = "<div class='alert alert-success'>Success update</div>";
                redirectPage($theMsg,'members.php');

            }
        }
    }

    /**
     * delete member
     * @param $userid
     */
    public function delete_member($userid){
        global $conn;
        echo " <h1 class='display-6'>Delete Member</h1>";
        echo " <div class='container'>";
        $check = CheckDB("UserID", "users", $userid);

        if ($check > 0) {
            $stmt = $conn->prepare("DELETE FROM users WHERE UserID=? ");
            $stmt->bindParam(1, $userid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>user Delted</div>";
            redirectPage($theMsg, 'back');
        } else {
            $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";
            redirectPage($theMsg, '');
        }
        echo "</div>";
    }

    /**
     * Activate member
     * @param $userid
     */
    public function activate_member($userid){
        global $conn;
        echo " <h1 class='display-6'>Activate Member</h1>";
        echo " <div class='container'>";
        $check = CheckDB("UserID", "users", $userid);

        if ($check > 0) {
            $stmt = $conn->prepare("UPDATE users SET RegStatus=1 WHERE UserID=? ");
            $stmt->bindParam(1, $userid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>user Activated</div>";
            redirectPage($theMsg, 'back');
        } else {
            $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";
            redirectPage($theMsg, '');
        }
        echo "</div>";
    }
}