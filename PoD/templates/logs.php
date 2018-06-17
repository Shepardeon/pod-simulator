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
    <h1 style="margin-top: 25px;">Logs</h1>
    <form action="controleur" method="POST">
        <textarea style="resize:none;" rows="25" cols="150" name="logs"><?php afficherLogs(valider("ip", "SESSION")); ?></textarea>
        <div class="text-center" style="padding-top:20px;">
            <button class="btn btn-outline-success" type="submit" name="action" value="editLogs">Envoyer</button>
        </div>
    </form>
</div>