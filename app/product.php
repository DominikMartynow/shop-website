<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handel wielobranżowy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200;0,6..72,300;0,6..72,400;0,6..72,500;0,6..72,600;0,6..72,700;0,6..72,800;1,6..72,200;1,6..72,300;1,6..72,400;1,6..72,500;1,6..72,600;1,6..72,700;1,6..72,800&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/mstyle.css">
</head>



<body>
    <header class=flex-box>
        <a href="index.php" id="logo">Sklep Wielobranżowy</a>

        <nav>
            <ul id=menu>
                <a class="menu-item" href="#main"><li>Home</li></a>
                <a class="menu-item" href="#assortment"><li>Asortyment</li></a>
                <a class="menu-item" href="#contact"><li>Kontakt</li></a>
            </ul>
        </nav>
    </header>

    <?php 
        if(isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];

            include "db_conn/connect.php";

            $conn = OpenConn();
        
            $sql = "SELECT * FROM products INNER JOIN product_category ON products.id_product_category = product_category.id_product_category WHERE products.id_products='".$product_id."';";
            $result = mysqli_query($conn, $sql);

            close($conn);

            if(mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                    $product_name = $row['product_name'];
                    $producer = $row['producer'];
                    $product_category = $row['product_category_name'];
                    try {
                        $product_photos = $row['product_photos'];

                        $product_photos = explode(", ", $product_photos);

                        foreach($product_photos as $key => $fullname) {
                            $photo_name = explode("/", $fullname);
                            $photo_name = end($photo_name);
                            
                            if(mb_substr($photo_name, 0, 1) == "m") {
                                $main_photo = $photo_name;
                                $photos[$key] = $photo_name;
                            } else {
                                $photos[$key] = $photo_name;
                            }
                        }

                    } finally {
                        $product_phots[0] = "uploaded_photos/placeholder.jpg";
                        $product_photo = $product_phots[0];
                    }

                    $product_description = $row['product_description'];

    ?>          

    <div id="product-main">
        
        <nav id="site-path">
            <a href="shop.php">sklep</a>
            <a> > </a>
            <a href="shop.php?cat=".<?php echo $product_category?>.><?php echo $product_category?></a>
            <a> > </a>
        </nav>

        <div id="product-basic-info">
            <div id="photo-box">
                <img id="main-photo" src=uploaded_photos/<?php echo $main_photo; ?>>

                </img>
                <div id="gallery">
                    <div class="gallery-photo">

                    </div>
                </div>
            </div>
            <div id="info-box">
                <h2></h2>
                <a href="" id="producer"></a>
            </div>
        </div>
    </div>

    <?php 
            }
        } else {
            echo "<p id=error-response-for-client>WYBRANY PRODUKT NIE ISTNIEJE :( </p><p id=error-back-link><a href='shop.php'>Wyszukaj inny produkt</a></p>";
        }
    ?>
</body>
</html>