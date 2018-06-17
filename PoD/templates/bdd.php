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
    <h1 style="margin-top: 25px;">BDD Hacker</h1>
    <h4 style="margin-left:20px;">Adresse IP :: Type de virus actif</h4>

    <div style="margin:20px 20px 10% 20px; padding:10px; background-color:#EEE; max-width:40%;">
        <?php afficherBDD(valider("id", "SESSION")); ?>
    </div>
</div>