<?php 
include("../functions/functions.php");
include("connect.php");
session_start();

if(isset($_GET['comment'])) {
    if(is_logged()) {
        if(isset($_GET['destination'])) {
            $comment = $_GET['comment'];
            $product = $_GET['destination'];

            $conn = OpenConn();
            $sql = "SELECT id_comments_likes FROM comments_likes WHERE id_user_likes = ".$_SESSION['id']." AND id_comment = ".$comment."";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                $sql = "DELETE FROM comments_likes WHERE id_user_likes = ".$_SESSION['id']." AND id_comment = ".$comment."";

                if(mysqli_query($conn, $sql)) {
                    if(isset($_GET['comment'])) {
                        header("Location: ../product.php?product=".$product."#comment".$_GET['comment']."");
                    } else {
                        header("Location: ../product.php?product=".$product."");
                    }
                } else {
                    echo mysqli_error($conn);
                }
            } else {
                $sql = "INSERT INTO `comments_likes`(`id_comments_likes`,`id_user_likes`,`id_comment`) VALUES ('', '".$_SESSION['id']."', '".$comment."');";
            
                if(mysqli_query($conn, $sql)) {
                    if(isset($_GET['comment'])) {
                        header("Location: ../product.php?product=".$product."#comment".$_GET['comment']."");
                    } else {
                        header("Location: ../product.php?product=".$product."");
                    }
                } else {
                    echo mysqli_error($conn);
                }
            }

            close($conn);
        } else {
            header("Location: ../product.php");
        }
    } else {
        if(isset($_GET['destination'])) {
            header("Location: ../product.php?product=".$_GET['destination']."");
        } else {
            header("Location: ../product.php");
        }
    }
} else {
    header("Location: ../product.php");
}


?>