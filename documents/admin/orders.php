<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
	$pageTitle = 'Comments';
	include 'init.php';
	// echo 'welcome ' . $_SESSION['username'];
        $_order=new order();
        $orders = $_order->get_order();
        if(!empty($orders)){
?>
		<h1>Orders</h1>
		<div class="container">
			<table class="table table-light table-hover table-bordered">
				<thead>
					<tr>
						<td>#ID</td>
						<td>User</td>
						<td>Email</td>
						<td>Product</td>
						<td>Price</td>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($orders as $order) {
						echo "
				<tr>
					<td>" . $order['Order_ID'] . "</td>
					<td>" . $order['user_name'] . "</td>
					<td>" . $order['Email'] . "</td>
					<td>" . $order['item_name'] . "</td>
					<td>" . $order['Total'] . "</td>
				</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
        <?php
}
} else {
	header('location:index.php');
	exit();
}
ob_end_flush();