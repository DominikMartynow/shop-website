<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        if($_SESSION["admin"] == 1) {
            if(isset($_POST['status']) && isset($_GET['order'])) {
                include "connect.php";

                $status = $_POST['status'];
                $order = $_GET['order'];

                if(!empty($_POST['pickup_date'])) {
                    $pickup = $_POST['pickup_date'];

                    if($pickup == '0000-00-00') {
                        $pickup = date('Y-m-d', strtotime(date('Y-m-d').' + 3 days'));
                    } 
                } else {
                    $pickup = date('Y-m-d', strtotime(date('Y-m-d'). ' + 3 days'));
                }

                if($status == 4 || $status == 5 || $status == 6) {
                    $pickup = '0000-00-00';
                }

                $conn = OpenConn();
                $sql = "UPDATE reservations SET reservation_status_id = ".$status.", reservation_pickup='".$pickup."' WHERE pickup_code = '".$order."'";
                if(mysqli_query($conn, $sql)) {
                    header("Location: ../admin.php?tool=orders&status=".$status."&reservation=".$order);
                } else {
                    echo "error".mysqli_error($conn);
                }

                close($conn);
            } else {
                header("Location: index.php");
            }
        } else {
            header("Location: index.php");
        }  
    } else {
        header("Location: index.php");
    }
?>
