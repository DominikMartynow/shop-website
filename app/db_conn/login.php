<?php 
    // session_start();

    include 'connect.php';

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);    
        return $data;
    }

    $mail = validate($_POST['mail']);
    $password = validate($_POST['password']);

    if(empty($mail)) {
        header("Location: ../login-site.php?error_l=Adres email wymagany");
    } else if (empty($password)) {
        header("Location: ../login-site.php?error_l=Hasło wymagane");
    } else {
        $conn = OpenConn();

        $sql = "SELECT * FROM users WHERE mail LIKE '".$mail."'";
        $result = mysqli_query($conn, $sql);

        close($conn);

        if(mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);

            if($row['mail'] === $mail && $row['password'] === $password) {
                $_SESSION['id'] = $row['id_user'];
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['mail'] = $row['mail'];

                if($row["admin"] == 1) {
                    $_SESSION["admin"] = 1;

                    print_r($_SESSION);
                    header("Location: ../admin.php");
                } else if(isset($_GET["destination"])){
                    $_SESSION['admin'] = 0;
                    header("Location: ".$_GET["destination"]."");
                } else {
                    header("Location: ../shop.php");
                }
            } else {
                header("Location: ../login-site.php?error_l=Błędny login i/lub hasło");
            }
        } else {
            header("Location: ../login-site.php?error_l=Użytkownik nie istnieje");
        }
    }
?>
