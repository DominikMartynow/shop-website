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
                <a class="menu-item" href="#main"><li>Home</li></a>
                <a class="menu-item" href="#assortment"><li>Asortyment</li></a>
                <a class="menu-item" href="#contact"><li>Kontakt</li></a>
            </ul>
        </nav>
    </header>

    <main>
        <div id="hours">
            <a id=is-open-info>
                <?php 
                    include 'db_conn/connect.php';

                    $conn = OpenConn();

                    $date = date('Y-m-d');
                    $time = date('H');
                    $dayOfWeek = date('w');

                    $sql = "SELECT * FROM exceptions WHERE date LIKE '".$date."'";
                    $result = mysqli_query($conn, $sql);

                    close($conn);

                    if(mysqli_num_rows($result) === 1) {
                        $row = mysqli_fetch_assoc($result);
                        if($row['is_open'] == 1) {
                            $open = $row['open'];
                            $close = $row['close'];

                            if($time < $open) {
                                echo "Dzisiaj sklep jest otwarty od godziny <b>".$open.":00</b>";
                            } else if ($time >= $open && $time <= $close) {
                                echo "Dzisiaj sklep jest otwarty do godziny <b>".$close.":00</b>";
                            } else {
                                echo "Sklep jest już dzisiaj <b>nieczynny</b>";
                            }
                        } else {
                            echo "Dzisiaj sklep jest <b>nieczynny</b>";
                        }
                    } else if(mysqli_num_rows($result) > 1) {
                        exit();
                    } else {
                        if($dayOfWeek != 7) {
                            if($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                                if($time < 8) {
                                    echo "Dzisiaj sklep jest otwarty od godziny <b>8:00</b>";
                                } else if ($time >= 8 && $time <= 17) {
                                    echo "Dzisiaj sklep jest otwarty do godziny <b>17:00</b>";
                                } else {
                                    echo "Sklep jest już dzisiaj <b>nieczynny</b>";
                                }
                            } else if($dayOfWeek == 6) {
                                if($time < 8) {
                                    echo "Dzisiaj sklep jest otwarty od godziny <b>8:00</b>";
                                } else if ($time >= 8 && $time <= 17) {
                                    echo "Dzisiaj sklep jest otwarty do godziny <b>13:00</b>";
                                } else {
                                    echo "Sklep jest już dzisiaj <b>nieczynny</b>";
                                }
                            } else {
                                echo "Dzisiaj sklep jest <b>nieczynny</b>";
                            }
                        }
                    }
                ?>
            </a>
        </div>
    </main>

    <div id="assortment">
        <h1>Asortyment</h1>
        <ul id=assortment-menu class=flex-box>
            <a id=haberdashery class=assortment-item href=""><li class=assortment-item-name>Pasmanteria</li></a>
            <a id=clothes class=assortment-item href=""><li class=assortment-item-name>Odzież</li></a>
            <a id=shoes class=assortment-item href=""><li class=assortment-item-name>Obuwie</li></a>
            <a id=gallantry class=assortment-item href=""><li class=assortment-item-name>Akcesoria</li></a>
            <a id=other class=assortment-item href=""><li class=assortment-item-name>Inne</li></a>
        </ul>
    </div>

    <div id="contact">
        <h1>Kontakt</h1>
        <ul id=contact-box class=flex-box>
            <li class=contact-option id=phone-number><img class="icon" src="ico/icons8-phone-50.png" alt="phone-icon"><a href="tel: 607 504 232">607 504 232</a></li>
            <li class=contact-option id=mail><img class="icon" src="ico/icons8-mail-50.png" alt="mail-icon"><a href="mailto: mail@example.com">mail@example.com</a></li>
        </ul>

        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d78720.40938819876!2d16.228148843359378!3d51.9337202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4705bf8541751b47%3A0x8ad445265a9aebdf!2sOdzie%C5%BC%20Obuwie%20Pasmanteria!5e0!3m2!1spl!2spl!4v1698067635568!5m2!1spl!2spl" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <footer>
        <p>ikony <a href="https://icons8.com/"><b>icons8</b></a> / strona <a href="martynow.pl"><b>Dominik Martynów</b></a></p>
    </footer>
</body>
</html>