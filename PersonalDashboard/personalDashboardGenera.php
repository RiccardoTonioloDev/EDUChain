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
    <link rel="stylesheet" href="../Styles/personalDashboardGenera.css">
    <title>Pagina personale</title>
</head>
<body>
<div class="navbar">
    <a href="#"><img class="logo" alt="logo" src="../Logo/EduchainLogo.png"></a>
    <ul class="nav-clickable">
        <li><a class="clickable" href="#">Saldo</a></li>
        <li><a class="clickable" href="#">Invia</a></li>
        <li><a class="clickable" href="#">Ricevi</a></li>
        <li><a class="clickable" href="#">Genera</a></li>
        <li><a class="clickable" href="#">Ritira</a></li>
        <li><a class="clickable" href="#">Blockchain</a></li>
    </ul>
</div>
<div class="container">
    <div class="intestazione">Quante monete EDU desideri generare riccardo <?php echo $_SESSION["username"]; ?>?</div>
    <form  action="../elaborazioneRichieste.php" method="post" class="saldo-card">
        <div class="saldo-show">
            <div>
            +
            </div>
            <input class="quantita" type="number" name="quantity">
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