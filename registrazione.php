<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/registrazione.css">
    <title>Sign In</title>
</head>
<body>
<div class="navbar">
    <div class="logo">RTChain by Toniolo Riccardo</div>
</div>
<div class="container">
    <div class="intestazione">Pagina di registrazione</div>
    <form action="elaborazioneRichieste.php" method="post">
        <div class="login-card">
            <div>Inserisci uno username:</div>
            <input type="text" name="UserID" required>
            <div>Inserisci una password:</div>
            <input type="password" name="Password1" required>
            <div>Ripeti la password:</div>
            <input type="password" name="Password2" required>
            <div class="button-container">
                <button class="login-button" type="submit" name="Registrazione" value="Invia">Invia</button>
            </div>
        </div>
    </form>
    <form action="index.php" method="get">
        <button class="register-button" type="submit" name="Login" value="Login">Se vuoi accedere clicca qui</button>
    </form>
</div>


</form>
    
</body>
</html>