<?php
session_start();
if(isset($_SESSION["username"])){
    //L'utente ha già effettuato il login poichè in sessione i suoi dati esistono già
    echo "<script type='text/javascript'> document.location = 'PersonalDashboard/personalDashboardSaldo.php'; </script>";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/Login&Register.css">
    <title>Login & Register</title>
</head>
<body>
    <div class="navbar">
        <div class="sideLeft">
            <img class="logo" src="../Images/EduchainLogo-BlackV.png" alt="Logo">
            <div class="nav-text">EDUChain</div>
        </div>
        <div class="sideRight">
            <ul class="nav-links">
                <li>
                    <a class="nav-button" href="#">
                        <img class="icon" src="../Images/dog-house-2white.png" alt="Home">
                        <div>Homepage</div>
                    </a>     
                </li>
                <li>
                    <a class="nav-button" href="#">
                        <img class="icon" src="../Images/login-3white.png" alt="Accedi">
                        <div>Accedi</div>
                    </a>    
                </li>
            </ul>
            <div class="hamburger">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </div>
    </div>
    <div class='container'>
        <?php
            if(isset($_SESSION["registrationDone"]) and $_SESSION["registrationDone"]===1){
                $_SESSION["registrationDone"] = 0;
                echo "<div style='background-color:green;color:white;' class='intestazione-alert'>Accedi con le credenziali appena create!</div>";
            }
            if(isset($_SESSION["errorType"])){
                switch($_SESSION["errorType"]){
                    case 1:
                        $_SESSION["errorType"] = 0;
                        echo "<div class='intestazione-alert'>Credenziali errate!</div>";
                        break;
                    case 5:
                        $_SESSION["errorType"] = 0;
                        echo "<div class='intestazione-alert'>Prima devi effettuare il login!</div>";
                        break;
                }
            }
        ?>
        <div class='card' id='card'>
            <div class='cardHeader' id="formType" value='Accedi'>
                Accedi
            </div>
            <form action='elaborazioneRichieste.php' method='post'>
                <div class='cardContent'>
                    <div class='username'>
                        <div class='formTitle'>Username:</div>
                        <input class='typeText' type='text' name='UserID' required>
                    </div>
                    <div class='password'>
                        <div class='formTitle'>Password:</div>
                        <input class='typeText' type='password' name='Password' required>
                    </div>
                </div>
                <div class='cardButtons'>
                    <button class='button-form' type='submit' name='Login' value='Invia'>Invia</button>
                    <div class='trigger' onclick='changeCard()'>Non hai ancora l'account? Clicca qui!</div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function changeCard(){
            if(document.getElementById("formType").getAttribute('value')=="Registrati"){
                document.getElementById("card").innerHTML = `<div class='cardHeader' id='formType' value='Accedi'>
                                                                    Accedi
                                                                </div>
                                                                <form action='elaborazioneRichieste.php' method='post'>
                                                                    <div class='cardContent'>
                                                                        <div class='username'>
                                                                            <div class='formTitle'>Username:</div>
                                                                            <input class='typeText' type='text' name='UserID' required>
                                                                        </div>
                                                                        <div class='password'>
                                                                            <div class='formTitle'>Password:</div>
                                                                            <input class='typeText' type='password' name='Password' required>
                                                                        </div>
                                                                    </div>
                                                                    <div class='cardButtons'>
                                                                        <input class='button-form' type='submit' name='Login' value='Invia'>
                                                                        <div class='trigger' onclick='changeCard()'>Non hai ancora l'account? Clicca qui!</div>
                                                                    </div>
                                                                </form>`;
            }else{
                document.getElementById("card").innerHTML = `<div class='cardHeader' id='formType' value='Registrati'>
                                                                    Registrati
                                                                </div>
                                                                <form action='elaborazioneRichieste.php' method='post'>
                                                                    <div class='cardContent'>
                                                                        <div class='username'>
                                                                            <div class='formTitle'>Username:</div>
                                                                            <input class='typeText' type='text' name='UserID' required>
                                                                        </div>
                                                                        <div class='password'>
                                                                            <div class='formTitle'>Password:</div>
                                                                            <input class='typeText' type='password' name='Password1' required>
                                                                        </div>
                                                                        <div class='password'>
                                                                            <div class='formTitle'>Ripeti la password:</div>
                                                                            <input class='typeText' type='password' name='Password2' required>
                                                                        </div>
                                                                    </div>
                                                                    <div class='cardButtons'>
                                                                        <input class='button-form' type='submit' name='Registrazione' value='Invia'>
                                                                        <div class='trigger' onclick='changeCard()'>Hai già un account? Accedi qui!</div>
                                                                    </div>
                                                                </form>`;
            }
        }

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