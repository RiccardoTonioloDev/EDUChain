<?php
session_start();
if(isset($_SESSION["username"])){
    //L'utente ha già effettuato il login poichè in sessione i suoi dati esistono già
    echo "<script type='text/javascript'> document.location = 'PersonalDashboard/personalDashboardSaldo.php'; </script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/index.css">
    <title>Log In</title>
</head>
<body>
<div class="navbar">
    <div style="padding-left:5vw;">FROM A STUDENT</div>
    <a href="#"><img class="logo" src="Logo/EduchainLogo.png" alt="logo"></a>
    <div style="padding-right:5vw;">TO THE STUDENTS</div>
</div>
<div class="container">
    
    <div class="intestazione">Pagina di login</div>
    <?php
        if(isset($_SESSION["registrationDone"]) and $_SESSION["registrationDone"]===1){
            $_SESSION["registrationDone"] = 0;
            echo "<div class='intestazione'>Accedi con le credenziali appena create!</div>";
        }
        if(isset($_SESSION["errorType"])){
            switch($_SESSION["errorType"]){
                case 1:
                    $_SESSION["errorType"] = 0;
                    echo "<div style='background-color:red;' class='intestazione'>Credenziali errate!</div>";
                    break;
                case 5:
                    $_SESSION["errorType"] = 0;
                    echo "<div style='background-color:red;' class='intestazione'>Prima devi effettuare il login!</div>";
                    break;
            }
        }
    ?>
    
    <form action="elaborazioneRichieste.php" method="post">
        <div class="login-card">
            <div>Username:</div>
            <input type="text" name="UserID" required>
            <div>Password:</div>
            <input type="password" name="Password" required>
            <div class="button-container">
                <button class="login-button" type="submit" name="Login" value="Invia">Invia</button>
            </div>
        </div>
    </form>
    <form action="registrazione.php" method="get">
        <button class="register-button" type="submit" name="Register" value="Registrati">Registrati qui se non hai ancora un account</button>
    </form>
</div>
    
</body>
</html>