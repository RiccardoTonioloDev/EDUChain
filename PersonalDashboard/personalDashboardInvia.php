<?php
    include("../funzioni.php");
    startPersonalDashboard();
    userComboBox();
    echo "<br><br>".pubkeyUsernameGiven("Martina")["ChiavePubblica"];
    echo "<br><br>".$_SESSION["pubkey"];

?>