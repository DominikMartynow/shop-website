<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        if($_SESSION["admin"] == 1) {
            if(isset($_GET['comment']) && isset($_GET['status'])) {
                $comment = $_GET['comment'];
                $status = $_GET['status'];

                include "connect.php";

                $conn = OpenConn(); 

                $sql = "UPDATE comments set verified = '1' WHERE id_comments =".$comment;

                if (mysqli_query($conn, $sql)) {
                    header("Location: ../admin.php?tool=comments&verified=".$status."&success=Zaakceptowano komentarz");
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }

                close($conn);
                
            } else {
                echo $_GET['status'];
                echo $_GET['comment'];

                // header("Location: ../admin.php?tool=comments");
            } 
        } else {
            header("Location: ../index.php");
        }  
    } else {
        header("Location: ../index.php");
    }
?>
