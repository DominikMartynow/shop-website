<?php 
    function is_logged() {
        session_start();

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
?>