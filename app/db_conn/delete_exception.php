<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        if($_SESSION["admin"] == 1) {
            include 'connect.php';
            $conn = OpenConn();

            if(isset($_GET['id_exception'])) {
                $sql = "DELETE FROM `exceptions` WHERE id_exception = '".$_GET['id_exception']."'";

                if (mysqli_query($conn, $sql)) {
                    header("Location: ../admin.php?success=usunieto poprawnie rekord ".$_GET['id_exception']);

                } else {
                    echo "Error deleting record: " . mysqli_error($conn);
                }
            }

            close($conn);
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
?>