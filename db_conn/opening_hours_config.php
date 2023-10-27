<?php 
    session_start();

    include 'connect.php';

    if(isset($_SESSION['login']) && isset($_SESSION['password'])){
        $conn = OpenConn();

        if(isset($_POST['config_date'])) {
            $cdate = $_POST['config_date'];

            $sql = "SELECT * FROM exceptions WHERE date LIKE '".$cdate."'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                header("Location: ../admin.php?error=juz istnieje zapisana zmiana dla tej daty");
            } else {
                if(isset($_POST['is_open'])) {
                    if(!empty($_POST['config_date']) && !empty($_POST['opening_hour']) && !empty($_POST['closing_hour'])) {
                        $ohour = $_POST['opening_hour'];
                        $chour = $_POST['closing_hour'];
                        $isopen = $_POST['is_open'];
            
                        $sql = "INSERT INTO `exceptions`(`id_exception`, `date`, `is_open`, `open`, `close`) VALUES ('', '".$cdate."', '1', '".$ohour."', '".$chour."')";
                    } else {
                        header("Location: ../admin.php?error=uzupelnij potrzebne dane");
                        exit();
                    }
    
                } else {
                    $sql = "INSERT INTO `exceptions`(`id_exception`, `date`, `is_open`) VALUES ('', '".$cdate."', '0')";
                }
        
                if (mysqli_query($conn, $sql)) {
                    header("Location: ../admin.php?success=zmiany zostaly zapisane - zmieniona data: ".$cdate);
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
        
                close($conn);
            }
        } else {
            header("Location: ../admin.php?error=uzupelnij potrzebne dane");
            exit();
        }

    } else {
        header("Location: index.php");
    }
?>