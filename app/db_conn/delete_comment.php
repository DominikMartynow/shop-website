<?php 
    session_start();

    //jeśli zalogowany
    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        //send to db
        if(!empty($_GET["comment"])) {
            $comment = $_GET["comment"];
            $user = $_SESSION['id'];

            //czy user = wlasciciel komentarza
            include("connect.php");
            
            $conn = OpenConn();

            $sql = "SELECT * FROM comments WHERE id_comments = ".$comment."";
            $result = mysqli_query($conn, $sql);

            close($conn);

            //komentarz istnieje
            if(mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_array($result);
                //zalogowany user = wlasciciel komentarza
                if($row['comments_author'] == $user) {
                    $conn = OpenConn();

                    $sql = "DELETE FROM comments WHERE id_comments = ".$comment."";
                    
                    if(mysqli_query($conn, $sql)) {
                        if(isset($_GET['thread'])) {
                            header("Location: ../product.php?product=".$_GET['destination']."#comment".$_GET['thread']."");
                        } else {
                            header("Location: ../product.php?product=".$_GET['destination']."");
                        }
                    } else {
                        echo "error".mysqli_error($conn);
                    }
        
                    close($conn);
                }
            }
            
        } else {
            header("Location: ../product.php?product=".$_GET['destination']."");
        }
    } else {
        header("Location: ../login-site.php?product=".$_GET['product']."&destination=add_basket.php?product=".$_GET['product']); 
    }
?>