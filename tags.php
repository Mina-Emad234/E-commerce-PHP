<?php
ob_start();
include "init.php"; ?>
    <div class="container">
        <div class='row'>
        <?php
        if(isset($_GET['name'])){
            $tag=$_GET['name'];
            echo "<h1 class='text-center'>{$tag}</h1>";
            $allitems =getAllFrom("*","items","tags LIKE '%$tag%'", null, "Approve", 1, "Item_ID", "ASC" );
             foreach( $allitems as $item){
                 echo "<div class='col-sm-6 col-md-3'>";
                    echo "<div class='card item-box'>";
                    if($item['Approve']==0){echo "<span class='approve-status'>Waiting Approval</span>";}
                        echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
                        echo '<img src="https://via.placeholder.com/300/#00f" classcard-img-top" alt="">';
                        echo "<div class='card-body'>";
                            echo "<h3  class='card-title'><a href='items.php?itemid=" . $item['Item_ID']  ."'>" . $item['Name'] . "</a></h3>";
                            echo "<p class='card-text'>" . $item['Description'] . "</p>";
                            echo "<div class='date'>" . $item['Add_Date'] . "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
             } 
             }else{
                echo "you must add tag Name";
             }
        ?>
    </div>
    </div>
    
<?php include $tpl . "footer.php"; 
ob_end_flush();
?>
