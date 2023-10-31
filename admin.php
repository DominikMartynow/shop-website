<?php 
    session_start();

    if(isset($_SESSION['login']) && isset($_SESSION['password'])){
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="style/admin-style.css">
</head>
<body>
    <header>
        <a>Zalogowano użytkownika <?php echo $_SESSION['login']; ?></a>

        <?php 
            $date = date('d.m.Y');
            $time = date('H:i:s');

            if(isset($_GET['error'])) {
                echo "<a class=error>".$date." ".$time." | <b>".$_GET['error']."</b></a>";
            } else if(isset($_GET['success'])) {
                echo "<a class=success>".$date." ".$time." | <b>".$_GET['success']."</b></a>";
            }
        ?>
    </header>

    <div id="exceptions-config">
        <h1>KONFIGURACJA GODZIN OTWARCIA</h1>

        <form id=config_date_form action="db_conn/opening_hours_config.php" method="POST">
            <label for="config_date">Data: </label>
            <input type="date" name="config_date" id="config_date" required><br>
            <label for="is_open">Czy sklep jest otwarty tego dnia?: </label>
            <input type="checkbox" name="is_open" id="is_open"><br>
            <label for="opening_hour">Godzina otwarcia: </label>
            <input type="number" name="opening_hour" id="opening_hour"><br>
            <label for="closing_hour">Godzina zamknięcia: </label>
            <input type="number" name="closing_hour" id="closing_hour"><br><br>
            <input type="submit" value="Wyślij zmiany"><br><br>
        </form>

        <br>

        <table id=exceptions_table_list border>
            <tr>
                <td colspan="6">Tabela nadchodzących zmian</td>
            </tr>
            <tr>
                <td>Lp.</td><td>Zmieniona data</td><td>Zamknięte/Otwarte</td><td>Otwarcie</td><td>Zamkniecie</td><td>Usuń</td>
            </tr>
            <?php 
                include 'db_conn/connect.php';

                $conn = OpenConn();

                $sql = "SELECT * FROM exceptions WHERE date <= '".$date."'";
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

    <div id="product-add">
        <h1>DODAJ NOWY PRODUKT</h1>

        <?php 
        $conn = OpenConn();

        $sql = "SELECT * FROM product_category";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $product_categories = array();
            while($row = mysqli_fetch_assoc($result)) {
                $product_categories[$row['id_product_category']] = $row['product_category_name'];
            }
        }

        close($conn);
        ?>

        <form action="product_add.php" method="post">
            <label for="product_name">Nazwa produktu: </label>
            <input type="text" name="product_name"><br>
            <label for="product_category">Wybierz kategorię: </label>
            <select name="product_category"><br>
                <option value="" disabled selected></option>
                <?php 
                    foreach($product_categories as $key => $value) {
                        echo "<option value=".$key.">".$value."</option>";
                    }    
                ?>
            </select><br>
            <label for="is_variant">Czy produkt ma warianty?: </label>
            <input type="checkbox" name="is_variant"><br>
            <label for="product_color">Kolor przewodni: </label>
            <input type="color" id="product_color"><br>
            <label for="product_photos">Zdjęcia produktu: </label>
            <input type="file" id="product_photos" accept="image/*" multiple onchange="displayPhotos()">
        </form>

        <div id="zdjecia">

        </div>

        <script>
            function displayPhotos() {
                let photos = document.getElementById("product_photos").files;

                let zdjecia = document.getElementById("zdjecia")

                for(i = 0; i < photos.length; i++) {
                    photo = URL.createObjectURL(photos[i]);

                    document.getElementById("zdjecia").innerHTML += "<img class=product_photo id=product_photo"+i+" src="+photo+">"

                    //URL.revokeObjectURL(photo)
                }
            }

        </script>
    </div>
    
</body>
</html>

<?php 
    } else {
        header("Location: index.php");
    }

?>