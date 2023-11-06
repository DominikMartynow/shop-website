<!-- checking number of uploaded files:
https://stackoverflow.com/questions/37363231/how-to-count-number-of-uploaded-files-in-php -->
<?php 
    // if(isset($_POST['product_name']) && isset($_POST['product_category']) && isset($_POST['variant']) && isset($_POST['product_photos']) && isset($_POST['producer']) && isset($_POST['product_description'])) {
        //setting variables from POST
        $product_name = $_POST['product_name'];
        $product_category = $_POST['product_category'];
        $variant = $_POST['variant'];
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
                $target_file = $target_dir . "main-" . $product_name . $product_category . substr($product_description, 0, 5) . $i .".".$extension;
                $photos[$i] = $target_file;
            } else {
                $target_file = $target_dir . $product_name . $product_category . substr($product_description, 0, 5) . $i .".".$extension;
                $photos[$i] = $target_file;
            }

            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
            if (move_uploaded_file($_FILES["product_photos"]["tmp_name"][$i], $target_file)) {
                echo "Uploaded:".$target_file."<br>";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }


        
    // }

?>
