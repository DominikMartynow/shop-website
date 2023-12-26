<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        if($_SESSION["admin"] == 1) {
            if(!empty($_GET['product']) && !empty($_GET['category'])) {
                //setting variables from POST
                $product_id = $_GET['product'];
                $product_category = $_GET['category'];

                include "connect.php";

                $conn = OpenConn(); 

                $sql = "DELETE FROM products WHERE id_products = '".$product_id."'";

                if (mysqli_query($conn, $sql)) {
                    header("Location: ../admin.php?tool=edit_product&category=".$product_category."&success=Usunięto ".$product_name);
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
