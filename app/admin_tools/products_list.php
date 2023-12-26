<div id="admin-list">
    <table id="list-table" border>
        <thead>
            <tr><td>Zdjęcie</td><td>Nazwa</td><td>Producent</td><td>Kategoria</td><td>Opis</td></tr>
        </thead>
    
        <?php 
        
        $conn = OpenConn();

        $sql = "SELECT * FROM products p INNER JOIN product_category pc ON p.id_product_category = pc.id_product_category WHERE p.id_product_category LIKE '".$category."'";
        $result = mysqli_query($conn, $sql);

        close($conn);

        if(mysqli_num_rows($result)) {
            while($row = mysqli_fetch_assoc($result)){

                //pobiera glowne zdjecie produktu
                $product_photos = $row['product_photos'];
    
                $product_photos = explode(", ", $product_photos);
    
                foreach($product_photos as $key => $fullname) {
                    $photo_name = explode("/", $fullname);
                    $photo_name = end($photo_name);
                    
                    if(mb_substr($photo_name, 0, 1) == "m") {
                        $main_photo = $photo_name;
                    } 
                }

                $short_description = substr($row['product_description'], 0, 50)."...";

                echo "<tr><td><div class='product-mini-photo' style='background-image: url(uploaded_photos/".$main_photo.")'></div></td><td>".$row['product_name']."</td><td>".$row['producer']."</td><td>".$row['product_category_name']."</td><td>".$short_description."</td></tr>";

            }
        }

        ?>
    </table>
</div>