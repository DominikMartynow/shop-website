<?php 
    function is_logged() {
        session_start();

        if(isset($_SESSION['id']) && isset($_SESSION['login']) && isset($_SESSION['password']) && isset($_SESSION['mail']) && isset($_SESSION['admin'])) {
            $session = array(
                "id" => $_SESSION['id'],
                "login" => $_SESSION['login'],
                "password" => $_SESSION['password'],
                "mail" => $_SESSION['password'],
                "admin" => $_SESSION['admin'],
                );

            return $session;
        } 
    }
?>