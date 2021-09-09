<?php
class new_ad{

    /**
     * insert new prdouct
     * @param $request
     * @param $img
     * @param $posted_name
     * @param $description
     * @param $posted_price
     * @param $posted_country
     * @param $stat
     * @param $category
     * @param $posted_tags
     */
    public function insert_product($request,$img,$posted_name,$description,$posted_price,$posted_country,$stat,$category,$posted_tags){
        global $conn;
        global $formErr;
        global $successMsg;
            $name       = filter_var($posted_name, FILTER_SANITIZE_STRING);
            $desc       = filter_var($description, FILTER_SANITIZE_STRING);
            $price      = filter_var($posted_price, FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($posted_country, FILTER_SANITIZE_STRING);
            $status     = filter_var($stat, FILTER_SANITIZE_NUMBER_INT);
            $cat        = filter_var($category, FILTER_SANITIZE_NUMBER_INT);
            $tags        = filter_var($posted_tags, FILTER_SANITIZE_STRING);
            $file_name = $img['name'];
            $file_size =$img['size'];
            $file_tmp =$img['tmp_name'];
            $file_type=$img['type'];
            $name_array = explode('.', $file_name);
            $file_ext=strtolower(end($name_array));

            $extensions= array("jpeg","jpg","png");

            $formErr = array();

            if (strlen($name) < 4) {
                $formErr[] = "Item Title must be at leasr <strong>4 characters</strong>";
            }
            if (strlen($desc) < 10) {
                $formErr[] = "Item Description must be at leasr <strong>10 characters</strong>";
            }
            if (strlen($country) < 4) {
                $formErr[] = "Item Country must be at leasr <strong>2 characters</strong>";
            }
            if (empty($price)) {
                $formErr[] = "Item Price mustn't <strong>Empty</strong>";
            }
            if ($status == 0) {
                $formErr[] = "Please, <strong>Choose One</strong>";
            }
            if ($cat == 0) {
                $formErr[] = "Please, <strong>Choose One</strong>";
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
            if (empty($formErr)) {
                $image=rand(0,1000000)."_".$file_name;
                move_uploaded_file($file_tmp,"uploads/images//".$image);

                $stmt = $conn->prepare("INSERT INTO items(Name, Description, Price, Country_Made, image, Status, Add_Date, Cat_ID, Member_ID ,tags) VALUES (:name,:desc,:price,:country,:img,:status, now(), :cat, :mem, :tags )");
                $stmt->execute(array(":name"    => $name,
                    ":desc"    => $desc,
                    ":price"   => $price,
                    ":country" => $country,
                    ":img"     => $image,
                    ":status"  => $status,
                    ":cat"     => $cat,
                    ":mem"     => $_SESSION['uid'],
                    ":tags"    => $tags));
                if ($stmt) {
                    $successMsg = "Item Added";
                }

            }
        }

    /**
     * get success & error status
     */
    public function get_status(){
        global $formErr;
        global $successMsg;
        if(!empty($formErr)){
            foreach ($formErr as $err) {
                echo "<div class='alert alert-danger'>" . $err . "</div>";
            }
        }
        if (isset($successMsg)) {
            echo "<div class='alert alert-success'>" . $successMsg  . "</div>";
        }
    }
}
