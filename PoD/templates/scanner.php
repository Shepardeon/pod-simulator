

<div class="container">
    <h1 style="margin-top: 25px;">Scanner</h1>

    <div class="row" style="margin-top: 50px;">
        <div class="col-md-5 text-center" style="margin:20px; padding:10px; background-color:#EEE;">
            <h3>Scan réseau</h3>
            <a href="controleur.php?action=scan" class="btn btn-outline-success">Scanner</a>

            <div style="margin-top:20px; background-color:#FFF; border-radius:5px;">
                <h5 style="padding:10px;">Résultat du dernier scan</h5>
                <?php
                    if(valider("nouveauScan", "SESSION")){
                        afficherRandomIP(randomIP(valider("ip", "SESSION")));
                        $_SESSION["nouveauScan"] = false;
                    }
                ?>
            </div>
        </div>

        <div class="col-md-5 text-center" style="margin:20px; padding:10px; background-color:#EEE;">
            <h3>Scan intensif</h3>
            <form class="form-inline" action="controleur.php" method="POST">
                <input style="margin: auto 20px auto 65px;" type="text" class="form-control" id="IP" name="IP" placeholder="127.0.0.1" required>
                <button type="submit" name="action" value="scanI" class="btn btn-warning">Scanner</button>
            </form>

            <div style="margin-top:20px; background-color:#FFF; border-radius:5px;">
                <h5 style="padding:10px;">Résultat du dernier scan</h5>
                <?php
                    if(valider("nouveauScanI", "SESSION")){
                        afficherScanI(valider("IScan", "SESSION"));
                        $_SESSION["nouveauScanI"] = false;
                    }
                ?>
            </div>
        </div>
    </div>
</div>