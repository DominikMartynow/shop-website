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

<header class=flex-box>
    <a href="index.php" id="logo">Sklep Wielobranżowy</a>

    <nav>
        <ul id=menu>
            <a class="menu-item" href="index.php"><li>Home</li></a>
            <a class="menu-item" href="shop.php"><li>Asortyment</li></a>
            <a class="menu-item" href="#contact"><li>Kontakt</li></a>
        </ul>
    </nav>
</header>


<body>
    <main id="login-site">
        <div id="login-box">
            <form id=login-form action="db_conn/login.php" method="POST">
                <input type="text" name="mail" id="mail" placeholder="Adres e-mail">
                <input type="password" name="password" id="password" placeholder="Hasło">
                <input id=submit-login type="submit" value="Zaloguj">
            </form>

            <?php 
                if(isset($_GET['error'])) {
                    echo "<a id=login-site-error>".$_GET['error']."</a>";
                }
            ?>

            <div id=login-options>
                <a href="forgot_password.php">Zapomniałem hasła</a>
                <a class=pointer onclick="display('register-box', 'login-box')">Zarejestruj się</a>
            </div>
        </div>

        <div id="register-box">
            <form action="db_conn/register.php" id="register-form" method="POST">
                <input type="text" name=rfirstname id=rfirstname placeholder=Imię>
                <input type="text" name=rsecname id=rsecname placeholder=Nazwisko>
                <input type="email" name=rmail id=rmail placeholder='Adres e-mail'>
                <input type="text" name="rphone" id="rphone" placeholder='Numer telefonu'>
                <input type="password" name="rpassword" id="rpassword" placeholder='Hasło'>
                <input type="password" name="rpassword_confirm" id="rpassword_confirm" placeholder='Powtórz hasło'>
                <input type="submit" id=submit-register value="Zarejestruj się">
            </form>

            <div id=register-options>
                <a href="forgot_password.php">Zapomniałem hasła</a>
                <a class=pointer onclick="display('login-box', 'register-box')">Zaloguj się</a>
            </div>
        </div>
    </main>

    <?php
        if(isset($_GET['mode'])) {
            echo '
            <script>
                document.getElementById("register-box").style.display = "block";
                document.getElementById("login-box").style.display = "none";
            </script>
            ';
        }
    ?>

    <script>
        function display(element_to_display, current_element) {
            element_to_display = document.getElementById(element_to_display).style.display = "block";
            current_element = document.getElementById(current_element).style.display = "none";
        }
    </script>
</body>
</html>