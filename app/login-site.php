<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login: Sklep Wielobranżowy</title>
</head>
<body>
    <form action="db_conn/login.php" method="POST">
        <input type="text" name="login" id="login" placeholder="login">
        <input type="password" name="password" id="password" placeholder="hasło">
        <input type="submit" value="zaloguj">
    </form>

    <?php 
        if(isset($_GET['error'])) {
            echo $_GET['error'];
        }
    ?>
</body>
</html>