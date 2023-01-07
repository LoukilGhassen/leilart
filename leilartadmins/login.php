<?php
@ob_start();
@session_start();
?>
<?php
include('includes/header.php');
include('../dbconfig.php');
?>

<?php
if (isset($_POST['login_btn'])) {
    $useremail = htmlspecialchars($_POST['email_login']);
    $motdpass = htmlspecialchars($_POST['mdp_login']);

    $req = $bdd->prepare('SELECT * FROM admins WHERE email_admin=:email');
    $req->execute(array(
        'email' => $useremail,
    ));

    if ($req->rowCount() > 0) {
        $row = $req->fetch();
        $user_password = $row['password_admin'];
        if (password_verify($motdpass, $user_password)) {
            $_SESSION['userid'] = $row['id_admin'];
            $_SESSION['username'] = $row['username_admin'];
            $_SESSION['profileimg'] = $row['img_admin'];
            $_SESSION['role'] = $row['role_admin'];
            $req->closeCursor();
            $bdd = null;
             header('Location:index.php');
             exit;
        }
    }
    $_SESSION['status'] = "verifier votre adresse/mot de passe";

}
?>
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-6 col-md-6">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Bienvenue!</h1>
                                        <?php
                                        if (isset($_SESSION['status']) && !empty($_SESSION['status'])) {
                                            echo '<p class="bg-warning"> ' . $_SESSION['status'] . '</p>';
                                            unset($_SESSION['status']);
                                        }
                                        ?>
                                    </div>
                                    <form class="user" action="login.php" method="POST">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                   name="email_login" id="exampleInputEmail"
                                                   aria-describedby="emailHelp" placeholder="Email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                   name="mdp_login" id="exampleInputPassword" placeholder="Mot de passe"
                                                   required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <input type="submit" value="Login" name="login_btn"
                                               class="btn btn-primary btn-user btn-block">

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


<?php
include('includes/scripts.php');
?>