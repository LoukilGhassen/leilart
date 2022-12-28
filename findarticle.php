<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Leil Art</title>
    <link href="css/findarticle.css" rel="stylesheet" type="text/css">
    <link type="text/css" rel="stylesheet" href="css/lightbox.min.css">
    <script src="js/lightbox-plus-jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.6/jquery.zoom.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <script>
    $(document).ready(function() {
        if ($(window).width() > 700) {
            $('.rechImage')
                .wrap('<span style="display:inline-flex;width: 100%;justify-content:center;"></span>')
                .css('display', 'block')
                .parent()
                .zoom();
        }
    });
    </script>

</head>

<body>
    <?php
include('header.html');
?>
    <div class="searchContainer">
        <div class="boxText">
            <h1 class="searchText">Find a product</h1>
            <div class="searchBox">
                <form method="POST" action="findarticle.php">
                    <input type="text" name="rechbar" placeholder="reference..." id="rechbar"
                        class="searchInput">
                    <input type="submit" name="rechbar_btn" value="Search" id="rechbarbtn" class="searchBtn">
                </form>
            </div>
        </div>
        <?php
        include('dbconfig.php');
        if (isset($_POST['rechbar']) && !empty($_POST['rechbar'])) {
            $idrech = htmlspecialchars($_POST['rechbar']);
            if ($idrech[0] == '#') {
                $idrech = ltrim($idrech, '#');
            }
            $req = $bdd->prepare('SELECT * FROM image where id_img = :idrech');
            $req->execute(array('idrech' => $idrech));
            if ($req->rowCount() > 0) {
                $row = $req->fetch();
                ?>
        <a href="uploads/<?php echo $row['img'] ?>" data-lightbox="gallery"><div class="rechImgDiv"><img src="uploads/<?php echo($row['img']); ?>" class="rechImage"></div></a>
        <?php } else {
                $_SESSION['rech'] = "Product not found";
            }
            $req->closeCursor();
            $bdd = null ;
        }
        ?>

        <?php
        if (isset($_SESSION['rech'])) {
            echo '<h2 class="sessionText">' . $_SESSION['rech'] . '</h2>';
            unset($_SESSION['rech']);
        }
        ?>
    </div>
    <?php

include('footer.html');
?>
</body>

</html>