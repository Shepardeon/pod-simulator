<?php

/**
 * @file libSQL.php
 * Ce fichier définit les fonctions permettant d'effectuer des requêtes dans la base de données.
 * Il reprend des fonctions de "malibSQL.pdo.php" vu en TP.
 */


/**
 * Permet d'exécuter une requête SQL de type Update.
 * Elle renvoie le nombre de modifications ou faux en cas de problème.
 * @param $sql string
 * @return int|false
 */
function SQLUpdate($sql){
    global $BDD_host;
    global $BDD_base;
    global $BDD_user;
    global $BDD_password;

    try {
        $dbh = new PDO("mysql:host=$BDD_host;dbname=$BDD_base", $BDD_user, $BDD_password);
    } catch (PDOException $e) {
        die("<span style=\"color:red;\">SQLUpdate/Delete: Erreur de connexion : " . $e->getMessage() . "</span>");
    }

    $dbh->exec("SET CHARACTER SET utf8");
    $res = $dbh->query($sql);
    if ($res === false) {
        $e = $dbh->errorInfo();
        die("<span style=\"color:red;\">SQLUpdate/Delete: Erreur de requete : " . $e[2] . "</span>");
    }

    $dbh = null;
    $nb = $res->rowCount();
    if ($nb != 0) return $nb;
    else return false;
}

/**
 * Permet d'exécuter une requette de suppression en appellant la fonction SQLUpdate.
 * @param $sql string
 */
function SQLDelete($sql){
    SQLUpdate($sql);
}

/**
 * Permet d'insérer des données dans la base de données
 * @param $sql string
 * @return string
 */
function SQLInsert($sql){
    global $BDD_host;
    global $BDD_base;
    global $BDD_user;
    global $BDD_password;

    try {
        $dbh = new PDO("mysql:host=$BDD_host;dbname=$BDD_base", $BDD_user, $BDD_password);
    } catch (PDOException $e) {
        die("<span style=\"color:red;\">SQLInsert: Erreur de connexion : " . $e->getMessage() . "</span>");
    }

    $dbh->exec("SET CHARACTER SET utf8");
    $res = $dbh->query($sql);
    if ($res === false) {
        $e = $dbh->errorInfo();
        die("<span style=\"color:red;\">SQLInsert: Erreur de requete : " . $e[2] . "</span>");
    }

    $lastInsertId = $dbh->lastInsertId();
    $dbh = null;
    return $lastInsertId;
}

/**
 * Effectue une requête SELECT pour récuprérer un seul champ.
 * renvoie "faux" si aucun résultat ou le champ s'il y a un résultat.
 * @param $sql string
 * @return bool|string
 */
function SQLGetChamp($sql){

    global $BDD_host;
    global $BDD_base;
    global $BDD_user;
    global $BDD_password;

    try {
        $dbh = new PDO("mysql:host=$BDD_host;dbname=$BDD_base", $BDD_user, $BDD_password);
    } catch (PDOException $e) {
        die("<span style=\"color:red;\">SQLGetChamp: Erreur de connexion : " . $e->getMessage() . "</span>");
    }

    $dbh->exec("SET CHARACTER SET utf8");
    $res = $dbh->query($sql);
    if ($res === false) {
        $e = $dbh->errorInfo();
        die("<span style=\"color:red;\">SQLGetChamp: Erreur de requete : " . $e[2] . "</span>");
    }

    $num = $res->rowCount();
    $dbh = null;

    if ($num==0) return false;

    $res->setFetchMode(PDO::FETCH_NUM);

    $ligne = $res->fetch();
    if ($ligne == false) return false;
    else return $ligne[0];
}

/**
 * Exécute un SELECT dans la base de données.
 * renvoie "faux" si pas de résultat et la ressource sinon.
 * @param $sql string
 * @return bool|PDOStatement
 */
function SQLSelect($sql){
    global $BDD_host;
    global $BDD_base;
    global $BDD_user;
    global $BDD_password;

    try {
        $dbh = new PDO("mysql:host=$BDD_host;dbname=$BDD_base", $BDD_user, $BDD_password);
    } catch (PDOException $e) {
        die("<span style=\"color:red;\">SQLSelect: Erreur de connexion : " . $e->getMessage() . "</span>");
    }

    $dbh->exec("SET CHARACTER SET utf8");
    $res = $dbh->query($sql);
    if ($res === false) {
        $e = $dbh->errorInfo();
        die("<span style=\"color:red;\">SQLSelect: Erreur de requete : " . $e[2] . "</span>");
    }

    $num = $res->rowCount();
    $dbh = null;

    if ($num==0) return false;
    else return $res;
}

/**
 * Parcours les résultats d'une requête SQL et les renvoies sous la forme d'un tableau associatif.
 * @param $result PDOStatement
 * @return array
 */
function parcoursRS($result){
    if  ($result == false) return array();

    $result->setFetchMode(PDO::FETCH_ASSOC);
    while ($ligne = $result->fetch())
        $tab[]= $ligne;

    return $tab;
}