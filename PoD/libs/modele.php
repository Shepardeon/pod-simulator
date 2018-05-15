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
 * Permet de rendre un utilisateur en base de données valide.
 * @param int $idUser
 */
function validerUtilisateur($idUser){
    $SQL = "UPDATE joueurs SET Valide = TRUE WHERE ID_Joueur = '$idUser'";
    SQLUpdate($SQL);
}


/**
 * Liste des fonctions opérant sur les ordinateurs
 */



/**
 * Liste des fonctions opérant sur les virus
 */