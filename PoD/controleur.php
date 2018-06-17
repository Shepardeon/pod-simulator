<?php
session_start();

include_once("libs/libUtils.php");
include_once("libs/libJoueur.php");
include_once("libs/libOrdinateur.php");

// On reçoit une action sur notre contrôleur
if($action = valider("action")){
    switch($action){
        /* On inscrit un nouvel utilisateur dans la base de données */
        case "inscription":
                if($login = valider("login", "POST"))
                    if($pass = valider("pass", "POST"))
                        if($mail = valider("mail", "POST")){
                            if(inscrireJoueur($login, $pass, $mail)){
                                enregistrerMessage("Votre compte a bien été créé ! Un mail de confirmation a été envoyé à l'adresse <b>$mail</b>.");
                                rediriger("connexion.php");
                            }
                            else{
                                enregistrerMessage("Ce pseudo ou ce mail a déjà été utilisé sur ce site.", "danger");
                                rediriger("inscription.php");
                            }
                        }
        break;

        /* On valide un nouvel utilisateur ici */
        case "valider":
                if($id = valider("id")){
                    if($chaine = valider("chaine")){
                        if(validerJoueur($id, $chaine)){
                            enregistrerMessage("Votre compte est <b>actif</b> ! Vous pouvez à présent vous <b>connecter</b>.");
                            creerOrdinateur($id);
                            rediriger("connexion.php");
                        }
                        else{
                            enregistrerMessage("Votre compte est déjà validé.", "danger");
                            rediriger("connexion.php");
                        }
                    }
                }
        break;

        /* On connecte l'utilisateur et on créer sa session */
        case "connexion":
                if($login = valider("login"))
                    if($pass = valider("pass"))
                    {
                        if(connecterJoueur($login, $pass)){
                            if(valider("remember")){
                                setcookie("login", $login, time()+60*60*24*30);
                                setcookie("pass", $pass, time()+60*60*24*30);
                                setcookie("remember", true, time()+60*60*24*30);
                            }
                            else{
                                setcookie("login", "", time()-3600);
                                setcookie("pass", "", time()-3600);
                                setcookie("remember", false, time()-3600);
                            }
                            rediriger("jeu.php");
                        }
                        else{
                            enregistrerMessage("Combinaison pseudo / mot de passe invalide.", "danger");
                            rediriger("connexion.php");
                        }
                    }
        break;

        /* On déconnecte l'utilisateur et on supprime sa session */
        case "deconnexion":
                session_destroy();
                rediriger("connexion.php");
        break;

        /* On édite le fichier de logs d'un ordinateur */
        case "editLogs":
                if(($ip = valider("ip", "SESSION")) && ($logs = valider("logs"))){
                    ecrireLogs($ip, $logs);
                    enregistrerMessage("Les logs de la machine $ip ont été mis à jour.");
                    rediriger("jeu.php?view=logs");
                }
                else{
                    enregistrerMessage("Une erreur est survenue lors de l'enregistrement des logs, veuillez rééssayer.", "danger");
                    rediriger("jeu.php?view=log");
                }
        break;

        /* On sécurise des fonds sur notre machine */
        case "secFonds":
                if(($id = valider("id", "SESSION")) && ($niv = recupNiveaMat(recupIPLocal($id), "Porte_Feuille"))){
                    $montant = securiserFonds($id, $niv);

                    if($montant > 0){
                        enregistrerMessage("Vous avez sécuriser <b>$montant</b> I2C sur votre compte.");
                        rediriger("jeu.php?view=money");
                    }
                    else{
                        enregistrerMessage("Vous n'avez pas de fonds sécurisable.", "danger");
                        rediriger("jeu.php?view=money");
                    }
                }
        break;

        /* On vole des fonds à un autre joueur */
        case "volFonds":
                if(($id = valider("id", "SESSION")) && ($o = recupProprio(valider("ip", "SESSION")))){
                    if($fonds = volerFonds($id, $o)){
                        enregistrerMessage("Vous avez transféré <b>$fonds</b> I2C vers la machine <b>".recupIPLocal($id)."</b>.");
                        ajouterLogs(valider("ip", "SESSION"), "[INFO] - Transfert de $fonds I2C vers " . recupIPLocal($id));
                        rediriger("jeu.php?view=money");
                    }
                    else{
                        enregistrerMessage("Aucun fonds à transférer.", "danger");
                        rediriger("jeu.php?view=money");
                    }
                }
        break;
        
        /* On achète un nouveau logiciel */
        case "acheterL":
                if(($id = valider("id", "SESSION")) && ($logi = valider("logiciel")) && ($prix = valider("prix"))){
                    if(acheter($id, $prix)){
                        augmenterNiveau($logi, $id);
                        setNiveauJoueur($id);
                        enregistrerMessage("Vous avez acheter un nouveau <b>".str_replace("_", " ", $logi)."</b> pour <b>$prix I2C</b>.");
                        rediriger("jeu.php?view=logiciels");
                    }
                    else{
                        enregistrerMessage("Vous n'avez pas assez de fonds pour acheter un nouveau <b>".str_replace("_", " ", $logi)."</b>.", "danger");
                        rediriger("jeu.php?view=logiciels");
                    }
                }
        break;

        /* On achète un nouveau materiel */
        case "acheterM":
                if(($id = valider("id", "SESSION")) && ($mat = valider("mat")) && ($prix = valider("prix"))){
                    if(acheter($id, $prix)){
                        if($mat === "Processeur")
                            updateRevenusJoueur($id);
                        augmenterNiveau($mat, $id);
                        setNiveauJoueur($id);
                        enregistrerMessage("Vous avez acheter un nouveau <b>".str_replace("_", " ", $mat)."</b> pour <b>$prix I2C</b>.");
                        rediriger("jeu.php?view=materiels");
                    }
                    else{
                        enregistrerMessage("Vous n'avez pas assez de fonds pour acheter un nouveau <b>".str_replace("_", " ", $mat)."</b>.", "danger");
                        rediriger("jeu.php?view=materiels");
                    }
                }
        break;
        
        /* On scan le réseau en totalité */
        case "scan":
                if($id = valider("id", "SESSION")){
                    $_SESSION["nouveauScan"] = true;
                    rediriger("jeu.php?view=scanner");
                }
        break;

        /* On scan un joueur en particulier */
        case "scanI":
                if(($id = valider("id", "SESSION")) && ($ip = valider("IP"))){
                    if(existsIP($ip)){
                        $_SESSION["nouveauScanI"] = true;
                        $_SESSION["IScan"] = $ip;
                        ajouterLogs($ip, "[ALERTE] - Scan intense en provenance de " . valider("ip", "SESSION"));
                        // TODO : Ajouter une gestion du temps avant de révéler le résultat du scan

                        rediriger("jeu.php?view=scanner");
                    }
                    else{
                        enregistrerMessage("L'adresse IP <b>$ip</b> n'est pas attribuée", "danger");
                        rediriger("jeu.php?view=scanner");
                    }
                }
        break;

        /* On scan avec l'antivirus */
        case "scanAV":
                if($id = valider("id", "SESSION")){
                    $_SESSION["nouveauScan"] = true;
                    rediriger("jeu.php?view=antivirus");
                }
        break;
                
        /* On lance une attaque sur un autre joueur */
        case "attaquer": // TODO : changer le système d'attaque complètement pour utiliser du temps
                if(($id = valider("id", "SESSION")) && ($ip = valider("IP"))){
                    if(existsIP($ip)){
                        ajouterLogs($ip, "[ALERTE] - Connexion externe en provenance de " . valider("ip", "SESSION"));
                        enregistrerMessage("Vous vous êtes bien connecté à la machine <b>$ip</b>");
                        $_SESSION["ip"] = $ip;
                        rediriger("jeu.php?view=status");
                    }
                    else{
                        enregistrerMessage("L'adresse IP <b>$ip</b> n'est pas attribuée", "danger");
                        rediriger("jeu.php?view=attaque");
                    }
                }
        break;

        /* On tente de se déconnecter de la machine d'un autre joueur */
        case "stopAttaque":
                if($id = valider("id", "SESSION")){
                    $ip = recupIPLocal($id);
                    $_SESSION["ip"] = $ip;
                    enregistrerMessage("Vous vous êtes bien connecté à la machine <b>$ip</b>");
                    rediriger("jeu.php?view=status");
                }
        break;
        
        /* On tente de télécharger un logiciel depuis un autre ordinateur */
        case "telecharger":
                if(($id = valider("id", "SESSION")) && ($logi = valider("logi"))){
                    $ip = recupIPLocal($id);
                    $niv = recupNiveaMat(valider("ip", "SESSION"), $logi);
                    telechargerLogiciel($ip, $logi, $niv);
                    enregistrerMessage("Vous avez transféré un <b>".str_replace("_", " ", $logi)."</b> de niveau $niv vers <b>$ip</b>");
                    ajouterLogs(valider("ip", "SESSION"), "[INFO] - Transfert de ".str_replace("_", " ", $logi)." vers $ip");
                    rediriger("jeu.php?view=status");
                }
        break;

        /* On tente de supprimer un fichier téléchargé */
        case "supprT":
                if(($ip = valider("ip", "SESSION")) && ($logi = valider("logi")) && ($niv = valider("niv"))){
                    supprimerLogiciel($ip, $logi, $niv);
                    enregistrerMessage("Vous avez supprimé un <b>".str_replace("_", " ", $logi)."</b> de niveau $niv sur la machine <b>$ip</b>");
                    rediriger("jeu.php?view=telechargement");
                }
        break;

        /* On tente de supprimer un virus sur notre machine */
        case "supprimerAV":
                if(($vir = valider("vir")) && ($prop = valider("prop")) && ($type = valider("type"))){
                    if(supprimerVirus($vir)){
                        $_SESSION["nouveauScan"] = true; // Pour ne pas devoir relancer un scan à chaque fois
                        updateRevenusJoueur($prop);
                        enregistrerMessage("Vous avez supprimé un <b>$type</b> de votre machine");
                        rediriger("jeu.php?view=antivirus");
                    }
                    else{
                        enregistrerMessage("Ce virus n'existe pas.", "danger");
                        rediriger("jeu.php?view=antivirus");
                    }
                }
        break;

        /* On tente de cracker un logiciel téléchargé */
        case "cracker":
                if(($ip = valider("ip", "SESSION")) && ($logi = valider("logi")) && ($niv = valider("niv"))){
                    // TODO : Ajouter un système de temps
                    if(crackerLogiciel($ip, $logi, $niv)){
                        enregistrerMessage("Vous avez cracké un <b>".str_replace("_", " ", $logi)."</b> de niveau $niv et l'avez intégré à la machine <b>$ip</b>");
                        setNiveauJoueur(valider("id", "SESSION"));
                        rediriger("jeu.php?view=telechargement");
                    }else{
                        enregistrerMessage("Le logiciel <b>".str_replace("_", " ", $logi)."</b> de niveau $niv n'existe pas sur la machine <b>$ip</b>", "danger");
                        rediriger("jeu.php?view=telechargement");
                    }
                }
        break;

        /* On upload un virus sur la machine de quelqu'un */
        case "upload":
                if(($id = valider("id", "SESSION")) && ($ip = valider("ip", "SESSION")) && ($vir = valider("vir")) && ($niv = valider("niv"))){
                    if(uploadVirus($ip, $id, $vir, $niv)){
                        if($vir === "Miner")
                            updateRevenusJoueur($id);
                        enregistrerMessage("Vous avez installé un virus de type <b>$vir</b> sur la machine <b>$ip</b>");
                        rediriger("jeu.php?view=telechargement");
                    }
                    else{
                        enregistrerMessage("Ce virus n'est pas disponnible pour le moment.", "danger");
                        rediriger("jeu.php?view=telechargement");
                    }
                }
        break;
        
        /* Cas utilitaire pour tester le fonctionnement de la paie SANS cronjob */
        case "debugJDP": // TODO : enlever avant une utilisation finale
                jourDePaie();
        break;

        /* Comportement par défaut : On ne reste pas bloqué sur le contrôleur et on retourne vers la page de connexion (qui renvoie vers le jeu si l'utilisateur est connecté) */
        default:
                rediriger("connexion.php");
        break;
    }
}
else{ // Si on n'a pas d'action, on redirige le joueur
    rediriger("connexion.php");
}