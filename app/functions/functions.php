<?php 
    function is_logged() {
        if(isset($_SESSION['id']) && isset($_SESSION['firstname']) && isset($_SESSION['password']) && isset($_SESSION['mail']) && isset($_SESSION['admin'])) {
            $session = array(
                "id" => $_SESSION['id'],
                "firstname" => $_SESSION['firstname'],
                "password" => $_SESSION['password'],
                "mail" => $_SESSION['password'],
                "admin" => $_SESSION['admin'],
                );
            return $session;
        }
    }

    function validateData($source_page, array $inputs) {
        foreach($inputs as $key => $value) {
            if(isset($key)) {
                
            } else {
                Header("Location: ".$source_page);
            }
        }

    }

    function countBasket($user) {
        $conn = OpenConn();

        $sql = "SELECT * FROM `basket` WHERE id_user = '".$user."'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0) {
        } else {
            echo mysqli_error($conn);
        }

        close($conn);

        return mysqli_num_rows($result);
    }

    function dateInfo($date_input) {
        $date = time();

        $add_date = $date_input;
        $add_date = strtotime($add_date);

        $diff = abs($date - $add_date);
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        if($years > 0) {
            $date_info = $date_input;
        } else if($months > 9 && $months <= 12) {
            $date_info = "kilkanaście miesięcy temu";
        } else if ($months > 1 && $months <= 9) {
            $date_info = "kilka miesięcy temu";
        } else if($days > 1 && $days <= 30) {
            $date_info = "kilka dni temu";
        } else if($days <= 1) {
            $date_info = "kilka godzin temu";
        } else {
            $date_info = $date_input;
        }

        return $date_info;
    }
    
?>
