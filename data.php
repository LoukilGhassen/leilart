<?php
if (isset($_POST['getData']))
{
    include("dbconfig.php");
    $start = htmlspecialchars($_POST['start']) ;
    $limit = htmlspecialchars($_POST['limit']) ;
    $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $req = $bdd->prepare('SELECT * FROM image ORDER BY id_img DESC LIMIT :start, :limit');
    $req->execute(array('start' => $start , 'limit' => $limit ));
    if ($req->rowCount()>0){
        $response= "" ;
        while ($row = $req->fetch()) {
            $response .='<a href="uploads/'.$row['img'].'" data-lightbox="gallery" class="link">
                <div class="box">
                <span style="display:inline-block;width: 100%;">
                    <img loading="lazy" src="uploads/'.$row['img'].'" class="image">
                 </span>
                    <p id="ref">#'.($row['id_img']).'</p>
                </div>
            </a>';
        }
        $req->closeCursor();
        $bdd = null ;
        exit($response);
    }else{
        exit('reachedMax');
    }
}


?>