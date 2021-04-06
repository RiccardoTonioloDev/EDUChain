<?php
    include("../funzioni.php");
    startPersonalDashboard();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/personalDashboardSaldo.css">
    <title>Pagina personale</title>
</head>
<body>
<div class="navbar">
    <a href="#"><img class="logo" alt="logo" src="../Logo/EduchainLogo.png"></a>
    <ul class="nav-clickable">
        <li><a class="clickable" href="personalDashboardSaldo.php">Saldo</a></li>
        <li><a class="clickable" href="personalDashboardInvia.php">Invia</a></li>
        <li><a class="clickable" href="personalDashboardRicevi.php">Ricevi</a></li>
        <li><a class="clickable" href="personalDashboardGenera.php">Genera</a></li>
        <li><a class="clickable" href="personalDashboardRitira.php">Ritira</a></li>
        <li><a class="clickable" href="personalDashboardBlockchain.php">Blockchain</a></li>
    </ul>
</div>
<div class="container">
    <div class="intestazione">Il tuo username è:</div>
    <div class="saldo-card">
        <div class="saldo-show">
            <div id="UserID">
            <?php
                echo $_SESSION["username"];
            ?>
            </div>
        </div>
        <button class="refresh-button" name="copy" value="Aggiorna" onClick="copyName()">Copia il nome</button>
    </div>
    <form action="../elaborazioneRichieste.php" method="post">
        <button class="logout-button" type="submit" name="logout" value="logout">Effettua logout</button>
    </form>
</div>
<script>
    function copyName(){
        var element = document.getElementById("UserID");
        elementText = element.textContent;
        navigator.clipboard.writeText(elementText);
        alert("Il seguente username: " + elementText + " è stato copiato con successo! Puoi inoltrarlo con chi vuoi!");
    }
</script>
</body>
</html>