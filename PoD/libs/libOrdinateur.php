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
            && recupNiveaMat(valider("ip", "SESSION"), str_replace(" ", "_", $logiciel)) > recupNiveaMat(recupIPLocal(valider("id", "SESSION")), str_replace(" ", "_", $logiciel))
            && recupNiveaMat(valider("ip", "SESSION"), str_replace(" ", "_", $logiciel)) > logicielTelecharge(recupIPLocal(valider("id", "SESSION")), str_replace(" ", "_", $logiciel)))
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

/**
 * Fonction qui renvoie le niveau du logiciel si celui-ci est téléchargé sur la machine
 * @param $ip
 * @param $logi
 * @return int
 */
function logicielTelecharge($ip, $logi){
    return recupLogiTelechargeBDD(recupIDOrdiBDD($ip), $logi);
}

/**
 * Fonction qui permet de supprimer un logiciel de la liste des téléchargements d'un joueur
 * @param $ip
 * @param $logi
 * @param $niv
 */
function supprimerLogiciel($ip, $logi, $niv){
    retirerTelechargementBDD(recupIDOrdiBDD($ip), $logi, $niv);
}

/**
 * Fonction qui permet à un joueur de télécharger un logiciel sur son ordinateur
 * @param $ip
 * @param $logi
 * @param $niv
 */
function telechargerLogiciel($ip, $logi, $niv){
    $idOrdi = recupIDOrdiBDD($ip);

    // Avons-nous déjà téléchargé ce logiciel ?
    if($oldNiv = logicielTelecharge($ip, $logi)){
        // On retire l'ancien logiciel
        retirerTelechargementBDD($idOrdi, $logi, $oldNiv);
        // On ajoute le nouveau
        ajoutTelechargementBDD($idOrdi, $logi, $niv);
    }
    else{
        // On ajoute simplement le nouveau logiciel téléchargé
        ajoutTelechargementBDD($idOrdi, $logi, $niv);
    }
}

function crackerLogiciel($ip, $logi, $niv){
    $idOrdi = recupIDOrdiBDD($ip);

    if(logicielTelecharge($ip, $logi)){
        retirerTelechargementBDD($idOrdi, $logi, $niv);
        setNiveauMatBDD($ip, $logi, $niv);
        return true;
    }
    return false;
}

/**
 * Fonction qui affiche les téléchargements d'une machine donnée
 * @param $ip
 */
function afficherTelechargements($ip){
    $idOrdi = recupIDOrdiBDD($ip);
    $telechargements = recupTelechargementsBDD($idOrdi);

    echo "<ul style='max-width:40%; margin-bottom:10%;'>";
    foreach($telechargements as $telechargement)
        foreach($telechargement as $k => $v)
            if($k === "Logiciel"){
                $logi = $v;
                echo "<li class='row' style='font-size:1.1em;'><span class='col-md-6'>".str_replace("_", " ", $logi)."</span>";
            }
            else{
                echo "<span class='col-md-6'>Niveau $v</span></li>";
                echo "<div style='margin:20px auto 15px auto;'><a href='controleur.php?action=cracker&&logi=$logi&&niv=$v' style='margin-left:25%;' class='btn btn-outline-success'>Cracker</a>
                <a href='controleur.php?action=supprT&&logi=$logi&&niv=$v' style='margin-left:5%;' class='btn btn-outline-danger'>Supprimer</a></div>";
            }
    echo "</ul>";
}

  ////////////////////////////////////////////////////////////////////////////////////////////
 ////////////////////////////// FONCTIONS DU SOUS MODULE VIRUS //////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Fonction qui permet d'uploader un virus sur la machine d'un autre joueur
 * Elle renvoie vraie si le joueur ne triche pas et faux sinon
 * @param $idOrdi
 * @param $idUser
 * @param $vir
 * @param $niv
 * @return bool
 */
function uploadVirus($ip, $idUser, $vir, $niv){
    $idOrdi = recupIDOrdiBDD($ip);

    $logiciel = array(
        "Miner" => "Generateur_de_Miner",
        "Backdoor" => "Generateur_de_Backdoor"
    );

    if($niv == recupNiveaMat(recupIPLocal($idUser), $logiciel[$vir])){
        $viruses = recupVirusUploade($idOrdi, $idUser);

        foreach($viruses as $virus)
            if($virus["Type_Virus"] === $vir)
                retirerVirusBDD(recupVirusIDBDD($idOrdi, $idUser, $vir));

        ajouterVirusBDD($idOrdi, $idUser, $vir, $niv);
        return true;
    }

    return false;
}

function supprimerVirus($idVir){
    if(virusExistsBDD($idVir)){
        retirerVirusBDD($idVir);
        return true;
    }

    return false;
}

/**
 * Fonction qui permet de calculer la somme des gains d'un joueur
 * @param $idUser
 * @return int
 */
function recupSommeGainVirus($idUser){
    $miners = recupVirusUploadeUser($idUser);
    $sum = 0;
    foreach($miners as $miner)
        $sum += 5 * recupNiveaMat(recupIPDepuisID($miner["ID_Ordinateurs"]), "Processeur") * $miner["Niveau"];
    return $sum;
}

/**
 * Fonction qui affiche la base de donnée des virus actifs d'un joueur
 */
function afficherBDD($idUser){
    $viruses = recupVirusUploadeUser($idUser, "");

    echo "<ul style='padding:20px 0 0 0;'>";
    foreach($viruses as $virus){
        $ip = recupIPDepuisID($virus["ID_Ordinateurs"]);

        if($virus["Type_Virus"] === "Miner")
            echo "<li class='row' style='font-size:1.1em; padding-bottom:15px; margin-left:10%;'>$ip::".$virus["Type_Virus"]."</li>";
        else{
            echo "<li class='row' style='font-size:1.1em; margin-left:10%;'>$ip::".$virus["Type_Virus"];
            echo "<div style='margin:-5px auto 15px auto;'><a href='controleur.php?action=attaquer&&IP=$ip' class='btn btn-outline-success'>Connexion</a></div>";
            echo "</li>";
        }
    }
    echo "</ul>";
}

function afficherVirusSurMachine($ip){
    $idOrdi = recupIDOrdiBDD($ip);
    $viruses = recupVirusBDD($idOrdi);
    $nivAV = recupNiveaMat($ip, "Anti_Virus");

    echo "<ul>";
    foreach($viruses as $virus){
        if($nivAV >= $virus["Niveau"]){
            echo "<li class='row' style='font-size:1.1em;'>".recupRandomVirName($virus["Type_Virus"])." ----> ".$virus["Type_Virus"]." niveau ".$virus["Niveau"]."</li>";
            echo "<div class='text-right' style='padding:15px;'><a href='controleur.php?action=supprimerAV&&vir=".$virus["ID_Virus"]."&&prop=".$virus["ID_Joueurs"]."&&type=".$virus["Type_Virus"]."' class='btn btn-outline-danger'>Supprimer</a></div>";
        }
    }
    echo "</ul>";
}

/**
 * Fonction qui gère l'affichage des virus disponnibles sur une machine adverse pour un joueur
 * @param $ip
 * @param $idUser
 */
function afficherVirus($ip, $idUser){
    $logiciels = listerLogiciels(recupIPLocal($idUser));
    $idOrdi = recupIDOrdiBDD($ip);

    $dejaBD = false;
    $dejaMI = false;
    
    $viruses = recupVirusUploade($idOrdi, $idUser);

    foreach($viruses as $virus)
        if($virus["Type_Virus"] === "Backdoor" && $virus["Niveau"] >= recupNiveaMat(recupIPLocal($idUser), "Generateur_de_Backdoor"))
            $dejaBD = true;
        elseif($virus["Type_Virus"] === "Miner" && $virus["Niveau"] >= recupNiveaMat(recupIPLocal($idUser), "Generateur_de_Miner"))
            $dejaMI = true;

    echo "<ul style='max-width:40%; margin-bottom:10%;'>";
    foreach($logiciels[0] as $logiciel => $niveau){
        if($logiciel === "Generateur_de_Miner")
            $logiciel = "Miner";
        elseif($logiciel === "Generateur_de_Backdoor")
            $logiciel = "Backdoor";
        
        if(($logiciel === "Miner" && !$dejaMI) || ($logiciel === "Backdoor" && !$dejaBD)){
            echo "<li class='row' style='font-size:1.1em;'><span class='col-md-6'>$logiciel</span><span class='col-md-6'>Niveau $niveau</span>";
            echo "<div style='margin:20px auto 30px 55%;'><a href='controleur.php?action=upload&&vir=$logiciel&&niv=$niveau' class='btn btn-outline-danger'>Upload</a></div></li>";
        }  
    }
    echo "</ul>";
}