<?php
session_start();

include_once("libs/libUtils.php");
include_once("libs/libJoueur.php");

// On reçoit une action sur notre contrôleur
if($action = valider("action")){
    switch($action){

        //TODO: Un système de messages d'informations / erreurs
        //TODO: Un système de redirection

        /* On inscrit un nouvel utilisateur dans la base de données */
        case "inscription":
                if($login = valider("login", "POST"))
                    if($pass = valider("pass", "POST"))
                        if($mail = valider("mail", "POST")){
                            if(inscrireJoueur($login, $pass, $mail)){
                                enregistrerMessage("Votre compte a bien été créé ! Un mail de confirmation a été envoyé à l'adresse <b>$mail</b>.");
                                rediriger("connexion.php");
                            }
                            else{
                                enregistrerMessage("Ce pseudo ou ce mail a déjà été utilisé sur ce site.", "danger");
                                rediriger("inscription.php");
                            }
                        }
        break;

        /* On valide un nouvel utilisateur ici */
        case "valider":
                if($id = valider("id")){
                    if($chaine = valider("chaine")){
                        if(validerJoueur($id, $chaine)){
                            enregistrerMessage("Votre compte est <b>actif</b> ! Vous pouvez à présent vous <b>connecter</b>.");
                            rediriger("connexion.php");
                        }
                        else{
                            enregistrerMessage("Une erreur est survenue lors de l'étape de validation, vérifiez le lien de validation.", "danger");
                            rediriger("inscription.php");
                        }
                        //TODO : Création de l'ordinateur
                    }
                }
        break;

        /* On connecte l'utilisateur et on créer sa session */
        case "connexion":
                if($login = valider("login"))
                    if($pass = valider("pass"))
                    {
                        if(connecterJoueur($login, $pass)){
                            if($remember = valider("remember")){
                                setcookie("login", $login, time()+60*60*24*30);
                                setcookie("pass", $pass, time()+60*60*24*30);
                                setcookie("remember", true, time()+60*60*24*30);
                            }
                            else{
                                setcookie("login", "", time()-3600);
                                setcookie("pass", "", time()-3600);
                                setcookie("remember", false, time()-3600);
                            }
                            rediriger("jeu.php");
                        }
                        else{
                            enregistrerMessage("Combinaison pseudo / mot de passe invalide.", "danger");
                            rediriger("connexion.php");
                        }
                    }
        break;

        /* On déconnecte l'utilisateur et on supprime sa session */
        case "deconnexion":
                session_destroy();
                rediriger("connexion.php");
        break;
    }
}