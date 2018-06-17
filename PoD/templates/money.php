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
    <h1 style="margin-top: 25px;">Porte-feuille</h1>
    <h5 style="margin-top: 15px;">Balance totale : <?php  afficherFondsTotaux(recupProprio(valider("ip", "SESSION"))) ?></h5>
    
    <div class="row" style="margin-top: 50px;">
        <div class="col-md-5 text-center" style="margin:20px; padding:10px; background-color:#EEE;">
            <h6>Fonds</h6>
            <p><?php afficherFonds(recupProprio(valider("ip", "SESSION"))) ?></p>
            <?php
                if(valider("ip", "SESSION") != recupIPLocal(valider("id", "SESSION")))
                    echo "<a href='controleur.php?action=volFonds' class='btn btn-outline-danger'>Transférer</a>";
            ?>
        </div>

        <div class="col-md-5 text-center" style="margin:20px; padding:10px; background-color:#EEE;">
            <h6>Fonds Sécurisés</h6>
            <p><?php afficherFondsSec(recupProprio(valider("ip", "SESSION"))) ?></p>
            <?php 
                if(valider("ip", "SESSION") == recupIPLocal(valider("id", "SESSION")))
                    echo "<a href='controleur.php?action=secFonds' class='btn btn-outline-success'>Sécuriser</a>";
            ?>
        </div>
    </div>
</div>