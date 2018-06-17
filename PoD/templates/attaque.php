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
    <h1 style="margin: 25px auto 50px auto;">Attaque</h1>

    <h3>Lancer une attaque</h3>

    <form action="controleur.php" method="POST" class="form-inline" style="padding-left:25px; margin-top:35px;">
        <input type="text" class="form-control" id="IP" name="IP" placeholder="127.0.0.1" required>
        <button style="margin: auto 20px auto 65px;" type="submit" name="action" value="attaquer" class="btn btn-outline-danger">Attaquer</button>
    </form>
</div>