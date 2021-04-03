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
    unset($_SESSION["importo"]);
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

function decryptRSAKeyGiven($publicKey,$chiphertext){
    $rsa = new Crypt_RSA();
    $rsa->loadKey($publicKey);
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
    verifyFile();
    $lock = fopen("Lock.txt","c+");
    while(flock($lock,LOCK_EX)===FALSE){
    }
    createNewBlock();
    createNewTransaction("-----BEGIN PUBLIC KEY-----\r\nMFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAMzOYRVq3ybatDs3WllSalsoU3y5jzxD\r\nwAdFfOXoMS9FRWWBQ9GT1Vi2rdSbvZZcoWDpp5DsEF1NBuFb2rNyVwMCAwEAAQ==\r\n-----END PUBLIC KEY--",396);
    fclose($lock);

}
function verifyTransaction($transazione){
    if($transazione["Mittente"]==="NETWORK" or $transazione["Destinatario"]==="NETWORK"){
        return true;
    }else{
        if(hash("sha256",serialize(array($transazione["Mittente"],$transazione["Destinatario"],$transazione["Importo"],$transazione["Timestamp"])))===decryptRSAKeyGiven($transazione["Mittente"],utf8_decode($transazione["Hash firmato"]))){
            return true;
        }else{
            return false;
        }
    }
}

function addMoneyGenerated($importo){
    verifyFile();
    $lock = fopen("Lock.txt","c+");
    while(flock($lock,LOCK_EX)===FALSE){
    }
    createNewBlock();
    createNewMoney($importo);
    fclose($lock);
}

function deleteMoney($importo){
    verifyFile();
    $lock = fopen("Lock.txt","c+");
    while(flock($lock,LOCK_EX)===FALSE){
    }
    eraseMoney($importo);
    fclose($lock);
}

function createNewTransaction($destinatario,$importo){
    $timestamp = time();
    $transazione = array("Mittente"=>$_SESSION["pubkey"],"Destinatario"=>$destinatario,"Importo"=>$importo,"Timestamp"=>$timestamp,"Hash firmato"=>utf8_encode(encryptRSA(hash("sha256",serialize(array($_SESSION["pubkey"],$destinatario,$importo,$timestamp))))));
    echo decryptRSA(utf8_decode($transazione["Hash firmato"]));
    echo "<br>".hash("sha256",serialize(array($_SESSION["pubkey"],$destinatario,$importo,$timestamp)));
    $blockchainArray = file_get_contents("blockchain.json");
    $blockchainArray = json_decode($blockchainArray,TRUE);
    $blockchainArray[count($blockchainArray)-1]["Transazioni"][] = $transazione;
    $blockchainErased = fopen("blockchain.json","w");
    fwrite($blockchainErased,json_encode($blockchainArray));
    fclose($blockchainErased);
}

function createNewMoney($importo){
    $timestamp = time();
    $transazione = array("Mittente"=>"NETWORK","Destinatario"=>$_SESSION["pubkey"],"Importo"=>$importo,"Timestamp"=>$timestamp);
    $blockchainArray = file_get_contents("blockchain.json");
    $blockchainArray = json_decode($blockchainArray,TRUE);
    $blockchainArray[count($blockchainArray)-1]["Transazioni"][] = $transazione;
    $blockchainErased = fopen("blockchain.json","w");
    fwrite($blockchainErased,json_encode($blockchainArray));
    fclose($blockchainErased);
}

function eraseMoney($quantitaDaSottrarre){
    $timestamp = time();
    $transazione = array("Mittente"=>$_SESSION["pubkey"],"Destinatario"=>"NETWORK","Importo"=>$quantitaDaSottrarre,"Timestamp"=>$timestamp);
    $blockchainArray = file_get_contents("blockchain.json");
    $blockchainArray = json_decode($blockchainArray,TRUE);
    $blockchainArray[count($blockchainArray)-1]["Transazioni"][] = $transazione;
    $blockchainErased = fopen("blockchain.json","w");
    fwrite($blockchainErased,json_encode($blockchainArray));
    fclose($blockchainErased);
}

function totalAmount(){
    $total = 0;
    $blockchainArray = file_get_contents("blockchain.json");
    $blockchainArray = json_decode($blockchainArray,TRUE);
    if(filesize("blockchain.json")){
        foreach ($blockchainArray as $numBlocco => $blocco) {
            for ($i=0; $i < count($blocco["Transazioni"]); $i++) { 
                if(verifyTransaction($blocco["Transazioni"][$i])){
                    if($blocco["Transazioni"][$i]["Destinatario"]===$_SESSION["pubkey"]){
                        $total+=$blocco["Transazioni"][$i]["Importo"];
                    }elseif ($blocco["Transazioni"][$i]["Mittente"]===$_SESSION["pubkey"]) {
                        $total-=$blocco["Transazioni"][$i]["Importo"];
                    }
                }
            }
        }
    }
    return $total;
}

function verificaSaldo($quantitaDaSottrarre){
    if($_SESSION["importo"]<$quantitaDaSottrarre){
        return false;
    }else{
        return true;
    }
}

function createNewBlock(){
    $blockchainArray = file_get_contents("blockchain.json");
    $blockchainArray = json_decode($blockchainArray,TRUE);
    if(filesize("blockchain.json")){
        $blocco = array("Transazioni"=>array(),"hashPrecedente"=>hash("sha256",serialize($blockchainArray[count($blockchainArray)-1])));
        $blockchainErased = fopen("blockchain.json","w");
        // array_push($blockchainArray[count($blockchainArray)],$blocco);
        $blockchainArray[]=$blocco;
        fwrite($blockchainErased,json_encode($blockchainArray));
        fclose($blockchainErased);
    }else{
        $bloccoGenesi = array(array("Transazioni"=>array(),"hashPrecedente"=>0));
        $blockchainErased = fopen("blockchain.json","w");
        fwrite($blockchainErased,json_encode($bloccoGenesi));
        fclose($blockchainErased);
    }

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