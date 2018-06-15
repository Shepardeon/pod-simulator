<?php
/**
 * @file libOrdinateur.php
 * Ce fichier définit les fonctions qui peuvent se rapporter à tous les ordinateurs de notre application WEB.
 */

include_once("modele.php");
include_once("libUtils.php");

/**
 * Fonction qui créer un nouvel ordinateur pour un nouvel utilisateur
 * @param $idUser
 */
function creerOrdinateur($idUser){
    $ip = "";
    do{
        $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    }while(!testIPUnique($ip));

    enregistrerOrdinateur($ip, $idUser);
}

/**
 * Fonction qui renvoie l'IP de l'utilisateur passé en paramètre
 * @param $idUser
 * @return string
 */
function recupIPLocal($idUser){
    if(!isset($idUser) || $idUser == "")
        return "X.X.X.X";
    return recupIPDepuisUtilisateur($idUser);
}

/**
 * Fonction qui renvoie l'id du propriétaire d'une machine
 * @param $ip
 */
function recupProprio($ip){
    return recupProprioDepuisOrdi($ip);
}

/**
 * Fonction qui affiche la liste des logiciels d'un ordinateur et leur niveau
 * @param $ip
 */
function afficherLogiciels($ip){

    $logiciels = listerLogiciels($ip);

    echo "<ul>";
    foreach($logiciels[0] as $logiciel => $niveau){
        $logiciel = str_replace("_", " ", $logiciel);
        echo "<li class='row' style='padding-bottom: 15px;'><span class='col-md-8'><b>$logiciel</b> - Niveau $niveau</span>";
            if(valider("ip", "SESSION") != recupIPLocal(valider("id", "SESSION")))
                echo "<a href='#' class='btn btn-danger col-md-4'>Télécharger</a>";
        echo "</li>";
    }
    echo "</ul>";
}

/**
 * Fonction qui affiche la liste des materiels d'un ordinateur et leur niveau
 * @param $ip
 */
function afficherMateriels($ip){

    $materiels = listerMateriels($ip);

    echo "<ul>";
    foreach($materiels[0] as $materiel => $niveau){
        $materiel = str_replace("_", " ", $materiel);
        echo "<li style='padding-bottom: 10px;'><b>$materiel</b> - Niveau $niveau</li>";
    }
    echo "</ul>";
}

/**
 * Fonction qui affiche les logs d'un ordinateur
 * @param $ip
 */
function afficherLogs($ip){
    echo chargerLogsBDD($ip);
}

/**
 * Fonction qui permet d'éditer les logs d'un ordinateur
 * @param $ip
 * @param $text
 */
function ecrireLogs($ip, $text){
    ecrireLogsBDD($ip, $text);
}