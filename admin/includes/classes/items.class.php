<?php
class Item{

    /**
     * get items
     * @return array
     */
    public function get_items(){
        global $conn;
        $stmt = $conn->prepare("SELECT 
                                    items.*,
                                    categories.Name AS Category_Name,
                                    users.Username
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
                                ORDER BY
                                    Item_ID DESC");

        $stmt->execute();
        return $items = $stmt->fetchAll();
    }

    /**
     * add new item
     * @param $item_name
     * @param $description
     * @param $_price
     * @param $_country
     * @param $_status
     * @param $_member
     * @param $_category
     * @param $_tags
     * @param $_image
     */
    public function add_item($item_name,$description,$_price,$_country,$_status,$_member,$_category,$_tags,$_image){
        global $conn;
        echo " <h1 class='display-6'>Add Item</h1>";
        echo " <div class='container'>";
        $name       = $item_name;
        $desc       = $description;
        $price      = $_price;
        $country    = $_country;
        $status     = $_status;
        $member     = $_member;
        $cat        = $_category;
        $tags       = $_tags;
        $file_name = $_image['name'];
        $file_size =$_image['size'];
        $file_tmp =$_image['tmp_name'];
        $file_type=$_image['type'];
        $name_array = explode('.', $file_name);
        $file_ext=strtolower(end($name_array));

        $extensions= array("jpeg","jpg","png");

        $formErr = array();
        if (empty($name)) {
            $formErr[] = "Name can't be <strong>Empty</strong>";
        }
        if (empty($desc)) {
            $formErr[] = "Description can't be <strong>Empty</strong>";
        }
        if (empty($price)) {
            $formErr[] = "Price can't be <strong>Empty</strong>";
        }
        if (empty($country)) {
            $formErr[] = "Country can't be <strong>Empty</strong>";
        }

        if ($status == 0) {
            $formErr[] = "Choose one<strong> Status </strong>";
        }
        if ($member == 0) {
            $formErr[] = "Choose one<strong> Member </strong>";
        }
        if ($cat == 0) {
            $formErr[] = "Choose one<strong> Category </strong>";
        }
        if (empty($file_name)) {
            $formErr[] = "Profile is <strong>Required</strong>";
        }
        if(in_array($file_ext,$extensions)=== false){
            $formErr[]="extension not allowed, please choose a <strong>JPEG</strong> or <strong>PNG</strong> file.";
        }

        if($file_size > 4194304){
            $formErr[]='File size must be exactly <strong>4 MB</strong>';
        }

        foreach ($formErr as $err) {
            echo "<div class='alert alert-danger'>" . $err . "</div>";
        }

        if (empty($formErr)) {
            $image=rand(0,1000000)."_".$file_name;
            move_uploaded_file($file_tmp,"../uploads/images//".$image);

            $stmt = $conn->prepare("INSERT INTO items(Name, Description, Price, Country_Made, image, Status, Add_Date, Cat_ID, Member_ID, tags ) VALUES (:name,:desc,:price,:country,:img,:status, now(), :cat, :mem, :tags )");
            $stmt->execute(array(":name"    => $name,
                ":desc"    => $desc,
                ":price"   => $price,
                ":country" => $country,
                ":img"     => $image,
                ":status"  => $status,
                ":cat"     => $cat,
                ":mem"     => $member,
                ":tags"    => $tags));
            $theMsg = "<div class='alert alert-success'>Item Added</div>";
            redirectPage($theMsg,'back');

        }

    }

    /**
     * edit item
     * @param $itemid
     * @return mixed
     */
    public function edit_item($itemid){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM items WHERE Item_ID=?");
        $stmt->execute(array($itemid));
        return $item = $stmt->fetch();
    }

    /**
     * get comments related to items
     * @param $itemid
     * @return array
     */
    public function get_item_comment($itemid){
        global $conn;
        $stmt = $conn->prepare("SELECT 
                                    comments.*,  users.Username AS member
                                FROM 
                                    comments
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = comments.user_id 
                                WHERE
                                    item_id=?");
        $stmt->execute(array($itemid));
        return $rows = $stmt->fetchAll();
    }

    /**
     * update item
     * @param $item_id
     * @param $item_name
     * @param $description
     * @param $_price
     * @param $_country
     * @param $_status
     * @param $_member
     * @param $_category
     * @param $_tags
     * @param $_image
     */
    public function update_item($item_id,$item_name,$description,$_price,$_country,$_status,$_member,$_category,$_tags,$_image,$img){
        global $conn;
        echo " <h1 class='display-6'>Update Item</h1>";
        echo " <div class='container'>";
        $id         = $item_id;
        $name       = $item_name;
        $desc       = $description;
        $price      = $_price;
        $country    = $_country;
        $status     = $_status;
        $member     = $_member;
        $cat        = $_category;
        $tags       = $_tags;
        $file_name = $_image['name'];
        $file_size =$_image['size'];
        $file_tmp =$_image['tmp_name'];
        $file_type=$_image['type'];
        $name_array = explode('.', $file_name);
        $file_ext=strtolower(end($name_array));

        $extensions= array("jpeg","jpg","png");
        if (!empty($file_name)) {
            $oldImage = getImageItem($id);
        }
        $formErr = array();
        if (empty($name)) {
            $formErr[] = "Name can't be <strong>Empty</strong>";
        }
        if (empty($desc)) {
            $formErr[] = "Description can't be <strong>Empty</strong>";
        }
        if (empty($price)) {
            $formErr[] = "Price can't be <strong>Empty</strong>";
        }
        if (empty($country)) {
            $formErr[] = "Country can't be <strong>Empty</strong>";
        }

        if ($status == 0) {
            $formErr[] = "Choose one<strong> Status </strong>";
        }
        if ($member == 0) {
            $formErr[] = "Choose one<strong> Member </strong>";
        }
        if ($cat == 0) {
            $formErr[] = "Choose one<strong> Category </strong>";
        }

        if(!empty($file_ext) && in_array($file_ext,$extensions)== false){
            $formErr[]="extension not allowed, please choose a <strong>JPEG</strong> or <strong>PNG</strong> file.";
        }

        if($file_size > 4194304){
            $formErr[]='File size must be exactly <strong>4 MB</strong>';
        }

        foreach ($formErr as $err) {
            echo "<div class='alert alert-danger'>" . $err . "</div>";
        }

        if (empty($formErr)) {
            if(isset($oldImage)) {
                unlink($oldImage);
                $image = rand(0, 1000000) . "_" . $file_name;
                move_uploaded_file($file_tmp, "../uploads/images//" . $image);
            }else{
                $image=$img;
            }
            $stmt = $conn->prepare("UPDATE items SET Name=?, Description=?, Price=?, Country_Made=?,  Image=?,Status=?,  Cat_ID=?, Member_ID=?, tags=? WHERE Item_ID=?");
            $stmt->execute(array($name, $desc, $price, $country, $image,$status, $cat, $member, $tags,$id));
            $theMsg = "<div class='alert alert-success'>Success update</div>";
            redirectPage($theMsg,'back');
        }
    }

    /**
     * delete item
     * @param $itemid
     */
    public function delete_item($itemid){
        global $conn;
        echo " <h1 class='display-6'>Delete Item</h1>";
        echo " <div class='container'>";
        $check = CheckDB("Item_ID", "items", $itemid);

        if ($check > 0) {
            $stmt = $conn->prepare("DELETE FROM items WHERE Item_ID=? ");
            $stmt->bindParam(1, $itemid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>Item Delted</div>";
            redirectPage($theMsg, 'back');
        } else {
            $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";
            redirectPage($theMsg, '');
        }
        echo "</div>";
    }

    /**
     * approve item
     * @param $itemid
     */
    public function approve_item($itemid){
        global $conn;
        echo " <h1 class='display-6'>Approve Item</h1>";
        echo " <div class='container'>";
        $check = CheckDB("Item_ID", "items", $itemid);

        if ($check > 0) {
            $stmt = $conn->prepare("UPDATE items SET Approve=1 WHERE Item_id=? ");
            $stmt->bindParam(1, $itemid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>Item Approved</div>";
            redirectPage($theMsg, 'back');
        } else {
            $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";
            redirectPage($theMsg, '');
        }
        echo "</div>";
    }
}