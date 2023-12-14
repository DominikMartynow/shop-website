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
                        <a href="db_conn/logout.php?destination=../product.php?product=<?php echo $_GET['product'] ?>"><li class=wrapper-account-menu-item id=wrapper-logout>Wyloguj się - <?php echo $_SESSION['firstname']?></li></a>    
                        <?php 
                            } else {
                        ?>        
                        
                        <form id='login-form-wrapper' id=inside action="db_conn/login.php?destination=../product.php?product=<?php echo $_GET['product'] ?>" method="POST">
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
        if(isset($_GET['product']) && !empty($_GET['product'])) {
            $product = $_GET['product'];

            $conn = OpenConn();
        
            $sql = "SELECT * FROM products INNER JOIN product_category ON products.id_product_category = product_category.id_product_category WHERE products.id_products='".$product."';";
            $result = mysqli_query($conn, $sql);

            close($conn);

            if(mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                    $product_name = $row['product_name'];
                    $producer = $row['producer'];
                    $product_category_id = $row['id_product_category'];
                    $product_category = $row['product_category_name'];
                    $product_photos = $row['product_photos'];

                    $product_photos = explode(", ", $product_photos);

                    foreach($product_photos as $key => $fullname) {
                        $photo_name = explode("/", $fullname);
                        $photo_name = end($photo_name);
                        
                        if(mb_substr($photo_name, 0, 1) == "m") {
                            $main_photo_key = $key;
                            $main_photo = $photo_name;
                            $photos[$key] = $photo_name;
                        } else {
                            $photos[$key] = $photo_name;
                        }
                    }

                    $product_description = $row['product_description'];
    ?>          

    <div id="product-main">
        <nav id="site-path">
            <a href="shop.php">sklep</a>
            <a> > </a>
            <a href="shop.php?category=<?php echo $product_category_id?>"><?php echo $product_category?></a>
            <a> > </a>
            <a href="" class=bold><?php echo $product_name?></a>
        </nav>

        <div id="product-basic-info">
            <div id="photo-box">
                <div photo-number="0" id="main-photo" style='background-image: url("uploaded_photos/<?php echo $main_photo; ?>")'></div>
                <div id="gallery">
                    <?php 
                        echo "<div photo-number=".$main_photo_key." class=gallery-photo style='background-image: url(uploaded_photos/".$main_photo.")' onclick='handle_click(this)'></div>";

                        foreach($photos as $key => $fullname) {
                            if($key != $main_photo_key) {
                                echo "<div photo-number=".$key." class=gallery-photo style='background-image: url(uploaded_photos/".$photos[$key].")' onclick='handle_click(this)'></div>";
                            }
                        }
                    ?>
                </div>
            </div>
            <div id="info-box">
                <h2 id=product-name><?php echo $product_name;?></h2>
                <a href="shop.php?category=<?php echo $product_category_id ?>&search=<?php echo $producer;?>" id="producer"><?php echo $producer;?><a>
                <?php 
                    if(is_logged()) {

                        $conn = OpenConn();

                        $sql = "SELECT * FROM `basket` WHERE id_product = '".$_GET['product']."' AND id_user = '".$_SESSION['id']."'";
                        $result = mysqli_query($conn, $sql);
            
                        if(mysqli_num_rows($result) === 1) {
                ?>
                        <a href="db_conn/del_basket.php?destination=../product.php?product=<?php echo $_GET['product']; ?>&product=<?php echo $_GET['product']?>" id="add-to-basket">Usuń z koszyka <img class="add-to-basket-ico" src="ico/icons8-minus-50.png"></a>
                <?php
                        } else {
                ?>
                        <a href="db_conn/add_basket.php?product=<?php echo $_GET['product']; ?>" id="add-to-basket">Dodaj do koszyka <img class="add-to-basket-ico" src="ico/icons8-plus-50.png"></a>
                <?php
                        }
                    } else {
                ?>      
                        <a href="db_conn/add_basket.php?product=<?php echo $_GET['product']; ?>" id="add-to-basket">Dodaj do koszyka <img class="add-to-basket-ico" src="ico/icons8-plus-50.png"></a>
                <?php
                    }

                ?>
            </div>
        </div>

        <div id="product_description">
            <?php echo $product_description; ?>
        </div>

        <div id="product-comments-main">
            <?php 
                if(is_logged()) {
            ?>

                <form action="db_conn/add_comment.php?destination=<?php echo $product?>" id="comment-input-form" method="post">
                    <textarea  name="comment" id="comment-input" placeholder="Dodaj komentarz"></textarea>
                    <input type="submit" id=comment-submit class=pointer value="Skomentuj">
                </form>

            <?php 
                    if(isset($_GET['success-comment'])) {
                ?>
                    <div id="comment-add-response">
                        Twój komentarz został wysłany do zatwierdzenia przez administratora strony.
                        <a onclick="closeResponse()" id=close-comment-response class=pointer>Zamknij</a>
                    </div>
                <?php
                    }
                ?>

                <?php
                    }

                    $conn = OpenConn();

                    $sql = "SELECT * FROM comments INNER JOIN users ON users.id_user = comments.comments_author WHERE id_product_comments = '".$product."' ORDER BY date DESC";
                    $result = mysqli_query($conn, $sql);
                    close($conn);

                    $date = time();

                    if(mysqli_num_rows($result) >= 1) {
                        echo "<div id=comments>";

                        while($row = mysqli_fetch_assoc($result)) {
                            if($row['verified'] == 1) {
                                $add_date = $row['date'];
                                $add_date = strtotime($add_date);

                                $diff = abs($date - $add_date);
                                $years = floor($diff / (365*60*60*24));
                                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                                if($years > 0) {
                                    $date_info = $row['date'];
                                } else if($months > 9 && $months <= 12) {
                                    $date_info = "kilkanaście miesięcy temu";
                                } else if ($months > 1 && $months <= 9) {
                                    $date_info = "kilka miesięcy temu";
                                } else if($days > 1 && $days <= 30) {
                                    $date_info = "kilka dni temu";
                                } else if($days <= 1) {
                                    $date_info = "kilka godzin temu";
                                } else {
                                    $date_info = $row['date'];
                                }

                                $conn = OpenConn();

                                $sql = "SELECT id_comments_likes FROM comments_likes WHERE id_comment = ".$row['id_comments']."";
                                $num = mysqli_query($conn, $sql);

                                if(is_logged()) {
                                    $sql = "SELECT id_comments_likes FROM comments_likes WHERE id_user_likes = ".$_SESSION['id']." AND id_comment = ".$row['id_comments']."";
                                    $reaction_check = mysqli_query($conn, $sql);

                                    if(mysqli_num_rows($reaction_check) == 0) {
                                        $check = "";
                                    } else {
                                        $check = "bold";
                                    }
                                } else {
                                    $check = "";
                                }

                                close($conn);

                                if(mysqli_num_rows($num) == 0) {
                                    $num_of_reactions = "";
                                } else {
                                    $num_of_reactions = "(".mysqli_num_rows($num).")";
                                }

                                ?>
                                <div class='comment-box alerts-border' id='comment<?php echo $row['id_comments']?>'>
                                    <div class='comment-info'>
                                        <a class='comment-author bold'><?php echo $row['firstname']?></a>
                                        <a class='comment-date'><?php echo $date_info ?></a>
                                    </div>
                                    <a class='comment-content'><?php echo $row['comments_content']?></a> 
                                    <div class='comment-interactions'>
                                        <a class='comment-interaction pointer <?php echo $check ?>' href="db_conn/comment_reaction.php?comment=<?php echo $row['id_comments']?>&destination=<?php echo $product?>">Przydatne <?php echo $num_of_reactions?></a>
                                        <a class='comment-interaction pointer' onclick="show(<?php echo $row['id_comments']?>)">Odpowiedz</a>
                                        <a class='comment-interaction pointer' id='interactions-options'>Opcje</a>
                                    </div>
                                    <form action="db_conn/add_answer.php?destination=<?php echo $product?>" class="answer-form" method="post" id="input<?php echo $row['id_comments']?>">
                                        <textarea name="answer" class="answer-input" placeholder="Dodaj odpowiedź"></textarea>
                                        <input type="hidden" name="answers_to" value=<?php echo $row['id_comments']?>>
                                        <div class="answer-form-options">
                                            <a class='pointer answer-form-cancel-input' onclick="hide(<?php echo $row['id_comments']?>)">Anuluj</a>
                                            <input type="submit" class='answer-submit pointer' value="Odpowiedz">
                                        </div>

                                    </form>
                                </div>
                                <?php
                            }
                        }
                    } else {
                        echo "<p id=error-response-for-client>Nikt jeszcze nie skomentował</p><p id=error-back-link><a>Bądź pierwszy!</a></p>";
                    }

                ?>
                </div>
        </div>
    </div>

    <script>
    photos = document.getElementsByClassName("gallery-photo")

    if(photos.length < 6) {
        document.getElementById("gallery").style.overflow = "hidden";
    }

    const opened_inputs = [];

    function handle_click(photo) {
        main = document.getElementById("main-photo")

        photos = document.getElementsByClassName("gallery-photo")

        if(photos.length < 6) {
            document.getElementById("gallery").style.overflow = "hidden";
        }

        for(let i = 0; i < photos.length; i++){
            try {
                photos[i].removeAttribute("id")
            } finally {
                photos[i].removeAttribute("id")
            }
            }
        
        photo.setAttribute("id", "choosen")

        file_name = photo.getAttribute("style")
        file_name = file_name.split(": ");
        file_name = file_name[1]

        main.style.backgroundImage = file_name
    }

    function closeResponse() {
        console.log("comment-add-response")
        document.getElementById("comment-add-response").style.display = "none";
    }

    function show(element) {
        opened_inputs.push(element)

        if(opened_inputs.length <= 1){
            console.log(opened_inputs[0])
            document.getElementById('input'+element).style.display = "flex";
        } else {
            console.log("2")
            document.getElementById('input'+opened_inputs[0]).style.display = "none";
            opened_inputs.splice(0,1)
            document.getElementById('input'+element).style.display = "flex";
        }
    }

    function hide(element) {
        console.log(element)
        document.getElementById('input'+element).style.display = "none";
    }

    // function highlight(element) {
    //     console.log(element)
    //     document.getElementById('comment'+element).style.animationPlayState = "running";

    //     setTimeout(() => { document.getElementById('comment'+element).style.animationPlayState = "paused"; }, 1000);
    // }

    </script>

    <?php 
            }
        } else {
            echo "<p id=error-response-for-client>WYBRANY PRODUKT NIE ISTNIEJE :( </p><p id=error-back-link><a href='shop.php'>Wyszukaj inny produkt</a></p>";
        }
    ?>
</body>
</html>