<?php
class order{
    public function get_order(){
        global $conn;
        $stmt = $conn->prepare("SELECT 
                                    orders.*, users.Username AS user_name, users.Email,items.Name AS item_name
                                FROM 
                                    orders
                                INNER JOIN
                                   users
                                ON
                                    users.UserID = orders.User_ID 
                                INNER JOIN
                                    items
                                ON
                                    items.Item_ID = orders.Item_ID 
                                where
                                    paid=1
                                ORDER BY
                                    Order_ID DESC
                                ");
        $stmt->execute();
        return $orders = $stmt->fetchAll();
    }
}