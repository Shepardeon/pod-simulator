<?php
/**
 * @file libJoueur.php
 * Ce fichier définit les fonctions qui peuvent se rapporter à tous les joueurs de notre application WEB.
 */

include_once("modele.php");
include_once("libUtils.php");

/**
 * Fonction permettant d'enregistrer un nouveau joueur dans la base de données.
 * Renvoie "vrai" si l'utilisateur a été enregistré et "faux" sinon.
 * @param $login
 * @param $password
 * @param $mail
 * @return bool
 */
function inscrireJoueur($login, $password, $mail){

    if(testUtilisateurUnique($login, $mail)){
        // On hash le mot de passe de l'utilisateur
        $password = sha1($password);

        // On génère une chaine de caractères aléatoires.
        $chaine = randStr(10);

        // On enregistre le joueur dans notre base de données.
        $id = enregistrerUtilisateur($login, $password, $mail, $chaine);

        $link = "localhost/PoD/controleur.php?action=valider&id=$id&chaine=$chaine";

        envoyerMailInscripion($login, $mail, $link);

        return true;
    }

    return false;
}

/**
 * Fonction qui permet de valider un joueur à partir de sa chaine de caractère.
 * Renvoie "vrai" si l'utilisateur est validé et "faux" sinon
 * @param $idUser
 * @param $chaine
 * @return bool
 */
function validerJoueur($idUser, $chaine){
    if($chaine === recupererChaine($idUser)){
        validerUtilisateur($idUser);

        return true;
    }

    return false;
}

/**
 * Fonction qui permet de connecter un joueur et de créer une session
 * Renvoie "vrai" si la session a été ouverte et "faux" sinon
 * @param $login
 * @param $password
 * @return bool
 */
function connecterJoueur($login, $password){

    $password = sha1($password);

    if($id = verifierUtilisateur($login, $password)){
        $_SESSION["connecte"] = true;
        $_SESSION["id"] = $id;
        $_SESSION["pseudo"] = $login;

        return true;
    }

    return false;
}

/**
 * Fonction qui renvoie vrai si l'utilisateur local est connecté et faux sinon
 * @return bool
 */
function isOnlineJoueur(){
    if(valider("connecte"))
        return true;
    return false;
}

/**
 * Fonction qui affiche les fonds d'un joueur
 * @param $id 
 */
function afficherFonds($id){
    $fonds = recupFonds($id);
    echo "$fonds I2C";
}