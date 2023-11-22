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
        <main id="reservations">
            <h1 id="reservations-header">Twoje rezerwacje</h1>
            <ul id=reservations-list>
                <?php 
                    $conn = OpenConn();

                    $sql = "SELECT * FROM `reservations` INNER JOIN reservation_status ON reservations.status = reservation_status.id_reservation_status WHERE reservations.id_user = '".$_SESSION['id']."' ORDER BY pickup_code DESC;";
                    $result = mysqli_query($conn, $sql);

                    print_r($result);

                    close($conn);

                    $reservations = array();
                    $date = array();
                    $pickup = array();
                    $status = array();

                    echo "nigger";

                    if(mysqli_num_rows($result) > 0) {

                        while($row = mysqli_fetch_assoc($result)) {
                            array_push($reservations, $row['pickup_code']);
                            array_push($date, $row['date']);
                            array_push($pickup, $row['pickup']);
                            array_push($status, $row['reservation_status.status']);
                        }

                        foreach($reservations as $key => $reservation) {
                            echo "<li class=reservation-box>
                                <a class=pickup_code>Numer zamówienia: ".$reservation."</a>
                                <div class=reservation-data> 
                                    <div class=reservation-data-box>
                                        <a class=reservation-data-caption>Data rezerwacji: </a>
                                        <a class=reservation-data-value>".$date[$key]."</a>
                                    </div>

                                    <div class=reservation-data-box>
                                        <a class=reservation-data-caption>Data odbioru: </a>
                                        <a class=reservation-data-value>".$pickup[$key]."</a>
                                    </div>

                                    <div class=reservation-data-box>
                                        <a class=reservation-data-caption>Status rezerwacji: </a>
                                        <a class=reservation-data-value>".$status[$key]."</a>
                                    </div>
                                </div>
                            ";

                            $conn = OpenConn();

                            $sql = "SELECT * FROM `reservations` INNER JOIN `products` ON reservations.id_products = products.id_products WHERE reservations.pickup_code = '".$reservation."'";
                            $result = mysqli_query($conn, $sql);

                            close($conn);
                            
                            if(mysqli_num_rows($result) > 0) {
                                echo "<ul class=reservation-products-list>";    

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
                                    <li class=reservations-product>
                                        <div class=reservations-product-photo style='background-image: url(uploaded_photos/".$main_photo.")'></div>
                                        <div class=reservations-product-data>
                                            <a class=reservations-product-name>".$row['product_name']."</a>
                                            <a class=reservations-product-producer>".$row['producer']."</a>
                                        </div>
                                    </li>
                                    ";
                                }

                                echo "</ul>";
                                
                            } else {
                                echo "<p id=error-response-for-client>Nie zarezerwowałeś jeszcze żadnych produktów </p><p id=error-back-link><a href='shop.php'>Oferta</a></p>";
                            }

                            echo "</li>";
                        }
                        
                ?>
            </ul>

                    <?php 
                    } else {
                        echo "<p id=error-response-for-client>Nie zarezerwowałeś jeszcze żadnych produktów </p><p id=error-back-link><a href='shop.php'>Oferta</a></p>";
                    }

                ?>
        </main>
    <?php
        } else {
            // not logged
            echo "<p id=error-response-for-client>Zaloguj się by przeglądać rezerwacje </p><p id=error-back-link><a href='login-site.php'>Zaloguj się</a></p>";
        }
    ?>
</body>
