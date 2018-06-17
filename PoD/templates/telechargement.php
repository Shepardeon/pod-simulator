<?php
// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
// Pas de soucis de bufferisation, puisque c'est dans le cas où on appelle directement la page sans son contexte
if (basename($_SERVER["PHP_SELF"]) != "jeu.php")
{
	header("Location:../jeu.php?view=status");
	die("");
}
?>

<div class="container">
    <h1 style="margin-top: 25px;">Téléchargement</h1>

    <?php if(valider("ip", "SESSION") == recupIPLocal(valider("id", "SESSION"))) { ?>
        <h4 style="margin: 25px auto 25px 1%">Fichiers téléchargés</h4>
    <?php 
            afficherTelechargements(valider("ip", "SESSION"));
        }else {
    ?>
        <h4 style="margin: 25px auto 25px 1%">Virus disponnibles</h4>
    <?php 
            afficherVirus(valider("ip", "SESSION"), valider("id", "SESSION"));
        } 
    ?>
</div>