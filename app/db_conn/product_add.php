<!-- checking number of uploaded files:
https://stackoverflow.com/questions/37363231/how-to-count-number-of-uploaded-files-in-php -->
<?php 
    require __DIR__ .'../../../vendor/autoload.php';

    // Use the Configuration class 
    use Cloudinary\Configuration\Configuration;

    // configure globally via a JSON object

    Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dw65cbyzt', 
        'api_key' => '695327744539975', 
        'api_secret' => 'ygtH4_Hxj1wM99KnkJ6ACjYpqUU'],
    'url' => [
        'secure' => true]]);

    // Use the UploadApi class for uploading assets
    use Cloudinary\Api\Upload\UploadApi;

    // Upload the image
    $upload = new UploadApi();
    echo '<pre>';
    echo json_encode(
        $upload->upload('https://res.cloudinary.com/demo/image/upload/flower.jpg', [
            'public_id' => 'flower_sample',
            'use_filename' => TRUE,
            'overwrite' => TRUE]),
        JSON_PRETTY_PRINT
    );
    echo '</pre>';
?>