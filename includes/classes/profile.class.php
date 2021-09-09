<?php
class profile{

    /**
     * get user info
     * @param $sessionUser
     * @return mixed
     */
    public function get_user($sessionUser){
        global $conn;
        $stmt=$conn->prepare("SELECT * FROM users WHERE Username=?");
        $stmt->execute(array($sessionUser));
        return $info=$stmt->fetch();
    }

    /**
     * get comments
     * @param $user_id
     */
    public function get_comment($user_id){
        global $conn;
        $stmt=$conn->prepare("SELECT comment FROM comments WHERE user_id=?");
        $stmt->execute(array($user_id));
        $comments=$stmt->fetchAll();
        if (!empty($comments)) {
            foreach ($comments as $comment ) {
                echo $comment['comment'];
            }
        } else {
            echo "there's no comment to show";
        }
    }

}
?>