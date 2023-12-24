<?php 
    session_start();

    if(isset($_SESSION['id']) && isset($_SESSION['password'])){
        if($_SESSION["admin"] == 1) {
            include "components/admin-components.php";
            include "db_conn/connect.php";
?>

<!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>
        <link rel="stylesheet" href="style/admin-style.css">
    </head>
        
    <body>
        <?php adminSiteHeader($_SESSION['firstname'])?>

        <main>
            <?php 
                if(isset($_GET['tool'])) {
                    $tool = $_GET['tool'];

                    adminSiteNav($tool);

                    include "admin_tools/".$tool.".php";
            ?>

            <?php
                } else {
            ?>

            <div class="areas-container">
                <a href="admin.php?tool=orders">
                    <div class="area-box">
                        <h2 class="admin-title">Zamówienia</h2>
                        <div class="area-main" id="orders">
                            
                        </div>
                    </div>
                </a>

                <a href="admin.php?tool=new_product">
                    <div class="area-box">
                        <h2 class="admin-title">Nowy produkt</h2>
                        <div class="area-main" id="new-product-summary">

                        </div>
                    </div>
                </a>

                <a href="admin.php?tool=edit_product">
                    <div class="area-box">
                        <h2 class="admin-title">Edytuj produkt</h2>
                        <div class="area-main" id="edit-product-summary">

                        </div>
                    </div>
                </a>

                <a href="admin.php?tool=comments">
                    <div class="area-box">
                        <h2 class="admin-title">Komentarze</h2>
                        <div class="area-main" id="comments-summary">

                        </div>
                    </div>
                </a>

                <a href="admin.php?tool=opening_hours">
                    <div class="area-box">
                        <h2 class="admin-title">Godziny otwarcia</h2>
                        <div class="area-main" id="opening-ours-summary">

                        </div>
                    </div>
                </a>
            </div>

            <?php
                }
            ?>
        </main>
    </body>
</html>

<?php 
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }

?>