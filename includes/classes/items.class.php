<?php
class items{

    /**
     * get product
     * @param $item_id
     * @return mixed
     */
    public function get_item_data($item_id){
        global $conn;
        global $itemid;
        global $count;
        $itemid = isset($item_id) && is_numeric($item_id) ? intval($item_id) : 0;
        $stmt = $conn->prepare("SELECT 
                                    items.*,
                                    categories.Name AS Category_Name,
                                    users.Username,users.UserID
                                FROM
                                    items
                                INNER JoIN
                                    categories
                                ON
                                    categories.ID=Items.Cat_ID
                                INNER JoIN
                                    users
                                ON
                                    users.UserID=Items.Member_ID
                                WHERE
                                    Item_ID =?
                                AND
                                    Approve = 1");
        $stmt->execute(array($itemid));
        $count=$stmt->rowCount();
        if($count>0) {
            return $item = $stmt->fetch();
        }
    }

    /**
     * add comment
     * @param $comm
     * @param $item_id
     * @param $user_id
     */
    public function insert_comment($comm,$item_id,$user_id){
        global $conn;
        $comment = filter_var($comm, FILTER_SANITIZE_STRING);
        $itemid  = $item_id;
        $userid  = $user_id;
        $form = array();
        if(!empty($comment)){
            $stmt = $conn -> prepare("INSERT INTO comments(comment, status, comment_date, item_id, user_id) 
                                                                    VALUES (:com, 0, NOW() , :itemid, :userid)");
            $stmt->execute(array(':com'=>$comment, ':itemid'=>$itemid, ':userid'=>$userid));
            if ($stmt) {
                echo "<div class = 'alert mt-2 alert-success' >comment Added</div>";

            }

        }
        if (empty($form)) {
            $comment = "";
            $itemid  = "";
            $userid  = "";
            header("location:items.php?itemid=" . $item_id . "");
        }
    }

    /**
     * get comments
     * @return array
     */
    public function get_comment()
    {
        global $conn;
        global $itemid;
        $stmt = $conn->prepare("SELECT 
                                            comments.*, users.Username AS member,users.Image AS img
                                        FROM 
                                            comments
                                        INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.user_id 
                                        WHERE
                                            item_id=? AND status = 1
                                        ORDER BY
                                            c_id DESC");
        $stmt->execute(array($itemid));
        return $comments = $stmt->fetchALl();
    }

    /**
     * get messages if item id isn,t correct
     */
    public function msg(){
        global $count;
        if ($count == 0){
        echo "<div class = 'container'>";
        echo "<div class = 'alert alert-danger'>there is no such id or this Item is waiting Approval</div></div>";
         }
    }
}