<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        //send to db
        echo "logged";
        if(!empty($_GET["product"])) {
            $product = $_GET["product"];
            $user = $_SESSION['id'];
            $now = date('Y-m-d');
            
            include("connect.php");

            $conn = OpenConn();
            $sql = "INSERT INTO `basket`(`id_basket`,`id_user`,`id_product`,`date_add`) VALUES('','".$user."','".$product."','".$now."')";
            
            if (mysqli_query($conn, $sql)) {
                header("Location: ../product.php?product=".$product);
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            close($conn);
        } else {
            header("Location: ../shop.php");
        }
    } else {
        // send to cookies
        echo "not logged"; 
        header("Location: ../login-site.php?product=".$_GET['product']."&destination=add_basket.php?product=".$_GET['product']); 
    }
?>