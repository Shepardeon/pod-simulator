<?php

/**
 * @file modele.php
 * Ce fichier définit des fonctions qui opèrent directement sur la base de données.
 */

// Inclusion de la librairie pour faciliter les requêtes SQL
include_once("libSQL.php");

/**
 * Liste des fonctions opérant sur les utilisateurs
 */

/**
 * Fonction permetant d'enregistrer un joueur dans la base de données.
 * On a besoin de son pseudo, mot de passe, mail et de la chaine pour valider.
 * @param $login
 * @param $password
 * @param $mail
 * @param $chaine
 */
function enregistrerUtilisateur($login, $password, $mail, $chaine){
    $SQL = "INSERT INTO joueurs (Pseudo, Pass, Mail, Chaine_Validation) VALUES ('$login', '$password', '$mail', '$chaine')";
    SQLInsert($SQL);
}

/**
 * Permet de rendre un utilisateur en base de données valide.
 * @param int $idUser
 */
function validerUtilisateur($idUser){
    $SQL = "UPDATE joueurs SET Valide = TRUE WHERE ID_Joueur = '$idUser'";
    SQLUpdate($SQL);
}

/**
 * Permet de vérifier l'identitée d'un joueur avec son pseudo et mot de passe.
 * Elle renvoie "faux" si l'utilisateur n'existe pas
 * @param $login
 * @param $password
 * @return bool|false|string
 */
function verifierUtilisateur($login, $password){
    $SQL = "SELECT id FROM joueur WHERE Pseudo = '$login' AND Pass = '$password'";
    return SQLGetChamp($SQL);
}

/**
 * Fonctions qui permet de lister les 50 premiers utilisateurs et de les classer en fonction de leur niveau de manière croissante
 * ou décroissante au choix
 * @param string $ordre
 * @return array
 */
function listerUtilisateurs($ordre="ASC"){
    $SQL = "SELECT Pseudo, Niveau from joueurs WHERE Valide = TRUE ORDER BY Niveau";

    if($ordre === "DESC")
        $SQL .= " DESC";
    $SQL .= " LIMIT 50";

    return parcoursRS(SQLSelect($SQL));
}


/**
 * Liste des fonctions opérant sur les ordinateurs
 */



/**
 * Liste des fonctions opérant sur les virus
 */