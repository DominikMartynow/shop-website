<?php 
    function adminSiteHeader($user) {
        echo "<header>";
        echo "<a id='welcome-banner'>Witaj ".$user."</a>";

        if(isset($_GET['success'])) {
            echo "<a class=database-response>".$_GET['success']."</a>";
        } else if(isset($_GET['error'])) {
            echo "<a class=database-response>".$_GET['error']."</a>";
        }

        echo "<a id='logout-link' href='db_conn/logout.php'>Wyloguj się</a>";
        echo "</header>";
    }

    function adminSiteNav($choosen) {
        $areas_list = array(
            'orders' => 'Zamówienia',
            'new_product' => 'Nowy produkt',
            'edit_product' => 'Edytuj produkt',
            'comments' => 'Komentarze',
            'opening_hours' => 'Godziny otwarcia',
        );

        echo "<nav>";
        echo "<a class='menu-option tools-menu-option' id='home' href='admin.php'>Strona główna</a>";

        foreach($areas_list as $tool => $caption) {
            if($tool == $choosen) {
                echo "<a class='menu-option tools-menu-option menu-option-active' href='admin.php?tool=".$tool."'>".$caption."</a>";
            } else {
                echo "<a class='menu-option tools-menu-option' href='admin.php?tool=".$tool."'>".$caption."</a>";
            }
        }
        echo "</nav>";
    }

    function selectReservationsStatuses() {
        $conn = OpenConn();

        $sql = "SELECT * FROM reservation_status";
        $result = mysqli_query($conn, $sql);

        close($conn);

        $reseravtion_status = array();

        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result)) {
                $reseravtion_status[$row['id_reservation_status']] = $row['reservation_status'];
            }
        }

        return $reseravtion_status;
    }
?>