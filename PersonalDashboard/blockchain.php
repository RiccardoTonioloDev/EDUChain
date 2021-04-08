<?php
    include("../funzioni.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blockchain</title>
    <link rel="stylesheet" href="../Styles/blockchain.css">
</head>
<body>
<?php
    if(verifyBlockchain()){
        printBlockchain();
    }else{
        echo "Non valida";
    }
?>
    
</body>
</html>