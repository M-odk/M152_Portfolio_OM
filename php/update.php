<?php
/*
 * Page : Page de modification d'un post (changer le commentaire, supprimer et ajouter des médias)
 * 
 * ODAKA M. || CFPT-I || IFDA-P3A
 * 
 * Date: 02.04.2021  
 * 
 */

require_once('../backend/functions.php');

// CONST 
$MAX_SIZE_FILE = 3000000;
$MAX_SIZE_POST = 90000000;
$IMG_REP = '../medias/';

// filter : récupérer l'id du post
$idPost = filter_input(INPUT_GET, "id", FILTER_DEFAULT);
$submit = filter_input(INPUT_POST, 'submit', FILTER_DEFAULT);
$back = filter_input(INPUT_POST, 'back', FILTER_DEFAULT);
$commModifie = filter_input(INPUT_POST, 'description',  FILTER_SANITIZE_STRING);
$check = filter_input(INPUT_POST, "ckbMedia", FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

$error = null;

// récupérer le commentaire du post
$commentaire = ReadPostById($idPost);
$medias = ReadMediasByPostId($idPost);

if (isset($submit)) {

    $files = $_FILES['mediaFiles'];

    /* Vérifier s'il n'y a aucun problème avant d'envoyer dans la bd */
    for ($i = 0; $i < count($files['name']); $i++) {

        // vérifier le type du fichier 
        if (strpos($files['type'][$i], "image") === false && strpos($files['type'][$i], "video") === false && strpos($files['type'][$i], "audio") === false) {
            $error = "Vous n'avez pas rentré le bon format de fichier.";
            echo $error;
            break;
        }

        // Verifier la taille du fichier
        if ($files['size'][$i] > $MAX_SIZE_FILE) {
            $error = "Vous avez dépassé la taille maximale de " . $MAX_SIZE_FILE . " bytes.";
            echo $error;
            break;
        }
    }
    // On regarde s'il n'y a pas d'erreur et à ce moment là on peut envoyer les modifications.
    if ($error == null) {

        // modifier le commentaire
        if (isset($commModifie)) {
            UpdatePostByID($idPost, $commModifie);
        }

        // supprimer les médias qui ont été checké
        if ($check) {
            for ($i = 0; $i < count($check); $i++) {

                //verifie si la fonction de suppression s'est bien exécuté
                if (DeleteMediasByID($check[$i]) == true) {
                    // suppression de l'upload en local
                    foreach ($medias as $media) {
                        if ($media["idMedia"] == $check[$i]) {
                            unlink("../medias/" . $media["nomMedia"]);
                        }
                    }
                }
            }
        }
        // Ajouter de nouveaux médias
        for ($i = 0; $i < count($files['name']); $i++) {

            // Récupérer le type du fichier
            $mediaType = $files['type'][$i];

            // Générer un ID aléatoire
            $filename = uniqid();
            // ajouter le nom de l'image nettoyé --> devient un nom unique 
            $filename .= "_" . preg_replace('/[^a-z0-9\.\-]/ i', '', $files['name'][$i]);
            // Déplacer le fichier temporaire dans un dossier pour ne pas perdre les images
            if (move_uploaded_file($files['tmp_name'][$i], $IMG_REP . $filename)) {
                // Date actuelle
                $dateCreation = date("Y-m-d H:i:s");

                // Insérer le média
                InsertMedia($mediaType, $filename, $idPost, $dateCreation);
            }
        }
        // Retourner sur index.php
        header('Location: ../index.php');
        exit;
    }
}

// Si l'user veut annuler ses changements il retourne sur la page d'accueil
if (isset($back)) {
    // Retourner sur index.php
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <title>Update</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- My CSS  -->
    <link rel="stylesheet" href="../css/mainCss.css">
</head>

<body>
    <div class="d-flex justify-content-center mt-5 ">
        <!-- Récupère l'idPost en POST -->
        <form class="w-50" action="update.php?id=<?php echo $idPost ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <textarea name="description" class="form-control" rows="3" placeholder="<?php echo $commentaire[1] ?>"><?php echo $commentaire[1] ?></textarea>
            </div>
            <!-- Image file -->
            <div class="mb-3">
                <input type="file" class="form-control" accept="image/*,video/*, audio/* " name="mediaFiles[]" multiple />
            </div>

            <div class=" d-flex flex-column align-items-center justify-content-center">
                <?php // Afficher les médias
                foreach ($medias as $media) {

                    // variables
                    $mediaName = $media[2];
                    $mediaIndex = $media[0];
                    $mediaType = $media[1];


                    // afichage 
                    echo ' <div class="card mt-5" style="width: 30rem;">';

                    if (is_numeric(strpos($mediaType, "image"))) {
                        echo '<img  src="./medias/' . $mediaName . '" class="d-block w-100 card-img-top" alt="Image : ' . $mediaName . '">';
                    }

                    if (is_numeric(strpos($mediaType, "video"))) {

                        echo '<video width="100%" controls>';
                        echo '<source src="./medias/' . $mediaName . '" class="d-block w-100 card-img-top" alt="Video : ' . $mediaName . '">';
                        echo '</video>';
                    }

                    if (is_numeric(strpos($mediaType, "audio"))) {
                        echo '<audio width="100%" controls>';
                        echo '<source src="./medias/' . $mediaName . '" class="d-block w-100 card-img-top" alt="Audio : ' . $mediaName . '">';
                        echo '</audio>';
                    }

                    echo  '<div class="card-body">';
                    echo '<h5 class="card-title">' . $mediaName . '</h5>';
                    //checkbox pour supprimer le média
                    echo '<input type="checkbox" id="media' . $mediaIndex . '" name="ckbMedia[]" value="' . $mediaIndex . '" class="mr-3">';
                    echo '<label for="media' . $mediaIndex . '"> Delete</label>';
                    echo '</div></div>';
                }
                ?>

            </div>
            <div class="mt-5">
                <button type="submit" class="btn btn-secondary" name="back">Back</button>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>
        </form>
    </div>

</body>
<!-- FontAwesome kitCode  -->
<script src="https://kit.fontawesome.com/b49b3eeefb.js" crossorigin="anonymous"></script>
<!-- Bootstrap JS  -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</html>