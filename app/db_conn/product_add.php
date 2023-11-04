<!-- checking number of uploaded files:
https://stackoverflow.com/questions/37363231/how-to-count-number-of-uploaded-files-in-php -->
<?php 
    $target_dir = "/../uploaded_photos";
    $target_file = $target_dir . basename($_FILES["product_photos"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    if (move_uploaded_file($_FILES["product_photos"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["product_photos"]["name"])). " has been uploaded.";
    } else {
    echo "Sorry, there was an error uploading your file.";
    }
  
    if(isset($_POST['product_name']) && isset($_POST['product_category']) && isset($_POST['variant']) && isset($_POST['product_photos']) && isset($_POST['producer']) && isset($_POST['product_description'])) {
        $product_name = $_POST['product_name'];
        $product_category = $_POST['product_category'];
        $variant = $_POST['variant'];
        $product_photos = $_POST['product_photos'];
        $producer = $_POST['producer'];
        $product_description = $_POST['product_description'];
    }
?>