<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include("phpseclib/Crypt/RSA.php");

function findUser($IdUtente,$Password){
    $conn = new mysqli("localhost","root");
    if($conn->connect_error){
        //Problemi con l'host del database o con il database stesso
        $_SESSION["errorType"] = 4;
        echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
    }
    if(!$conn->select_db("ritchain")){
        //Problemi con l'host del database o con il database stesso
        $_SESSION["errorType"] = 4;
        echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
    }
    $IdUtente = secureString($conn,$IdUtente);
    $Password = secureString($conn,$Password);
    $SQLquery = "SELECT count(*), ChiavePrivata, ChiavePubblica FROM utenti WHERE IdUtente = '".$IdUtente."' and Password = '".hash("sha256",$Password)."'";
    $result = $conn->query($SQLquery) or trigger_error("Query Failed! SQL:  - Error: ".mysqli_error($conn), E_USER_ERROR);
    $conn -> close();
    if(!$result){
        //Errore della query, possibile server offline
        $_SESSION["errorType"] = 4;
        echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
    }else{
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
}

function logOut(){
    unset($_SESSION["usename"]);
    unset($_SESSION["password"]);
    unset($_SESSION["pubkey"]);
    unset($_SESSION["privkey"]);
    session_destroy();
}

function encryptRSA($plainText){
    $rsa = new Crypt_RSA();
    $rsa->loadKey($_SESSION["privkey"]);
    $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
    $chiphertext = $rsa->encrypt($plainText);
    return $chiphertext;
}

function decryptRSA($chiphertext){
    $rsa = new Crypt_RSA();
    $rsa->loadKey($_SESSION["pubkey"]);
    $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
    $plainText = $rsa->decrypt($chiphertext);
    return $plainText;
}

function createNewKeyPair(){
    //Genera private keys da 492 caratteri
    //Genera public keys da 181 caratteri
    $rsa = new Crypt_RSA();
    $rsa->setHash('sha256');

    $keys = $rsa->createKey(512);

    $keyPrivate	= $keys["privatekey"];

    $keyPublic = $keys["publickey"];
    return [$keyPrivate,$keyPublic];
}

function addNewUser($IdUtente,$Password){
    $conn = new mysqli("localhost","root");
    if($conn->connect_error){
        //Problemi con l'host del database o con il database stesso
        $_SESSION["errorType"] = 4;
        echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
    }
    if(!$conn->select_db("ritchain")){
        //Problemi con l'host del database o con il database stesso
        $_SESSION["errorType"] = 4;
        echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
    }
    $IdUtente = secureString($conn,$IdUtente);
    $Password = secureString($conn,$Password);
    if(!CheckUserExist($conn,$IdUtente)){
        addUser($conn,$IdUtente,$Password);
        $conn -> close();
        return FALSE;
    }else{
        $conn -> close();
        return TRUE;
    }
    
}

function addUser($conn,$IdUtente,$Password){
    $KeyPair = createNewKeyPair();
    $SQLquery = "INSERT INTO `utenti`(`IdUtente`, `Password`, `ChiavePrivata`, `ChiavePubblica`) VALUES ('".$IdUtente."','".hash("sha256",$Password)."','".$KeyPair[0]."','".$KeyPair[1]."')";
    if($conn->query($SQLquery) === FALSE){
        //Utente già esistente, poichè la chiave primaria è sul nome utente
        //$_SESSION["errorType"]=3;
        //echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
    }
}

function secureString($conn,$dataString){
    return mysqli_real_escape_string($conn,stripslashes($dataString));
}

function CheckUserExist($conn,$IdUtente){
    $SQLquery = "SELECT count(*) FROM utenti WHERE IdUtente = '".$IdUtente."'";
    $result = $conn->query($SQLquery) or trigger_error("Query Failed! SQL:  - Error: ".mysqli_error($conn), E_USER_ERROR);
    if(!$result){
        //Problemi con l'host del database o con il database stesso
        $_SESSION["errorType"] = 4;
        echo "<script type='text/javascript'> document.location = 'errorHandling.php'; </script>";
    }else{
        $row = mysqli_fetch_assoc($result);
        if(intval($row["count(*)"])===0){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}

function addTransaction(){
    if(verifyFile()){
        $bloccoGenesi = array(array(array("Pubblica Mittente"=>"prova 1",
                                    "Pubblica Destinatario"=>"prova 2",
                                    "Importo"=>100,
                                    "Timestamp"=>time(),
                                    "Firma Hash"=>"prova 3")),
                                "hashPrecedente"=>0);
        $file = fopen("blockchain.json","c+");
        while(flock($file,LOCK_EX)===FALSE){
        }
        $actualFile = fread($file,filesize("blockchain.json"));
        $actualFile = json_decode($actualFile,TRUE);
        array_push($actualFile,$bloccoGenesi);
        print_r($actualFile);
        $file = fopen("blockchain.json","w");
        fwrite($file,json_encode($actualFile));
        flock($file,LOCK_UN);
        fclose($file);
        
    }else{
        $bloccoGenesi = array(array(array(array("Pubblica Mittente"=>"prova 1",
                                    "Pubblica Destinatario"=>"prova 2",
                                    "Importo"=>100,
                                    "Timestamp"=>time(),
                                    "Firma Hash"=>"prova 3")),
                                "hashPrecedente"=>0));
        $file = fopen("blockchain.json","c+");
        while(flock($file,LOCK_EX)===FALSE){
        }
        fwrite($file,json_encode($bloccoGenesi));
        flock($file,LOCK_UN);
        fclose($file);
    }
}

function createNewBlock(){

}

function verifyFile(){
    if(!file_exists("blockchain.json")){
        $blockchain = fopen("blockchain.json","c+");
        fclose($blockchain);
        return false;
    }else{
        return true;
    }
}

?>