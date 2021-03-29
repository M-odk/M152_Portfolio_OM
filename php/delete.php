<?php
require_once('../backend/functions.php');

// filter pour récupérer l'id en GET
$idPost = filter_input(INPUT_GET, "id", FILTER_DEFAULT);

// Récupérer les noms de fichiers de la base
$medias = ReadMediasByPostId($idPost);

//verifie si la fonction s'est bien exécuté
if (DeletePostByID($idPost) == true) {
    // suppression de l'upload en local
    foreach ($medias as $media) {
        unlink("../medias/" . $media["nomMedia"]);
    }
}

// Retourner sur index.php
header('Location: ../index.php');
exit;
