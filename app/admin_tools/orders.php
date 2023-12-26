<div class='tool-body' id='orders-body'>
    <h1 class=admin-title>Zamówienia</h1>

    <?php 
        if(isset($_GET['reservation'])) {
            $reservation = $_GET['reservation'];
            
            include 'order_details.php';

        } else {
    ?>

    <nav class=sub-nav>
        <a href='admin.php?tool=orders' class='menu-option sub-menu-option' id='home'>Wszystkie</a>
        <?php 
            //pobiera mozliwe statusy zamowienia
            if(isset($_GET['status'])) {
                $status = $_GET['status'];
            } else {
                $status = '%';
            }    

            $reservation_status = adminSubMenu('reservation_status', 'id_reservation_status', 'reservation_status');

            foreach($reservation_status as $status_id => $value) {
                if($status_id == $status) {
                    echo "<a href='admin.php?tool=orders&status=".$status_id."'class='menu-option sub-menu-option sub-menu-option-active'>".$value."</a>";
                } else {
                    echo "<a href='admin.php?tool=orders&status=".$status_id."'class='menu-option sub-menu-option'>".$value."</a>";
                }
            }
        ?>
    </nav>

    <div id="admin-list">
        <table id="list-table" border>
            <thead>
                <tr><td>Klient</td><td>Numer telefonu</td><td>Data zamówienia</td><td>Data odbioru</td><td>Numer rezerwacji</td><td>Zarezerwowane produkty</td><td>Status</td></tr>
            </thead>
        <?php
                //pobiera zamowienia z danym statusem
                $pickup_code = array();
                $firstname = array();
                $secondname = array();
                $telnumber = array();
                $reservation_date = array();
                $reservation_pickup = array();
                $reservation_status_id = array();

                $conn = OpenConn();

                $sql = "SELECT DISTINCT pickup_code, users.firstname, users.secondname, users.telnumber, reservation_date, reservation_pickup, reservation_status_id FROM reservations INNER JOIN users ON users.id_user = reservations.id_user WHERE reservation_status_id LIKE '".$status."' ORDER BY reservation_date DESC";
                $result = mysqli_query($conn, $sql);

                close($conn);

                //zapisuje je do listy
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_array($result)) {
                        array_push($pickup_code, $row['pickup_code']);
                        array_push($firstname, $row['firstname']);
                        array_push($secondname, $row['secondname']);
                        array_push($telnumber, $row['telnumber']);
                        array_push($reservation_date, $row['reservation_date']);
                        array_push($reservation_pickup, $row['reservation_pickup']);
                        array_push($reservation_status_id, $row['reservation_status_id']);
                    }
                }

                foreach($pickup_code as $key => $value) {
                    //pobiera produkty w danym zamowieniu
                    $conn = OpenConn();

                    $sql = "SELECT products.id_products, products.producer, products.product_name FROM reservations INNER JOIN products ON products.id_products = reservations.id_products WHERE pickup_code LIKE '".$pickup_code[$key]."'";
                    $result = mysqli_query($conn, $sql);

                    close($conn);

                    echo "<tr><td>".$firstname[$key]." ".$secondname[$key]."</td><td>".$telnumber[$key]."</td><td>".$reservation_date[$key]."</td><td>".$reservation_pickup[$key]."</td><td><a href='admin.php?tool=orders&status=".$status."&reservation=".$pickup_code[$key]."'>".$pickup_code[$key]."</a></td><td><ul class='products-list'>";
                    
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result)) {
                            echo "<li>".$row['producer']." - ".$row['product_name']."</li>";
                        }
                    }

                    echo "</ul></td><td>".$reservation_status[$reservation_status_id[$key]]."</td></tr>";
                }
            }

        ?>
        </table>
    </div>
</div>