<?php 
    session_start();

    include 'connect.php';

    if(isset($_POST['login']) && isset($_POST['password'])) {
        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);    
            return $data;
        }

        $login = validate($_POST['login']);
        $password = validate($_POST['password']);

        if(empty($login)) {
            header("Location: ../login-site.php?error=login wymagany");
        } else if (empty($password)) {
            header("Location: ../login-site.php?error=hasło wymagane");
        } else {
            $conn = OpenConn();

            $sql = "SELECT * FROM users WHERE login LIKE '".$login."'";
            $result = mysqli_query($conn, $sql);

            close($conn);

            if(mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                if($row['login'] === $login && $row['password'] === $password) {
                    $_SESSION['login'] = $row['login'];
                    $_SESSION['password'] = $row['password'];

                    header("Location: ../admin.php");
                } else {
                    header("Location: ../login-site.php?error=bledny login i/lub hasło");
                }
            } else {
                header("Location: ../login-site.php?error=użytkownik nie istnieje");
            }

        }


    } else {
        header("Location: ../login-site.php?error=podaj dane logowania");
    }
?>
