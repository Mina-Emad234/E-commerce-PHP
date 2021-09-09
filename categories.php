<?php
ob_start();
include "init.php"; ?>
    <h1>Show Categories</h1>
    <div class="container">
        <div class='row'>
        <?php
        if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
            $category=intval($_GET['pageid']);
            foreach( getItems('cat_id',"{$category}",1) as $item){
                echo "<div class='col-sm-6 col-md-3'>";
                    echo "<div class='card item-box'>";
                    if($item['Approve']==0){echo "<span class='approve-status'>Waiting Approval</span>";}
                        echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
                        echo '<img src=uploads/images/' . $item['Image'] .'  class="card-img-top" alt="">';
                        echo "<div class='card-body'>";
                            echo "<h3  class='card-title'><a"; if($item['Approve']==1) {echo " href='items.php?itemid=" . $item['Item_ID']  ."'";}echo">" . $item['Name'] . "</a></h3>";
                            echo "<p class='card-text'>" . $item['Description'] . "</p>";
                            echo "<div class='date'>" . $item['Add_Date'] . "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
             }
             }else{
                echo "you must add page id";
             }
        ?>
    </div>
    </div>
    
<?php include $tpl . "footer.php"; 
ob_end_flush();
?>
