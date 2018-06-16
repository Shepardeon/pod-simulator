<?php

/**
 * @file modele.php
 * Ce fichier définit des fonctions qui opèrent directement sur la base de données.
 */

// Inclusion de la librairie pour faciliter les requêtes SQL
include_once("libSQL.php");

  /////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////// FONCTIONS DU MODULE UTILISATEUR //////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

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
    return SQLInsert($SQL);
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
 * Fonction qui récupère la chaine de caractère de validation du joueur.
 * @param $idUser
 * @return bool|false|string
 */
function recupererChaine($idUser){
    $SQL = "SELECT Chaine_Validation FROM joueurs WHERE ID_Joueurs = '$idUser'";
    return SQLGetChamp($SQL);
}

/**
 * Permet de rendre un utilisateur en base de données valide.
 * @param int $idUser
 */
function validerUtilisateur($idUser){
    $SQL = "UPDATE joueurs SET Valide = 1 WHERE ID_Joueurs = '$idUser'";
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
    $SQL = "SELECT ID_Joueurs FROM joueurs WHERE Pseudo = '$login' AND Pass = '$password'";
    if($valide)
        $SQL .= " AND Valide = 1";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui permet de vérifier si un joueur est valide
 * @param $idUser
 * @return bool
 */
function testUtilisateurValide($idUser){
    $SQL = "SELECT Valide FROM joueurs WHERE ID_Joueurs = '$idUser'";
    return SQLGetChamp($SQL);
}

/**
 * Fonctions qui permet de lister les X premiers utilisateurs et de les classer en fonction de leur niveau de manière croissante
 * ou décroissante au choix
 * @param string $ordre
 * @return array
 */
function listerUtilisateurs($limite=50, $ordre="ASC"){
    $SQL = "SELECT Pseudo, Niveau FROM joueurs WHERE Valide = 1 ORDER BY Niveau";

    if($ordre === "DESC")
        $SQL .= " DESC";
    $SQL .= " LIMIT $limite";

    return parcoursRS(SQLSelect($SQL));
}

/**
 * Fonction qui récupère les fonds actuels d'un joueur
 * @param $idUser
 * @return int
 */
function recupFonds($idUser){
    $SQL = "SELECT Fonds FROM joueurs WHERE ID_Joueurs = '$idUser'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui récupère les fonds sécurisés actuels d'un joueur
 * @param $idUser
 * @return int
 */
function recupFondsSec($idUser){
    $SQL = "SELECT Fonds_Securise FROM joueurs WHERE ID_Joueurs = '$idUser'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui récupère les fonds totaux actuels d'un joueur
 * @param $idUser
 * @return int
 */
function recupFondsTotaux($idUser){
    $SQL = "SELECT Fonds + Fonds_Securise FROM joueurs WHERE ID_Joueurs = '$idUser'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui permet de renvoyer le niveau du joueur à partir de son appli la plus haute
 * @param $idUser
 * @return int
 */
function recupNiveauJoueur($idUser){
    $SQL = "SELECT GREATEST(Pare_feu, Anti_Virus, Porte_Feuille, Scanner_Reseau, FW_Cracker, SW_Cracker, Generateur_de_Miner, Generateur_de_Backdoor, Carte_Reseau, Processeur, Disque_Dur) 
    FROM ordinateurs WHERE ID_Joueurs = '$idUser'";

    return SQLGetChamp($SQL);
}

function setNiveauJoueurBDD($idUser, $niv){
    $SQL = "UPDATE joueurs SET Niveau = '$niv' WHERE ID_Joueurs = '$idUser'";
    SQLUpdate($SQL);
}

/**
 * Fonction qui permet de définir les fonds d'un joueurs en BDD
 * @param $id
 * @param $montant
 * @param string $type
 */
function setFonds($id, $montant, $type="nsec"){
    if($type == "nsec")
        $SQL = "UPDATE joueurs SET Fonds = '$montant' WHERE ID_Joueurs = '$id'";
    else
    $SQL = "UPDATE joueurs SET Fonds_Securise = '$montant' WHERE ID_Joueurs = '$id'";

    SQLUpdate($SQL);
}

/**
 * Fonction qui permet de définir en BDD combien gagne un joueur
 * @param $id
 * @param $revenus
 */
function setRevenusBDD($id, $revenus){
    $SQL = "UPDATE joueurs SET Revenus = '$revenus' WHERE ID_Joueurs = '$id'";
    SQLUpdate($SQL);
}

  ////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////// FONCTIONS DU MODULE ORDINATEUR //////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Fonction qui permet d'enregistrer un nouvel ordinateur dans la BDD
 * Elle renvoie l'ID du dernier ordinateur enregistré
 * @param $ip
 * @param $idUser
 * @return int|bool
 */
function enregistrerOrdinateur($ip, $idUser){
    $SQL = "INSERT INTO ordinateurs (IP, ID_Joueurs, LOG) VALUES('$ip', '$idUser', '====| Début des logs |====')";
    return SQLInsert($SQL);
}

/**
 * Fonction qui permet de savoir si un ordinateur avec une adresse IP existe déjà en BDD
 * Elle renvoie "vrai" si non et "faux" si oui
 * @return bool
 */
function testIPUnique($ip){
    $SQL = "SELECT ID_Ordinateurs FROM ordinateurs WHERE IP = '$ip'";

    if(!SQLGetChamp($SQL))
        return true;
    return false;
}

/**
 * Fonction qui récupère en BDD l'IP de l'utilisateur passé en paramètre
 * @param $idUser
 * @return string
 */
function recupIPDepuisUtilisateur($idUser){
    $SQL = "SELECT IP FROM ordinateurs WHERE ID_Joueurs = '$idUser'";

    return SQLGetChamp($SQL);
}

/**
 * Fonction qui récupère en BDD l'IP de l'identifiant ordinateur passé en paramètre
 * @param $idOrdi
 * @return string
 */
function recupIPDepuisID($idOrdi){
    $SQL = "SELECT IP FROM ordinateurs WHERE ID_Ordinateurs = '$idOrdi'";

    return SQLGetChamp($SQL);
}

/**
 * Fonction qui récupère en BDD l'ID du propriétaire d'un ordinateur
 * @param $ip
 * @return int
 */
function recupProprioDepuisOrdi($ip){
    $SQL = "SELECT ID_Joueurs FROM ordinateurs WHERE IP = '$ip'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui récupère la liste des logiciels d'un ordinateurs en BDD
 * @param $ip
 * @return array
 */
function listerLogiciels($ip){
    $SQL = "SELECT Pare_feu, Anti_Virus, Porte_Feuille, Scanner_Reseau, FW_Cracker, SW_Cracker, Generateur_de_Miner, Generateur_de_Backdoor FROM ordinateurs WHERE IP = '$ip'";
    return parcoursRS(SQLSelect($SQL));
}

/**
 * Fonction qui récupère la liste des materiels d'un ordinateurs en BDD
 * @param $ip
 * @return array
 */
function listerMateriels($ip){
    $SQL = "SELECT Carte_Reseau, Processeur, Disque_Dur FROM ordinateurs WHERE IP = '$ip'";
    return parcoursRS(SQLSelect($SQL));
}

/**
 * Fonction qui charge le contenu des logs d'un ordinateur
 * @param $ip
 * @return string
 */
function chargerLogsBDD($ip){
    $SQL = "SELECT LOG FROM ordinateurs WHERE IP = '$ip'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui écrit un texte dans les logs d'un ordinateur
 * @param $ip
 * @param $text
 */
function ecrireLogsBDD($ip, $text){
    $SQL = "UPDATE ordinateurs SET LOG = '$text' WHERE IP = '$ip'";
    SQLUpdate($SQL);
}

/**
 * Fonction qui augmente le niveau de +1 dans la base de donnée
 * @param $col
 * @param $ip
 */
function augmenterNiveauBDD($col, $ip){
    $SQL = "SELECT $col FROM ordinateurs WHERE IP = '$ip'";
    $niv = SQLGetChamp($SQL) + 1;

    $SQL = "UPDATE ordinateurs SET $col = '$niv' WHERE IP = '$ip'";
    SQLUpdate($SQL);
}

/**
 * Fonction qui permet de récupérer un niveau d'un logiciel/materiel dans la BDD
 * @param $ip
 * @param $col
 * @return int
 */
function recupNiveauMatBDD($ip, $col){
    $SQL = "SELECT $col FROM ordinateurs WHERE IP = '$ip'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui permet de définir le niveau d'un logiciel/materiel dans la BDD
 * @param $ip
 * @param $logi
 * @param $niv
 */
function setNiveauMatBDD($ip, $logi, $niv){
    $SQL = "UPDATE ordinateurs SET $logi = '$niv' WHERE IP = '$ip'";
    SQLUpdate($SQL);
}

/**
 * Fonction qui permet de renvoyer jusqu'à X IP aléatoirement de la BDD à l'exception de celle passée en paramètre
 * @param $ip
 * @param $limit
 * @return array
 */
function recupRandomIP($ip, $limit){
    $SQL = "SELECT IP FROM ordinateurs WHERE IP != '$ip' ORDER BY RAND() LIMIT $limit";
    return parcoursRs(SQLSelect($SQL));
}

/********************************************************************************************
 *                      Les fonctions du sous module téléchargement                         *
 *******************************************************************************************/

/**
 * Fonction qui renvoie l'ID d'un ordinateur à partir de son adresse IP
 * @param $ip
 * @return int
 */
function recupIDOrdiBDD($ip){
    $SQL = "SELECT ID_Ordinateurs FROM ordinateurs WHERE IP = '$ip'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui enregistre un nouveau téléchargement en BDD
 * @param $idOrdi
 * @param $logi
 * @param $niv
 * @return int
 */
function ajoutTelechargementBDD($idOrdi, $logi, $niv){
    $SQL = "INSERT INTO telechargements(ID_Ordinateurs, Logiciel, Niveau) VALUES ('$idOrdi', '$logi', '$niv')";
    return SQLInsert($SQL);
}

/**
 * Fonction qui permet de récupérer les téléchargements d'un ordinateur spécifique
 * @param $idOrdi
 * @return array
 */
function recupTelechargementsBDD($idOrdi){
    $SQL = "SELECT Logiciel, Niveau FROM telechargements WHERE ID_Ordinateurs = '$idOrdi'";
    return parcoursRs(SQLSelect($SQL));
}

/**
 * Fonction qui permet de savoir si un logiciel a été téléchargé et renvoie son niveau
 * @param $idOrdi
 * @param $logi
 * @return int
 */
function recupLogiTelechargeBDD($idOrdi, $logi){
    $SQL = "SELECT Niveau FROM telechargements WHERE ID_Ordinateurs = '$idOrdi' AND Logiciel = '$logi'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui permet de retirer un téléchargement de la BDD
 * @param $idOrdi
 * @param $logi
 * @param $niv
 */
function retirerTelechargementBDD($idOrdi, $logi, $niv){
    $SQL = "DELETE FROM telechargements WHERE ID_Ordinateurs = '$idOrdi' AND Logiciel = '$logi' AND Niveau = '$niv'";
    SQLDelete($SQL);
}

/********************************************************************************************
 *                      Les fonctions du sous module virus                                  *
 *******************************************************************************************/

/**
 * Fonction qui permet d'ajouter un virus en BDD
 * @param $idOrdi
 * @param $idUser
 * @param $vir
 * @param $niv
 * @return int
 */
function ajouterVirusBDD($idOrdi, $idUser, $vir, $niv){
    $SQL = "INSERT INTO virus(ID_Ordinateurs, ID_Joueurs, Type_Virus, Niveau) VALUES ('$idOrdi', '$idUser', '$vir', '$niv')";
    return SQLInsert($SQL);
}

/**
 * Fonction qui permet de récupérer la liste des virus sur un ordinateur
 * @param $idOrdi
 * @return array
 */
function recupVirusBDD($idOrdi){
    $SQL = "SELECT ID_Virus, Type_Virus, Niveau FROM virus WHERE ID_Ordinateurs = '$idOrdi'";
    return parcoursRs(SQLSelect($SQL));
}

/**
 * Fonction qui renvoie la liste des Miners appartenant à un joueur particulier
 * @param $idUser
 * @return array
 */
function recupMinerUploade($idUser){
    $SQL = "SELECT ID_Ordinateurs, Niveau FROM virus WHERE ID_Joueurs = '15' AND Type_Virus = 'Miner'";
    return parcoursRs(SQLSelect($SQL));
}

/**
 * Fonction qui renvoie la liste des virus sur un ordinateur appartenant à un joueur particulier
 * @param $idOrdi
 * @param $idUser
 * @return array
 */
function recupVirusUploade($idOrdi, $idUser){
    $SQL = "SELECT Type_Virus, Niveau FROM virus WHERE ID_Ordinateurs = '$idOrdi' AND ID_Joueurs = '$idUser'";
    return parcoursRs(SQLSelect($SQL));
}

/**
 * Fonction qui permet de récupérer l'identifiant en BDD d'un virus
 * @param $idOrdi
 * @param $idUser
 * @param $vir
 * @param $niv
 * @return int
 */
function recupVirusIDBDD($idOrdi, $idUser, $vir){
    $SQL = "SELECT ID_Virus FROM virus WHERE ID_Ordinateurs = '$idOrdi' AND ID_Joueurs = '$idUser' AND Type_Virus = '$vir'";
    return SQLGetChamp($SQL);
}

/**
 * Fonction qui vérifie si un virus existe en BDD
 * @param $idVirus
 * @return bool
 */
function virusExistsBDD($idVirus){
    $SQL = "SELECT 1 FROM virus WHERE ID_Virus = '$idVirus'";
    
    if(SQLGetChamp($SQL))
        return true;
    return false;
}

/**
 * Fonction qui permet de retirer un virus de la BDD
 * @param $idVirus
 */
function retirerVirusBDD($idVirus){
    $SQL = "DELETE FROM virus WHERE ID_Virus = '$idVirus'";
    SQLDelete($SQL);
}