<?php
if (isset($_POST['categor']) && !empty($_POST['categor'])) {
    include("dbconfig.php");
    $nomcategorie = "Categorie introuvable";
    $categorie = htmlspecialchars($_POST['categor']);
    $req1 = $bdd->prepare('SELECT nom_categorie from categories where id_categorie = :catid');
    $req1->execute(array('catid' => $categorie));
    if ($row1 = $req1->fetch()) {
        $nomcategorie = $row1['nom_categorie'];
    }
    $req1->closeCursor();
    $bdd = null;
    exit($nomcategorie);
}
if (isset($_POST['categorImages']) && !empty($_POST['categorImages'])) {
    include("dbconfig.php");
    $start = htmlspecialchars($_POST['start']) ;
    $limit = htmlspecialchars($_POST['limit']) ;
    $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $req = $bdd->prepare('SELECT * from image where categorie = :cat ORDER BY id_img DESC LIMIT :start, :limit');
    $req->execute(array('cat' => htmlspecialchars($_POST['categorImages']) , 'start' => $start , 'limit' => $limit ));
    $response= "";
    while ($row = $req->fetch()) {
        $response .= '<a href="uploads/' . $row['img'] . '" data-lightbox="gallery" class="link">
                <div class="box">
                <span style="display:inline-block;width: 100%;">
                    <img loading="lazy" src="uploads/' . $row['img'] . '" class="image">
                 </span>
                    <p id="ref">#' . ($row['id_img']) . '</p>
                </div>
            </a>';
    }
    $req->closeCursor();
    $bdd = null;
    exit($response);
} else {
    exit('reachedMax');
}


?>