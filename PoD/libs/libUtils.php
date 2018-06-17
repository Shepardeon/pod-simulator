<?php
/**
 * @file libUtils.php
 * Ce fichier définit des fonctions utilitaires pour l'ensemble de notre applicatin web.
 * Il s'inspire du fichier maLibUtils.php vu en TP
 */

include_once("modele.php");
include_once("libJoueur.php");

/**
 * Cette fonction permet de valider une chaine en vérifiant qu'elle est bien définie et non nulle.
 * @param string $nom
 * @param string $type
 * @return array|bool|string
 */
function valider($nom, $type = "REQUEST"){
    switch($type)
    {
        case 'REQUEST':
            if(isset($_REQUEST[$nom]) && !($_REQUEST[$nom] == ""))
                return proteger($_REQUEST[$nom]);
            break;
        case 'GET':
            if(isset($_GET[$nom]) && !($_GET[$nom] == ""))
                return proteger($_GET[$nom]);
            break;
        case 'POST':
            if(isset($_POST[$nom]) && !($_POST[$nom] == ""))
                return proteger($_POST[$nom]);
            break;
        case 'COOKIE':
            if(isset($_COOKIE[$nom]) && !($_COOKIE[$nom] == ""))
                return proteger($_COOKIE[$nom]);
            break;
        case 'SESSION':
            if(isset($_SESSION[$nom]) && !($_SESSION[$nom] == ""))
                return $_SESSION[$nom];
            break;
        case 'SERVER':
            if(isset($_SERVER[$nom]) && !($_SERVER[$nom] == ""))
                return $_SERVER[$nom];
            break;
    }
    return false;
}

/**
 * Cette fonction permet d'accéder au contenu d'un tableau GET, POST, SESSION...
 * Il vérifie par la même occasion l'existance de la chaine et qu'elle ne soit pas nulle.
 * @param string $nom
 * @param bool|int|string $defaut
 * @param string $type
 * @return array|bool|string
 */
function getValue($nom, $defaut = false, $type = "REQUEST"){
    if (($resultat = valider($nom,$type)) === false)
        $resultat = $defaut;

    return $resultat;
}

/**
 * Evite les injections SQL en protegeant les apostrophes par des '\'
 * Attention : SQL server utilise des doubles apostrophes au lieu de \'
 * ATTENTION : LA PROTECTION N'EST EFFECTIVE QUE SI ON ENCADRE TOUS LES ARGUMENTS PAR DES APOSTROPHES
 * Y COMPRIS LES ARGUMENTS ENTIERS !!
 * @param $str
 * @return array|string
 */
function proteger($str){
    if (is_array($str))
    {
        $nextTab = array();
        foreach($str as $cle => $val)
        {
            $nextTab[$cle] = addslashes($val);
        }
        return $nextTab;
    }
    else
        return addslashes ($str);
    //return str_replace("'","''",$str); 	//utile pour les serveurs de bdd Crosoft
}

/**
 * Permet d'afficher le contenu d'un tableau en PHP
 * @param array $tbl
 */
function tprint($tbl){
    echo "<pre>\n";
    print_r($tbl);
    echo "</pre>\n";
}

/**
 * Permet de générer une chaine de caractère d'une taille donnée
 * @param int $l
 * @return string
 */
function randStr($l){
    $str = "";
    $listeChars = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
    $max = count($listeChars) - 1;

    for($i = 0; $i < $l; $i++){
        $rand = mt_rand(0, $max);
        $str .= $listeChars[$rand];
    }

    return $str;
}

/**
 * Fonction permettant d'envoyer un mail de confirmation à l'utilisateur
 * renvoie faux si le mail n'a pas été envoyé
 * @param $login
 * @param $mail
 * @param $link
 * @return bool
 */
function envoyerMailInscripion($login, $mail, $link){
    $subject = "P0D - Simulator - Confirmez vore inscription";

    $message = "<html><body>
                    <h1>PoD Simulator Inscription</h1>
                    <p>Bonjour $login, vous venez de vous inscrire sur PoD !</p>
                    <p>Merci de confirmer votre inscription en cliquant sur ce lien : <a href='$link'>$link</a></p>
                </body></html>";
    $message = wordwrap($message, 70, "\r\n");

    $headers = array(
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/html; charset=utf-8',
        'From' => 'thomas.de-maen@ig2i.centralelille.fr',
        'Reply-To' => 'thomas.de-maen@ig2i.centralelille.fr',
        'X-Mailer' => 'PHP/' . phpversion()
    );

    if(!mail($mail, $subject, $message, $headers))
        return false;
}

/**
 * Fonction qui permet de rediriger l'utilisateur avant d'accéder à une page
 * Nécessite d'être utilisée avant du code HTML.
 * @param $path
 */
function rediriger($path){
    header("Location: $path");
}

/**
 * Fonction qui enregistre en session un message pour l'utilisateur
 * @param $type
 * @param $message
 */
function enregistrerMessage($message, $type="success"){
    $_SESSION["msgType"] = $type;
    $_SESSION["msgText"] = $message;
}

/**
 * Fonction qui affiche un message enregistré en session s'il existe
 * puis le supprime
 */
function ecrireMessage(){
    if(($type = valider("msgType", "SESSION")) && ($msg = valider("msgText", "SESSION"))){
        echo "<div class='alert alert-$type'>$msg</div>";
        $_SESSION["msgType"] = "";
        $_SESSION["msgText"] = "";
    }
}

/**
 * Fonction permettant d'afficher le classement des nb premiers joueurs en fonction de leur niveau
 * @param $nb
 */
function afficherClassement($nb){
    $joueurs = listerUtilisateurs($nb, "DESC");
    $rank = 1;
    $style = "border:1px solid black; padding:10px; font-size:1.05em;";

    echo "<ul style='margin-bottom: 75px;'>";
    foreach($joueurs as $joueur){
        foreach($joueur as $key => $value){
            if($key === "Pseudo" && $rank === 1)
                echo "<li class='row' style='$style'><span class='col-md-10'><b>$rank</b> - $value</span>";
            elseif($key === "Pseudo" && $rank !== 1)
                echo "<li class='row' style='$style border-top:none;'><span class='col-md-10'><b>$rank</b> - $value</span>";
            else
                echo "<span class='col-md-2 text-right'>Niveau $value</span></li>";
        }
        $rank++;
    }
    echo "</ul>";
}

/**
 * Fonction qui renvoie un nom de virus aléatoirement en fonction de son type
 * @param $type
 * @return string
 */
function recupRandomVirName($type){
    $minerNames = array(
        "Gen:Miner",
        "Win64::Miner",
        "MALV-I2CM",
        "Virus.Gen.Miner",
        "Win32/Mining",
        "generic.malv-min",
        "Linux.coinminer",
        "Trojan:Script-Miner"
    );

    $bdoorNames = array(
        "Win32/Malware-gen",
        "Trojan::Backdoor",
        "MALV-BD",
        "Virus.Malv.Bdoor",
        "generic.backdoor",
        "Linux.remoteaccess",
        "Win64::RemoteAccess-Malv",
        "PUA.HackTool.RAT"
    );

    if($type === "Miner")
        return $minerNames[array_rand($minerNames)];
    else
        return $bdoorNames[array_rand($bdoorNames)];
}

/**
 * Fonction qui permet à tous les joueurs de gagner leurs revenus
 */
function jourDePaie(){
    $joueurs = recupListeJoueurs();

    echo "<pre>";
    foreach($joueurs as $joueur){
        $id = $joueur["ID_Joueurs"];
        updateRevenusJoueur($id);
        setFonds($id, recupFonds($id) + recupRevenusJoueur($id));
    }
    echo "</pre>";
}