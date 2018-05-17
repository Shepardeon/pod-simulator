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
        $password = password_hash($password, PASSWORD_DEFAULT);

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