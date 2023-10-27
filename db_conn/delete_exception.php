<?php 
    session_start();

    include 'connect.php';

    if(isset($_SESSION['login']) && isset($_SESSION['password'])){
        $conn = OpenConn();

        if(isset($_GET['id_exception'])) {
            $sql = "DELETE FROM `exceptions` WHERE id_exception = '".$_GET['id_exception']."'";

            if (mysqli_query($conn, $sql)) {
                header("Location: ../admin.php?success=usunieto poprawnie rekord ".$_GET['id_exception']);

              } else {
                echo "Error deleting record: " . mysqli_error($conn);
              }
        }


    } else {
        header("Location: index.php");
    }
?>