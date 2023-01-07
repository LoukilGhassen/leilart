<?php
@ob_start();
@session_start();
?>
<?php
include("security.php");
include("includes/header.php");
include("includes/navbar.php");
include("../dbconfig.php");

$allowedext = array('jpg', 'jpeg', 'png', 'tiff', 'bmp', 'gif');

if (isset($_POST['subbtn']) && !empty($_FILES['file']['name'])) {
    $categ = $_POST['cat'] !== "null" ?  $_POST['cat'] : null ;

    foreach ($_FILES['file']['name'] as $key => $val) {

        if (
            !empty($_FILES['file']['tmp_name'][$key])
            && file_exists($_FILES['file']['tmp_name'][$key])
        ) {
            $imgerror = $_FILES['file']['error'][$key];
            $imgtype = $_FILES['file']['name'][$key];
            $imgsize = $_FILES['file']['size'][$key];
            $imgext = explode('.', $imgtype);
            $imgfinalext = strtolower(end($imgext));
            $allowedext = array('jpg', 'jpeg', 'png', 'tiff', 'bmp', 'gif');
            if (in_array($imgfinalext, $allowedext)) {
                if ($imgerror === 0) {
                    if ($imgsize < 500000000) {

                        $image = time() . '_' . $_FILES['file']['name'][$key];
                        $target = '../uploads/' . $image;
                        if (move_uploaded_file($_FILES['file']['tmp_name'][$key], $target)) {
                            $req = $bdd->prepare('INSERT INTO image (img,categorie) VALUES(:img,:categorie)');
                            $req->execute(array(
                                'img' => $image,
                                'categorie' => $categ
                            ));
                            $_SESSION['addimg'] = "Images ajoutés";
                        }
                    } else {
                        $_SESSION['addimg'] = "Certaines images ont la taille volimuneux !";
                    }
                } else {
                    $_SESSION['addimg'] = "Certaines images ont causeés une erreur";
                }
            } else {
                $_SESSION['addimg'] = "certaines images n'ont pas le type correctes !";
            }
        }else{
            $_SESSION['addimg'] = "Veuillez inserer au moins une image !";
        }
    }
    header('Location:addimg.php');
    exit;
}
?>


<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ajouter des photos

            </h6>
        </div>
        <?php

        if (isset($_SESSION['addimg']) && !empty($_SESSION['addimg'])) {
            echo '<h2 class="bg-success"> ' . $_SESSION['addimg'] . '</h2>';
            unset($_SESSION['addimg']);
        }

        ?>
        <div class="card-body">
            <form name="form" id="form" enctype="multipart/form-data" method="POST" action="addimg.php">
                <div class="form-group">
                    <label>Selectionner les photos a ajouter :</label>
                    <input type="file" name="file[]" multiple="" id="file" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>categories :</label>
                    <select name="cat" class="form-control" id="catimg">
                        <option value="null" selected>Selectionner une catégorie</option>
                        <?php
                        $req2 = $bdd->query('SELECT * from categories');
                        while ($row2 = $req2->fetch()) {
                        ?><option value="<?php echo $row2['id_categorie'] ?>"><?php echo $row2['nom_categorie'] ?></option>
                        <?php }
                        $req2->closeCursor();
                        $bdd = null;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" name="subbtn" id="subbtn" class="form-control form-control-user btn btn-primary">
                </div>
                <div class="form-group">
                    <a href="index.php" class="btn btn-danger form-control form-control-user">Retour</a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php
include("includes/scripts.php");

?>
</body>

</html>