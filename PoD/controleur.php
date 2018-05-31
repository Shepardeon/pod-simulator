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
                echo "pas de pb.";
                if($login = valider("login") && $pass = valider("pass") && $mail = valider("mail")){
                    inscrireJoueur($login, $pass, $mail);
                }
        break;

        /* On valide un nouvel utilisateur ici */
        case "valider":
                if($id = valider("id") && $chaine = valider("chaine")){
                    validerJoueur($id, $chaine);
                    //TODO : Création de l'ordinateur
                }
        break;

        /* On connecte l'utilisateur et on créer sa session */
        case "connexion":
                if($login = valider("login") && $pass = valider("pass")){
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
                    }
                }
        break;

        /* On déconnecte l'utilisateur et on supprime sa session */
        case "deconnexion":
                session_destroy();

                //TODO: Rediriger vers la page de login
        break;
    }
}