<?php
try {
    $bdd = new PDO('mysql:host=db;dbname=leilart;port=3306;charset=utf8', 'root', 'root'
    ,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>