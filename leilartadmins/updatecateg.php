<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');
include("../dbconfig.php");
?>

<!-- modifier le profil -->
<?php
$allowedext = array('jpg', 'jpeg', 'png', 'tiff', 'bmp', 'gif');
if (isset($_POST['update_categ_btn'])) {
  
    $nom = $_POST['username'];
    $iid = $_POST['idcategorie'];
    if (
        !empty($_FILES['file']['tmp_name'])
        && file_exists($_FILES['file']['tmp_name'])
    ) {
        $imgerror = $_FILES['file']['error'];
        $imgtype = $_FILES['file']['name'];
        $imgsize = $_FILES['file']['size'];
        $imgext = explode('.', $imgtype);
        $imgfinalext = strtolower(end($imgext));
        $allowedext = array('jpg', 'jpeg', 'png', 'tiff', 'bmp', 'gif');
        if (in_array($imgfinalext, $allowedext)) {
            if ($imgerror === 0) {
                if ($imgsize < 500000000) {

                    $image = time() . '_' . $_FILES['file']['name'];
                    $target = '../thumb/' . $image;
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                        $req = $bdd->prepare('UPDATE categories set nom_categorie = :nom,
                        image_categorie = :imag
                         where id_categorie = :idcat');
                        $req->execute(array(
                            'imag' => $image,
                            'nom' => $nom,
                            'idcat' => $iid
                        ));
                        $req->closeCursor();
                        unlink("../thumb/" . $_POST['imgname']);
                        header('Location:categmanag.php');
                        exit;
                    }
                } else {
                    $_SESSION['updatecateg'] = "l'image est tres volumineuse";
                }
            } else {
                $_SESSION['updatecateg'] = "une erreur est survenue";
            }
        } else {
            $_SESSION['updatecateg'] = "le type de l'image est incorrect";
        }
    } else {
        $req = $bdd->prepare('UPDATE categories set nom_categorie = :nom
        
         where id_categorie = :idcat');
        $req->execute(array(
            'nom' => $nom,
            'idcat' => $iid
        ));
        $req->closeCursor();
        header('Location:categmanag.php');
        exit;
    }
}
?>
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Modifier la categorie</h6>
        </div>

        <div class="card-body">
            <!-- charger le profil -->
            <?php

            if (isset($_SESSION['updatecateg']) && !empty($_SESSION['updatecateg'])) {
                echo '<h2 class="bg-danger"> ' . $_SESSION['updatecateg'] . '</h2> <br> <a href="categmanag.php" class="btn btn-danger">Retour</a>';
                unset($_SESSION['updatecateg']);
            }

            if (isset($_POST['edit_categ_btn'])) {
                $id = $_POST['edit_categ_id'];
                $req = $bdd->prepare('SELECT * from categories where id_categorie = :idcategorie');
                $req->execute(array('idcategorie' => $id));
                while ($row = $req->fetch()) {
            ?>

                    <form method="POST" action="updatecateg.php" enctype="multipart/form-data">
                        <input hidden type="text" name="idcategorie" value="<?php echo $row['id_categorie']; ?>">
                        <div class="form-group">
                            <label> nom de categorie </label>
                            <input type="text" name="username" class="form-control" placeholder="Nom de categorie" value="<?php echo $row['nom_categorie']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>image de categorie</label>
                            <input type="text" name="imgname" value="<?php echo $row['image_categorie']; ?>" hidden>
                            <img src="../thumb/<?php echo $row['image_categorie']; ?>" width="150px" height="150px" style="border-radius: 50%; margin-left:10px;" id="fdp_update" onclick="passclick('fdp_update_file')">
                            <input type="file" name="file" id="fdp_update_file" value="<?php echo $row['image_categorie']; ?>" onchange="loadimage(this,'fdp_update')" class="form-control" style="display: none;">
                        </div>
                       

                        <div class="modal-footer">
                            <button type="submit" name="update_categ_btn" class="btn btn-primary">Modifier</button>
                            <a href="categmanag.php" class="btn btn-danger">Retour</a>
                        </div>
                    </form>
            <?php
                }
                $req->closeCursor();
                $bdd = null;
            }
            ?>

        </div>
    </div>
</div>
</div>
<script src="includes/imagepreviewscript.js"></script>
<?php
include("includes/scripts.php");
include("includes/footer.php");
?>