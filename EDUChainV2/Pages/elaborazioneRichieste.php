<?php
session_start();
include("funzioni.php");
print_r($_POST);

    if(isset($_POST["Login"]) and $_POST["Login"]==="Invia"){
        $user = findUser($_POST["UserID"],$_POST["Password"]);
        if($user["count(*)"]==0){
            //Utente non trovato, credenziali di accesso sbagliate
            errorHandlingSorter(1,"Login&Register.php");
        }else{
            $_SESSION["username"] = $_POST["UserID"];
            $_SESSION["password"] = $_POST["Password"];
            $_SESSION["pubkey"] = $user["ChiavePubblica"];
            $_SESSION["privkey"] = $user["ChiavePrivata"];
            $_SESSION["importo"] = totalAmount();
            echo "<script type='text/javascript'> document.location = 'PersonalDashboard/personalDashboardSaldo.php'; </script>";
        }
    }
    if(isset($_POST["Registrazione"]) and $_POST["Registrazione"]==="Invia"){
        if($_POST["Password1"]!==$_POST["Password2"]){
            // Le password non combaciano
            errorHandlingSorter(2,"errorHandling.php");
        }else{
            if(!addNewUser($_POST["UserID"],$_POST["Password1"])===TRUE){
                $_SESSION["registrationDone"] = 1;
                echo "<script type='text/javascript'> document.location = 'Login&Register.php'; </script>";
                exit;
            }else{
                //Esiste già un account con quel nome
                errorHandlingSorter(3,"errorHandling.php");
            }
            
        }
    }
    if(isset($_POST["logout"]) and $_POST["logout"]==="logout"){
        logOut();
        echo "<script type='text/javascript'> document.location = 'Login&Register.php'; </script>";
    }
    if(isset($_POST["genera"]) and $_POST["genera"]==="Genera"){
        if($_POST["quantity"]>=0){
            addMoneyGenerated($_POST["quantity"]);
            $_SESSION["importo"] = totalAmount();
            $_SESSION["transazioni"] = showTransaction();
            echo "<script type='text/javascript'> document.location = 'PersonalDashboard/personalDashboardSaldo.php'; </script>";
        }else{
            // si aggiunge al saldo un numero negativo di soldi
            errorHandlingSorter(6,"errorHandling.php");
        }
    }
    if(isset($_POST["refresh"]) and $_POST["refresh"]==="Aggiorna"){
        $_SESSION["importo"] = totalAmount();
        $_SESSION["transazioni"] = showTransaction();
        echo "<script type='text/javascript'> document.location = 'PersonalDashboard/personalDashboardSaldo.php'; </script>";
    }
    if(isset($_POST["ritira"]) and $_POST["ritira"]==="Ritira"){
        if(verificaSaldo($_POST["quantity"])){
            if($_POST["quantity"]>=0){
                deleteMoney($_POST["quantity"]);
                $_SESSION["importo"] = totalAmount();
                $_SESSION["transazioni"] = showTransaction();
                echo "<script type='text/javascript'> document.location = 'PersonalDashboard/personalDashboardSaldo.php'; </script>";
            }else{
                // si rimuove dal saldo un numero negativo di soldi
                errorHandlingSorter(8,"errorHandling.php");
            }
        }else{
            // Il saldo corrente è minore della richiesta da sotrrarre
            errorHandlingSorter(7,"errorHandling.php");
        }
    }
    if(isset($_POST["invia-transaction"]) and $_POST["invia-transaction"]==="Invia"){
        $conn = mysqliConnectorCreator();
        if(CheckUserExist($conn,$_POST["nome"])){
            $conn->close();
            if(verificaSaldo($_POST["quantity"])){
                if($_POST["quantity"]>=0){
                    addTransaction(pubkeyUsernameGiven($_POST["nome"]),$_POST["quantity"]);
                    $_SESSION["importo"] = totalAmount();
                    $_SESSION["transazioni"] = showTransaction();
                    echo "<script type='text/javascript'> document.location = 'PersonalDashboard/personalDashboardSaldo.php'; </script>";
                }else{
                    // si rimuove dal saldo un numero negativo di soldi
                    errorHandlingSorter(11,"errorHandling.php");
                }
            }else{
                // Il saldo corrente è minore della richiesta da sotrrarre
                errorHandlingSorter(10,"errorHandling.php");
            }
        }else{
            $conn->close();
            // Si sta cercando di mandare una somma di denaro a un utente inesistente
            errorHandlingSorter(9,"errorHandling.php");
        }
        
    }
?>