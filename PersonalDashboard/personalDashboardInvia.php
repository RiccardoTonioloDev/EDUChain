<?php
    include("../funzioni.php");
    startPersonalDashboard();
    // userComboBox();
    // echo "<br><br>".pubkeyUsernameGiven("Martina")["ChiavePubblica"];
    // echo "<br><br>".$_SESSION["pubkey"];

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/personalDashboardInvia.css">
    <title>Pagina personale</title>
</head>
<body onload="typeName()">
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
    <div class="intestazione">Selezionare il metodo per trovare a chi mandare denaro:
        <div class="method-card">
            <div>
                <input type="radio" name="methodChosen" id="typedName" onClick="typeName()" value="Scrivi il nome" checked>
                <label for="Scrivi il nome">Scrivi il nome</label>
            </div>
            <div>
                <input type="radio" name="methodChosen" id="comboBox" onClick="findName()" value="Trova il nome">
                <label for="Trova il nome">Trova il nome</label>
            </div>
        </div>
    </div>
    <form  action="../elaborazioneRichieste.php" method="post" class="action-card">
        <div class="action-show">
            <div class="actionBox" id="actionBox">
                <input type='text' name='nome'>
            </div>
            <div>
                <input class="quantita" type="number" name="quantity" required>
                <img src="../Logo/EduchainLogo-BlackV.png" alt="€" class="currency">
            </div>
        </div>
        <button class="refresh-button" type="submit" name="invia-transaction" value="invia">Invia</button>
    </form>
    <form action="../elaborazioneRichieste.php" method="post">
        <button class="logout-button" type="submit" name="logout" value="logout">Effettua logout</button>
    </form>
</div>
<script>
    function findName(){
        document.getElementById("actionBox").innerHTML = "<div class='action-box'><div>Scegli uno username tra quelli elencati:</div><?php userComboBox() ?></div>";
    }
    function typeName(){
        document.getElementById("actionBox").innerHTML = "<div class='action-box'><div>Digita lo username della persona a cui mandare i soldi:</div><input type='text' name='nome' required></div>";
    }
</script>
</body>
</html>