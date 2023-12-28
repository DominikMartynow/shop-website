<div class='tool-body' id='opening_hours-body'>
    <h1 class=admin-title>Komentarze</h1>

    <div id="opening_hours-form-box">

    </div>

    <div id="opening-hours-list">
        <div id="admin-list">
            <table id="list-table" border>
                <thead>
                    <tr><td>Użytkownik</td><td>Komentarz</td><td>Usuń</td><td>Zaakceptuj</td></tr>
                </thead>
            
                <tbody>
                    <?php 
                    
                    $conn = OpenConn();

                    $sql = "SELECT * FROM comments c INNER JOIN users u ON c.comments_author = u.id_user WHERE c.verified LIKE '".$status."'";
                    $result = mysqli_query($conn, $sql);

                    close($conn);

                    if(mysqli_num_rows($result)) {
                        while($row = mysqli_fetch_assoc($result)){
                            if($row['verified'] == 1) {
                                echo "<tr><td>".$row['firstname']."</td><td>".$row['comments_content']."</td><td><a href='db_conn/reject_comment.php?comment=".$row['id_comments']."&status=".$status."'>usuń</a></td><td>-</td></tr>";
                            } else {
                                echo "<tr><td>".$row['firstname']."</td><td>".$row['comments_content']."</td><td><a href='db_conn/reject_comment.php?comment=".$row['id_comments']."&status=".$status."'>usuń</a></td><td><a href='db_conn/accept_comment.php?comment=".$row['id_comments']."&status=".$status."'>Zaakceptuj</a></td></tr>";
                            }
                        }
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>