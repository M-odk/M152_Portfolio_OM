<!--
Projet: Page formulaire de création  post 
Description: où l'on créé des posts 
Nom, Prénom: Odaka Michi
Date: 
Version: 1.0
-->
<?php
require_once('../backend/functions.php');

// variables
$nbFiles;
$mediaType;
$filename;


$error = null;
$post_size = null;


// CONST 
$MAX_SIZE_FILE = 3000000;
$MAX_SIZE_POST = 70000000;
$TEMP = './temp_upload/';

// Filters 

$commentaire = filter_input(INPUT_POST, 'description',  FILTER_SANITIZE_STRING);
// faire un filter file ?
$submit = filter_input(INPUT_POST, 'submit', FILTER_DEFAULT);


if(isset($submit)) {

        $files = $_FILES['mediaFiles[]']; // NULL
        var_dump($files);
        // regarder si la limite globale de taille est dépassée
        if ($post_size < $MAX_SIZE_POST) {
            
            // regarde si la limite de taille de l'image est dépassée
            if ($files['size']< $MAX_SIZE_FILE) {

                 // parcourir la liste selon le nombre d'item qu'il y a 
                for ($i=0; $i < count($files['name']) ; $i++) { 
                    
                    // Récupérer le type du fichier
                    $mediaType = $files['type'][$i];

                    // nettoyer le nom du fichier et le récupérer
                    $filename = preg_replace('/[^a-z0-9\.\-]/ i','',$files['name'][$i]);
                    echo $filename;

                    // ajouter la taille de l'image a la taille globale
                    $post_size += $files['size'][$i];
                    
                    // Déplacer le fichier temporaire dans un dossier pour ne pas perdre les images
                    // !!!! VERFIFIER SI NOM UNIQUE AVANT D'ENVOYER
                    move_uploaded_file($fichier['tmp_name'][$i], $TEMP);
                }
                
            }
            else{
                $error = "Vous avez dépassé la taille maximale de " . $MAX_SIZE_FILE . " bytes.";;
            }
        }
        else{
            $error = "Vous avez dépassé la taille maximale de " . $MAX_SIZE_POST . " bytes.";;
        }
        // ajouter les informations dans la BD
        createMediaAndPost($commentaire, $mediaType, $filename);
        
    }
   
    // Checker si c'est bien une image (côté server) php.init

?>
<!DOCTYPE html>

<html lang="fr">

<head>
    <title>Post</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- My CSS  -->
    <link rel="stylesheet" href="../css/mainCss.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">

                        <a class="collapse-item nav-link" href="../index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">

                        <a class="collapse-item nav-link active" href="./php/post.php"><i class="fas fa-clone"></i> Post</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-center mt-5 ">
        <form class="w-50" action="post.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <textarea class="form-control" rows="3" placeholder="Laissez votre commentaire..."></textarea>
            </div>
            <!-- Image file --> 
            <div class="mb-3">
                <input type="file" class="form-control" accept="image/*" name="mediaFiles[]"  multiple  />

            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>

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