<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        //send to db
        if(!empty($_GET["product"])) {
            $product = $_GET["product"];
            $user = $_SESSION['id'];
            $now = date('Y-m-d');
            
            include("connect.php");
            
            $conn = OpenConn();

            $sql = "DELETE FROM `basket` WHERE `id_user` = '".$user."' && `id_product` = '".$product."'";
            if (mysqli_query($conn, $sql)) {
                if(!empty($_GET['destination'])) {
                    header("Location: ".$_GET['destination']);
                } else {
                    header("Location: ../shop.php");
                }

            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            close($conn);
            
        } else {
            header("Location: ../shop.php");
        }
    } else {
        echo "not logged"; 
        header("Location: ../login-site.php?product=".$_GET['product']."&destination=add_basket.php?product=".$_GET['product']); 
    }
?>