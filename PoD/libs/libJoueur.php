<?php
/**
 * @file libJoueur.php
 * Ce fichier définit les fonctions qui peuvent se rapporter à tous les joueurs de notre application WEB.
 */

include_once("modele.php");
include_once("libUtils.php");

function inscrireJoueur($login, $password, $mail){

    // TODO: Vérifier que le pseudo ou le mail n'est pas déjà utilisé sur la bdd et renvoyer un message d'erreur.

    // On hash le mot de passe de l'utilisateur
    $password = password_hash($password, PASSWORD_DEFAULT);

    // On génère une chaine de caractères aléatoires.
    $chaine = randStr(10);

    // On enregistre le joueur dans notre base de données.
    enregistrerUtilisateur($login, $password, $mail, $chaine);

    // TODO : On envoie un mail au joueur pour valider son compte.
}