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
    <script src="../js/script.js"></script>
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
                <form method="POST" action="#">
                    <input type="text" name="rechbar" placeholder="reference..." id="rechbar"  class="searchInput" >
                    <input type="button" name="rechbar_btn" value="Search" id="rechbarbtn" class="searchBtn" onclick='findArticle()'>
                </form>
            </div>
        </div>
        <div id="rechImageContainer" style="display:none;">
        <a id="rechImageLink"   data-lightbox="gallery"><div class="rechImgDiv"><img id="rechImage" class="rechImage"></div></a>
        </div>
        <div id="notFoundContainer" style="display:none;">
        </div>
    </div>
    <?php

include('footer.html');
?>
</body>

</html>