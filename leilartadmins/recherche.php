<?php
include("includes/header.php");
include("security.php");
include("includes/navbar.php");
include("../dbconfig.php");
?>

<div class="container-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Supprimer des photos</h6>
    </div>

    <div class="card-body">


<form class="form-inline d-flex justify-content-center md-form form-sm active-cyan active-cyan-2 mt-2" style="margin: 20px;" method="POST" action="recherche.php">
  <i class="fas fa-search" aria-hidden="true"></i>
  <input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="Saisir le référence de la photo a chercher"
    aria-label="Search" required name="idrech">
</form>

<?php
        if(isset( $_POST['idrech'])){
        $iidimg = $_POST['idrech'];        
        $req = $bdd->prepare('SELECT * FROM image where id_img = :idim');
        $req->execute(array(
            'idim'=>$iidimg 
        ));
        if($req->rowCount()>0)
        { ?>

<div class="table-responsive">
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Photo</th>
            <th>supprimer </th>
        </tr>
    </thead>
    <tbody>
     <?php
        while ($row = $req->fetch()) {
        ?>
            <tr>

                <td><img src="../uploads/<?php echo $row['img']; ?>" width="70%" height="40%"></td>

                <td>
                    <form action="deletimg.php" method="POST">
                        <input type="hidden" name="imgdelfolder" value="<?php echo $row['img']; ?>">
                        <input type="hidden" name="delete_del_id" value="<?php echo $row['id_img']; ?>">
                        <button type="submit" name="delete_del_btn" class="btn btn-danger"> Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php
        }
        $req->closeCursor();
        $bdd = null ;

    }else{
        $_SESSION['rechstatus']="la photo est introuvable";
    }
}
        ?>
    </tbody>
</table>
<?php
if(isset($_SESSION['rechstatus']))
{
    echo '<h2>' . $_SESSION['rechstatus'] .'</h2>' ;
    unset($_SESSION['rechstatus']);
}
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