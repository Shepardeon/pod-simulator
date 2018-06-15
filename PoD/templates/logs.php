

<div class="container">
    <h1 style="margin-top: 25px;">Logs</h1>
    <form action="controleur" method="POST">
        <textarea style="resize:none;" rows="25" cols="150" name="logs"><?php afficherLogs(valider("ip", "SESSION")); ?></textarea>
        <div class="text-center" style="padding-top:20px;">
            <button class="btn btn-outline-success" type="submit" name="action" value="editLogs">Envoyer</button>
        </div>
    </form>
</div>