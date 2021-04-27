<?php
    include("../funzioni.php");
    startPersonalDashboard();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Styles/personalDashboardSaldo.css">
    <link rel="stylesheet" href="../../Styles/sharedNavbar.css">
    <title>Saldo</title>
</head>
<body>
    <div class="navbar">
        <div class="sideLeft">
            <img class="logo" src="../../Images/EduchainLogo-BlackV.png" alt="Logo">
            <div class="nav-text">EDUChain</div>
        </div>
        <div class="sideRight">
            <ul class="nav-links">
            <li>
                    <a class="nav-button" href="personalDashboardSaldo.php">
                        <img class="icon" src="../../Images/EduchainLogo.png" alt="Saldo">
                        <div>Saldo</div>
                    </a>     
                </li>
                <li>
                    <a class="nav-button" href="personalDashboardInvia.php">
                        <img class="icon" src="../../Images/send-email-1-white.png" alt="Invia">
                        <div>Invia</div>
                    </a>    
                </li>
                <li>
                    <a class="nav-button" href="personalDashboardRicevi.php">
                        <img class="icon" src="../../Images/email-action-receive-white.png" alt="Ricevi">
                        <div>Ricevi</div>
                    </a>    
                </li>
                <li>
                    <a class="nav-button" href="personalDashboardGenera.php">
                        <img class="icon" src="../../Images/style-two-pin-add-white.png" alt="Genera">
                        <div>Genera</div>
                    </a>    
                </li>
                <li>
                    <a class="nav-button" href="personalDashboardRitira.php">
                        <img class="icon" src="../../Images/style-two-pin-minus-white.png" alt="Ritira">
                        <div>Ritira</div>
                    </a>    
                </li>
                <li>
                    <a class="nav-button" href="personalDashboardBlockchain.php">
                        <img class="icon" src="../../Images/app-window-four-white.png" alt="Blockchain">
                        <div>Blockchain</div>
                    </a>    
                </li>
                <li><form action="../elaborazioneRichieste.php" method="post">
                    <button name="logout" type="submit" value="logout" class="nav-button" href="personalDashboardBlockchain.php">
                        <img class="icon" src="../../Images/logout-3white.png" alt="Blockchain">
                        <div>Logout</div>
                        </button>    
                    </form>
                </li>
            </ul>
            <div class="hamburger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </div>
    </div>
    <div class="pageContainer">
        <div class="upperPart">
            <div class="headerSaldo">
                Il tuo saldo è di:
            </div>
            <div class="saldoCard">
                <div class="saldoCard-saldo">
                    <div class="saldo">
                    <?php
                        echo $_SESSION["importo"];
                    ?>
                    </div>
                    <img class="saldo-icon" src="../../Images/EduchainLogo-BlackV.png" alt="Currency">
                </div>
                <form action="../elaborazioneRichieste.php" method="POST" class="saldoCard-buttons">
                    <input class="button-form" type="submit" name="refresh" value="Aggiorna">
                </form>
            </div>
        </div>
        <div class="devider"></div>
        <div class="lowerPart">
            <div class="header-transazioni">Transazioni:</div>
            <div class="scrollable-card">
                <?php 
                    echo $_SESSION["transazioni"];
                ?>
            </div>
        </div>
    </div>
    <script>
        const navSlide = () =>{
            const burger = document.querySelector('.hamburger');
            const nav = document.querySelector('.nav-links');
            const navLinks = document.querySelectorAll('.nav-button');


            burger.addEventListener('click',()=>{
                nav.classList.toggle('nav-active');
                    
                navLinks.forEach((link,index) =>{
                    if(link.style.animation){
                        link.style.animation = "";
                    }else{
                        link.style.animation = "navLinkFade 0.3s ease forwards "+(index / 15)+"s";
                    }
                });
                burger.classList.toggle("toggle");
            });
                
        }
            navSlide();
    </script>
</body>
</html>