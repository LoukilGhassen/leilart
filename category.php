<?php
include("dbconfig.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Leil Art</title>
    <link rel="stylesheet" type="text/css" href="css/category.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

</head>
<?php include("header.html"); ?>
<?php include ("carousel.html") ; ?>

<h3 class="categorie" id="categorieWord">Categories</h3>

<div class="categoriesContainer">

    <?php 
		$req = $bdd->query('SELECT * from categories') ;
		while($row = $req->fetch())
		{
	    ?>
    <div class="categoryContainer">
        <a href="showimg.php?categor=<?php echo $row['id_categorie'] ?>">
            <img src="thumb/<?php echo $row['image_categorie'] ?>" class="categoryImage" />
            <div class="categoryName">
                <?php echo $row['nom_categorie'] ?>
            </div>
        </a>
    </div>

    <?php
		 }
		 $req->closeCursor();
		 $bdd = null ;
		 ?>
</div>
<?php include("footer.html") ?>
</body>

</html>