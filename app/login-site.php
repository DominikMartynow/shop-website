<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login: Sklep Wielobranżowy</title>
    <link rel="stylesheet" href="style/style.css">
</head>
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
        </div>
    </main>
</body>
</html>