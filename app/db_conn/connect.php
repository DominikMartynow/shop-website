<?php 
function OpenConn() {
    $servername = "handelwielobranzowy.cbjh5ckoevmm.eu-central-1.rds.amazonaws.com";
    $username = "admin";
    $password = "*FspP8trV";
    $db = "handel_wielobranzowy";
    
    $conn = mysqli_connect($servername, $username, $password, $db);

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    return $conn;
}

function close($conn) {
    mysqli_close($conn);
}

?>
