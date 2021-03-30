<?php
session_start();
include("funzioni.php");

    if(isset($_POST["Login"]) and $_POST["Login"]==="Invia"){
        $user = findUser($_POST["UserID"],$_POST["Password"]);
        if($user["count(*)"]==0){
            //Utente non trovato, credenziali di accesso sbagliate
            $_SESSION["errorType"] = 1;
            echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
        }else{
            $_SESSION["username"] = $_POST["UserID"];
            $_SESSION["password"] = $_POST["Password"];
            $_SESSION["pubkey"] = $user["ChiavePubblica"];
            $_SESSION["privkey"] = $user["ChiavePrivata"];
            echo "<script type='text/javascript'> document.location = 'PersonalDashboard/personalDashboardSaldo.php'; </script>";
        }
    }
    if(isset($_POST["Registrazione"]) and $_POST["Registrazione"]==="Invia"){
        if($_POST["Password1"]!==$_POST["Password2"]){
            $_SESSION["errorType"] = 2;
            echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
            exit;
        }else{
            if(!addNewUser($_POST["UserID"],$_POST["Password1"])===TRUE){
                $_SESSION["registrationDone"] = 1;
                echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
                exit;
            }else{
                //Esiste già un account con quel nome
                $_SESSION["errorType"] = 3;
                echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
                exit;
                
            }
            
        }
    }
    if(isset($_POST["logout"]) and $_POST["logout"]==="logout"){
        logOut();
        echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    }
    if(isset($_POST["genera"]) and $_POST["genera"]==="Genera"){
        addTransaction();
    }
?>