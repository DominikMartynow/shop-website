<?php 
    if(!empty($_POST['rfirstname']) && !empty($_POST['rsecname']) && !empty($_POST['rmail']) && !empty($_POST['rphone']) && !empty($_POST['rpassword']) && !empty($_POST['rpassword_confirm'])) {
        function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);    
            return $data;
        }
        
        $firstname = validate($_POST['rfirstname']);
        $secondname = validate($_POST['rsecname']);
        $mail = validate($_POST['rmail']);
        $phone = validate($_POST['rphone']);
        $password = validate($_POST['rpassword']);
        $password_confirm = validate($_POST['rpassword_confirm']);

        include "connect.php";

        $conn = OpenConn();

        $sql = "SELECT * FROM `users` WHERE mail = '".$mail."'";
        $result = mysqli_query($conn, $sql);

        close($conn);

        if(mysqli_num_rows($result) > 0) {
            header("Location: ../login-site.php?mode=register&error_r=Konto z takim adresem e-mail już istnieje.");
        } else { 
            $conn = OpenConn();

            $sql = "SELECT * FROM `users` WHERE telnumber = '".$phone."'";
            $result = mysqli_query($conn, $sql);
    
            close($conn);

            if(mysqli_num_rows($result) > 0) {
                header("Location: ../login-site.php?mode=register&error_r=Konto z takim numerem telefonu już istnieje.");
            } else {
                if($password != $password_confirm) {
                    header("Location: ../login-site.php?mode=register&error_r=Hasła nie są takie same.");
                } else {
                    $conn = OpenConn();
                    $sql = "INSERT INTO `handel_wielobranzowy`.`users`(`id_user`,`password`,`admin`,`mail`,`firstname`,`secondname`,`confirmed`,`telnumber`) VALUES ('', '".$password."', '', '".$mail."', '".$firstname."', '".$secondname."', '', '".$phone."')";    
                    
                    if (mysqli_query($conn, $sql)) {
                        header("Location: ../login-site.php?mode=confirm_registration");
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }
                }
            }
        }
        

    } else {
        header("Location: ../login-site.php?mode=register&error_r=Uzupełnij wsyzstkie dane potrzebne do rejestracji.");
    }

?>