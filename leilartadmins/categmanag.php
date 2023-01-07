<?php
@ob_start();
@session_start();
?>
<?php
include('security.php');
include('includes/header.php');
include('includes/navbar.php');

?>
<!-- Delete data -->
<?php
include("../dbconfig.php");  
if(isset($_POST['delete_categ_btn']))
{
    $delid = $_POST['delete_categ_id'] ;
    $req1 = $bdd->prepare('SELECT img from image where categorie = :cattid');
    $req1->execute(array('cattid'=>$delid));
    while($row1 = $req1->fetch())
    {
        unlink("../uploads/" . $row1['img'] );
    }
    $req = $bdd->prepare('DELETE FROM categories where id_categorie = :idd') ;
    if($req->execute(array('idd'=>$delid)))
    {
        $_SESSION['statuscateg'] = "Categorie supprimé";
        unlink("../thumb/" . $_POST['imgfolder']);
        header('Location:categmanag.php');
        exit;
    }
    
}
?>
<?php
// Inserting new categories to the database 

include("../dbconfig.php");
$allowedext = array('jpg', 'jpeg', 'png', 'tiff', 'bmp', 'gif');

if (isset($_POST['categmanag_btn'])) {
        $nom = $_POST['username'];
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
                            $req = $bdd->prepare('INSERT INTO categories (nom_categorie,image_categorie)
                             VALUES(:nom,:imag)');
                            $req->execute(array(
                                'imag' => $image,
                                'nom' => $nom
                                    ));
                            header('Location:categmanag.php');
                            exit;
                        }
                    } else {
                        $_SESSION['statuscateg'] = "l'image est tres volumineuse";
                    }
                } else {
                    $_SESSION['statuscateg'] = "une erreur est survenue";
                }
            } else {
                $_SESSION['statuscateg'] = "le type de l'image est incorrect";
            }
        }else{
            $_SESSION['statuscateg'] = "veuillez inserer votre image";
        }
   
}



?>


<div class="modal fade" id="addcateg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter une categorie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="categmanag.php" method="POST" enctype="multipart/form-data">

                <div class="modal-body">

                    <div class="form-group">
                        <label> Nom de categorie </label>
                        <input type="text" name="username" class="form-control" placeholder="Nom de categorie" required>
                    </div>
                   
                    <div class="form-group">
                        <label>image de categorie</label>
                        <img src="placeholder/addphoto.jpg" width="150px" height="150px"
                         style="margin-left:10px;" id="fdp_categmanag" onclick="passclick('fdp_categmanag_file')">
                        <input type="file" name="file" id="fdp_categmanag_file" onchange="loadimage(this,'fdp_categmanag')" class="form-control" style="display: none;">
                    </div>
                  
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" name="categmanag_btn" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Categories
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addcateg">
                    Ajouter une categorie 
                </button>
            </h6>
        </div>

        <div class="card-body">

            <?php
            if (isset($_SESSION['statuscateg']) && !empty($_SESSION['statuscateg'])) {
                echo '<p class="bg-warning"> ' . $_SESSION['statuscateg'] . '</p>';
                unset($_SESSION['statuscateg']);
            }
            ?>
            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                         
                            <th>Nom de categorie </th>
                            <th>Image de categorie</th>
                            <th>Modifier </th>
                            <th>Supprimer </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $req = $bdd->query("SELECT * from categories");
                        while ($row = $req->fetch()) {

                        ?>
                            <tr>
                                <td> <?php echo $row['nom_categorie']; ?></td>
                                <td><img src="../thumb/<?php echo $row['image_categorie']; ?>" width="100px" height="100px"></td>
                                <td>
                                    <form action="updatecateg.php" method="post">
                                        <input type="hidden" name="edit_categ_id" value="<?php echo $row['id_categorie']; ?>">
                                        <button type="submit" name="edit_categ_btn" class="btn btn-success"> Modifier</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="categmanag.php" method="post">
                                        <input type="hidden" name="imgfolder" value="<?php echo $row['image_categorie']; ?>">
                                        <input type="hidden" name="delete_categ_id" value="<?php echo $row['id_categorie']; ?>">
                                        <button type="submit" name="delete_categ_btn" class="btn btn-danger"> Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        $req->closeCursor();
                        $bdd = null ;
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script src="includes/imagepreviewscript.js"></script>
    <?php
    include('includes/scripts.php');
    include('includes/footer.php');
    ?>
    <!-- /.container-fluid -->