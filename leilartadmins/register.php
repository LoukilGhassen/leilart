<?php
@ob_start();
@session_start();
?>
<?php
include('includes/header.php');

include('security.php');
if(isset($_SESSION['role']) && $_SESSION['role'] !== "admin")
{
    header('Location:index.php');
}
include('includes/navbar.php');

?>
<!-- Delete data -->
<?php
include("../dbconfig.php");
if(isset($_POST['delete_btn']))
{
    $delid = $_POST['delete_id'] ;
    $req = $bdd->prepare('DELETE FROM admins where id_admin = :idd') ;
    if($req->execute(array('idd'=>$delid)))
    {
        $_SESSION['status'] = "Profile supprimer";
        unlink("img/" . $_POST['imgfolder']);
        $req->closeCursor();
        $bdd =null ;
        header('Location:register.php');
        exit;
    }
    
}
?>
<?php
// Inserting new admins to the database 

include("../dbconfig.php");
$allowedext = array('jpg', 'jpeg', 'png', 'tiff', 'bmp', 'gif');

if (isset($_POST['registerbtn'])) {
    $mdp = $_POST['password'];
    $mdpconf = $_POST['confirmpassword'];
    if ($mdp === $mdpconf) {
        $mdp = password_hash($mdp ,PASSWORD_BCRYPT);
        $nom = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
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
                        $target = 'img/' . $image;
                        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                            $req = $bdd->prepare('INSERT INTO admins (username_admin,email_admin,password_admin,img_admin,role_admin)
                             VALUES(:username,:email,:mdp,:imag,:rol)');
                            $req->execute(array(
                                'imag' => $image,
                                'username' => $nom,
                                'email' => $email,
                                'mdp' => $mdp,
                                'rol' => $role
                            ));
                            $req->closeCursor();
                            $bdd = null ;
                            header('Location:register.php');
                            exit;
                        }
                    } else {
                        $_SESSION['status'] = "l'image est tres volumineuse";
                    }
                } else {
                    $_SESSION['status'] = "une erreur est survenue";
                }
            } else {
                $_SESSION['status'] = "le type de l'image est incorrect";
            }
        }else{
            $_SESSION['status'] = "veuiller inserer votre image";
        }
    } else {
        $_SESSION['status'] = "mot de passe non confirmé";
    }
}



?>


<div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="register.php" method="POST" enctype="multipart/form-data">

                <div class="modal-body">

                    <div class="form-group">
                        <label> Nom </label>
                        <input type="text" name="username" class="form-control" placeholder="Nom" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                    </div>
                    <div class="form-group">
                        <label>Confirmer Mot de passe</label>
                        <input type="password" name="confirmpassword" class="form-control" placeholder="Confirmer votre mot de passe" required>
                    </div>
                    <div class="form-group">
                        <label>photo de profil</label>
                        <img src="placeholder/addphoto.jpg" width="150px" height="150px"
                         style="border-radius: 50%;margin-left:10px;" id="fdp_register" onclick="passclick('fdp_register_file')">
                        <input type="file" name="file" id="fdp_register_file" onchange="loadimage(this,'fdp_register')" class="form-control" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="editeur">Editeur</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" name="registerbtn" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Admin Profile
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addadminprofile">
                    Ajouter un admin 
                </button>
            </h6>
        </div>

        <div class="card-body">

            <?php
            if (isset($_SESSION['status']) && !empty($_SESSION['status'])) {
                echo '<p class="bg-warning"> ' . $_SESSION['status'] . '</p>';
                unset($_SESSION['status']);
            }
            ?>
            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th> ID </th>
                            <th> nom </th>
                            <th>Email </th>
                            <th>Mot de passe</th>
                            <th>Photo</th>
                            <th>Role</th>
                            <th>modifier </th>
                            <th>supprimer </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $req = $bdd->query("SELECT * from admins");
                        $i = 0;
                        while ($row = $req->fetch()) {

                            $i++;
                        ?>
                            <tr>
                                <td> <?php echo $i ?> </td>
                                <td> <?php echo $row['username_admin']; ?></td>
                                <td> <?php echo $row['email_admin']; ?></td>
                                <td> ****** </td>
                                <td><img class="img-profile rounded-circle" src="img/<?php echo $row['img_admin']; ?>" width="50px" height="50px"></td>
                                <td> <?php echo $row['role_admin']; ?></td>
                                <td>
                                    <form action="update.php" method="post">
                                        <input type="hidden" name="edit_id" value="<?php echo $row['id_admin']; ?>">
                                        <button type="submit" name="edit_btn" class="btn btn-success"> Modifier</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="register.php" method="post">
                                        <input type="hidden" name="imgfolder" value="<?php echo $row['img_admin']; ?>">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id_admin']; ?>">
                                        <button type="submit" name="delete_btn" class="btn btn-danger"> Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        $req->closeCursor();
                        $bdd=null;
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