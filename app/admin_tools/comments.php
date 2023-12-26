<div class='tool-body' id='comments-body'>
    <h1 class=admin-title>Komentarze</h1>

    <nav class=sub-nav>
        <?php
            //widok listy
            if(isset($_GET['verified'])) {
                $status = $_GET['verified'];
            } else {
                $status = "%";
            }

            if($status == 1) {
                    echo "<a href='admin.php?tool=comments' class='menu-option sub-menu-option' id='home'>Wszystkie</a>";
                    echo "<a href='admin.php?tool=comments&verified=1' class='menu-option sub-menu-option sub-menu-option-active'>Zatwierdzone</a>";
                    echo "<a href='admin.php?tool=comments&verified=0' class='menu-option sub-menu-option'>Do zatwierdzenia</a>";
            } else if($status == 0) {
                    echo "<a href='admin.php?tool=comments' class='menu-option sub-menu-option' id='home'>Wszystkie</a>";
                    echo "<a href='admin.php?tool=comments&verified=1' class='menu-option sub-menu-option'>Zatwierdzone</a>";
                    echo "<a href='admin.php?tool=comments&verified=0' class='menu-option sub-menu-option sub-menu-option-active'>Do zatwierdzenia</a>"; 
            } else {
                echo "<a href='admin.php?tool=comments' class='menu-option sub-menu-option' id='home'>Wszystkie</a>";
                echo "<a href='admin.php?tool=comments&verified=1' class='menu-option sub-menu-option'>Zatwierdzone</a>";
                echo "<a href='admin.php?tool=comments&verified=0' class='menu-option sub-menu-option'>Do zatwierdzenia</a>";
            }
        ?>
    </nav>

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