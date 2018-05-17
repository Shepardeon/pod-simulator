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
 * Elle renvoie l'ID du dernier utilisateur enregistré en BDD.
 * @param $login
 * @param $password
 * @param $mail
 * @param $chaine
 * @return int|false
 */
function enregistrerUtilisateur($login, $password, $mail, $chaine){
    $SQL = "INSERT INTO joueurs (Pseudo, Pass, Mail, Chaine_Validation) VALUES ('$login', '$password', '$mail', '$chaine')";
    SQLInsert($SQL);

    $SQL = "SELECT ID_Joueurs FROM joueurs ORDER BY ID_Joueurs DESC LIMIT 1";
    return SQLGetChamp($SQL);
}

/**
 * Fonction permettant de tester si un pseudo ou un mail est déjà utilisé dans la base de données.
 * Renvoie "vrai" si l'utilisateur n'existe pas et "faux" sinon.
 * @param $login
 * @param $mail
 * @return bool
 */
function testUtilisateurUnique($login, $mail){
    $SQL = "SELECT ID_Joueurs FROM joueurs WHERE Pseudo = '$login' OR Mail = '$mail'";

    if(!SQLGetChamp($SQL))
        return true;
    return false;
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
 * @param $valide
 * @return bool|false|string
 */
function verifierUtilisateur($login, $password, $valide = true){
    $SQL = "SELECT ID_Joueurs FROM joueur WHERE Pseudo = '$login' AND Pass = '$password'";
    if($valide)
        $SQL .= " AND Valide = TRUE";
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