

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