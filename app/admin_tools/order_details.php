<nav class=sub-nav>
    <a href='admin.php?tool=orders&status=<?php echo $_GET['status']?>' class='menu-option sub-menu-option' id='home'>Lista zamówień</a>
</nav>

<div class="tool-body" id="order-details-body">
    <h1 class=admin-title-2>Szczegóły zamówienia</h1>

    <div id="order-details-box">
        <?php 
            $reservation_status = adminSubMenu('reservation_status', 'id_reservation_status', 'reservation_status');
            $reservation = $_GET['reservation'];

            $conn = OpenConn();
            $sql = "SELECT DISTINCT pickup_code, users.firstname, users.secondname, users.telnumber, users.mail, reservation_date, reservation_pickup, reservation_status_id FROM reservations INNER JOIN users ON users.id_user = reservations.id_user WHERE pickup_code = '".$reservation."'";
            $result = mysqli_query($conn, $sql);
            close($conn);

            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                //wysweitla dane klienta
                echo "<div class='order-details-area' id='order-details-client-data'>";
                echo "<h1 class=admin-title-3>Dane klienta:</h1>";
                echo "<ul class='admin-details-list'>";
                echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Imię: </a><a class=-details-list-value>".$row['firstname']."</a></li>";
                echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Nazwisko: </a><a class=-details-list-value>".$row['secondname']."</a></li>";
                echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Numer telefonu: </a><a class=-details-list-value>".$row['telnumber']."</a></li>";
                echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>E-mail: </a><a class=-details-list-value>".$row['mail']."</a></li>";
                echo "</ul>";
                echo "</div>";

                //wyswietla dane zamowienia
                echo "<div class='order-details-area' id='order-details-client-data'>";
                echo "<h1 class=admin-title-3>Dane zamówienia:</h1>";
                echo "<ul class='admin-details-list'>";
                echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Numer zamówienia: </a><a class=-details-list-value>".$row['pickup_code']."</a></li>";
                echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Data zamówienia: </a><a class=-details-list-value>".$row['reservation_date']."</a></li>";
                
                echo "<form id='admin-tools-box' action='db_conn/order_status_change.php?order=".$row['pickup_code']."' method='post'>";
                echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Data odbioru: </a><a class=-details-list-value><input type=text onfocus=(this.type='date') onblur=(this.type='text') name=pickup_date placeholder=".$row['reservation_pickup']."></a></li>";
                echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Status zamówienia: </a><a class=-details-list-value>";
                
                echo "<select name='status' id='admin-tools-input' onchange='this.form.submit()'>";
                        foreach($reservation_status as $key => $value) {
                            if($key == $row['reservation_status_id']) {
                                echo "<option value=".$key." selected disabled>".$value."</option>";
                            } else {
                                echo "<option value=".$key.">".$value."</option>";
                            }
                        }
                echo "</select>";
                echo "</form>";

                echo "</a></li>";
                echo "</ul>";
                echo "</div>";
            } else {
                echo "Nie ma takiego zamówienia";
            }

            $conn = OpenConn();
            $sql = "SELECT products.producer, products.product_name, products.product_photos FROM reservations INNER JOIN products ON products.id_products = reservations.id_products WHERE pickup_code = '".$reservation."'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                echo "<div class='order-details-area' id='order-details-products'>";
                echo "<h1 class=admin-title-3>Produkty:</h1>";
                echo "<ul class='order-details-products-list' id='order-details-products'>";
                while($row = mysqli_fetch_array($result)){
                    $product_photos = $row['product_photos'];
                    $product_photos = explode(", ", $product_photos);
        
                    foreach($product_photos as $key => $fullname) {
                        $photo_name = explode("/", $fullname);
                        $photo_name = end($photo_name);
                        
                        if(mb_substr($photo_name, 0, 1) == "m") {
                            $main_photo = $photo_name;
                        }
                    }

                    //wysweitla dane produktów
                    echo "<li class='product-list-option'>";
                    echo "<div class='reservations-product-photo bg-placeholder' style='background-image: url(uploaded_photos/".$main_photo.")'></div>";
                    echo "<ul class='admin-details-list'>";
                    echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Producent: </a><a class=-details-list-value>".$row['producer']."</li>";
                    echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Nazwa produktu: </a><a class=-details-list-value>".$row['product_name']."</li>";
                    echo "</ul>";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</div>";
            }

            close($conn);
        ?>
    </div>
</div>