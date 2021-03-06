<?php
/*
 * Page : Séparation des requêtes avec des fonctions 
 * 
 * ODAKA M. || CFPT-I || IFDA-P3A
 * 
 * Date: 02.04.2021  
 * 
 */


// Require files
require_once('config.inc.php');

/* CONNEXION BD*/
function connectDB()
{
    static $conn =  null;
    if ($conn == null) {
        try { // constantes de const.inc.php
            $conn = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, DBPWD, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_PERSISTENT => true
            ));
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } // Exceptions
        catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage() . '<br />';
            echo 'N° : ' . $e->getCode();
            // Quitte le script et meurt
            die('Could not connect to MySQL');
        }
    }

    return $conn;
}

//---------------------------------------------------------------- INSERT -------------------------------------------------------------


/* Relation entre la table t_post et t_media (transaction utilisé) */
function createMediaAndPost($comment, $mediaType, $filename)
{
    // Transaction 
    $conn = connectDB();
    $conn->beginTransaction();

    try {
        // Date actuelle
        $dateCreation = date("Y-m-d H:i:s");

        // Vérifier si le poste existe 
        $post = ReadPostByComAndDate($comment, $dateCreation);

        // si le post n'existe pas on l'ajoute
        if ($post == NULL) {
            // Créer un nouveau post
            InsertPost($comment);

            // Récupérer l'id du nouveau poste
            $post = ReadPostByComAndDate($comment, $dateCreation);
        }
        // insère le tout dans les médias avec l'id du post
        InsertMultipleMedia($mediaType, $filename, $post["idPost"], $dateCreation);

        $conn->commit(); // Valide Transaction 
        return true;
    } catch (Exception $e) {
        $conn->rollBack(); // Annule Transaction     
        return false;
    }
}


/* Ajouter le post de l'user dans la BD */
function InsertPost($comment)
{

    // Date actuelle
    $dateCreation = date("Y-m-d H:i:s");

    static $req = null;

    // ajoute un post
    $sql = "INSERT INTO t_post(commentaire, creationDate, modificationDate) VALUES(:comment, :dateActuelle, :dateActuelle)";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }

    $answer = false;
    try {
        $req->bindParam(':dateActuelle', $dateCreation, PDO::PARAM_STR);
        $req->bindParam(':comment', $comment, PDO::PARAM_STR);

        $answer = $req->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/* Ajouter plusieurs médias pour un post */
function InsertMultipleMedia($mediaType_array, $filename_array, $idPost, $laDate)
{
    // ajouter chaque média d'un post dans la base
    for ($i = 0; $i < count($mediaType_array); $i++) {
        InsertMedia($mediaType_array[$i], $filename_array[$i], $idPost, $laDate);
    }
}

/* Ajouter le média choisi dans la BD */
function InsertMedia($mediaType, $filename, $idPost, $laDate)
{
    static $req = null;

    // ajoute un média
    $sql = "INSERT INTO t_media(typeMedia, nomMedia, creationDate, postUtilise) VALUES(:mediaType, :nomFichier, :laDate, :idPost);";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }

    $answer = false;
    try {
        $req->bindParam(':mediaType', $mediaType, PDO::PARAM_STR);
        $req->bindParam(':nomFichier', $filename, PDO::PARAM_STR);
        $req->bindParam(':laDate', $laDate, PDO::PARAM_STR);
        $req->bindParam(':idPost', $idPost, PDO::PARAM_INT);

        $answer = $req->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}


//---------------------------------------------------------------- READ -------------------------------------------------------------

/* Récupérer l'id du post pour l'insérer dans le média en clé étrangère */
function ReadPostByComAndDate($commentaire, $dateCreation)
{
    static $req = null;

    $sql = "SELECT idPost FROM t_post WHERE commentaire = :commentaire AND creationDate = :dateCreation";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }
    $answer = false;
    try {
        $req->bindParam(":commentaire", $commentaire, PDO::PARAM_STR);
        $req->bindParam(":dateCreation", $dateCreation, PDO::PARAM_STR);

        if ($req->execute()) {
            $answer = $req->fetch();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}


/* Parcourir la table t_post pour afficher par la suite tous les posts */
function ReadPost()
{
    static $req = null;

    $sql = "SELECT * FROM t_post ORDER BY creationDate DESC ";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }
    $answer = false;
    try {

        if ($req->execute()) {
            $answer = $req->fetchAll();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/* Récuperer les médias d'un post en fonction de idPost */
function ReadMediasByPostId($idPost)
{
    static $req = null;

    $sql = "SELECT * FROM t_media WHERE postUtilise = :idPost ";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }
    $answer = false;
    try {

        $req->bindParam(":idPost", $idPost, PDO::PARAM_INT);

        if ($req->execute()) {
            $answer = $req->fetchAll();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

/* Lire un post en fonction de son id */
function ReadPostById($idPost)
{

    static $req = null;

    $sql = "SELECT * FROM t_post WHERE idPost = :idPost ";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }
    $answer = false;
    try {

        $req->bindParam(":idPost", $idPost, PDO::PARAM_INT);

        if ($req->execute()) {
            $answer = $req->fetch();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

//---------------------------------------------------------------- UPDATE -------------------------------------------------------------

/*Fonction qui met à jour un post en fonction de son ID (son commentaire et sa date de modification se change (transaction utilisé)*/
function UpdatePostByID($idPost, $commentaire)
{
    // transaction
    $conn = connectDB();
    $conn->beginTransaction();

    $dateModification = date("Y-m-d H:i:s");

    static $req = null;

    $sql = "UPDATE t_post SET commentaire = :commentaire, modificationDate = :dateDeModif WHERE idPost = :idPost";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }
    $answer = false;
    try {
        $req->bindParam(":idPost", $idPost, PDO::PARAM_INT);
        $req->bindParam(":commentaire", $commentaire, PDO::PARAM_STR);
        $req->bindParam(":dateDeModif", $dateModification, PDO::PARAM_STR);

        $answer = $req->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo $e->getMessage();
        $conn->rollback();
    }
    return $answer;
}

//---------------------------------------------------------------- DELETE -------------------------------------------------------------
/*Fonction qui supprime un post en fonction de son ID (utilisation de transaction)*/
function DeletePostByID($idPost)
{
    $conn = connectDB();
    $conn->beginTransaction();

    static $req = null;

    $sql = "DELETE FROM t_post WHERE idPost = :idPost ";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }
    $answer = false;
    try {
        $req->bindParam(":idPost", $idPost, PDO::PARAM_INT);

        $answer = $req->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo $e->getMessage();
        $conn->rollback();
    }
    return $answer;
}

/*Fonction qui supprime les médias en fonction de l'ID du post  (utilisation de transaction)*/
function DeleteMediasByID($idMedia)
{
    $conn = connectDB();
    $conn->beginTransaction();

    static $req = null;

    $sql = "DELETE FROM t_media WHERE idMedia = :idMedia ";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }
    $answer = false;
    try {
        $req->bindParam(":idMedia", $idMedia, PDO::PARAM_INT);

        $answer = $req->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo $e->getMessage();
        $conn->rollback();
    }
    return $answer;
}

//---------------------------------------------------------------- AFFICHAGE -------------------------------------------------------------
/* Créer un tableau ordonnée pour ensuite afficher les posts dans le home (index.php)
<return> $postsArray : tableau contenant les informations à afficher </return>
*/
function DisplayPost()
{
    // initialisation 
    $comment =  array();
    $medias = array();
    $idPost = array();
    $date = array();
    $structureArray = array();
    $postsArray = array();
    //   $eachMedia[] = array();

    // récupérer les posts
    $posts = ReadPost();


    // parcourir les posts --> chaque post est une ligne
    foreach ($posts as $record) {

        // Récupérer l'id de chaque post
        $idPost = $record['idPost'];
        // Récupérer le commentaire de chaque post
        $comment = $record['commentaire'];
        // Récupérer la date de création de chaque post
        $date = $record['creationDate'];
        // Récupérer la date de création de chaque post
        $dateModif = $record['modificationDate'];

        // parcourir les médias et ajouter celles qui dépende le l'id actuelle
        $medias = ReadMediasByPostId($idPost);

        $structureArray = array(
            "idPost" => $idPost,
            "commentaire" => $comment,
            "date" => $date,
            "medias" => $medias,
            "dateModif" => $dateModif
        );

        array_push($postsArray, $structureArray);
    }

    return $postsArray;
}
