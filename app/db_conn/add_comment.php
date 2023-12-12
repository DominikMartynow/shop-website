<?php 
    if(!empty($_GET['destination'])) {
        $product = $_GET['destination'];
        if(!empty($_POST['comment'])) {
            session_start();
            include("connect.php");

            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);    
                return $data;
            }

            $comment = $_POST['comment'];
            $comment = validate($comment);
        
            $author = $_SESSION['id'];
            $conn = OpenConn();

            $date = date('Y-m-d H:i:s');

            if($_SESSION['admin'] == 1) {
                $sql = "INSERT INTO `handel_wielobranzowy`.`comments`(`id_comments`,`comments_author`,`comments_content`,`verified`,`id_product_comments`, `date`) VALUES ('', '".$author."', '".$comment."', '1', '".$product."', '".$date."')";
            } else {
                $sql = "INSERT INTO `handel_wielobranzowy`.`comments`(`id_comments`,`comments_author`,`comments_content`,`verified`,`id_product_comments`, `date`) VALUES ('', '".$author."', '".$comment."', '', '".$product."', '".$date."')";
            }
            
            if(mysqli_query($conn, $sql)) {
                header("Location: ../product.php?product=".$product."&success-comment=1");
            } else {
                echo mysqli_error($conn);
            }
            close($conn);
        } else {
            header("Location: ../product.php?product=".$product."");
        }
    } else {
        header("Location: ../product.php");
    }
?>