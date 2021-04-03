<?php
    session_start();
    include("../funzioni.php");
    if(!isset($_SESSION["username"])){
        //Utente tenta di accedere alla dashboard senza login
        $_SESSION["errorType"] = 5;
        echo "<script type='text/javascript'> document.location = '../index.php'; </script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/personalDashboardGeneraERitira.css">
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
    <div class="intestazione">Quante monete EDU desideri generare <?php echo $_SESSION["username"]; ?>?</div>
    <form  action="../elaborazioneRichieste.php" method="post" class="saldo-card">
        <div class="saldo-show">
            <div>
            +
            </div>
            <input class="quantita" type="number" name="quantity" required>
            <img src="../Logo/EduchainLogo-BlackV.png" alt="€" class="currency">
        </div>
        <button class="refresh-button" type="submit" name="genera" value="Genera">Genera</button>
    </form>
    <form action="../elaborazioneRichieste.php" method="post">
        <button class="logout-button" type="submit" name="logout" value="logout">Effettua logout</button>
    </form>
</div>
</body>
</html>