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
                    if(!empty($_GET['history'])) {
            ?>
                <div id=comments-history-box>
                    <div id="comments-history">
                        <?php
                                include 'history_comments.php';

                                commentsHistory($_GET['history'], $_GET['product']);
                        ?>
                        <a href="product.php?product=<?php echo $_GET['product']?>#comment<?php echo $_GET['history']?>">Anuluj</a>
                    </div>
                </div>
            <?php
                    }
            ?>
                
                <form action="db_conn/add_comment.php?destination=<?php echo $product?>&mode=c" id="comment-input-form" method="post">
                    <textarea  name="comment" id="comment-input" placeholder="Dodaj komentarz"></textarea>
                    <input type="submit" id='comment-submit' class=pointer value="Skomentuj">
                </form>

                <?php
                    }

                    $answers = array();

                    $conn = OpenConn();

                    $sql = "SELECT * FROM comments INNER JOIN users ON users.id_user = comments.comments_author WHERE id_product_comments = '".$product."' ORDER BY date DESC";
                    $result = mysqli_query($conn, $sql);
                    close($conn);

                    if(mysqli_num_rows($result) >= 1) {
                        echo "<div id=comments>";

                        while($row = mysqli_fetch_assoc($result)) {
                            if($row['verified'] == 1) {

                                $date_info = dateInfo($row['date']);

                                $conn = OpenConn();

                                $sql = "SELECT id_comments_likes FROM comments_likes WHERE id_comment = ".$row['id_comments']."";
                                $num = mysqli_query($conn, $sql);

                                if(is_logged()) {
                                    $sql = "SELECT id_comments_likes FROM comments_likes WHERE id_user_likes = ".$_SESSION['id']." AND id_comment = ".$row['id_comments']."";
                                    $reaction_check = mysqli_query($conn, $sql);
                                    close($conn);

                                    if(mysqli_num_rows($reaction_check) == 0) {
                                        $check = "";
                                    } else {
                                        $check = "bold";
                                    }
                                } else {
                                    $check = "";
                                }

                                if(mysqli_num_rows($num) == 0) {
                                    $num_of_reactions = "";
                                } else {
                                    $num_of_reactions = "(".mysqli_num_rows($num).")";
                                }

                                //wysweitla tylko komentarze wątkowe
                                if($row['path'] == "/") {
                                ?>

                                <!-- wyswietla komentarz -->
                                <div class='comment-box alerts-border' id='comment<?php echo $row['id_comments']?>'>
                                    <div class='comment-info'>
                                        <a class='comment-author bold'><?php echo $row['firstname']?></a>
                                        <a class='comment-date'><?php echo $date_info ?></a>
                                    </div>
                                    <div class='comment-content' id=<?php echo "content".$row['id_comments']?>><?php echo $row['comments_content']?></div> 
                                    
                                    <!-- Edycja komentarza -->

                                    <form class="comment-edit-box" id=<?php echo "edit".$row['id_comments']?> action="db_conn/edit_comment.php?destination=<?php echo $product?>&comment=<?php echo $row['id_comments']?>" method='post'>
                                        <textarea name="edit" class="edit-comment-input" placeholder="Edytuj komentarz"><?php echo $row['comments_content']?></textarea>
                                        <div class='edit-comment-options-box'>
                                            <a class='pointer' onclick="showHide(<?php echo 'content'.$row['id_comments']?>, <?php echo 'edit'.$row['id_comments']?>)">Anuluj</a>
                                            <input type="submit" class='edit-submit pointer' value="Zapisz">
                                        </div>
                                    </form>

                                    <div class='comment-interactions'>
                                        <a class='comment-interaction pointer <?php echo $check ?>' href="db_conn/comment_reaction.php?comment=<?php echo $row['id_comments']?>&destination=<?php echo $product?>">Przydatne <?php echo $num_of_reactions?></a>
                                        <a class='comment-interaction pointer' onclick="show(<?php echo 'input'.$row['id_comments']?>)">Odpowiedz</a>
                                        <a class='comment-interaction pointer' id='interactions-options' onclick="show(<?php echo 'options'.$row['id_comments']?>)">Opcje</a>
                                    </div>
                                    
                                    <ul class="comment-options-list" id="options<?php echo $row['id_comments']?>">
                                        <a class='pointer answer-form-cancel-input' onclick="hide(<?php echo 'options'.$row['id_comments']?>)">Anuluj</a>
                                        <?php
                                            //jeśli user to wlasciciel komentarza wyswietla dodatkowe opcje
                                            if(is_logged()) {
                                                if($_SESSION['id'] == $row['comments_author']) {
                                            ?>
                                                <div class=comment-options-box>
                                                    <li class="comment-options-list-option pointer" onclick="showHide(<?php echo 'edit'.$row['id_comments']?>, <?php echo 'content'.$row['id_comments']?>)">Edytuj</li>
                                                    <a href='db_conn/delete_comment.php?destination=<?php echo $product?>&comment=<?php echo $row['id_comments']?>'><li class="comment-options-list-option">Usuń</li></a>
                                                    <a href="product.php?product=<?php echo $product;?>&history=<?php echo $row['id_comments'];?>#comment<?php echo $row['id_comments'];?>"><li class="comment-options-list-option">Historia edycji</li></a>
                                                </div>
                                            <?php
                                                } else {
                                            ?>
                                                <div class=comment-options-box>
                                                    <a href="product.php?product=<?php echo $product;?>&history=<?php echo $row['id_comments'];?>#comment"><li class="comment-options-list-option">Historia edycji</li></a>
                                                </div>
                                            <?php
                                                }
                                            }
                                        ?>

                                    </ul>

                                    <form action="db_conn/add_comment.php?comment=<?php echo $row['id_comments']?>&destination=<?php echo $product?>&mode=a" class="answer-form" method="post" id="input<?php echo $row['id_comments']?>">
                                        <textarea name="comment" class="answer-input" placeholder="Dodaj odpowiedź"></textarea>
                                        <input type="hidden" name="path" value='/<?php echo $row['id_comments']?>'>
                                        <div class="answer-form-options">
                                            <a class='pointer answer-form-cancel-input' onclick="hide(<?php echo 'input'.$row['id_comments']?>)">Anuluj</a>
                                            <input type="submit" class='answer-submit pointer' value="Odpowiedz">
                                        </div>
                                    </form>
                                </div>
                                <?php
                                    //wyswietla odpowiedzi do danego komentarza
                                    
                                    $conn_a = OpenConn();

                                    $sql_a = "SELECT * FROM comments INNER JOIN users ON users.id_user = comments.comments_author WHERE id_product_comments = '".$product."' AND path LIKE '/".$row['id_comments']."%' ORDER BY date ASC";
                                    $result_a = mysqli_query($conn_a, $sql_a);
                                    
                                    if(mysqli_num_rows($result_a) > 0) {
                                        while($row_a = mysqli_fetch_array($result_a)) {

                                            $conn = OpenConn();

                                            $sql = "SELECT id_comments_likes FROM comments_likes WHERE id_comment = ".$row_a['id_comments']."";
                                            $num = mysqli_query($conn, $sql);

                                            if(is_logged()) {
                                                $sql = "SELECT id_comments_likes FROM comments_likes WHERE id_user_likes = ".$_SESSION['id']." AND id_comment = ".$row_a['id_comments']."";
                                                $reaction_check = mysqli_query($conn, $sql);

                                                if(mysqli_num_rows($reaction_check) == 0) {
                                                    $check = "";
                                                } else {
                                                    $check = "bold";
                                                }
                                            } else {
                                                $check = "";
                                            }

                                            if(mysqli_num_rows($num) == 0) {
                                                $num_of_reactions = "";
                                            } else {
                                                $num_of_reactions = "(".mysqli_num_rows($num).")";
                                            }
                                                        
                                            $date_info = dateInfo($row_a['date']);

                                            $answer_to = explode("/", $row_a['path']);
                                            $answer_to = end($answer_to);

                                            $sql = "SELECT firstname FROM comments INNER JOIN users ON users.id_user = comments.comments_author WHERE id_comments = ".$answer_to."";
                                            $author_check = mysqli_query($conn, $sql);

                                            if(mysqli_num_rows($author_check) > 0) {
                                                $row_author_check = mysqli_fetch_array($author_check);
                                                $thread_autor = $row_author_check['firstname'];
                                            } else {
                                                $thread_autor = "anonim";
                                            }

                                            close($conn);
                                        ?>
                                            <!-- wyswietla odpowiedz -->
                                            <div class='answer-box alerts-border' id='comment<?php echo $row_a['id_comments']?>'>
                                                <div class='comment-info'>
                                                    <a class='comment-author bold'><?php echo $row_a['firstname']?></a>
                                                    <a class='comment-date'><?php echo $date_info ?></a>
                                                </div>
                                                <div class='comment-content' id="content<?php echo $row_a['id_comments']?>">
                                                    <?php echo "<a class=answer-to href='product.php?product=".$product."#comment".$answer_to."' onclick='highlight(".$answer_to.")'>@".$thread_autor."</a> ".$row_a['comments_content']?>
                                                </div> 

                                                <!-- Edycja komentarza -->

                                                <form class="comment-edit-box" id=<?php echo "edit".$row_a['id_comments']?> action="db_conn/edit_comment.php?destination=<?php echo $product?>&comment=<?php echo $row_a['id_comments']?>" method='post'>
                                                    <textarea name="edit" class="edit-comment-input" placeholder="Edytuj komentarz"><?php echo $row_a['comments_content']?></textarea>
                                                    <div class='edit-comment-options-box'>
                                                        <a class='pointer' onclick="showHide(<?php echo 'content'.$row_a['id_comments']?>, <?php echo 'edit'.$row_a['id_comments']?>)">Anuluj</a>
                                                        <input type="submit" class='edit-submit pointer' value="Zapisz">
                                                    </div>
                                                </form>

                                                <div class='comment-interactions'>
                                                    <a class='comment-interaction pointer <?php echo $check ?>' href="db_conn/comment_reaction.php?comment=<?php echo $row_a['id_comments']?>&destination=<?php echo $product?>">Przydatne <?php echo $num_of_reactions?></a>
                                                    <a class='comment-interaction pointer' onclick="show(<?php echo 'input'.$row_a['id_comments']?>)">Odpowiedz</a>
                                                    <a class='comment-interaction pointer' id='interactions-options' onclick="show(<?php echo 'options'.$row_a['id_comments']?>)">Opcje</a>
                                                </div>

                                                <ul class="comment-options-list" id="options<?php echo $row_a['id_comments']?>">
                                                    <a class='pointer answer-form-cancel-input' onclick="hide(<?php echo 'options'.$row_a['id_comments']?>)">Anuluj</a>
                                                    
                                                    <?php
                                                        //jeśli user to wlasciciel komentarza wyswietla dodatkowe opcje
                                                        if(is_logged()) {
                                                            if($_SESSION['id'] == $row_a['comments_author']) {
                                                        ?>
                                                            <div class=comment-options-box>
                                                                <li class="comment-options-list-option" onclick="showHide(<?php echo 'edit'.$row_a['id_comments']?>, <?php echo 'content'.$row_a['id_comments']?>)">Edytuj</li>
                                                                <a href='db_conn/delete_comment.php?destination=<?php echo $product?>&comment=<?php echo $row_a['id_comments']?>&thread=<?php echo $row['id_comments']?>'><li class="comment-options-list-option">Usuń</li></a>
                                                                <a href="product.php?product=<?php echo $product;?>&history=<?php echo $row_a['id_comments'];?>#comment<?php echo $row_a['id_comments'];?>"><li class="comment-options-list-option">Historia edycji</li></a>
                                                            </div>
                                                       <?php
                                                            } else {
                                                        ?>
                                                            <div class=comment-options-box>
                                                                <a href="product.php?product=<?php echo $product;?>&history=<?php echo $row_a['id_comments'];?>#comment<?php echo $row_a['id_comments'];?>"><li class="comment-options-list-option">Historia edycji</li></a>
                                                            </div>
                                                        <?php
                                                            }
                                                        }
                                                    ?>

                                                </ul>
                                                <form action="db_conn/add_comment.php?comment=<?php echo $row_a['id_comments']?>&destination=<?php echo $product?>&mode=a" class="answer-form" method="post" id="input<?php echo $row_a['id_comments']?>">
                                                    <textarea name="comment" class="answer-input" placeholder="Dodaj odpowiedź"></textarea>
                                                    <input type="hidden" name="path" value='<?php echo $row_a['path']."/".$row_a['id_comments'] ?>'>
                                                    <div class="answer-form-options">
                                                        <a class='pointer answer-form-cancel-input' onclick="hide(<?php echo 'input'.$row_a['id_comments']?>)">Anuluj</a>
                                                        <input type="submit" class='answer-submit pointer' value="Odpowiedz">
                                                    </div>
                                                </form>
                                            </div>
                                        <?php
                                        }
                                    }

                                    close($conn_a);
                                }
                            }
                        }
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
    const opened_sh = [];

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
        element = element['id']

        opened_inputs.push(element)

        if(opened_inputs.length <= 1){
            console.log(opened_inputs[0])
            document.getElementById(element).style.display = "flex";

        } else {
            console.log("2")
            document.getElementById(opened_inputs[0]).style.display = "none";
            opened_inputs.splice(0,1)
            document.getElementById(element).style.display = "flex";
        }
    }

    function hide(element) {
        element = element['id']

        document.getElementById(element).style.display = "none";
    }

    function highlight(element) {
        console.log(element)
        document.getElementById('comment'+element).style.animationPlayState = "running";

        setTimeout(() => { document.getElementById('comment'+element).style.animationPlayState = "paused"; }, 2000);
    }

    function showHide(to_show, to_hide) {
        to_show = to_show['id']

        opened_sh.push(to_show)

        if(opened_sh.length <= 1){
            console.log(opened_sh[0])
            document.getElementById(to_show).style.display = "flex";

        } else {
            console.log("2")
            document.getElementById(opened_sh[0]).style.display = "none";
            opened_inputs.splice(0,1)
            document.getElementById(to_show).style.display = "flex";
        }

        to_hide = to_hide['id']

        console.log(to_hide)

        document.getElementById(to_hide).style.display = "none";

    }

    </script>

    <?php 
            }
        } else {
            echo "<p id=error-response-for-client>WYBRANY PRODUKT NIE ISTNIEJE :( </p><p id=error-back-link><a href='shop.php'>Wyszukaj inny produkt</a></p>";
        }
    ?>
</body>
</html>