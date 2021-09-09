<?php
class comment{
    /**
     * get comments
     * @return array
     */
    public function get_comments()
    {
        global $conn;
        $stmt = $conn->prepare("SELECT 
                                    comments.*, items.Name AS item_name, users.Username AS member
                                FROM 
                                    comments
                                INNER JOIN
                                    items
                                ON
                                    items.Item_ID = comments.item_id 
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = comments.user_id 
                                ORDER BY
                                    c_id DESC");
        $stmt->execute();
        return $comments = $stmt->fetchAll();
    }

    /**
     * edit comment
     * @param $comid
     * @return mixed
     */
    public function edit_comment($comid){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM comments WHERE c_id=?");
        $stmt->execute(array($comid));
        return $row = $stmt->fetch();
    }

    /**
     * update comment
     * @param $c_id
     * @param $_comment
     */
    public function update_comment($c_id,$_comment){
        global $conn;
        echo " <h1 class='display-6'>Update Comment</h1>";
        echo " <div class='container'>";
        $id         = $c_id;
        $comment    = $_comment;
        $stmt = $conn->prepare("UPDATE comments SET comment=? WHERE c_id=?");
        $stmt->execute(array($comment,$id));
        $theMsg = "<div class='alert alert-success'>Success update</div>";
        redirectPage($theMsg,'back');
    }

    /**
     * delete comment
     * @param $comid
     */
    public function delete_comment($comid){
        global $conn;
        echo " <h1 class='display-6'>Delete Comment</h1>";
        echo " <div class='container'>";
        $check = CheckDB("c_id", "comments", $comid);

        if ($check > 0) {
            $stmt = $conn->prepare("DELETE FROM comments WHERE c_id=? ");
            $stmt->bindParam(1, $comid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>comment Delted</div>";
            redirectPage($theMsg, 'back');
        } else {
            $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";
            redirectPage($theMsg, '');
        }
        echo "</div>";
    }

    /**
     * approve comment
     * @param $comid
     */
    public function approve_comment($comid){
        global $conn;
        echo " <h1 class='display-6'>Approve Comment</h1>";
        echo " <div class='container'>";
        $check = CheckDB("c_id", "comments", $comid);

        if ($check > 0) {
            $stmt = $conn->prepare("UPDATE comments SET status=1 WHERE c_id=? ");
            $stmt->bindParam(1, $comid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>Comment Approved</div>";
            redirectPage($theMsg, 'back');
        } else {
            $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";
            redirectPage($theMsg, '');
        }
        echo "</div>";
    }

}