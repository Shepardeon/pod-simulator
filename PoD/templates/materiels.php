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
    <h1 style="margin: 25px auto 50px auto;">Materiels</h1>
    <?php afficherMaterielsMagasin(valider("id", "SESSION")); ?>
</div>