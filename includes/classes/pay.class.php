<?php
class pay{

    /**
     * payment process
     * @param $price
     * @param $user_id
     * @param $item_id
     * @return mixed
     */
    public function payment_process($price){

        global $total;

        $total = $price;

            $url = "https://test.oppwa.com/v1/checkouts";
            $data = "entityId=8a8294174b7ecb28014b9699220015ca" .
                "&amount=" .  $price .
                "&currency=EUR" .
                "&paymentType=DB";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if (curl_errno($ch)) {
                echo curl_error($ch);
            }
            curl_close($ch);
           return json_decode($responseData, true);
        }

    /**
     * get status & set order
     * @param $resource_path
     * @param $user
     */

    public function set_order($resource_path,$user){
        global $conn;

        if (isset($resource_path)){
            $url = "https://test.oppwa.com";
            $url .= $resource_path;
            $url .= "?entityId=8a8294174b7ecb28014b9699220015ca";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));//auth_token
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if (curl_errno($ch)) {
                echo curl_error($ch);
            }
            curl_close($ch);
            $status = json_decode($responseData, true);
            parse_str(parse_url($_SERVER['HTTP_REFERER'],PHP_URL_QUERY ),$array);

            if($status == true){
                $stmt = $conn->prepare("INSERT INTO orders(User_ID, Item_ID, Total,paid) VALUES (:userid,:itemid,:price,:paid)");
            $stmt->execute(array(":userid" => $user, ":itemid" => $array['item_id'], ":price" => $array['item_price'],":paid"=>1));
                echo "<div class='container-fluid'><div class = 'alert mt-2 alert-success'>Payment process succeeded</div></div>";
                header("refresh: 3; url=index.php");
            }

            if( $status == false && !isset($price)) {
                echo "<div class='container-fluid'><div class = 'alert mt-2 alert-danger' >Payment process failed</div></div>";
            }
        }
    }
}