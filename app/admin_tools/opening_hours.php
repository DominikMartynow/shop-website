<div class='tool-body' id='opening_hours-body'>
    <h1 class=admin-title>Godziny otwarcia/zamknięcia</h1>

    <div class="flex-direction-row">
        <div id="opening_hours-form-box">
s
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
                                print_r($_SESSION);
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