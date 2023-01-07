<?php
@ob_start();
@session_start();
?>
<?php
include("includes/header.php");
include("security.php");
include("includes/navbar.php");
include("../dbconfig.php");
//delete image
if (isset($_POST['delete_del_btn'])) {
    $idimg = $_POST['delete_del_id'];
    $imgdir = $_POST['imgdelfolder'];
    $req = $bdd->prepare('DELETE from image where id_img = :idimg');
    if ($req->execute(array('idimg' => $idimg))) {
        unlink('../uploads/' . $imgdir);
        header('Location:deletimg.php?page=1');
    }
}

?>
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Supprimer des photos</h6>
            </div>

            <div class="card-body">

                <!-- Search form -->
                <form class="form-inline d-flex justify-content-center md-form form-sm active-cyan active-cyan-2 mt-2"
                      style="margin: 20px;" method="POST" action="recherche.php">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input class="form-control form-control-sm ml-3 w-75" type="text"
                           placeholder="Saisir le référence de la photo a chercher"
                           aria-label="Search" required name="idrech">
                </form>

                <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Photo</th>
                            <th>supprimer</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $limit = 20;
                            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                            $start = ($page - 1) * $limit;
                            $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                            $req = $bdd->prepare('SELECT * FROM image ORDER BY id_img DESC LIMIT :star, :lim');
                            $req->execute(array(
                                'star' => $start,
                                'lim' => $limit
                            ));

                            while ($row = $req->fetch()) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id_img']; ?></td>
                                    <td><img src="../uploads/<?php echo $row['img']; ?>" width="290px" height="300px">
                                    </td>

                                    <td>
                                        <form action="deletimg.php" method="POST">
                                            <input type="hidden" name="imgdelfolder" value="<?php echo $row['img']; ?>">
                                            <input type="hidden" name="delete_del_id"
                                                   value="<?php echo $row['id_img']; ?>">
                                            <button type="submit" name="delete_del_btn" class="btn btn-danger">
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                            $req->closeCursor();

                        ?>
                        </tbody>
                    </table>

                </div>

                <div class="row">
                    <div class="col-md-10">
                        <nav aria-label="Navigation">
                            <ul class="pagination">
                                <?php
                                $req1 = $bdd->query('SELECT COUNT(id_img) as nbpage from image');
                                $donnee = $req1->fetch();
                                $pages = ceil($donnee['nbpage'] / $limit);
                                $maxgauche = $page - 2;
                                $maxdroite = $page + 2;
                                if ($maxgauche < 1) {
                                    $maxgauche = 1;
                                    $maxdroite = 5;
                                }
                                if ($maxdroite > $pages) {
                                    $maxgauche = $pages - 4;
                                    $maxdroite = $pages;
                                    if ($maxgauche < 1) {
                                        $maxgauche = 1;
                                    }
                                }
                                ?>
                                <li><a style="border:1px solid blue; padding:10px; background-color:darkblue;"
                                       href="deletimg.php?page=1">Premier</a></li><?php
                                for ($i = $maxgauche; $i <= $maxdroite; $i++) {
                                    if ($i === $page) {
                                        ?>
                                        <li><a style="border:1px solid blue; padding:10px; background-color:cyan;"
                                               href="deletimg.php?page=<?php echo $i ?>"><?php echo $i ?></a></li><?php
                                    } else {

                                        ?>
                                        <li><a style="border:1px solid blue; padding:10px;"
                                               href="deletimg.php?page=<?php echo $i ?>"><?php echo $i ?></a></li><?php
                                    }
                                }
                                ?>
                                <li><a style="border:1px solid blue; padding:10px; background-color:darkblue;"
                                       href="deletimg.php?page=<?php echo $pages ?>">Dernier</a></li>
                            </ul>
                        </nav>
                    </div>
                    <?php $req1->closeCursor();
                    $bdd = null;
                    ?>
                </div>

            </div>

        </div>
    </div>
    </div>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>