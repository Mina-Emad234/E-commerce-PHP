<?php
class dashboard{

    /**
     * get comments
     * @param $num
     */
    public function get_comment($num){
        global $conn;
        $stmt = $conn->prepare("SELECT 
                                comments.*,  users.Username AS member
                            FROM 
                                comments
                            INNER JOIN
                                users
                            ON
                                users.UserID = comments.user_id 
                            ORDER BY
                                c_id DESC
                            LIMIT
                                $num");
        $stmt->execute();
        $comments = $stmt->fetchAll();
        if(!empty($comments)){
            foreach($comments as $comment){
                echo "<div class='comment-box'>";
                echo "<span class='member-n'> <a href='members.php?do=Edit&userid=". $comment['user_id'] ."'>". $comment['member'] . "</a></span>" ;
                echo "<p class='member-c'> ". $comment['comment'] . "</p>";
                echo "</div>";
            }
        }else{
            echo "there is no comment to show";
        }
    }
}