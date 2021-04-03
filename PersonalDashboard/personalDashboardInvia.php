<?php
    session_start();
    include("../funzioni.php");
    if(!isset($_SESSION["username"])){
        //Utente tenta di accedere alla dashboard senza login
        $_SESSION["errorType"] = 5;
        echo "<script type='text/javascript'> document.location = '../index.php'; </script>";
    }
    userComboBox();
    echo "<br><br>".pubkeyUsernameGiven("Martina")["ChiavePubblica"];
    echo "<br><br>".$_SESSION["pubkey"];

?>