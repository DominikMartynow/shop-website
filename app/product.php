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
                <li class="menu-item"><a href="shop.php">Sklep</a></li>
                <li class="menu-item">
                    <a href="basket.php">Koszyk</a>
                        <?php 
                            session_start();

                            include "db_conn/connect.php";
                            include "functions/functions.php";

                            if(is_logged()) {
                                if(countBasket($_SESSION['id']) > 0) {
                                    echo "<a id=count-basket>".countBasket($_SESSION['id'])."</a>";
                                }
                            }
                        ?>
                </li>
                <li class="menu-item" id=unwrap-account-menu>
                    <a href="account.php">Konto</a>
                    <ul id=wrapper-account-menu>
                        <?php 
                            if(is_logged()) {
                        ?>
                        <li class=wrapper-account-menu-item>Twoje konto</li>
                        <a href="reservations.php"><li class=wrapper-account-menu-item>Rezerwacje</li></a>
                        <li class=wrapper-account-menu-item>Ustawienia</li>
                        <a href="db_conn/logout.php?destination=../product.php?product=<?php echo $_GET['product'] ?>"><li class=wrapper-account-menu-item id=wrapper-logout>Wyloguj się - <?php echo $_SESSION['firstname']?></li></a>    
                        <?php 
                            } else {
                        ?>        
                        
                        <form id='login-form-wrapper' id=inside action="db_conn/login.php?destination=../product.php?product=<?php echo $_GET['product'] ?>" method="POST">
                            <input type="text" name="mail" id="mail" placeholder="Adres e-mail">
                            <input type="password" name="password" id="password" placeholder="Hasło">
                            <input id=submit-login type="submit" value="Zaloguj">
                        </form>

                        <div class=center id=wrapper-register>
                            <a href="login-site.php?mode=register">Zarejestruj się</a>
                        </div>

                        <?php 
                            if(isset($_GET['error'])) {
                                echo "<a id=login-site-error>".$_GET['error']."</a>";
                            }
                        ?>

                        <?php
                            }
                        ?>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <?php 
        if(isset($_GET['product']) && !empty($_GET['product'])) {
            $product = $_GET['product'];

            $conn = OpenConn();
        
            $sql = "SELECT * FROM products INNER JOIN product_category ON products.id_product_category = product_category.id_product_category WHERE products.id_products='".$product."';";
            $result = mysqli_query($conn, $sql);

            close($conn);

            if(mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                    $product_name = $row['product_name'];
                    $producer = $row['producer'];
                    $product_category_id = $row['id_product_category'];
                    $product_category = $row['product_category_name'];
                    $product_photos = $row['product_photos'];

                    $product_photos = explode(", ", $product_photos);

                    foreach($product_photos as $key => $fullname) {
                        $photo_name = explode("/", $fullname);
                        $photo_name = end($photo_name);
                        
                        if(mb_substr($photo_name, 0, 1) == "m") {
                            $main_photo_key = $key;
                            $main_photo = $photo_name;
                            $photos[$key] = $photo_name;
                        } else {
                            $photos[$key] = $photo_name;
                        }
                    }

                    $product_description = $row['product_description'];
    ?>          

    <div id="product-main">
        <nav id="site-path">
            <a href="shop.php">sklep</a>
            <a> > </a>
            <a href="shop.php?category_id=<?php echo $product_category_id?>"><?php echo $product_category?></a>
            <a> > </a>
            <a href="" class=bold><?php echo $product_name?></a>
        </nav>

        <div id="product-basic-info">
            <div id="photo-box">
                <div photo-number="0" id="main-photo" style='background-image: url("uploaded_photos/<?php echo $main_photo; ?>")'></div>
                <div id="gallery">
                    <?php 
                        echo "<div photo-number=".$main_photo_key." class=gallery-photo style='background-image: url(uploaded_photos/".$main_photo.")' onclick='handle_click(this)'></div>";

                        foreach($photos as $key => $fullname) {
                            if($key != $main_photo_key) {
                                echo "<div photo-number=".$key." class=gallery-photo style='background-image: url(uploaded_photos/".$photos[$key].")' onclick='handle_click(this)'></div>";
                            }
                        }
                    ?>
                </div>
            </div>
            <div id="info-box">
                <h2 id=product-name><?php echo $product_name;?></h2>
                <a href="shop.php?producer=<?php echo $producer;?>" id="producer"><?php echo $producer;?><a>
                <?php 
                    if(is_logged()) {

                        $conn = OpenConn();

                        $sql = "SELECT * FROM `basket` WHERE id_product = '".$_GET['product']."' AND id_user = '".$_SESSION['id']."'";
                        $result = mysqli_query($conn, $sql);
            
                        if(mysqli_num_rows($result) === 1) {
                ?>
                        <a href="db_conn/del_basket.php?destination=../product.php?product=<?php echo $_GET['product']; ?>&product=<?php echo $_GET['product']?>" id="add-to-basket">Usuń z koszyka <img class="add-to-basket-ico" src="ico/icons8-minus-50.png"></a>
                <?php
                        } else {
                ?>
                        <a href="db_conn/add_basket.php?product=<?php echo $_GET['product']; ?>" id="add-to-basket">Dodaj do koszyka <img class="add-to-basket-ico" src="ico/icons8-plus-50.png"></a>
                <?php
                        }
                    } else {
                ?>      
                        <a href="db_conn/add_basket.php?product=<?php echo $_GET['product']; ?>" id="add-to-basket">Dodaj do koszyka <img class="add-to-basket-ico" src="ico/icons8-plus-50.png"></a>
                <?php
                    }

                ?>
            </div>
        </div>

        <div id="product_description">
            <?php echo $product_description; ?>
        </div>
    </div>

    <script>
    photos = document.getElementsByClassName("gallery-photo")

    if(photos.length < 6) {
        document.getElementById("gallery").style.overflow = "hidden";
    }

        function handle_click(photo) {
            main = document.getElementById("main-photo")

            photos = document.getElementsByClassName("gallery-photo")

            if(photos.length < 6) {
                document.getElementById("gallery").style.overflow = "hidden";
            }

            for(let i = 0; i < photos.length; i++){
                try {
                    photos[i].removeAttribute("id")
                } finally {
                    photos[i].removeAttribute("id")
                }
             }
            
            photo.setAttribute("id", "choosen")

            file_name = photo.getAttribute("style")
            file_name = file_name.split(": ");
            file_name = file_name[1]

            main.style.backgroundImage = file_name
        }
    </script>

    <?php 
            }
        } else {
            echo "<p id=error-response-for-client>WYBRANY PRODUKT NIE ISTNIEJE :( </p><p id=error-back-link><a href='shop.php'>Wyszukaj inny produkt</a></p>";
        }
    ?>
</body>
</html>