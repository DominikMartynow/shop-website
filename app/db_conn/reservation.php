<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        include "connect.php";
        $pickup = date("Y-m-d", strtotime("+3 days",time()));
        $now = date("Y-m-d");
        $user = $_SESSION['id'];
        $pickup_code = time().$user;

        $conn = OpenConn();

        $sql = "SELECT * FROM `basket` WHERE basket.id_user = '".$user."'";
        $result = mysqli_query($conn, $sql);

        $products = array();
        $insert_log = array();
        $delete_log = array();

        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($products, $row['id_product']);
            }

            foreach($products as $key => $product) {
                $sql = "INSERT INTO `reservations`(`id_reservations`,`id_user`,`id_products`,`reservation_date`,`reservation_pickup`, `pickup_code`) VALUES ('', '".$user."', '".$product."', '".$now."', '".$pickup."', '".$pickup_code."')";
                
                if(mysqli_query($conn, $sql)) {
                    array_push($insert_log, "SUCCESS inert".$product);
                    $sql = "DELETE FROM `basket` WHERE id_user = '".$user."' AND id_product = '".$product."'";

                    if (mysqli_query($conn, $sql)) {
                        array_push($delete_log, "SUCCESS delete".$product);

                        header("Location: ../basket.php");
                    } else {
                        array_push($delete_log, "ERROR delete". $sql . "<br>" . mysqli_error($conn));
                    }
                } else {
                    array_push($insert_log, "ERROR". $sql . "<br>" . mysqli_error($conn));
                }
            }
        }

    close($conn);
        
    } else {
        header("Location: basket.php");
    }

?>