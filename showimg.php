<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="css/showimg.css">
    <link type="text/css" rel="stylesheet" href="css/lightbox.min.css">
    <script src="js/lightbox-plus-jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/js/script.js" type="text/javascript"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <title>Leil art</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.6/jquery.zoom.min.js"></script>
    <script>
        $(document).ready(function () {
            if ($(window).width() > 700) {
                $('.image')
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
include("header.html");
include("dbconfig.php");
?>
<div class="showimgBody">

    <h3 class="categorie"></h3>

    <div class="imgcontain">
    </div>
</div>
<?php include("footer.html"); ?>
<script src="./js/category.js"></script>
</body>

</html>