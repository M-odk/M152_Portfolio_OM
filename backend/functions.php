<?php
// Session start


// Require files
require_once('config.inc.php');

// Redirections 


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

//---------------------------------------------------------------- CREATE -------------------------------------------------------------

// Relation entre la table t_post et t_media
function createMediaAndPost($comment, $mediaType, $filename)
{
    // Date actuelle
    $dateCreation = date("Y-m-d H:i:s");

    // Vérifier si le poste existe 
   $post =  ReadPostByComAndDate($comment, $dateCreation);

   // si le post n'existe pas on l'ajoute
    if ($post == NULL) {
        // Créer un nouveau post
        InsertPost($comment, $dateCreation);

        // Récupérer l'id du nouveau poste
        $post =  ReadPostByComAndDate($comment, $dateCreation);

    }
    // insère le tout dans les médias avec l'id du post
    InsertMedia($mediaType,$filename, $post['idPost']);

}

//---------------------------------------------------------------- INSERT -------------------------------------------------------------

// Ajouter le post de l'user dans la BD
function InsertPost($comment, $date)
{
    static $req = null;

    // ajoute un post
    $sql = "INSERT INTO t_post(commentaire, dateDeCreation, dateDeModification) VALUES(:comment, :dateActuelle, :dateActuelle)";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }

    $answer = false;
    try {
        $req->bindParam(':dateActuelle', $date, PDO::PARAM_STR);
        $req->bindParam(':comment', $comment, PDO::PARAM_STR);

        $answer = $req->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}


// Ajouter le média choisi dans la BD
function InsertMedia($mediaType, $filename, $idPost)
{
    static $req = null;

    // ajoute un post
    $sql = "INSERT INTO t_media(typeMedia, nomFichierMedia, idPostFK) VALUES(:mediaType, :nomFichier, :idPost)";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }

    $answer = false;
    try {
        $req->bindParam(':mediaType', $mediaType, PDO::PARAM_STR);
        $req->bindParam(':nomFichier', $filename, PDO::PARAM_STR);
        $req->bindParam(':idPost', $idPost, PDO::PARAM_INT);

        $answer = $req->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}

function InsertLastID()
{
    static $req = null;

    // prend le dernier id FONCTIONNE PAS
    $sql = "SELECT LAST_INSERT_ID(); ";

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


//---------------------------------------------------------------- READ -------------------------------------------------------------

// Récupérer l'id du post pour l'insérer dans le média en clé étrangère
function ReadPostByComAndDate($commentaire, $dateCreation)
{
    static $req = null;

    $sql = "SELECT idPost FROM t_post WHERE commentaire = :commentaire AND dateDeCreation = :dateCreation";

    if ($req == null) {
        $req = connectDB()->prepare($sql);
    }
    $answer = false;
    try {
        $req->bindParam(":commentaire", $commentaire, PDO::PARAM_STR);
        $req->bindParam(":dateCreation", $dateCreation, PDO::PARAM_STR);

        if ($req->execute()) {
            $answer = $req->fetchAll();
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    return $answer;
}


// Parcourir la table t_post pour afficher par la suite tous les posts
function ReadPost()
{
    static $req = null;

    $sql = "SELECT * FROM t_post";

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

// Afficher les posts dans le home
function DisplayPost()
{
    // Récupérer les données de la base
    ReadPost();

    // affichage des données
   /* foreach ($variable as $key => $value) {
        // créer chaque article 
        // leur structure css et affichage dans ces boxs
    } */

}
?>