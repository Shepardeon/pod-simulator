<?php
/**
 * @file libJoueur.php
 * Ce fichier définit les fonctions qui peuvent se rapporter à tous les joueurs de notre application WEB.
 */

include_once("modele.php");
include_once("libUtils.php");
include_once("libOrdinateur.php");

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
    if($chaine === recupererChaine($idUser) && !testUtilisateurValide($idUser)){
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
        $_SESSION["ip"] = recupIPLocal($id);

        return true;
    }

    return false;
}

/**
 * Fonction qui récupère les fonds d'un joueur
 * @param $id
 * @return int
 */
function recupFondsJoueur($id){
    return recupFonds($id);
}

/**
 * Fonction qui récupère les fonds sécurisés d'un joueur
 * @param $id
 * @return int
 */
function recupFondsSecJoueur($id){
    return recupFondsSec($id);
}

/**
 * Fonction qui permet au joueur de sécuriser des fonds s'il le peut
 * @param $id
 * @param $niv
 * @return int
 */
function securiserFonds($id, $niv){
    $fondsSec = recupFondsSec($id);
    $fonds = recupFonds($id);
    $fondsSecMax = 1000*$niv - $fondsSec;

    if($fonds - $fondsSecMax >= 0){
        $montant = $fondsSecMax;
    }else{
        $montant = $fonds;
    }

    $fonds -= $montant;
    $fondsSec += $montant;

    setFonds($id, $fonds);
    setFonds($id, $fondsSec, "sec");

    return $montant;
}

/**
 * Fonction qui permet de transférer l'intégralités des fonds non sécurisés d'un joueur à l'autre
 * Renvoie les fonds s'ils ont été transférés et faux sinon
 * @param $vol
 * @param $o
 * @return bool
 */
function volerFonds($vol, $o){
    $fonds = recupFonds($o);
    
    if($fonds > 0){
        setFonds($vol, recupFonds($vol) + $fonds);
        setFonds($o, 0);
        return $fonds;
    }

    return false;
}

/**
 * Fonction qui gère l'achat d'un logiciel/matériel en fonction de son prix
 * Renvoie "vrai" si l'achat s'est effectué et "faux" sinon
 * @param $id
 * @param $prix
 * @return bool
 */
function acheter($id, $prix){
    $fondsSec = recupFondsSec($id);
    $fonds = recupFonds($id);

    if($fonds+$fondsSec - $prix >= 0){
        // On a assez d'argent
        if($fonds - $prix >= 0)
            setFonds($id, $fonds - $prix);
        else{
            $prix -= $fonds;
            setFonds($id, 0);
            setFonds($id, $fondsSec - $prix, "sec");
        }

        return true;
    }
    else
        return false; // On en a pas assez
}

/**
 * Fonction qui récupère le niveau d'un joueur
 * @param $id
 */
function recupNiveau($id){
    return recupNiveauJoueur($id);
}

/**
 * Fonction qui définie le niveau d'un joueur en fonction de son niveau actuel
 * @param $id
 */
function setNiveauJoueur($id){
    setNiveauJoueurBDD($id, recupNiveauJoueur($id));
}

function updateRevenusJoueur($id){
    $revenus = 10 * recupNiveaMat(recupIPLocal($id), "Processeur") + recupSommeGainVirus($id);
    setRevenusBDD($id, $revenus);
}

/**
 * Fonction qui affiche les fonds d'un joueur
 * @param $id 
 */
function afficherFonds($id){
    $fonds = recupFonds($id);
    echo "$fonds I2C";
}

/**
 * Fonction qui affiche les fonds sécurisés d'un joueur
 * @param $id 
 */
function afficherFondsSec($id){
    $fonds = recupFondsSec($id);
    echo "$fonds I2C";
}

/**
 * Fonction qui affiche les fonds totaux d'un joueur
 * @param $id 
 */
function afficherFondsTotaux($id){
    $fonds = recupFondsTotaux($id);
    echo "$fonds I2C";
}

/**
 * Fonction qui affiche le niveau d'un joueur
 * @param $id
 */
function afficherNiveau($id){
    $niv = recupNiveauJoueur($id);
    echo "Niveau joueur : $niv";
}