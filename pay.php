<?php

ob_start();
session_start();


$pageTitle = 'Payment';
include "init.php";

$payment=new pay();

if (isset($_GET['item_price'])) {
    $res = $payment->payment_process($_GET['item_price']);
}
?>

    <script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=<?php echo $res['id']; ?>"></script>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="paymentWidgets" data-brands="VISA MASTER MADA"></form>

    <div id="showPayForm"></div>
<?php
if(isset($_GET['resourcePath'])) {
    $payment->set_order($_GET['resourcePath'],$_SESSION['uid']);
}




include $tpl . "footer.php";
ob_end_flush();
