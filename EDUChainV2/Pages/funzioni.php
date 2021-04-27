<?php
set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include("phpseclib/Crypt/RSA.php");

function findUser($IdUtente,$Password){
    $conn = new mysqli("localhost","root");
    if($conn->connect_error){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"errorHandling.php");
    }
    if(!$conn->select_db("ritchain")){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"errorHandling.php");
    }
    $IdUtente = secureString($conn,$IdUtente);
    $Password = secureString($conn,$Password);
    $SQLquery = "SELECT count(*), ChiavePrivata, ChiavePubblica FROM utenti WHERE IdUtente = '".$IdUtente."' and Password = '".hash("sha256",$Password)."'";
    $result = $conn->query($SQLquery) or trigger_error("Query Failed! SQL:  - Error: ".mysqli_error($conn), E_USER_ERROR);
    $conn -> close();
    if(!$result){
        //Errore della query, possibile server offline
        errorHandlingSorter(4,"errorHandling.php");
    }else{
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
}

function userComboBox(){
    $conn = new mysqli("localhost","root");
    if($conn->connect_error){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"../errorHandling.php");
    }
    if(!$conn->select_db("ritchain")){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"../errorHandling.php");
    }
    $SQLquery = "SELECT IdUtente FROM utenti";
    $result = $conn->query($SQLquery);
    $conn -> close();
    $rows = mysqli_fetch_all($result);
    echo "<select name='nome' class='selectionUtenti' required>";
    foreach ($rows as $key => $value) {
        echo "<option value='".$value[0]."'>".$value[0]."</option>";
    }
    echo "</select>";
}

function pubkeyUsernameGiven($username){
    $conn = new mysqli("localhost","root");
    if($conn->connect_error){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"../errorHandling.php");
    }if(!$conn->select_db("ritchain")){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"../errorHandling.php");
    }
    $SQLquery = "SELECT ChiavePubblica FROM utenti WHERE IdUtente = '".secureString($conn,$username)."'";
    $result = $conn->query($SQLquery);
    $conn->close();
    $row = mysqli_fetch_assoc($result);
    return $row["ChiavePubblica"];
}

function startPersonalDashboard(){
    session_start();
    if(!isset($_SESSION["username"])){
        //Utente tenta di accedere alla dashboard senza login
        errorHandlingSorter(5,"../Login&Register.php");
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

function  errorHandlingSorter(int $errorType, string $path){
    $_SESSION["errorType"] = $errorType;
    echo "<script type='text/javascript'> document.location = '".$path."'; </script>";
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
        errorHandlingSorter(4,"errorHandling.php");
    }
    if(!$conn->select_db("ritchain")){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"errorHandling.php");
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

function mysqliConnectorCreator(){
    $conn = new mysqli("localhost","root");
    if($conn->connect_error){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"errorHandling.php");
    }
    if(!$conn->select_db("ritchain")){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"errorHandling.php");
    }
    return $conn;
}

function CheckUserExist($conn,$IdUtente){
    $IdUtente = secureString($conn,$IdUtente);
    $SQLquery = "SELECT count(*) FROM utenti WHERE IdUtente = '".$IdUtente."'";
    $result = $conn->query($SQLquery) or trigger_error("Query Failed! SQL:  - Error: ".mysqli_error($conn), E_USER_ERROR);
    if(!$result){
        //Problemi con l'host del database o con il database stesso
        errorHandlingSorter(4,"errorHandling.php");
    }else{
        $row = mysqli_fetch_assoc($result);
        if(intval($row["count(*)"])===0){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}

function addTransaction(string $destinatario,$importo){
    verifyFile();
    $lock = fopen("Lock.txt","c+");
    while(flock($lock,LOCK_EX)===FALSE){
    }
    if(VerifyTransactionsInABlock()){
        createNewTransaction($destinatario,$importo);
    }else{
        createNewBlock();
        createNewTransaction($destinatario,$importo);
    }
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
    if(VerifyTransactionsInABlock()){
        createNewMoney($importo);
    }else{
        createNewBlock();
        createNewMoney($importo);
    }
    fclose($lock);
}

function deleteMoney($importo){
    verifyFile();
    $lock = fopen("Lock.txt","c+");
    while(flock($lock,LOCK_EX)===FALSE){
    }
    if(VerifyTransactionsInABlock()){
        eraseMoney($importo);
    }else{
        createNewBlock();
        eraseMoney($importo);
    }
    fclose($lock);
}

function createNewTransaction($destinatario,$importo){
    $timestamp = time();
    $transazione = array("Mittente"=>$_SESSION["pubkey"],"Destinatario"=>$destinatario,"Importo"=>$importo,"Timestamp"=>$timestamp,"Hash firmato"=>utf8_encode(encryptRSA(hash("sha256",serialize(array($_SESSION["pubkey"],$destinatario,$importo,$timestamp))))));
    $blockchainArray = file_get_contents("blockchain.json");
    $blockchainArray = json_decode($blockchainArray,TRUE);
    $blockchainArray[count($blockchainArray)-1]["Transazioni"][] = $transazione;
    $blockchainErased = fopen("blockchain.json","w");
    fwrite($blockchainErased,json_encode($blockchainArray));
    fclose($blockchainErased);
}

function VerifyTransactionsInABlock(){
    $blockchainArray = file_get_contents("blockchain.json");
    $blockchainArray = json_decode($blockchainArray,TRUE);
    $hashedSeen = array();
    if(filesize("blockchain.json")){
        if(count($blockchainArray[count($blockchainArray)-1]["Transazioni"])<3){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
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
    if(file_exists("blockchain.json")){
        if(filesize("blockchain.json")){
            $blockchainArray = file_get_contents("blockchain.json");
            $blockchainArray = json_decode($blockchainArray,TRUE);
            $hashedSeen = array();
            foreach ($blockchainArray as $numBlocco => $blocco) {
                for ($i=0; $i < count($blocco["Transazioni"]); $i++) { 
                    if(verifyTransaction($blocco["Transazioni"][$i]) and !in_array(hash("sha256",serialize($blocco["Transazioni"][$i])),$hashedSeen)){
                        if($blocco["Transazioni"][$i]["Destinatario"]===$blocco["Transazioni"][$i]["Mittente"]){
                            $hashedSeen[] = hash("sha256",serialize($blocco["Transazioni"][$i]));
                        }elseif($blocco["Transazioni"][$i]["Destinatario"]===$_SESSION["pubkey"]){
                            $hashedSeen[] = hash("sha256",serialize($blocco["Transazioni"][$i]));
                            $total+=$blocco["Transazioni"][$i]["Importo"];
                        }elseif ($blocco["Transazioni"][$i]["Mittente"]===$_SESSION["pubkey"]) {
                            $total-=$blocco["Transazioni"][$i]["Importo"];
                            $hashedSeen[] = hash("sha256",serialize($blocco["Transazioni"][$i]));
                        }
                    }
                }
            }
        }
    }
    return $total;
}

function TransactionToBeShown($status,$amount,$mittente,$destinatario){
    $transaction = "<div class='transaction'>
                        <div class='leftTransaction'>
                            <div class='status'>".$status."</div>
                            <div class='transactionAmount'>
                                <div class='amount'>".$amount."</div>
                                <img src='../../Images/EduchainLogo-BlackV.png' class='currency'>
                            </div>
                        </div>
                        <div class='rightTransaction'>
                            <div class='peer'>
                                <div class='peerHeader'>
                                    From:
                                </div>
                                <div class='key'>
                                    ".str_replace(array("-----END PUBLIC KEY--","-----BEGIN PUBLIC KEY-----","\r","\n"),"",$mittente)."
                                </div>
                            </div>
                            <div class='peer'>
                                <div class='peerHeader'>
                                To:
                                </div>
                                <div class='key'>
                                    ".str_replace(array("-----END PUBLIC KEY--","-----BEGIN PUBLIC KEY-----","\r","\n"),"",$destinatario)."
                                </div>
                            </div>
                        </div>
                    </div>";
    return $transaction;
}

function showTransaction(){
    $total = 0;
    $totTransazioni = "";
    if(file_exists("blockchain.json")){
        if(filesize("blockchain.json")){
            $blockchainArray = file_get_contents("blockchain.json");
            $blockchainArray = json_decode($blockchainArray,TRUE);
            $hashedSeen = array();
            foreach ($blockchainArray as $numBlocco => $blocco) {
                for ($i=0; $i < count($blocco["Transazioni"]); $i++) { 
                    if(verifyTransaction($blocco["Transazioni"][$i]) and !in_array(hash("sha256",serialize($blocco["Transazioni"][$i])),$hashedSeen)){
                        if($blocco["Transazioni"][$i]["Destinatario"]===$blocco["Transazioni"][$i]["Mittente"]){
                            $hashedSeen[] = hash("sha256",serialize($blocco["Transazioni"][$i]));
                            $totTransazioni = TransactionToBeShown("NULL:",$blocco["Transazioni"][$i]["Importo"],$blocco["Transazioni"][$i]["Mittente"],$blocco["Transazioni"][$i]["Destinatario"])."".$totTransazioni;
                        }elseif($blocco["Transazioni"][$i]["Destinatario"]===$_SESSION["pubkey"]){
                            $hashedSeen[] = hash("sha256",serialize($blocco["Transazioni"][$i]));
                            $total+=$blocco["Transazioni"][$i]["Importo"];
                            $totTransazioni = TransactionToBeShown("Received:",$blocco["Transazioni"][$i]["Importo"],$blocco["Transazioni"][$i]["Mittente"],$blocco["Transazioni"][$i]["Destinatario"])."".$totTransazioni;
                        }elseif ($blocco["Transazioni"][$i]["Mittente"]===$_SESSION["pubkey"]) {
                            $total-=$blocco["Transazioni"][$i]["Importo"];
                            $hashedSeen[] = hash("sha256",serialize($blocco["Transazioni"][$i]));
                            $totTransazioni = TransactionToBeShown("Sent:",$blocco["Transazioni"][$i]["Importo"],$blocco["Transazioni"][$i]["Mittente"],$blocco["Transazioni"][$i]["Destinatario"])."".$totTransazioni;
                        }
                    }
                }
            }
        }
    }
    return $totTransazioni;
}

function circulatingSupply(){
    $total = 0;
    if(file_exists("blockchain.json")){
        if(filesize("blockchain.json")){
            $blockchainArray = file_get_contents("blockchain.json");
            $blockchainArray = json_decode($blockchainArray,TRUE);
            $hashedSeen = array();
            foreach ($blockchainArray as $numBlocco => $blocco) {
                for ($i=0; $i < count($blocco["Transazioni"]); $i++) { 
                    if(verifyTransaction($blocco["Transazioni"][$i]) and !in_array(hash("sha256",serialize($blocco["Transazioni"][$i])),$hashedSeen)){
                        if($blocco["Transazioni"][$i]["Destinatario"]==="NETWORK"){
                            $hashedSeen[] = hash("sha256",serialize($blocco["Transazioni"][$i]));
                            $total+=$blocco["Transazioni"][$i]["Importo"];
                        }elseif ($blocco["Transazioni"][$i]["Mittente"]==="NETWORK") {
                            $total-=$blocco["Transazioni"][$i]["Importo"];
                            $hashedSeen[] = hash("sha256",serialize($blocco["Transazioni"][$i]));
                        }
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

function verifyBlockchain(){
    $isNotCorrupted = true;
    if(file_exists("../blockchain.json")){
        if(filesize("../blockchain.json")){
            $blockchainArray = file_get_contents("../blockchain.json");
            $blockchainArray = json_decode($blockchainArray,TRUE);
            $counter=0;

            foreach ($blockchainArray as $numBlocco => $blocco) {
                if($counter===0){
                }else{
                    if($blocco["hashPrecedente"]!==hash("sha256",serialize($blockchainArray[$counter-1]))){
                        $isNotCorrupted = false;
                    }
                }
                $counter++;
            }
        }else{
            $isNotCorrupted = true;
        }
    }else{
        $isNotCorrupted = true;
    }
    return $isNotCorrupted;
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

function printBlockchain(){
    $file = file_get_contents("../blockchain.json");
    $blockchainArray = json_decode($file,TRUE);
    foreach ($blockchainArray as $numBlocco => $blocco) {
        echo "<div class='blocco' id='blocco".($numBlocco+1)."'>";
        echo "<div class='block-title'>Blocco: #".($numBlocco+1)."</div>";
        foreach ($blocco["Transazioni"] as $numeroTransazione => $transazione) {
            echo "<div class='transazione' id='transazione".($numeroTransazione+1)."'>";
                echo "<div class='transaction-title'>Transazione: #".($numeroTransazione+1)."</div>";
                echo "<div class='mittente'>
                        <div class='header'>Mittente:</div><div>".$transazione["Mittente"]."</div>
                    </div>";
                echo "<div class='destinatario'>
                        <div class='header'>Destinatario:</div><div>".$transazione["Destinatario"]."</div>
                    </div>";
                echo "<div class='importo'>
                        <div class='header'>Importo:</div><div>".$transazione["Importo"]."</div>
                    </div>";
                echo "<div class='data'>
                        <div class='header'>Data:</div><div>".date('m/d/Y', $transazione["Timestamp"])."</div>
                    </div>";
                if(isset($transazione["Hash firmato"])){
                    echo "<div class='hashFirmato'>
                        <div class='header'>Hash firmato:</div><div>".$transazione["Hash firmato"]."</div>
                    </div>";
                }
            echo "</div>";
        }
        echo "<div  class='hash'>
            <div class='header'>Hash blocco precedente</div>
            <div>".$blocco["hashPrecedente"]."</div>
        </div>";
        echo "</div>";
    }
}

?>