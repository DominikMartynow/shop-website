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

            $sql = "SELECT * FROM `basket` WHERE id_product = '".$product."' AND id_user = '".$user."'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) === 1) {
                header("Location: ../product.php?product=".$product);
            } else {
                $sql = "INSERT INTO `basket`(`id_basket`,`id_user`,`id_product`,`date_add`) VALUES('','".$user."','".$product."','".$now."')";
            
                if (mysqli_query($conn, $sql)) {
                    header("Location: ../product.php?product=".$product);
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
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