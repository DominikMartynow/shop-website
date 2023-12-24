<div class='tool-body' id='new_product-body'>
    <h1 class=admin-title>Dodaj nowy produkt</h1>

    <?php 
        $conn = OpenConn();

        $sql = "SELECT * FROM product_category";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $product_categories = array();
            while($row = mysqli_fetch_assoc($result)) {
                $product_categories[$row['id_product_category']] = $row['product_category_name'];
            }
        }

        close($conn);
        ?>

        <form id=product-add-form action="db_conn/product_add.php" method="post" enctype="multipart/form-data">
            <ul class='admin-details-list'>
                <li class='admin-details-list-option'><a class=admin-details-list-caption>Nazwa produktu: </a><a class=admin-details-list-value><input type="text" name="product_name"></li>
                
                <li class='admin-details-list-option'><a class=admin-details-list-caption>Wybierz kategorię: </a>
                    <select name="product_category"><br>
                        <option value="" disabled selected></option>
                        <?php 
                            foreach($product_categories as $key => $value) {
                                echo "<option value=".$key.">".$value."</option>";
                            }    
                        ?>
                    </select>
                </li>

                <li class='admin-details-list-option'><a class=admin-details-list-caption>Zdjęcia produktu: </a>
                    <input type="file" id="product_photos" name="product_photos[]" accept="image/*" multiple onchange="displayPhotos()"><br>
                    
                    <div id="photos"></div>
                </li>

                <li class='admin-details-list-option'><a class=admin-details-list-caption>Producent: </a><a class=admin-details-list-value><input type="text" id="producer" name="producer"></li>
                <li class='admin-details-list-option'><a class=admin-details-list-caption>Opis produktu: </a><a class=admin-details-list-value>
                    <textarea id="product_description" name="product_description" cols="30" rows="10"></textarea>
                </li>
            </ul>
            <input type="submit" value="Dodaj produkt">
        </form>

        <script>
            function displayPhotos() {
                document.cookie = "main_photo = "

                document.getElementById('photos').innerHTML = '';

                let photos = document.getElementById("product_photos").files;

                let zdjecia = document.getElementById("photos")

                for(i = 0; i < photos.length; i++) {
                    photo = URL.createObjectURL(photos[i]);

                    document.getElementById("photos").innerHTML += "<img class=product_photo id='product_photo"+i+"' number="+i+" src="+photo+" onclick='handle_click(this)'>"

                }
            }

            function handle_click(photo) {
                console.log("kilkineto")

                photos = document.getElementsByClassName("product_photo")

                for(let i = 0; i < photos.length; i++){
                    try {
                        photos[i].removeAttribute("main")
                    } finally {
                        photos[i].removeAttribute("main")
                        photos[i].style.border = "0px";
                    }
                }

                let choosen_photo = document.getElementById(photo.id);

                choosen_photo.setAttribute("main", 1)
                choosen_photo.style.border = "1px solid blue";
                
                document.cookie = "main_photo ="+choosen_photo.getAttribute("number")
            }
        </script>

</div>