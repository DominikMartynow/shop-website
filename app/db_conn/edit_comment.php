<?php 
    session_start();

    //jeśli zalogowany
    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        //send to db
        if(!empty($_GET["comment"]) && !empty($_POST['edit'])) {

            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);    
                return $data;
            }

            $comment = $_GET["comment"];
            $edit = $_POST['edit'];
            $edit = validate($edit);
            $user = $_SESSION['id'];
            $date = date('Y-m-d H:i:s');

            //czy user = wlasciciel komentarza
            include("connect.php");
            
            $conn = OpenConn();

            $sql = "SELECT * FROM comments WHERE id_comments = ".$comment."";
            $result = mysqli_query($conn, $sql);

            //komentarz istnieje
            if(mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_array($result);
                //zalogowany user = wlasciciel komentarza
                if($row['comments_author'] == $user) {
                    //jeśli stary komentarz nei jest taki sam
                    if($row['comments_content'] != $edit) {
                        //Dołaczenie poprzedniego komentarza do tabeli comments_history
                        $conn_insert = OpenConn();

                        $sql_insert = "INSERT INTO comments_history (`id_comments_history`, `edited_comment`, `date_edit`, `previous_comment`) VALUES ('', '".$comment."', '".$date."', '".$row['comments_content']."')";

                        if(mysqli_query($conn_insert, $sql_insert)) {
                            //Update w tabeli comments
                            $conn = OpenConn();

                            $sql = "UPDATE comments SET comments_content = '".$edit."', date = '".$date."' WHERE id_comments = ".$comment."";
                            
                            if(mysqli_query($conn, $sql)) {
                                header("Location: ../product.php?product=".$_GET['destination']."#comment".$comment."");
                            } else {
                                echo "error".mysqli_error($conn);
                            }
                        } 
                    } else {
                        header("Location: ../product.php?product=".$_GET['destination']."#comment".$comment."");
                    }
                }
            }

            close($conn);
            
        } else {
            header("Location: ../product.php?product=".$_GET['destination']."");
        }
    } else {
        header("Location: ../login-site.php?product=".$_GET['product']."&destination=add_basket.php?product=".$_GET['destination']); 
    }
?>