<?php
        include('dbconfig.php');
        if (isset($_POST['rechbar']) && !empty($_POST['rechbar'])) {
            $idrech = htmlspecialchars($_POST['rechbar']);
            if ($idrech[0] == '#') {
                $idrech = ltrim($idrech, '#');
            }
            $req = $bdd->prepare('SELECT * FROM image where id_img = :idrech');
            $req->execute(array('idrech' => $idrech));
            if ($req->rowCount() > 0) {
                $row = $req->fetch();
               
     echo $row['img']  ;
  } else {
echo false;
            }
            $req->closeCursor();
            $bdd = null ;
        }
      
        if (isset($_SESSION['rech'])) {
            echo '<h2 class="sessionText">' . $_SESSION['rech'] . '</h2>';
            unset($_SESSION['rech']);
        }
        ?>