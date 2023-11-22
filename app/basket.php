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
                            } else {

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
                        <a href="db_conn/logout.php?destination=../basket.php"><li class=wrapper-account-menu-item id=wrapper-logout>Wyloguj się - <?php echo $_SESSION['firstname']?></li></a>    
                        <?php 
                            } else {
                        ?>        
                        
                        <form id='login-form-wrapper' id=inside action="db_conn/login.php?destination=../basket.php" method="post">
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
        if(isset($_SESSION['id']) && isset($_SESSION['password'])){
            // logged
    ?>
        <main id="basket">
            <h1 id="basket-header">Twój koszyk</h1>
            <ul id=products-basket>
                <?php 
                    $conn = OpenConn();

                    $sql = "SELECT * FROM `basket` INNER JOIN `products` ON basket.id_product = products.id_products WHERE basket.id_user = '".$_SESSION['id']."'";
                    $result = mysqli_query($conn, $sql);

                    close($conn);

                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {

                        $product_photos = $row['product_photos'];

                        $product_photos = explode(", ", $product_photos);
    
                        foreach($product_photos as $key => $fullname) {
                            $photo_name = explode("/", $fullname);
                            $photo_name = end($photo_name);
                            
                            if(mb_substr($photo_name, 0, 1) == "m") {
                                $main_photo = $photo_name;
                            }
                        }

                        echo "
                        <li class=product-basket>
                            <a href=product.php?product=".$row['id_products']."><div class=basket-product-photo style='background-image: url(uploaded_photos/".$main_photo.")'></div></a>
                            <div class=basket-product-info-box>
                                <div class=basket-product-info>
                                    <a href=product.php?product=".$row['id_products']." class=basket-product-name>".$row['product_name']."</a>
                                    <a href=shop.php?producer=".$row['producer']." class=basket-product-producer>".$row['producer']."</a>
                                </div>
                                <a href=db_conn/del_basket.php?destination=../basket.php&product=".$row['id_products']." class=basket-product-delete>Usuń z koszyka</a>
                            </div>
                        </li>
                        ";
                        }

                ?>
            </ul>
                        
                        <div id=summary-basket>
                            <ul>
                                <li class=summary-basket-category id=products>
                                    <a class=summary-basket-category-name>Produkty (<?php echo mysqli_num_rows($result); ?>)</a>
                                    <ul id=summary-basket-products-list>
                                        <?php
                                            $conn = OpenConn();
                                            $sql = "SELECT * FROM `basket` INNER JOIN `products` ON basket.id_product = products.id_products WHERE basket.id_user = '".$_SESSION['id']."'";
                                            $result = mysqli_query($conn, $sql);

                                            if(mysqli_num_rows($result) > 0) {
                                                while($row = mysqli_fetch_assoc($result)) {
                                                    echo "<li>".$row['product_name']."</li>";
                                                }
                                            }

                                            close($conn);
                                        ?>
                                    </ul>
                                </li>

                                <li class=summary-basket-category id=products>
                                    <a class=summary-basket-category-name>Odbierz do</a>
                                    <?php 
                                        echo date("d.m.Y", strtotime("+3 days",time()));
                                    ?>
                                </li>
                            </ul>

                            <a id=submit-reservation-button href="db_conn/reservation.php">
                                Rezerwuj
                            </a>
                        </div>
                    <?php 
                    } else {
                        echo "koszyk pusty";
                    }

                ?>
        </main>
    <?php
        } else {
            // not logged
            echo "<p id=error-response-for-client>Zaloguj się by używać koszyka </p><p id=error-back-link><a href='login-site.php'>Zaloguj się</a></p>";
        }
    ?>
</body>
