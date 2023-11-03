<?php 
use Cloudinary\Configuration\Configuration;
          
Configuration::instance([
  'cloud' => [
    'cloud_name' => 'dw65cbyzt', 
    'api_key' => '695327744539975', 
    'api_secret' => '***************************'],
  'url' => [
    'secure' => true]]);

    $cloudinary->uploadApi()->upload("https://upload.wikimedia.org/wikipedia/commons/a/ae/Olympic_flag.jpg", 
  ["public_id" => "olympic_flag"]);
?>