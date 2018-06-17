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
    <h1 style="margin-top: 25px;">Antivirus</h1>

    <div style="margin:30px 20px 20px 20px; padding:10px; background-color:#EEE; max-width:50%;">
        <a href="controleur.php?action=scanAV" class="btn btn-outline-success" style="margin-left:40%;">Scanner</a>

        <div style="margin-top:20px; background-color:#FFF; border-radius:5px;">
            <h5 style="padding:10px;">Résultat du dernier scan</h5>
            <?php
                if(valider("nouveauScan", "SESSION")){
                    afficherVirusSurMachine(valider("ip", "SESSION"));
                    $_SESSION["nouveauScan"] = false;
                }
            ?>
        </div>
    </div>
</div>