<!--
Projet: Portfolio 
Description:
Nom, Prénom: Odaka Michi
Date: 
Version: 1.0
-->
<?php
require_once('backend/functions.php');

//  fonction pour lire les posts
$posts = DisplayPost();


?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <title>Home</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- My CSS  -->
    <link rel="stylesheet" href="./css/mainCss.css">
</head>

<body>

       <!--  TODO : links effects when mouse on -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">

                        <a class="collapse-item nav-link active" href="../index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">

                        <a class="collapse-item nav-link " href="./php/post.php"><i class="fas fa-clone"></i> Post</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- 
        section profile de l'user (sa photo + message de bienvenue)
        source : https://bbbootstrap.com/snippets/bootstrap-sidebar-user-profile-62301382
        - message de  bienvenue -
        TODO : animation or other for a better renderer
        -->
    <h1 style="text-align: center;">Bienvenu à toi !</h1>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <div class="image"> <img src="img/chitanda.jpg" class="rounded" width="155"> </div>
                <div class="ml-3 w-100">
                    <h4 class="mb-0 mt-0">User01</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- afficher les posts-->
    
        
    <?php

    /* Parcourir le tableau qui contient toutes les infos en fonction des posts  */
    foreach ($posts as $post) {
   // var_dump($post["medias"][0]["typeMedia"]);
        echo '<div class="card" style="width: 18rem; height: 15rem; background" >';

       /* post avec médias */
        if ($post["medias"] != null) {

            // utilisation d'un affichage carrousel
            $isActive = 'class="carousel-item"';

            // récpérer l'id du post pour créer un id (carrousel)
            echo '<div id="_'.$post['idPost'].'" class="carousel slide" data-bs-ride="carousel">';
        

            // parcourir les médias du post
            for ($i=0; $i <  count($post["medias"]); $i++) { 

                echo ' <div class="carousel-inner">';
                echo '<div'.(($i == 0)? $isActive = 'class="carousel-item active"' : 'class="carousel-item"').$isActive.'>';


                if (strpos($post["medias"][0]["typeMedia"], "image") === true) {
                    echo '<img  width="200px" height="300px" src="./medias/' . $post["medias"][$i]["nomMedia"] . '" class="d-block w-100 card-img-top" alt="image trop grande">';
                }

                if (strpos($post["medias"][0]["typeMedia"], "video") === true) {
            
                    echo '<video  width="320px" height="240px" controls>';
                    echo 'src="./medias/' . $post["medias"][$i]["nomMedia"] . '" class="d-block w-100 card-img-top" alt="vidéo trop grande">';
                    echo '</video>';
                }
                
                echo '</div>';
                        
            }
            echo ' </div>';
                echo ' <button class="carousel-control-prev" type="button" data-bs-target="#_'.$post['idPost'].'"  data-bs-slide="prev">';
                echo '  <span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                echo '  <span class="visually-hidden"><img src="img\flecheGauche.png" width="30px" height="30px"></span>';
                echo ' </button>';

                echo ' <button class="carousel-control-next" type="button" data-bs-target="#_'.$post['idPost'].'"data-bs-slide="next">';
                echo '  <span class="carousel-control-next-icon" aria-hidden="true"></span>';
                echo '   <span class="visually-hidden" style="color: black;"><img src="img\flecheDroit.png" width="30px" height="30px"> </span>';
                echo ' </button>';
            echo '</div>';
        }

            echo '<div class="card-body">';

                echo ' <p class="card-text">' . $post["commentaire"] . '<br>' . $post["date"] . '</p>';
                
                echo "<a href='php\delete.php?id=${post['idPost']}'><img src='img\deleteIcon.png' width='30px' height='30px'></a></td>";
                echo "<a href='php\update.php?id=${post['idPost']}'><img src='img\updateIcon.png' width='30px' height='30px'></a></td>";

            echo ' </div>';
        echo '</div>';

    }
?>
    </section>
</body>
<!-- FontAwesome kitCode  -->
<script src="https://kit.fontawesome.com/b49b3eeefb.js" crossorigin="anonymous"></script>
<!-- Bootstrap JS  -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</html>