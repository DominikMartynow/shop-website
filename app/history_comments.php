<?php 
    function commentsHistory($comment, $product) {
        $conn = OpenConn();

        $sql = "SELECT * FROM comments_history WHERE edited_comment = ".$comment." ORDER BY date_edit desc";
        $result = mysqli_query($conn, $sql);

        close($conn);

        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "
                <div class='comments-history-comment'>
                    <a class=edit-date>".$row['date_edit']."</a>
                    <a class=previous-content>".$row['previous_comment']."</a>
                </div>
                ";
            }
        } else {
            echo "<a class=previous-content>Komentarz nie był edytowany</a>";

        }
    }
?>