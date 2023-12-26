<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        if($_SESSION["admin"] == 1) {
            if(!empty($_POST['product_id']) && !empty($_POST['product_name']) && !empty($_POST['product_category']) && !empty($_POST['producer']) && !empty($_POST['product_description'])) {
                //setting variables from POST
                $product_id = $_POST['product_id'];
                $product_name = $_POST['product_name'];
                $product_category = $_POST['product_category'];
                $producer = $_POST['producer'];
                $product_description = $_POST['product_description'];

                include "connect.php";

                $conn = OpenConn(); 

                $sql = "UPDATE products SET id_product_category = ".$product_category.", producer = '".$producer."', product_name = '".$product_name."', product_description = '".$product_description."' WHERE id_products = '".$product_id."'";

                if (mysqli_query($conn, $sql)) {
                    header("Location: ../admin.php?tool=edit_product&category=".$product_category."&product=".$product_id."&success=Edytowano ".$product_name);
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }

                close($conn);
                
            } else {
                header("Location: ../admin.php?tool=edit_product&error=Uzupełnij wszystkie dane formularza");
            } 
        } else {
            header("Location: ../index.php");
        }  
    } else {
        header("Location: ../index.php");
    }
?>
