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
 * Fonction qui renvoie vrai si une IP existe et faux sinon
 * @param $ip
 * @return bool
 */
function existsIP($ip){
    if(recupProprioDepuisOrdi($ip))
        return true;
    return false;
}

/**
 * Fonction qui renvoie l'id du propriétaire d'une machine
 * @param $ip
 * @return int
 */
function recupProprio($ip){
    return recupProprioDepuisOrdi($ip);
}

/**
 * Fonction qui renvoie le niveau du logiciel/materiel d'une machine
 * @param $ip
 * @param $mat
 * @return int
 */
function recupNiveaMat($ip, $mat){
    return recupNiveauMatBDD($ip, $mat);
}

/**
 * Fonction qui permet d'augmenter le niveau d'un logiciel ou d'un materiel
 * @param $log
 * @param $id
 */
function augmenterNiveau($log, $id){
    augmenterNiveauBDD($log, recupIPLocal($id));
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
            if(valider("ip", "SESSION") != recupIPLocal(valider("id", "SESSION")) 
            && recupNiveaMat(valider("ip", "SESSION"), str_replace(" ", "_", $logiciel)) > recupNiveaMat(recupIPLocal(valider("id", "SESSION")), str_replace(" ", "_", $logiciel)))
                echo "<a href='controleur.php?action=telecharger&&logi=".str_replace(" ", "_", $logiciel)."' class='btn btn-danger col-md-4'>Télécharger</a>";
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
        echo "<li class='row' style='padding-bottom: 10px;'><b>$materiel</b> - Niveau $niveau</li>";
    }
    echo "</ul>";
}

/**
 * Fonction qui affiche les logiciels en vente dans le magasin
 * @param $id
 */
function afficherLogicielsMagasin($id){
    $logiciels = listerLogiciels(recupIPLocal($id));

    $prixBase = array(
        "Pare feu" => 200,
        "Anti Virus" => 300,
        "Porte Feuille" => 100,
        "Scanner Reseau" => 400,
        "FW Cracker" => 500,
        "SW Cracker" => 500,
        "Generateur de Miner" => 250,
        "Generateur de Backdoor" => 420
    );

    echo "<ul>";
    foreach($logiciels[0] as $logiciel => $niveau){
        $logiciel = str_replace("_", " ", $logiciel);
        $niveau++;
        $prix = $prixBase[$logiciel]*$niveau;
        echo "<li class='row' style='padding-bottom: 15px;'><span class='col-md-8'><b>$logiciel</b> - Niveau $niveau ---> $prix I2C</span>";
        echo "<a href='controleur.php?action=acheterL&&logiciel=".str_replace(" ", "_", $logiciel)."&&prix=$prix' class='btn btn-outline-success col-md-4'>Acheter</a>";
        echo "</li>";
    }
    echo "</ul>";
}

/**
 * Fonction qui affiche les materiels en vente dans le magasin
 * @param $id
 */
function afficherMaterielsMagasin($id){
    $materiels = listerMateriels(recupIPLocal($id));

    $prixBase = array(
        "Carte Reseau" => 200,
        "Processeur" => 300,
        "Disque Dur" => 100
    );

    echo "<ul>";
    foreach($materiels[0] as $materiel => $niveau){
        $materiel = str_replace("_", " ", $materiel);
        $niveau++;
        $prix = $prixBase[$materiel]*$niveau;
        echo "<li class='row' style='padding-bottom: 15px;'><span class='col-md-8'><b>$materiel</b> - Niveau $niveau ---> $prix I2C</span>";
        echo "<a href='controleur.php?action=acheterM&&mat=".str_replace(" ", "_", $materiel)."&&prix=$prix' class='btn btn-outline-success col-md-4'>Acheter</a>";
        echo "</li>";
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

/**
 * Fonction qui permet d'éditer les logs d'un ordinateur en ajoutant une nouvelle ligne
 * @param $ip
 * @param $text
 */
function ajouterLogs($ip, $text){
    $logs = chargerLogsBDD($ip);
    $logs .= "\r\n[". date("H:i", time()) . "] " . $text;
    ecrireLogsBDD($ip, $logs);
}

/**
 * Fonction qui renvoie nb nombre d'IP sauf celle en paramètre
 * @param $ip
 * @param int $nb
 * @return array
 */
function randomIP($ip, $nb=3){
    return recupRandomIP($ip, $nb);
}

/**
 * Fonction qui affiche une liste d'IP
 * @param $ips
 */
function afficherRandomIP($ips){
    echo "<ul>";
    foreach($ips as $ip)
        foreach($ip as $k => $v)
            echo "<li class='row' style='padding-bottom:15px;'><span class='col-md-12'>$v</span></li>";
    echo "</ul>";
}

/**
 * Fonction qui affiche le résultat d'un scan intensif
 */
function afficherScanI($ip){
    $niv = recupNiveaMat($ip, "Pare_feu");

    echo "<h6>Résultat pour <b>$ip</b> :</h6>";
    echo "<p style='padding-bottom:20px;'>Pare-feu niveau $niv</p>";
}

  /////////////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////// FONCTIONS DU SOUS MODULE TELECHARGEMENT //////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

