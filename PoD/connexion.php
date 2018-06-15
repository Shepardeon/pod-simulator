<?php
include_once("libs/libUtils.php");

session_start();

if(valider("connecte", "SESSION"))
    rediriger("jeu.php");

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Import de Bootstrap sur la page -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- Import de FontAwesome pour les icones -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <link rel="stylesheet" href="css/form.css">
        <title>PoD Simulator - Connexion</title>
    </head>

    <body>
        <header>
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <a href="index.html" class="navbar-brand">P0D Simulator</a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#podNav" aria-controls="navbarNavAltMarkups" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="podNav">
                    <div class="navbar-nav ml-auto">
                        <a href="inscription.php" class="nav-item nav-link">Inscription</a>
                        <a href="#" class="nav-item nav-link active">Connexion</a>
                    </div>
                </div>
            </nav>
        </header>

        <main class="container">

            <?php 
                ecrireMessage(); 
                
                if(($login = valider("login", "COOKIE")) && ($pass = valider("pass", "COOKIE")))
                    $remember = "checked";
                else{
                    $login = "";
                    $pass = "";
                    $remember = "";
                }
            ?>

            <form action="controleur.php" method="POST">
                <div class="form-group">
                    <label for="login">Pseudo</label>
                    <input type="text" class="form-control" id="login" name="login" placeholder="Entrez pseudo" value="<?php echo $login; ?>" required>
                </div>
                <div class="form-group">
                    <label for="pass">Mot de passe</label>
                    <input type="password" class="form-control" id="pass" name="pass" placeholder="******" value="<?php echo $pass; ?>" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="Yes" <?php echo $remember; ?>>
                    <label class="form-check-label" for="check">Se souvenir de moi</label>
                </div>

                <button type="submit" name="action" value="connexion" class="btn btn-primary">Connexion</button>
            </form>
            <a href="inscription.php">Pas encore de compte ?</a>
        </main>

        <footer class="row mx-auto fixed-bottom" style="background-color:#212529;">
            <div class="col-md-12">
                <p class="text-center" style="color:#fff; padding-top:16px;">P0D © Copyright 2018, tous droits réservés</p>
            </div>
        </footer>

        <!-- Import des librairies JS pour Bootstrap -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>