<?php 
    if(!empty($_GET['destination'])) {
        $product = $_GET['destination'];
        if(!empty($_POST['answer']) && !empty($_POST['answers_to'])) {
            session_start();
            include("connect.php");
            function validate($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);    
                return $data;
            }

            $answer = $_POST['answer'];
            $answer = validate($answer);
            $author = $_SESSION['id'];
            $answers_to = $_POST['answers_to'];
            $conn = OpenConn();

            $date = date('Y-m-d H:i:s');

            if($_SESSION['admin'] == 1) {
                $sql = "INSERT INTO `handel_wielobranzowy`.`comments_answers`(`id_answers`,`answers_to`,`answers_author`,`answers_content`,`verified`, `date`) VALUES ('', '".$answers_to."', '".$author."', '$answer', '1', '".$date."')";
            } else {
                $sql = "INSERT INTO `handel_wielobranzowy`.`comments_answers`(`id_answers`,`answers_to`,`answers_author`,`answers_content`,`verified`, `date`) VALUES ('', '".$answers_to."', '".$author."', '$answer', '', '".$date."')";
            }
            
            if(mysqli_query($conn, $sql)) {
                header("Location: ../product.php?product=".$product."&success-answer=1");
            } else {
                echo mysqli_error($conn);
            }
            close($conn);
        } else {
            header("Location: ../product.php?product=".$product."");
        }
    } else {
        header("Location: ../product.php");
    }
?>