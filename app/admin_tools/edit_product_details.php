<nav class=sub-nav>
    <a href='admin.php?tool=edit_product&category=<?php echo $_GET['category']?>' class='menu-option sub-menu-option' id='home'>Lista produktów</a>
</nav>

<div class="tool-body" id="edit_product-details-body">
    <?php 
        $conn = OpenConn();

        $sql = "SELECT * FROM products WHERE id_products = ".$product;
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_array($result);

            echo "<form action='db_conn/product_update.php' method='post'>";
            echo "<ul class='admin-details-list'>";
            echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Nazwa: </a><a class=-details-list-value><input type=text name='product_name' value='".$row['product_name']."'></a></li>";
            echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Producent: </a><a class=-details-list-value><input type=text name='producer' value='".$row['producer']."'></a></li>";
            echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Kategoria: </a><a class=-details-list-value>";
            echo "<select name='product_category' id='admin-tools-input'>";
                foreach($product_categories as $key => $value) {
                    if($key == $row['id_product_category']) {
                        echo "<option value=".$key." selected>".$value."</option>";
                    } else {
                        echo "<option value=".$key.">".$value."</option>";
                    }
                }
            echo "</select>";
            echo "</a></li>";
            echo "<li class='admin-details-list-option'><a class=admin-details-list-caption>Opis: </a><a class=-details-list-value><textarea type=text name='product_description'>".$row['product_description']."</textarea></a></li>";
            echo "<li class='admin-details-list-option'><a class=admin-details-list-caption><input type=submit value='Zapisz zmiany'></a><a class=-details-list-value href='db_conn/delete_product.php?product=".$row['id_products']."&category=".$row['id_product_category']."'>Usuń produkt</a>";
            echo "</ul>";
            echo "<input type=hidden name=product_id value='".$product."'>";
            echo "</form>";
        } else {
            echo "Nie ma takiego produktu";
        }

        close($conn);
    ?>
</div>