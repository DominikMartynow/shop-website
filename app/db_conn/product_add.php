<?php 
    session_start();

    if(isset($_SESSION['login']) && isset($_SESSION['password'])){
        if(!empty($_POST['product_name']) && !empty($_POST['product_category']) && !empty($_POST['producer']) && !empty($_POST['product_description'])) {
            //setting variables from POST
            $product_name = $_POST['product_name'];
            $product_category = $_POST['product_category'];
            $producer = $_POST['producer'];
            $product_description = $_POST['product_description'];
            
            if(!empty($_COOKIE["main_photo"])) {
                $main = $_COOKIE["main_photo"];
            } else {
                $main = 0;
            }
            
            //uploading photos to local folder
            for($i = 0; $i < count($_FILES['product_photos']['name']); $i++) {
                $target_dir = "../uploaded_photos/";

                $target_file = $_FILES["product_photos"]["name"][$i];
                $extension = explode(".", $target_file);
                $extension = end($extension);
                if($i == $main) {
                    $target_file = $target_dir . "main" . "-". time() . "-" . $i .".".$extension;
                    $photos[$i] = $target_file;
                } else {
                    $target_file = $target_dir . time() ."-". $i .".".$extension;
                    $photos[$i] = $target_file;
                }

                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
                if (move_uploaded_file($_FILES["product_photos"]["tmp_name"][$i], $target_file)) {
                    echo "Uploaded:".$target_file."<br>";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

            $photos = implode(", ", $photos);

            echo $photos;

            include "connect.php";

            $conn = OpenConn(); 

            $sql = "INSERT INTO `products`(`id_products`,`id_product_category`,`producer`,`product_name`,`product_description`,`product_photos`) VALUES('','".$product_category."','".$producer."','".$product_name."', '".$product_description."','".$photos."')";

            if (mysqli_query($conn, $sql)) {
                header("Location: ../admin.php?success=Dodano ".$product_name);
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            close($conn);
            
        } else {
            header("Location: ../admin.php?error=Uzupełnij wszystkie dane formularza");
        }   
    } else {
        header("Location: index.php");
    }
?>
