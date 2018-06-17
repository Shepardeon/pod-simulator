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

    <div class="row" style="margin-top: 25px;">
        <h1 class="col-md-8">Status</h1>
        <span class="col-md-4" style="padding-top:15px;"><?php afficherNiveau(recupProprioDepuisOrdi(valider("ip", "SESSION"))); ?></span>
    </div>

    <div class="row" style="margin-top: 15%">
        <div class="col-md-6">
            <h3>Logiciels</h3>
            <?php afficherLogiciels(valider("ip", "SESSION")); 
                if(valider("ip", "SESSION") == recupIPLocal(valider("id", "SESSION")))
                    echo "<a href='jeu.php?view=logiciels' class='btn btn-outline-success' style='margin-left:25%;'>Améliorer</a>";
            ?>
        </div>

        <div class="col-md-6">
            <h3>Matériels</h3>
            <?php afficherMateriels(valider("ip", "SESSION")); 
                if(valider("ip", "SESSION") == recupIPLocal(valider("id", "SESSION")))
                    echo "<a href='jeu.php?view=materiels' class='btn btn-outline-success' style='margin-left:25%;'>Améliorer</a>";
            ?>
        </div>
    </div>
</div>