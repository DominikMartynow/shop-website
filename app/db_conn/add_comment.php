<?php 
    //jest lokalizacja finalna
    if(!empty($_GET['destination'])) {
        $product = $_GET['destination'];

        // user zalogowany
        include "../functions/functions.php";
        session_start();

        if(is_logged()) {
            //jest komentarz
            if(!empty($_POST['comment'])) {
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

                //tryb ustalony
                if(isset($_GET['mode'])) {
                    $mode = $_GET['mode'];
                //tryb nieznany
                } else {
                    $mode = 'c';
                }

                echo $_SESSION['admin'];

                //czy user = admin
                if($_SESSION['admin'] == 1) {
                    //dodaje bez zatwierdzenia

                    //tryb comment
                    if($mode == 'c') {
                        $sql = "INSERT INTO `handel_wielobranzowy`.`comments`(`id_comments`,`comments_author`,`comments_content`,`verified`,`id_product_comments`, `date`, `path`) VALUES ('', '".$author."', '".$comment."', '1', '".$product."', '".$date."', '/')";
                    //tryb answer
                    } else if($mode == 'a') {
                        //znany komentarz wątku
                        if(isset($_POST['path'])) {
                            $sql = "INSERT INTO `handel_wielobranzowy`.`comments`(`id_comments`,`comments_author`,`comments_content`,`verified`,`id_product_comments`, `date`, `path`) VALUES ('', '".$author."', '".$comment."', '1', '".$product."', '".$date."', '".$_POST['path']."')";                        
                        //nieznany komentarz wątku
                        } else {
                            header("Location: ../product.php?product=".$product."");
                        }
                    }

                } else {
                    //dodaje z zatwierdzeniem

                    //tryb comment
                    if($mode == 'c') {
                        $sql = "INSERT INTO `handel_wielobranzowy`.`comments`(`id_comments`,`comments_author`,`comments_content`,`verified`,`id_product_comments`, `date`, `path`) VALUES ('', '".$author."', '".$comment."', '0', '".$product."', '".$date."', '/')";
                    //tryb answer
                    } else if($mode == 'a') {
                        //znany komentarz wątku
                        if(isset($_POST['path'])) {
                            $sql = "INSERT INTO `handel_wielobranzowy`.`comments`(`id_comments`,`comments_author`,`comments_content`,`verified`,`id_product_comments`, `date`, `path`) VALUES ('', '".$author."', '".$comment."', '0', '".$product."', '".$date."', '".$_POST['path']."')";                        
                        //nieznany komentarz wątku
                        } else {
                            header("Location: ../product.php?product=".$product."");
                        }
                    }
                }
                
                //jesli dodanie sukces
                if(mysqli_query($conn, $sql)) {
                    //przenosi do lokalizacji finalnej

                    //lokalizacja komentarza ustalona
                    if(isset($_GET['comment'])) {
                        // header("Location: ../product.php?product=".$product."#comment".$_GET['comment']);
                    //lokalizacja komentarza nieustalona
                    } else {
                        header("Location: ../product.php?product=".$product."");
                    }


                //jesli dodanie blad
                } else {
                    //wysweitla blad
                    echo mysqli_error($conn);
                }

                close($conn);

            //komentarz pusty
            } else {
                header("Location: ../product.php?product=".$product."");
            }
        //uzytkownik niezalogowany
        } else {
            header("Location: ../product.php?product=".$product."");
        }
    // brak lokalizacji finalnej
    } else {
        header("Location: ../product.php");
    }
?>