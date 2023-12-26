<div class='tool-body' id='edit_product-body'>
    <h1 class=admin-title>Edytuj produkt</h1>
    
    <?php 
        $product_categories = adminSubMenu('product_category', 'id_product_category', 'product_category_name');


        if(isset($_GET['category'])) {
            $category = $_GET['category'];
        } else {
            $category = '%';
        }    

        //widok produktu
        if(isset($_GET['product'])) {
            $product = $_GET['product'];

            include "edit_product_details.php";
        } else {
    ?>

    <nav class=sub-nav>
        <a href='admin.php?tool=edit_product' class='menu-option sub-menu-option' id='home'>Wszystkie</a>
        <?php
            //widok listy
            foreach($product_categories as $product_category_id => $value) {
                if($product_category_id == $category) {
                    echo "<a href='admin.php?tool=edit_product&category=".$product_category_id."'class='menu-option sub-menu-option sub-menu-option-active'>".$value."</a>";
                } else {
                    echo "<a href='admin.php?tool=edit_product&category=".$product_category_id."'class='menu-option sub-menu-option'>".$value."</a>";
                }
            }
        ?>
    </nav>

    <?php 
        include "products_list.php";
        }
    ?>

</div>