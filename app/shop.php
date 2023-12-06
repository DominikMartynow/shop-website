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
                        
                        <form id='login-form-wrapper' id=inside action="db_conn/login.php?destination=../shop.php" method="post">
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

    <main id="shop">
        <nav id="site-path">
            <a href="shop.php">sklep</a>
            <a> > </a>
            <?php
                if(isset($_GET['category'])) {
                    $category = $_GET['category'];
                } else {
                    $category = "%";
                }

                if(isset($category) && $category != "%") {
                    $conn = OpenConn();

                    $sql = "SELECT * FROM product_category WHERE id_product_category = '".$_GET['category']."'";
                    $result = mysqli_query($conn, $sql);

                    close($conn);

                    if(mysqli_num_rows($result) === 1) {
                        $row = mysqli_fetch_assoc($result);
                        $product_category = $row['product_category_name'];                        
                    }

                    echo "<a href=shop.php?category_id=".$_GET['category']." class=bold>".$product_category."</a>";
                    echo "<a> > </a>";
                }
            ?>
        </nav>

        <div id=shop-main-conent>
            <div id="left-bar">
                <a href="shop.php" class=bold>Kategorie:</a>
                <ul id=categories-list>
                    <?php 
                        $conn = OpenConn();

                        $sql = "SELECT * FROM product_category";
                        $result = mysqli_query($conn, $sql);

                        close($conn);

                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                if(isset($_GET['category'])) {
                                    if($_GET['category'] == $row['id_product_category']) {
                                        echo "<a href='shop.php?category=".$row['id_product_category']."'><li class='product-list-option underline'>".$row['product_category_name']."</li></a>";
                                    } else {
                                        echo "<a href='shop.php?category=".$row['id_product_category']."'><li class=product-list-option>".$row['product_category_name']."</li></a>";
                                    }
                                } else {
                                    echo "<a href='shop.php?category=".$row['id_product_category']."'><li class=product-list-option>".$row['product_category_name']."</li></a>";
                                }
                            }
                        }

                        if(isset($_POST['limit']) & !empty($_POST['limit'])) {
                            $limit = $_POST['limit'];
                        } else {
                            $limit = 10;
                        }

                        if(isset($_POST['sort'])) {
                            $sort = $_POST['sort'];
                        } else {
                            $sort = "product_name ASC";
                        }

                        $conn = OpenConn();
                        if(isset($_POST['search'])) {
                            $search = $_POST['search'];
                        } else if(isset($_GET['search'])) {
                            $search = $_GET['search'];
                        }

                        if(isset($search)) {
                            $search = explode(" ", $search);
                            
                            foreach($search as $key => $value) {
                                if($key == 0){
                                    $search_condition = "producer LIKE '%".$value."%' OR product_name LIKE '%".$value."%' OR product_description LIKE '%".$value."%'";  
                                } else  if($key > 0) {
                                    $search_condition = $search_condition." OR producer LIKE '%".$value."%' OR product_name LIKE '%".$value."%' OR product_description LIKE '%".$value."%'";
                                }
                            }

                            $url = "category=".$category;

                            if(isset($_POST['sort'])) {
                                $sql = "SELECT * FROM products WHERE id_product_category LIKE '".$category."' AND (".$search_condition.") ORDER BY ".$sort."";
                            } else {
                                $sql = "SELECT * FROM products WHERE id_product_category LIKE '".$category."' AND (".$search_condition.") ORDER BY ".$sort."";

                            }
                        } else { 
                            $sql = "SELECT * FROM products WHERE id_product_category LIKE '".$category."'"; 

                            if(isset($_POST['sort'])) {
                                $sql = "SELECT * FROM products WHERE id_product_category LIKE '".$category."' ORDER BY ".$sort."";
                            } else {
                                $sql = "SELECT * FROM products WHERE id_product_category LIKE '".$category."' ORDER BY ".$sort."";
                            }
                        }

                        $result = mysqli_query($conn, $sql);

                        $num_of_products = mysqli_num_rows($result);

                        close($conn);

                        $url = "category=".$category;

                        if(isset($_POST['sort'])) {
                            $sort_selected = $_POST['sort'];
                        } else {
                            $sort_selected = "product_name ASC";
                        }
                            
                    ?>
                </ul>
            </div>

            <div id="right-bar">
                <div id="search-box">
                    <form id=search-form action="shop.php<?php echo "?".$url; ?>" method="post">
                        <div id=search>
                            <input type="search" name="search" id="search-input" placeholder="Nazwa, producent..." value='<?php if(isset($_POST['search'])) {echo $_POST['search'];}?>'>
                            <input type="submit" value="Szukaj" id="search-submit" class=pointer>
                        </div>

                        <div id="sort-box">
                            <select name="sort" id="sort">
                                <?php 
                                    $sort_options = array(
                                        "product_name ASC" => "Nazwa rosnąco",
                                        "product_name DESC" => "Nazwa malejąco",
                                        "producer ASC" => "Producent rosnąco",
                                        "producer DESC" => "Producent malejąco",
                                    );

                                    foreach($sort_options as $key => $value) {
                                        if($key == $sort_selected) {
                                            echo "<option value='".$key."' class=sort-option selected>".$value."</option>";
                                        } else {
                                            echo "<option value='".$key."' class=sort-option >".$value."</option>";
                                        }
                                    }
                                ?>
                            
                            </select>

                            <div id="product-num">
                                <label for="product-num" id="product-num-label">Liczba produktów na stronie: </label>
                                <input type="range" name="limit" id="product-num" min="10" max="<?php echo mysqli_num_rows($result)?>" step="1" value="<?php echo $limit;?>">
                            </div>
                        </div>
                    </form>
                    <a id="result-info">Wyniki wyszukiwania: <?php echo $num_of_products;?>, wyświetlasz: <?php echo $limit;?></a>
                </div>

                <?php 
                        if(mysqli_num_rows($result) > 0) {
                ?>
                    <div id=shop-products-list>
                
                <?php
                            for($i = 0; $i < $limit; $i++) {
                            
                            $row = mysqli_fetch_assoc($result);

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
                                    <a href='product.php?product=".$row['id_products']."'>
                                        <div class=shop-product>
                                            <div class='shop-product-main-photo bg-placeholder' style='background-image: url(uploaded_photos/".$main_photo.")'></div>
                                            <div class=shop-prouct-info>
                                                <a class=shop-product-name>".$row['product_name']."</a>
                                                <a class=shop-product-producer>".$row['producer']."</a>
                                            </div>
                                        </div>
                                    </a>
                                ";
                            }

                    echo "</div>";

                    if($num_of_products > $limit) {
                        $limit = $limit + 10;

                        if($limit > $num_of_products) {
                            $limit = $limit - ($limit-$num_of_products);
                        }

                        echo "<a id='show-more-button' class='center' href='shop.php?".$url."&limit=".$limit."'>Pokaż więcej produktów<img id=arrow-down-icon src='ico/icons8-down-arrow-32.png'></a>";
                    }
                ?>


                <?php
                        } else {
                            echo "<p id=error-response-for-client>W wybranej kategorii nie ma żadnych produktów </p><p id=error-back-link><a href='shop.php'>Pełna oferta</a></p>";
                        }
                
                    ?>

            </div>
        </div>
    </main>
    
    <footer>
        <p>ikony <a href="https://icons8.com/"><b>icons8</b></a> / strona <a href="martynow.pl"><b>Dominik Martynów</b></a></p>
    </footer>

</body>
