<?php
@ob_start();
@session_start();
?>
<?php
include('security.php');
if(isset($_SESSION['role']) && $_SESSION['role'] !== "admin")
{
    header('Location:index.php');
    exit;
}

include('includes/header.php');
include('includes/navbar.php');
include("../dbconfig.php");
?>

<!-- modifier le profil -->
<?php
$allowedext = array('jpg', 'jpeg', 'png', 'tiff', 'bmp', 'gif');
if (isset($_POST['updatebtn'])) {
    $mdp = $_POST['password'];
    $nom = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $iid = $_POST['idadmin'];

    $mdp = password_hash($mdp,PASSWORD_BCRYPT);

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
                        $req = $bdd->prepare('UPDATE admins set username_admin = :username,
                        email_admin = :email,password_admin = :mdp,img_admin = :imag,role_admin = :rol
                         where id_admin = :idadm');
                        $req->execute(array(
                            'imag' => $image,
                            'username' => $nom,
                            'email' => $email,
                            'mdp' => $mdp,
                            'rol' => $role,
                            'idadm' => $iid
                        ));
                        unlink("img/" . $_POST['imgname']);
                        header('Location:register.php');
                        exit;
                    }
                } else {
                    $_SESSION['update'] = "l'image est tres volumineuse";
                }
            } else {
                $_SESSION['update'] = "une erreur est survenue";
            }
        } else {
            $_SESSION['update'] = "le type de l'image est incorrect";
        }
    } else {
        $req = $bdd->prepare('UPDATE admins set username_admin = :username,
        email_admin = :email,password_admin = :mdp,role_admin = :rol
         where id_admin = :idadm');
        $req->execute(array(
            'username' => $nom,
            'email' => $email,
            'mdp' => $mdp,
            'rol' => $role,
            'idadm' => $iid
        ));
        $req->closeCursor();
        $bdd = null;
        header('Location:register.php');
        exit;
    }
}
?>
<div class="container-fluid">

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Modifier le profile</h6>
        </div>

        <div class="card-body">
            <!-- charger le profil -->
            <?php

            if (isset($_SESSION['update']) && !empty($_SESSION['update'])) {
                echo '<h2 class="bg-danger"> ' . $_SESSION['update'] . '</h2> <br> <a href="register.php" class="btn btn-warning">Retour</a>';
                unset($_SESSION['update']);
            }

            if (isset($_POST['edit_btn'])) {
                $id = $_POST['edit_id'];
                $req = $bdd->prepare('SELECT * from admins where id_admin = :idadmin');
                $req->execute(array('idadmin' => $id));
                while ($row = $req->fetch()) {
            ?>

                    <form method="POST" action="update.php" enctype="multipart/form-data">
                        <input hidden type="text" name="idadmin" value="<?php echo $row['id_admin']; ?>">
                        <div class="form-group">
                            <label> Username </label>
                            <input type="text" name="username" class="form-control" placeholder="Nom" value="<?php echo $row['username_admin']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $row['email_admin']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" value="******" required>
                        </div>
                        <div class="form-group">
                            <label>photo de profil</label>
                            <input type="text" name="imgname" value="<?php echo $row['img_admin']; ?>" hidden>
                            <img src="img/<?php echo $row['img_admin']; ?>" width="150px" height="150px" style="border-radius: 50%; margin-left:10px;" id="fdp_update" onclick="passclick('fdp_update_file')">
                            <input type="file" name="file" id="fdp_update_file" value="<?php echo $row['img_admin']; ?>" onchange="loadimage(this,'fdp_update')" class="form-control" style="display: none;">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="admin" <?php if ($row['role_admin'] === 'admin') {
                                                            echo 'selected';
                                                        } ?>>Admin</option>
                                <option value="editeur" <?php if ($row['role_admin'] === 'editeur') {
                                                            echo 'selected';
                                                        } ?>>Editeur</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="updatebtn" class="btn btn-primary">Modifier</button>
                            <a href="register.php" class="btn btn-danger">Retour</a>
                        </div>
                    </form>
            <?php
                }
                $req->closeCursor();
                $bdd = null ;
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