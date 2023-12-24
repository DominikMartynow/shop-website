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
                        <a href="db_conn/logout.php?destination=../reservations.php"><li class=wrapper-account-menu-item id=wrapper-logout>Wyloguj się - <?php echo $_SESSION['firstname']?></li></a>    
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
            <div id="reservations-summary">
                <h2>Podsumowanie rezerwacji</h2>
                <div id="summary-box">
                        <?php 
                            $conn = OpenConn();

                            $sql = "SELECT DISTINCT pickup_code, reservation_date, reservation_pickup, reservation_status_id, reservation_status.reservation_status FROM `handel_wielobranzowy`.`reservations` INNER JOIN `handel_wielobranzowy`.`reservation_status` ON reservations.reservation_status_id = reservation_status.id_reservation_status WHERE reservations.id_user = '".$_SESSION['id']."' ORDER BY pickup_code DESC;";
                            $result = mysqli_query($conn, $sql);

                            close($conn);

                            $expired = array();
                            $waiting = array();
                            $preparation = array();

                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $remaining_time = (strtotime($row['reservation_pickup']) - strtotime(date("Y-m-d")))/86400;
                                    if($row['reservation_status_id'] == 3) {
                                        //gotowe do odbioru
                                        if($remaining_time < 0) {
                                            //przedawnione
                                            array_push($expired, $row['pickup_code']);
                                        } else {
                                            $waiting_array = array($code = $row['pickup_code'], $remaining = $remaining_time);
                                            array_push($waiting, $waiting_array);
                                        }
                                    } else if($row['reservation_status_id'] == 2) {
                                        //zatwierdzone
                                        array_push($preparation, $row['pickup_code']);
                                    }
                                }
                            } else {
                                echo '<li class="summary-list-option">Żadne zamówienie nie czeka na odebranie</li>';
                            }
                            
                            echo '<div class="summary-category">';
                            echo '<a class="summary-category-header bold">Rezerwacje do odebrania ('.count($waiting).'):</a>';
                            echo '<ul class="summary-category-list fontw-normal">';
                            foreach($waiting as $key => $value) {
                                $pickup_code = $value[0];
                                $remaining = $value[1];
                                
                                echo '<li class="summary-list-option"><a href="#'.$pickup_code.'">'.$pickup_code.' oczekuje jeszcze '.$remaining.' dni</a></li>';
                            }
                            echo '</ul></div>';

                            echo '<div class="summary-category">';
                            echo '<a class="summary-category-header bold">W przygotowaniu ('.count($preparation).'):</a>';
                            echo '<ul class="summary-category-list fontw-normal">';
                            foreach($preparation as $key => $value) {
                                echo '<li class="summary-list-option "><a href="#'.$key.'">'.$value.'</a></li>';
                            }
                            echo '</ul></div>';
                        ?>

                </div>
                <div id="reservations-filter">
                    <h2>Wyświetlaj</h2>
                <ul id="display-filters-list">
                    <a href="reservations.php"><li class='display-filters-option bold'>Wszystko</li></a>
                    <?php
                        $conn = OpenConn();

                        $sql = "SELECT DISTINCT reservation_status_id, reservation_status.reservation_status FROM `handel_wielobranzowy`.`reservations` INNER JOIN `handel_wielobranzowy`.`reservation_status` ON reservations.reservation_status_id = reservation_status.id_reservation_status WHERE reservations.id_user = '".$_SESSION['id']."' ORDER BY pickup_code DESC;";
                        $result = mysqli_query($conn, $sql);

                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                if(isset($_GET['display']) && $_GET['display'] == $row['reservation_status_id']) {
                                    echo "<a href='reservations.php?display=".$row['reservation_status_id']."'><li class='display-filters-option underline'>".$row['reservation_status']."</li></a>";
                                } else {
                                    echo "<a href='reservations.php?display=".$row['reservation_status_id']."'><li class=display-filters-option>".$row['reservation_status']."</li></a>";
                                }
                            }
                        }

                        close($conn);
                    ?>
                </ul>
            </div>
            </div>

            <ul id=reservations-list>
                <?php 
                    $conn = OpenConn();

                    if(isset($_GET['display'])) {
                        $sql = "SELECT DISTINCT pickup_code, reservation_date, reservation_pickup, reservation_status.reservation_status FROM `handel_wielobranzowy`.`reservations` INNER JOIN `handel_wielobranzowy`.`reservation_status` ON reservations.reservation_status_id = reservation_status.id_reservation_status WHERE reservations.id_user = '".$_SESSION['id']."' AND reservation_status_id = ".$_GET['display']." ORDER BY pickup_code DESC;";
                    } else {
                        $sql = "SELECT DISTINCT pickup_code, reservation_date, reservation_pickup, reservation_status.reservation_status FROM `handel_wielobranzowy`.`reservations` INNER JOIN `handel_wielobranzowy`.`reservation_status` ON reservations.reservation_status_id = reservation_status.id_reservation_status WHERE reservations.id_user = '".$_SESSION['id']."' ORDER BY pickup_code DESC;";
                    }
                    $result = mysqli_query($conn, $sql);

                    close($conn);

                    $reservations = array();
                    $date = array();
                    $pickup = array();
                    $status = array();

                    if(mysqli_num_rows($result) > 0) {

                        while($row = mysqli_fetch_assoc($result)) {
                            array_push($reservations, $row['pickup_code']);
                            array_push($date, $row['reservation_date']);
                            if($row['reservation_pickup'] == "0000-00-00") {
                                array_push($pickup, "niezatwierdzone");
                            } else {
                                array_push($pickup, $row['reservation_pickup']);
                            }
                            array_push($status, $row['reservation_status']);
                        }

                        foreach($reservations as $key => $reservation) {
                            echo "<li class=reservation-box id=".$reservation.">
                                <a class=pickup_code>Numer zamówienia: ".$reservation."</a>
                                <div class=reservation-data> 
                                    <div class=reservation-data-box>
                                        <a class=reservation-data-caption>Data rezerwacji: </a>
                                        <a class=reservation-data-value>".$date[$key]."</a>
                                    </div>

                                    <div class=reservation-data-box>
                                        <a class=reservation-data-caption>Oczekuje do: </a>
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
                                        <div class='reservations-product-photo bg-placeholder' style='background-image: url(uploaded_photos/".$main_photo.")'></div>
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
