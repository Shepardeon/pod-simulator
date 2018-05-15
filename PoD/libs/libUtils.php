<?php
/**
 * @file libUtils.php
 * Ce fichier définit des fonctions utilitaires pour l'ensemble de notre applicatin web.
 * Il s'inspire du fichier maLibUtils.php vu en TP
 */

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