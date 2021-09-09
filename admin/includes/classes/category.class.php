<?php
class category{
    /**
     * add new category
     * @param $cat_name
     * @param $desc
     * @param $ordering
     * @param $cat_parent
     * @param $cat_visibility
     * @param $commenting
     * @param $advertise
     */
    public function insert_cat($cat_name,$desc,$ordering,$cat_parent,$cat_visibility,$commenting,$advertise){
        global $conn;
        echo " <h1 class='display-6'>Add Member</h1>";
        echo " <div class='container'>";
        $name          = $cat_name;
        $description   = $desc;
        $order         = $ordering;
        $parent        = $cat_parent;
        $visibility    = $cat_visibility;
        $comment       = $commenting;
        $ads           = $advertise;


        if (!empty($name)) {
            $check = CheckDB("Name", "categories", $name);
            if ($check == 1) {
                $theMsg = "<div class='alert alert-danger'>Category Exists</div>";
                redirectPage($theMsg, 'back');
            } else {
                $stmt = $conn->prepare("INSERT INTO categories (Name, Description, Parent,Ordering, Visibility, Allow_Comment, Allow_Ads) VALUES (:name,:desc, :parent, :ord,:visible, :comm, :ads )");
                $stmt->execute(array(
                    ":name"     => $name,
                    ":desc"     => $description,
                    ":parent"   => $parent,
                    ":ord"      => $order,
                    ":visible"  => $visibility,
                    ":comm"     => $comment,
                    ":ads"      => $ads
                ));
                $theMsg = "<div class='alert alert-success'>Category Added</div>";
                redirectPage($theMsg, 'back');
            }
        }
    }

    /**
     * edit a specific category
     * @param $cat_id
     * @return mixed
     */
    public function edit_cat($cat_id){
        global $conn;
        $catid = isset($cat_id) && is_numeric($cat_id) ? intval($cat_id) : 0;
        $stmt = $conn->prepare("SELECT * FROM categories WHERE ID=?");
        $stmt->execute(array($catid));
        return $cat = $stmt->fetch();
    }

    /**
     * update category
     * @param $cat_id
     * @param $cat_name
     * @param $desc
     * @param $ordering
     * @param $cat_parent
     * @param $cat_visibility
     * @param $commenting
     * @param $advertise
     */
    public function update_cat($cat_id,$cat_name,$desc,$ordering,$cat_parent,$cat_visibility,$commenting,$advertise){
        global $conn;
        $id   			= $cat_id;
        $name           = $cat_name;
        $description    = $desc;
        $order          = $ordering;
        $parent         = $cat_parent;
        $visibility     = $cat_visibility;
        $comment        = $commenting;
        $ads            = $advertise;


            $stmt = $conn->prepare("UPDATE Categories SET 	Name=?,
															Description=?,
															Parent=?,
															Ordering=?,
															Visibility=?, 
															Allow_Comment=?, 
															Allow_Ads=?
																WHERE 
															ID=?");
            $stmt->execute(array($name, $description, $parent, $order, $visibility, $comment, $ads, $id));
            $theMsg = "<div class='alert alert-success'>Success update</div>";
            redirectPage($theMsg,'back');

    }

    /**
     * delete category
     * @param $cat_id
     */
    public function delete_cat($cat_id){
        global $conn;
        echo " <h1 class='display-6'>Delete Category</h1>";
        echo " <div class='container'>";
        $catid = isset($cat_id) && is_numeric($cat_id) ? intval($cat_id) : 0;
        $check = CheckDB("ID", "categories", $catid);

        if ($check > 0) {
            $stmt = $conn->prepare("DELETE FROM Categories WHERE ID=? ");
            $stmt->bindParam(1, $catid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>Category Delted</div>";
            redirectPage($theMsg, 'back');
        } else {
            $theMsg = "<div class='alert alert-danger'>This ID is not exist</div>";
            redirectPage($theMsg, '');
        }
        echo "</div>";
    }
}