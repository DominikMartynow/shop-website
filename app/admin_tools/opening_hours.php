<div class='tool-body' id='opening_hours-body'>
    <h1 class=admin-title>Godziny otwarcia/zamknięcia</h1>

    <div class="flex-direction-row">
        <div id="opening_hours-form-box">
            <form id=config_date_form action="db_conn/opening_hours_config.php" method="POST">
                <ul class='admin-details-list'>
                    <li class='admin-details-list-option'><a class=admin-details-list-caption>Data: </a><a class=-details-list-value><input type="date" name="config_date" id="config_date" required></a></li>
                    <li class='admin-details-list-option'><a class=admin-details-list-caption>Czy otwarte: </a><a class=-details-list-value><input type="checkbox" name="is_open" id="is_open"></a></li>
                    <li class='admin-details-list-option'><a class=admin-details-list-caption>Godzina otwarcia: </a><a class=-details-list-value><input type="number" name="opening_hour" id="opening_hour"></a></li>
                    <li class='admin-details-list-option'><a class=admin-details-list-caption>Godzina zamknięcia: </a><a class=-details-list-value><input type="number" name="closing_hour" id="closing_hour"></a></li>
                    <li class='admin-details-list-option'><a class=admin-details-list-caption>Godzina otwarcia: </a><a class=-details-list-value><input type="submit" value="Wyślij zmiany"></a></li>
                </ul>
            </form>
        </div>

        <div id="opening-hours-list">
            <div id="admin-list">
                <table id="list-table" border>

                <thead>
                    <tr><td>Lp.</td><td>Zmieniona data</td><td>Zamknięte/Otwarte</td><td>Otwarcie</td><td>Zamkniecie</td><td>Usuń</td></tr>
                </thead>
                    <?php 
                        $date = date('Y-m-d');

                        $conn = OpenConn();

                        $sql = "SELECT * FROM exceptions WHERE date >= '".$date."'";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $isOpen = $row['is_open'];

                                if($isOpen == 1){
                                    $isOpen = "Otwarte";
                                } else {
                                    $isOpen = "Zamkniete";
                                }
                                
                                echo "<tr><td>".$row["id_exception"]."</td><td>".$row["date"]."</td><td>".$isOpen."</td><td>".$row['open']."</td><td>".$row['close']."</td><td><a href='db_conn/delete_exception.php?id_exception=".$row['id_exception']."'>Usuń</a></td></tr>";
                            }
                        }

                        close($conn);
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>